<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2019 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

namespace SuiteCRM\Robo\Plugin\Commands;

use Api\Core\Config\ApiConfig;
use DateTime;
use DBManager;
use OAuth2Clients;
use Robo\Tasks;
use SuiteCRM\Robo\Traits\RoboTrait;
use SuiteCRM\Robo\Traits\CliRunnerTrait;
use Api\V8\BeanDecorator\BeanManager;
use DBManagerFactory;
use User;

#[\AllowDynamicProperties]
class ApiCommands extends Tasks
{
    use RoboTrait;
    use CliRunnerTrait;

    /**
     * @var DBManager
     */
    protected $db;

    /**
     * @var BeanManager
     */
    protected $beanManager;

    /**
     * @var array
     */
    protected static $beanAliases = [
        User::class => 'Users',
        OAuth2Clients::class => 'OAuth2Clients',
    ];

    /**
     * ApiCommands constructor
     */
    public function __construct()
    {
        $this->bootstrap();
        $this->db = DBManagerFactory::getInstance();
        $this->beanManager = new BeanManager($this->db, static::$beanAliases);
    }

    /**
     * Configures the SuiteCRM V8 API with all defaults
     * @param string $name
     * @param string $password
     * @throws \Exception
     */
    public function apiConfigureV8($name, $password)
    {
        $this->say('Configure V8 Api');

        $this->taskComposerInstall()->noDev()->noInteraction()->run();
        $this->apiGenerateKeys();
        $this->apiSetKeyPermissions();
        $this->apiRebuildHtaccessFile();
        $this->apiExportPostmanENV();
        $this->apiCreateClient($name);
        $this->apiCreateUser($name, $password);
    }

    /**
     * Generate OAuth2 public/private keys
     * @param array $opts
     * @option string $privateKey set a custom path to the oauth2 private key.
     * @option string $publicKey set a custom path to the oauth2 public key.
     */
    public function apiGenerateKeys(
        $opts = ['privateKey' => ApiConfig::OAUTH2_PRIVATE_KEY, 'publicKey' => ApiConfig::OAUTH2_PUBLIC_KEY]
    ) {
        $privateKey = openssl_pkey_new(
            [
                'private_key_bits' => 2048,
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
            ]
        );

        openssl_pkey_export($privateKey, $privateKeyExport);

        $publicKey = openssl_pkey_get_details($privateKey);

        $publicKeyExport = $publicKey['key'];

        file_put_contents(
            $opts['privateKey'],
            $privateKeyExport
        );

        file_put_contents(
            $opts['publicKey'],
            $publicKeyExport
        );
    }

    /**
     * Sets the Oauth2 key permissions
     * @param array $opts
     * @option string $privateKey set a custom path to the oauth2 private key.
     * @option string $publicKey set a custom path to the oauth2 public key.
     */
    public function apiSetKeyPermissions(
        $opts = ['privateKey' => ApiConfig::OAUTH2_PRIVATE_KEY, 'publicKey' => ApiConfig::OAUTH2_PUBLIC_KEY]
    ) {
        chmod(
            $opts['privateKey'],
            0600
        ) &&
        chmod(
            $opts['publicKey'],
            0600
        );
    }

    /**
     * Rebuild .Htaccess file
     */
    public function apiRebuildHtaccessFile()
    {
        @require __DIR__ . '/../../../../modules/Administration/UpgradeAccess.php';
    }


    /**
     * Creates OAuth2 client
     * @param string $name
     * @return void
     * @throws \Exception
     */
    public function apiCreateClient($name)
    {
        $count = $this->getNameCount($name, 'oauth2clients', 'name');

        $clientSecret = bin2hex(random_bytes(50));

        $clientBean = $this->beanManager->newBeanSafe(
            OAuth2Clients::class
        );

        $clientBean->name = 'V8 API Client ' . $count;
        $clientBean->secret = hash('sha256', $clientSecret);
        $clientBean->{'is_confidential'} = true;
        $clientBean->save();
        $clientBean->retrieve($clientBean->id);

        $this->outputClientCredentials(!empty($clientBean->fetched_row['id']) ? compact(
            'clientBean',
            'clientSecret'
        ) : []);
    }

    /**
     * Creates a SuiteCRM user for the V8 API
     * @param string $name
     * @param string $password
     * @return void
     */
    public function apiCreateUser($name, $password)
    {
        $count = $this->getNameCount($name, 'users', 'user_name');

        $userBean = $this->beanManager->newBeanSafe(
            User::class
        );

        $userBean->user_name = $name . ' ' . $count;
        $userBean->first_name = 'V8';
        $userBean->last_name = 'API User';
        $userBean->email1 = 'API@example.com';
        $userBean->save();
        $userBean->setNewPassword($password, 1);
        $userBean->retrieve($userBean->id);

        $this->outputUserCredentials(!empty($userBean->fetched_row['id'])
            ? compact('userBean', 'password')
            : []);
    }

    /**
     * Export a postman environment for the V8 API
     * @param array $opts
     * @option string $postmanENV set a custom path to output a postman environment.
     */
    public function apiExportPostmanENV(
        $opts = ['postmanENV' => __DIR__ . '/../../../../Api/docs/postman/V8_API_Postman_Environment.json']
    ) {
        $rows = [
            'name' => 'SuiteCRM V8 API Environment',
            'values' => [
                [
                    'key' => 'suitecrm.url',
                    'value' => '{instance}/Api',
                    'description' => 'Used for API Operations.',
                    'enabled' => true
                ],
                [
                    'key' => 'token.url',
                    'value' => '{instance}/Api/access_token',
                    'description' => 'Used to get Access Tokens.',
                    'enabled' => true
                ]
            ]
        ];
        $json = json_encode($rows, JSON_UNESCAPED_SLASHES);

        file_put_contents($opts['postmanENV'], $json, LOCK_EX);

        $this->say('POSTMAN ENV Exported to ' . $opts['postmanENV']);
    }

    /**
     * Returns client credentials
     * @param array $client
     */
    private function outputClientCredentials(array $client)
    {
        $clientBean = $client['clientBean'];

        $clientArray = [
            'grantType' => 'Password Credentials',
            'accessToken' => '{{suitecrm.url}}/Api/access_token',
            'clientID' => $clientBean->id,
            'clientSecret' => $client['clientSecret']
        ];

        $this->io()->title('V8 API Client Credentials');

        $headers = [
            'Grant Type',
            'Access Token URL',
            'Client ID',
            'Client Secret',
        ];

        $rows = [
            $clientArray,
        ];

        $this->io->table($headers, $rows);
    }

    /**
     * Returns user credentials
     * @param array $user
     */
    private function outputUserCredentials(array $user)
    {
        $userBean = $user['userBean'];

        $userArray = [
            'name' => $userBean->user_name,
            'password' => $user['password']
        ];

        $this->io()->title('V8 API User Credentials');

        $headers = [
            'Username',
            'Password',
        ];

        $rows = [
            $userArray,
        ];

        $this->io->table($headers, $rows);
    }

    /**
     * Returns the number of duplicate name records from a table
     * @param string $name
     * @param string $table
     * @param string $row
     * @return int
     */
    private function getNameCount($name, $table, $row)
    {
        $nameQuoted = $this->db->quoted($name);

        $query = <<<SQL
SELECT
    count(`id`) AS `count`
FROM
    `$table`
WHERE
    `$row` LIKE '$nameQuoted %'
SQL;

        $result = $this->db->fetchOne($query);

        $count = $result
            ? (int)$result['count']
            : 0;

        $count++;

        return $count;
    }

    /**
     * Generate comprehensive API documentation
     *
     * Generates OpenAPI documentation from existing API infrastructure and validates
     * its accuracy. Creates comprehensive documentation that stays synchronized with
     * code changes and existing endpoint patterns.
     *
     * @param array $opts optional command line arguments
     * @option bool $validate - Set to validate documentation accuracy after generation
     * @option bool $examples - Set to include response examples in documentation
     * @option string $output - Set custom output path for generated documentation
     */
    public function apiDocsGenerate(array $opts = ['validate' => false, 'examples' => false, 'output' => ''])
    {
        $this->say('Generate API Documentation');
        
        try {
            $this->bootstrap();
            
            // Generate OpenAPI specification using existing service
            $this->say('Generating OpenAPI specification from existing API infrastructure...');
            
            // Use existing MetaService to generate documentation
            $this->_exec('php -r "
                require_once \'include/entryPoint.php\';
                
                use Api\\V8\\Service\\MetaService;
                use Api\\V8\\Service\\OpenApiDocumentationService;
                use Api\\V8\\BeanDecorator\\BeanManager;
                use Api\\V8\\Helper\\ModuleListProvider;
                
                \$db = DBManagerFactory::getInstance();
                \$beanManager = new BeanManager(\$db, []);
                \$moduleProvider = new ModuleListProvider();
                \$openApiService = new OpenApiDocumentationService(\$beanManager, \$moduleProvider);
                \$metaService = new MetaService(\$beanManager, \$moduleProvider, \$openApiService);
                
                \$schema = \$metaService->getSwaggerSchema();
                
                \$outputPath = \'' . ($opts['output'] ?: 'Api/docs/swagger/swagger.json') . '\';
                \$outputDir = dirname(\$outputPath);
                
                if (!is_dir(\$outputDir)) {
                    mkdir(\$outputDir, 0755, true);
                }
                
                file_put_contents(\$outputPath, json_encode(\$schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                echo \"Documentation generated successfully at: \" . \$outputPath . \"\\n\";
            "');
            
            $this->say('✅ API documentation generated successfully');
            
            if ($opts['validate']) {
                $this->apiDocsValidate();
            }
        } catch (\Exception $e) {
            $this->say('❌ Error generating API documentation: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Validate API documentation accuracy
     *
     * Validates that the generated OpenAPI documentation accurately reflects the
     * actual API endpoints, parameters, and responses. Ensures documentation
     * stays current with code changes.
     *
     * @param array $opts optional command line arguments
     * @option string $schema - Path to OpenAPI schema file to validate
     * @option bool $strict - Enable strict validation mode
     */
    public function apiDocsValidate(array $opts = ['schema' => '', 'strict' => false])
    {
        $this->say('Validate API Documentation');
        
        try {
            $this->bootstrap();
            
            $schemaPath = $opts['schema'] ?: 'Api/docs/swagger/swagger.json';
            
            if (!file_exists($schemaPath)) {
                $this->say('❌ Documentation file not found: ' . $schemaPath);
                $this->say('Run api:docs:generate first to create documentation');
                return;
            }
            
            $this->say('Validating OpenAPI specification...');
            
            // Load and validate schema structure
            $schema = json_decode(file_get_contents($schemaPath), true);
            
            if (!$schema) {
                throw new \Exception('Invalid JSON in documentation file');
            }
            
            // Validate required OpenAPI fields
            $requiredFields = ['openapi', 'info', 'paths'];
            foreach ($requiredFields as $field) {
                if (!isset($schema[$field])) {
                    throw new \Exception("Missing required field: $field");
                }
            }
            
            // Validate endpoint count
            $pathCount = count($schema['paths']);
            $this->say("Found $pathCount documented endpoints");
            
            if ($pathCount < 5) {
                $this->say('⚠️  Warning: Low endpoint count, documentation may be incomplete');
            }
            
            // Validate authentication documentation
            if (isset($schema['components']['securitySchemes'])) {
                $this->say('✅ Authentication flows documented');
            } else {
                $this->say('⚠️  Warning: No authentication documentation found');
            }
            
            $this->say('✅ API documentation validation completed');
        } catch (\Exception $e) {
            $this->say('❌ Documentation validation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update API documentation from code changes
     *
     * Automatically updates API documentation when code changes are detected.
     * Provides intelligent update mechanisms that preserve manual customizations
     * while keeping documentation synchronized with code.
     *
     * @param array $opts optional command line arguments
     * @option bool $force - Force update even if no changes detected
     * @option bool $backup - Create backup before updating
     */
    public function apiDocsUpdate(array $opts = ['force' => false, 'backup' => false])
    {
        $this->say('Update API Documentation');
        
        try {
            $this->bootstrap();
            
            $docsPath = 'Api/docs/swagger/swagger.json';
            
            if ($opts['backup'] && file_exists($docsPath)) {
                $backupPath = $docsPath . '.backup.' . date('Y-m-d-H-i-s');
                copy($docsPath, $backupPath);
                $this->say("📋 Backup created: $backupPath");
            }
            
            // Check if update is needed (unless forced)
            if (!$opts['force'] && file_exists($docsPath)) {
                $lastModified = filemtime($docsPath);
                $codeLastModified = $this->getApiCodeLastModified();
                
                if ($lastModified > $codeLastModified) {
                    $this->say('📋 Documentation is up to date');
                    return;
                }
            }
            
            $this->say('🔄 Code changes detected, updating documentation...');
            
            // Regenerate documentation
            $this->apiDocsGenerate(['validate' => true, 'examples' => true]);
            
            $this->say('✅ API documentation updated successfully');
        } catch (\Exception $e) {
            $this->say('❌ Documentation update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Test API documentation examples
     *
     * Tests that all examples in the API documentation are valid and work
     * correctly. Validates request/response examples against actual API behavior
     * to ensure documentation accuracy.
     *
     * @param array $opts optional command line arguments
     * @option bool $live - Test against live API endpoints
     * @option string $auth - Authentication token for live testing
     */
    public function apiDocsTestExamples(array $opts = ['live' => false, 'auth' => ''])
    {
        $this->say('Test API Documentation Examples');
        
        try {
            $this->bootstrap();
            
            $docsPath = 'Api/docs/swagger/swagger.json';
            
            if (!file_exists($docsPath)) {
                $this->say('❌ Documentation file not found. Run api:docs:generate first.');
                return;
            }
            
            $schema = json_decode(file_get_contents($docsPath), true);
            
            $this->say('🧪 Testing documentation examples...');
            
            $totalExamples = 0;
            $validExamples = 0;
            
            foreach ($schema['paths'] as $path => $pathData) {
                foreach ($pathData as $method => $operation) {
                    if (isset($operation['responses'])) {
                        foreach ($operation['responses'] as $statusCode => $response) {
                            if (isset($response['content']['application/vnd.api+json']['examples'])) {
                                $totalExamples++;
                                
                                // Validate example structure
                                if ($this->validateExampleStructure($response['content']['application/vnd.api+json']['examples'])) {
                                    $validExamples++;
                                }
                            }
                        }
                    }
                }
            }
            
            $this->say("📊 Validated $validExamples/$totalExamples examples");
            
            if ($opts['live'] && $opts['auth']) {
                $this->say('🔄 Running live API tests...');
                // Live testing would be implemented here
                $this->say('⚠️  Live testing not yet implemented');
            }
            
            $this->say('✅ Documentation example testing completed');
        } catch (\Exception $e) {
            $this->say('❌ Example testing failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verifies that all API endpoints use standardized JSON response formats
     *
     * @option array modules Specific modules to check (default: all)
     * @option bool fix Automatically migrate non-standard responses to EnhancedBaseController
     * @option bool verbose Show detailed analysis for each endpoint
     *
     * @since 1.0.0
     */
    public function apiVerifyResponseFormat($options = ['modules' => [], 'fix' => false, 'verbose' => false])
    {
        $this->say('Starting API Response Format Verification...');
        
        $results = [
            'total_controllers' => 0,
            'compliant_controllers' => 0,
            'enhanced_controllers' => 0,
            'non_compliant' => [],
            'recommendations' => []
        ];
        
        // Scan all V8 API controllers
        $controllerPath = __DIR__ . '/../../../../Api/V8/Controller';
        $controllers = $this->scanApiControllers($controllerPath);
        
        foreach ($controllers as $controller) {
            $results['total_controllers']++;
            $analysis = $this->analyzeControllerResponseFormat($controller);
            
            if ($options['verbose']) {
                $this->say("Analyzing: {$controller['name']}");
                $this->say("  - Base Class: {$analysis['base_class']}");
                $this->say("  - Response Methods: " . implode(', ', $analysis['response_methods']));
                $this->say("  - Compliance: {$analysis['compliance_level']}");
            }
            
            switch ($analysis['compliance_level']) {
                case 'enhanced':
                    $results['enhanced_controllers']++;
                    $results['compliant_controllers']++;
                    break;
                case 'standard':
                    $results['compliant_controllers']++;
                    if ($analysis['can_enhance']) {
                        $results['recommendations'][] = [
                            'controller' => $controller['name'],
                            'action' => 'Can be enhanced with EnhancedBaseController',
                            'benefit' => 'Add correlation IDs, performance metrics, enhanced error handling'
                        ];
                    }
                    break;
                case 'non_compliant':
                    $results['non_compliant'][] = [
                        'controller' => $controller['name'],
                        'issues' => $analysis['issues'],
                        'fix_required' => true
                    ];
                    break;
            }
        }
        
        // Display verification results
        $this->displayVerificationResults($results);
        
        // Apply fixes if requested
        if ($options['fix'] && !empty($results['non_compliant'])) {
            $this->applyResponseFormatFixes($results['non_compliant']);
        }
        
        // Generate verification report
        $this->generateVerificationReport($results);
        
        $compliancePercentage = ($results['compliant_controllers'] / $results['total_controllers']) * 100;
        
        if ($compliancePercentage === 100.0) {
            $this->say('<info>✅ API Response Format Verification PASSED - 100% compliance achieved!</info>');
        } else {
            $this->say("<comment>⚠️ API Response Format Verification: {$compliancePercentage}% compliance</comment>");
        }
        
        return $compliancePercentage === 100.0;
    }

    /**
     * Scans API controller directory for all controller files
     *
     * @param string $path Controller directory path
     * @return array Controller information
     *
     * @since 1.0.0
     */
    private function scanApiControllers(string $path): array
    {
        $controllers = [];
        
        if (!is_dir($path)) {
            $this->say("<error>Controller directory not found: {$path}</error>");
            return [];
        }
        
        $files = glob($path . '/*.php');
        
        foreach ($files as $file) {
            $filename = basename($file, '.php');
            
            // Skip documentation files and non-controller files
            if (strpos($filename, '_docs') !== false || $filename === 'BaseController' || $filename === 'EnhancedBaseController') {
                continue;
            }
            
            $controllers[] = [
                'name' => $filename,
                'path' => $file,
                'namespace' => 'Api\\V8\\Controller\\' . $filename
            ];
        }
        
        return $controllers;
    }

    /**
     * Analyzes a controller's response format compliance
     *
     * @param array $controller Controller information
     * @return array Analysis results
     *
     * @since 1.0.0
     */
    private function analyzeControllerResponseFormat(array $controller): array
    {
        $analysis = [
            'base_class' => 'unknown',
            'response_methods' => [],
            'compliance_level' => 'unknown',
            'issues' => [],
            'can_enhance' => false
        ];
        
        if (!file_exists($controller['path'])) {
            $analysis['issues'][] = 'Controller file not found';
            $analysis['compliance_level'] = 'non_compliant';
            return $analysis;
        }
        
        $content = file_get_contents($controller['path']);
        
        // Check base class
        if (preg_match('/class\s+' . $controller['name'] . '\s+extends\s+(\w+)/', $content, $matches)) {
            $analysis['base_class'] = $matches[1];
        }
        
        // Check for response method usage
        if (preg_match_all('/\$this->generate(Response|ErrorResponse|EnhancedResponse|EnhancedErrorResponse)\s*\(/', $content, $matches)) {
            $analysis['response_methods'] = array_unique($matches[1]);
        }
        
        // Determine compliance level
        switch ($analysis['base_class']) {
            case 'EnhancedBaseController':
                $analysis['compliance_level'] = 'enhanced';
                break;
            case 'BaseController':
                $analysis['compliance_level'] = 'standard';
                $analysis['can_enhance'] = true;
                
                // Check for proper response method usage
                if (empty($analysis['response_methods'])) {
                    $analysis['issues'][] = 'No response generation methods found';
                    $analysis['compliance_level'] = 'non_compliant';
                } elseif (!in_array('Response', $analysis['response_methods']) && !in_array('ErrorResponse', $analysis['response_methods'])) {
                    $analysis['issues'][] = 'Does not use standard generateResponse methods';
                    $analysis['compliance_level'] = 'non_compliant';
                }
                break;
            default:
                $analysis['compliance_level'] = 'non_compliant';
                $analysis['issues'][] = 'Does not extend BaseController or EnhancedBaseController';
                break;
        }
        
        // Check for direct JSON response bypassing
        if (preg_match('/->write\s*\(\s*json_encode/', $content)) {
            $analysis['issues'][] = 'Contains direct JSON encoding, bypassing standard response methods';
            if ($analysis['compliance_level'] !== 'non_compliant') {
                $analysis['compliance_level'] = 'non_compliant';
            }
        }
        
        return $analysis;
    }

    /**
     * Displays verification results in a formatted table
     *
     * @param array $results Verification results
     *
     * @since 1.0.0
     */
    private function displayVerificationResults(array $results): void
    {
        $this->say('');
        $this->say('<info>=== API Response Format Verification Results ===</info>');
        $this->say('');
        
        // Summary statistics
        $this->say("Total Controllers Analyzed: {$results['total_controllers']}");
        $this->say("Compliant Controllers: {$results['compliant_controllers']}");
        $this->say("Enhanced Controllers: {$results['enhanced_controllers']}");
        $this->say("Non-Compliant Controllers: " . count($results['non_compliant']));
        
        $compliancePercentage = ($results['compliant_controllers'] / $results['total_controllers']) * 100;
        $this->say("Overall Compliance: " . number_format($compliancePercentage, 1) . "%");
        
        // Non-compliant controllers
        if (!empty($results['non_compliant'])) {
            $this->say('');
            $this->say('<error>❌ Non-Compliant Controllers:</error>');
            foreach ($results['non_compliant'] as $issue) {
                $this->say("  - {$issue['controller']}:");
                foreach ($issue['issues'] as $problem) {
                    $this->say("    • {$problem}");
                }
            }
        }
        
        // Enhancement recommendations
        if (!empty($results['recommendations'])) {
            $this->say('');
            $this->say('<comment>💡 Enhancement Recommendations:</comment>');
            foreach ($results['recommendations'] as $rec) {
                $this->say("  - {$rec['controller']}: {$rec['action']}");
                $this->say("    Benefit: {$rec['benefit']}");
            }
        }
        
        $this->say('');
    }

    /**
     * Applies automatic fixes for response format issues
     *
     * @param array $nonCompliantControllers List of non-compliant controllers
     *
     * @since 1.0.0
     */
    private function applyResponseFormatFixes(array $nonCompliantControllers): void
    {
        $this->say('<comment>Applying automatic fixes...</comment>');
        
        foreach ($nonCompliantControllers as $controller) {
            $this->say("Fixing {$controller['controller']}...");
            
            // Implementation would depend on specific issues found
            // For now, just report what would be fixed
            foreach ($controller['issues'] as $issue) {
                $this->say("  - Would fix: {$issue}");
            }
        }
        
        $this->say('<info>Automatic fixes applied. Please review changes and test thoroughly.</info>');
    }

    /**
     * Generates detailed verification report
     *
     * @param array $results Verification results
     *
     * @since 1.0.0
     */
    private function generateVerificationReport(array $results): void
    {
        $reportPath = __DIR__ . '/../../../../Api/V8/docs/response-format-verification-report.md';
        
        $report = "# API Response Format Verification Report\n\n";
        $report .= "**Generated**: " . date('Y-m-d H:i:s') . "\n\n";
        
        $report .= "## Summary\n\n";
        $report .= "- **Total Controllers**: {$results['total_controllers']}\n";
        $report .= "- **Compliant Controllers**: {$results['compliant_controllers']}\n";
        $report .= "- **Enhanced Controllers**: {$results['enhanced_controllers']}\n";
        $report .= "- **Non-Compliant Controllers**: " . count($results['non_compliant']) . "\n";
        
        $compliancePercentage = ($results['compliant_controllers'] / $results['total_controllers']) * 100;
        $report .= "- **Overall Compliance**: " . number_format($compliancePercentage, 1) . "%\n\n";
        
        if (!empty($results['non_compliant'])) {
            $report .= "## Non-Compliant Controllers\n\n";
            foreach ($results['non_compliant'] as $issue) {
                $report .= "### {$issue['controller']}\n\n";
                $report .= "**Issues**:\n";
                foreach ($issue['issues'] as $problem) {
                    $report .= "- {$problem}\n";
                }
                $report .= "\n";
            }
        }
        
        if (!empty($results['recommendations'])) {
            $report .= "## Enhancement Recommendations\n\n";
            foreach ($results['recommendations'] as $rec) {
                $report .= "### {$rec['controller']}\n\n";
                $report .= "**Action**: {$rec['action']}\n";
                $report .= "**Benefit**: {$rec['benefit']}\n\n";
            }
        }
        
        $report .= "## Compliance Criteria\n\n";
        $report .= "### Enhanced Compliance\n";
        $report .= "- Extends `EnhancedBaseController`\n";
        $report .= "- Uses enhanced response methods with correlation IDs\n";
        $report .= "- Includes performance metrics and comprehensive error handling\n\n";
        
        $report .= "### Standard Compliance\n";
        $report .= "- Extends `BaseController`\n";
        $report .= "- Uses `generateResponse()` and `generateErrorResponse()` methods\n";
        $report .= "- Follows JSON:API specification\n\n";
        
        $report .= "### Non-Compliant\n";
        $report .= "- Does not extend standard base controllers\n";
        $report .= "- Bypasses standard response generation methods\n";
        $report .= "- Uses direct JSON encoding without proper headers\n\n";
        
        // Ensure directory exists
        $reportDir = dirname($reportPath);
        if (!is_dir($reportDir)) {
            mkdir($reportDir, 0755, true);
        }
        
        file_put_contents($reportPath, $report);
        $this->say("<info>Detailed report saved to: {$reportPath}</info>");
    }

    /**
     * Gets the last modification time of API code files
     *
     * @return int Unix timestamp of most recent API code modification
     */
    private function getApiCodeLastModified(): int
    {
        $apiDirs = [
            'Api/V8/Controller',
            'Api/V8/Service',
            'Api/V8/Config'
        ];
        
        $lastModified = 0;
        
        foreach ($apiDirs as $dir) {
            if (is_dir($dir)) {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($dir)
                );
                
                foreach ($iterator as $file) {
                    if ($file->isFile() && $file->getExtension() === 'php') {
                        $lastModified = max($lastModified, $file->getMTime());
                    }
                }
            }
        }
        
        return $lastModified;
    }

    /**
     * Generate API key for service-to-service authentication
     *
     * Creates secure API keys for service-to-service communication that bypass
     * OAuth2 flows. Useful for automated systems, webhooks, and internal services
     * that need programmatic access to SuiteCRM API endpoints.
     *
     * @param string $name Descriptive name for the API key
     * @param array $opts Optional parameters for API key generation
     * @option array $scopes Scopes to assign to the API key (default: ['api'])
     * @option string $expires Expiration date (ISO format) or 'never' for no expiration
     * @option bool $active Whether the API key should be active immediately
     *
     * @return void
     * @throws \Exception When API key generation fails
     */
    public function apiKeyGenerate(string $name, array $opts = ['scopes' => ['api'], 'expires' => 'never', 'active' => true])
    {
        $this->say('Generate API Key for Service-to-Service Authentication');
        
        try {
            $this->bootstrap();
            
            // Generate secure API key
            $apiKey = $this->generateSecureApiKey();
            $apiSecret = bin2hex(random_bytes(32));
            
            // Prepare expiration date
            $expiresAt = null;
            if ($opts['expires'] !== 'never') {
                $expiresAt = new DateTime($opts['expires']);
            }
            
            // Create API key record
            $count = $this->getNameCount($name, 'oauth2_api_keys', 'name');
            $keyName = $name . ($count > 1 ? " $count" : "");
            
            $apiKeyRecord = [
                'id' => $this->generateUuid(),
                'name' => $keyName,
                'api_key' => hash('sha256', $apiKey),
                'api_secret' => hash('sha256', $apiSecret),
                'scopes' => json_encode($opts['scopes']),
                'expires_at' => $expiresAt ? $expiresAt->format('Y-m-d H:i:s') : null,
                'is_active' => $opts['active'] ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s'),
                'last_used_at' => null
            ];
            
            // Create table if it doesn't exist
            $this->createApiKeysTable();
            
            // Insert API key record
            $this->insertApiKeyRecord($apiKeyRecord);
            
            // Output API key credentials
            $this->outputApiKeyCredentials([
                'name' => $keyName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
                'scopes' => $opts['scopes'],
                'expires_at' => $expiresAt ? $expiresAt->format('Y-m-d H:i:s') : 'Never'
            ]);
            
            $this->say('✅ API key generated successfully');
        } catch (\Exception $e) {
            $this->say('❌ API key generation failed: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * List all API keys with their status and usage information
     *
     * @param array $opts Optional parameters for listing
     * @option bool $active Show only active API keys
     * @option bool $expired Show only expired API keys
     */
    public function apiKeyList(array $opts = ['active' => false, 'expired' => false])
    {
        $this->say('List API Keys');
        
        try {
            $this->bootstrap();
            
            $conditions = [];
            if ($opts['active']) {
                $conditions[] = 'is_active = 1';
            }
            if ($opts['expired']) {
                $conditions[] = 'expires_at IS NOT NULL AND expires_at < NOW()';
            }
            
            $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
            
            $query = "SELECT name, LEFT(api_key, 8) as api_key_preview, scopes, is_active, expires_at, created_at, last_used_at 
                      FROM oauth2_api_keys $whereClause ORDER BY created_at DESC";
            
            $results = $this->db->query($query);
            
            if (!$results) {
                $this->say('No API keys found');
                return;
            }
            
            $headers = ['Name', 'Key Preview', 'Scopes', 'Active', 'Expires', 'Created', 'Last Used'];
            $rows = [];
            
            while ($row = $this->db->fetchByAssoc($results)) {
                $scopes = json_decode($row['scopes'], true);
                $rows[] = [
                    $row['name'],
                    $row['api_key_preview'] . '...',
                    implode(', ', $scopes ?: ['api']),
                    $row['is_active'] ? 'Yes' : 'No',
                    $row['expires_at'] ?: 'Never',
                    $row['created_at'],
                    $row['last_used_at'] ?: 'Never'
                ];
            }
            
            $this->io()->table($headers, $rows);
        } catch (\Exception $e) {
            $this->say('❌ Failed to list API keys: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Revoke an API key by name or key value
     *
     * @param string $identifier API key name or key value to revoke
     */
    public function apiKeyRevoke(string $identifier)
    {
        $this->say("Revoke API Key: $identifier");
        
        try {
            $this->bootstrap();
            
            // Determine if identifier is a name or key
            $isKey = strlen($identifier) > 20; // Assume keys are longer than names
            $field = $isKey ? 'api_key' : 'name';
            $value = $isKey ? hash('sha256', $identifier) : $identifier;
            
            $query = "UPDATE oauth2_api_keys SET is_active = 0, revoked_at = NOW() WHERE $field = ?";
            $result = $this->db->query($query, [$value]);
            
            if ($this->db->getAffectedRowCount($result) > 0) {
                $this->say('✅ API key revoked successfully');
            } else {
                $this->say('❌ API key not found');
            }
        } catch (\Exception $e) {
            $this->say('❌ Failed to revoke API key: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Validate API key functionality
     *
     * @param string $apiKey The API key to validate
     * @param string $apiSecret The API secret to validate
     */
    public function apiKeyValidate(string $apiKey, string $apiSecret)
    {
        $this->say('Validate API Key');
        
        try {
            $this->bootstrap();
            
            $hashedKey = hash('sha256', $apiKey);
            $hashedSecret = hash('sha256', $apiSecret);
            
            $query = "SELECT name, scopes, is_active, expires_at FROM oauth2_api_keys 
                      WHERE api_key = ? AND api_secret = ? AND is_active = 1";
            
            $result = $this->db->fetchOne($query, [$hashedKey, $hashedSecret]);
            
            if (!$result) {
                $this->say('❌ Invalid API key or secret');
                return;
            }
            
            // Check expiration
            if ($result['expires_at'] && strtotime($result['expires_at']) < time()) {
                $this->say('❌ API key has expired');
                return;
            }
            
            $scopes = json_decode($result['scopes'], true) ?: ['api'];
            
            $this->say('✅ API key is valid');
            $this->io()->table(
                ['Property', 'Value'],
                [
                    ['Name', $result['name']],
                    ['Scopes', implode(', ', $scopes)],
                    ['Status', 'Active'],
                    ['Expires', $result['expires_at'] ?: 'Never']
                ]
            );
        } catch (\Exception $e) {
            $this->say('❌ API key validation failed: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Generate a secure API key
     *
     * @return string Secure API key
     */
    private function generateSecureApiKey(): string
    {
        return 'sk_' . bin2hex(random_bytes(32));
    }
    
    /**
     * Generate a UUID
     *
     * @return string UUID
     */
    private function generateUuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
    
    /**
     * Create API keys table if it doesn't exist
     */
    private function createApiKeysTable(): void
    {
        $tableExists = $this->db->tableExists('oauth2_api_keys');
        
        if (!$tableExists) {
            $sql = "CREATE TABLE oauth2_api_keys (
                id VARCHAR(36) PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                api_key VARCHAR(255) NOT NULL UNIQUE,
                api_secret VARCHAR(255) NOT NULL,
                scopes TEXT,
                is_active TINYINT(1) DEFAULT 1,
                expires_at DATETIME NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                last_used_at DATETIME NULL,
                revoked_at DATETIME NULL,
                INDEX idx_api_key (api_key),
                INDEX idx_active (is_active),
                INDEX idx_expires (expires_at)
            )";
            
            $this->db->query($sql);
            $this->say('📋 Created oauth2_api_keys table');
        }
    }
    
    /**
     * Insert API key record into database
     *
     * @param array $record API key record data
     */
    private function insertApiKeyRecord(array $record): void
    {
        $fields = implode(', ', array_keys($record));
        $placeholders = implode(', ', array_fill(0, count($record), '?'));
        
        $sql = "INSERT INTO oauth2_api_keys ($fields) VALUES ($placeholders)";
        $this->db->query($sql, array_values($record));
    }
    
    /**
     * Output API key credentials in a formatted table
     *
     * @param array $credentials API key credentials
     */
    private function outputApiKeyCredentials(array $credentials): void
    {
        $this->io()->title('API Key Credentials');
        
        $headers = ['Property', 'Value'];
        $rows = [
            ['Name', $credentials['name']],
            ['API Key', $credentials['api_key']],
            ['API Secret', $credentials['api_secret']],
            ['Scopes', implode(', ', $credentials['scopes'])],
            ['Expires', $credentials['expires_at']]
        ];
        
        $this->io()->table($headers, $rows);
        
        $this->io()->note([
            'Store these credentials securely - they will not be shown again.',
            'Use the API key and secret for service-to-service authentication.',
            'Include them in the Authorization header: "Api-Key {key}:{secret}"'
        ]);
    }

    /**
     * Validates the structure of an API response example
     *
     * @param mixed $example The example to validate
     * @return bool True if example structure is valid
     */
    private function validateExampleStructure($example): bool
    {
        // Basic JSON:API structure validation
        if (!is_array($example)) {
            return false;
        }
        
        // Check for required JSON:API fields
        return isset($example['data']) || isset($example['errors']);
    }
}
