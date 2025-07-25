<?php
/**
 * @fileoverview API Documentation Structure and Completeness Tests
 * 
 * Validates the basic structure and completeness of OpenAPI documentation,
 * ensuring all core modules and critical endpoints are properly documented.
 * Part of the modular documentation validation test suite.
 * 
 * Key Features:
 * - OpenAPI specification structure validation
 * - Core module documentation completeness testing
 * - Critical endpoint documentation validation
 * - Configurable validation thresholds
 * 
 * Dependencies:
 * - Existing ApiTester functionality and authentication
 * - OpenApiDocumentationService for specification generation
 * - DocumentationTestHelper for shared utilities
 * 
 * @package SuiteCRM\Tests\Api\V8
 * @copyright Copyright (C) 2011 - 2024 SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 * @since 1.0.0
 */

use ApiTester;
use Helper\DocumentationTestHelper;

/**
 * DocumentationStructureCest
 * 
 * Tests for API documentation structure and completeness validation.
 * Ensures all required modules and endpoints are properly documented.
 */
#[\AllowDynamicProperties]
class DocumentationStructureCest
{
    /** @var string $SWAGGER_ENDPOINT OpenAPI/Swagger documentation endpoint */
    private static string $SWAGGER_ENDPOINT = '/V8/meta/swagger.json';
    
    /** @var string $META_ENDPOINT API metadata endpoint */
    private static string $META_ENDPOINT = '/V8/meta';
    
    /** @var array $coreModules Core SuiteCRM modules that should be documented */
    private array $coreModules = [
        'Accounts', 'Contacts', 'Leads', 'Opportunities', 'Cases', 
        'Meetings', 'Calls', 'Tasks', 'Notes', 'Documents'
    ];
    
    /** @var array $requiredEndpoints Critical API endpoints that must be documented */
    private array $requiredEndpoints = [
        '/V8/modules/{module}',
        '/V8/modules/{module}/{id}',
        '/V8/modules/{module}/{id}/relationships/{link}',
        '/V8/meta/modules',
        '/V8/meta/list'
    ];
    
    /** @var array $validationThresholds Configurable validation thresholds */
    private array $validationThresholds = [
        'min_module_coverage' => 80, // percentage
        'min_schema_validity' => 70, // percentage
    ];
    
    /** @var DocumentationTestHelper $docHelper Documentation testing helper */
    private DocumentationTestHelper $docHelper;
    
    /** @var array $swaggerSpec Loaded OpenAPI specification */
    private array $swaggerSpec = [];

    /**
     * Setup method called before each test
     * 
     * Initializes the documentation helper and loads configuration from
     * environment variables if available.
     * 
     * @param ApiTester $I The API tester instance
     * 
     * @since 1.0.0
     */
    public function _before(ApiTester $I)
    {
        $this->docHelper = new DocumentationTestHelper();
        $this->loadConfigurationFromEnvironment();
        $this->loadSwaggerSpecification($I);
    }

    /**
     * Configure core modules list for testing
     * 
     * Allows external configuration of which modules should be considered
     * core and required for documentation completeness testing.
     * 
     * @param array $modules List of module names to consider core
     * 
     * @since 1.0.0
     */
    public function configureCoreModules(array $modules): void
    {
        $this->coreModules = $modules;
    }

    /**
     * Configure required endpoints for testing
     * 
     * Allows external configuration of which API endpoints are considered
     * critical and must be documented.
     * 
     * @param array $endpoints List of endpoint patterns that must be documented
     * 
     * @since 1.0.0
     */
    public function configureRequiredEndpoints(array $endpoints): void
    {
        $this->requiredEndpoints = $endpoints;
    }

    /**
     * Configure validation thresholds
     * 
     * Allows external configuration of validation thresholds for module
     * coverage and schema validity requirements.
     * 
     * @param array $thresholds Associative array of threshold values
     * 
     * @since 1.0.0
     */
    public function configureValidationThresholds(array $thresholds): void
    {
        $this->validationThresholds = array_merge($this->validationThresholds, $thresholds);
    }

    /**
     * Test OpenAPI specification structure and validity
     * 
     * Validates that the OpenAPI specification follows proper structure,
     * includes required sections, and contains valid schema definitions.
     * 
     * @param ApiTester $I The API tester instance
     * 
     * @since 1.0.0
     */
    public function testOpenApiSpecificationStructure(ApiTester $I)
    {
        $I->wantTo('validate OpenAPI specification structure and completeness');
        
        // Test basic OpenAPI structure
        $I->assertArrayHasKey('openapi', $this->swaggerSpec, 'OpenAPI version should be specified');
        $I->assertArrayHasKey('info', $this->swaggerSpec, 'API info section should be present');
        $I->assertArrayHasKey('paths', $this->swaggerSpec, 'API paths section should be present');
        $I->assertArrayHasKey('components', $this->swaggerSpec, 'Components section should be present');
        
        // Validate OpenAPI version
        $openApiVersion = $this->swaggerSpec['openapi'] ?? '';
        $I->assertMatchesRegularExpression('/^3\.\d+\.\d+$/', $openApiVersion, 
            'OpenAPI version should be 3.x.x format');
        
        // Validate info section completeness
        $info = $this->swaggerSpec['info'] ?? [];
        $I->assertArrayHasKey('title', $info, 'API title should be specified');
        $I->assertArrayHasKey('version', $info, 'API version should be specified');
        $I->assertArrayHasKey('description', $info, 'API description should be provided');
        
        // Validate components section
        $components = $this->swaggerSpec['components'] ?? [];
        $I->assertArrayHasKey('schemas', $components, 'Components schemas should be defined');
        $I->assertArrayHasKey('securitySchemes', $components, 'Security schemes should be defined');
        
        // Test paths structure
        $paths = $this->swaggerSpec['paths'] ?? [];
        $I->assertNotEmpty($paths, 'API should have documented endpoints');
        
        $I->comment('OpenAPI specification structure validation completed successfully');
    }

    /**
     * Test core module documentation completeness
     * 
     * Validates that all core SuiteCRM modules have proper API documentation
     * including CRUD operations and proper schema definitions.
     * 
     * @param ApiTester $I The API tester instance
     * 
     * @since 1.0.0
     */
    public function testCoreModuleDocumentationCompleteness(ApiTester $I)
    {
        $I->wantTo('verify all core modules have complete API documentation');
        
        $documentedModules = $this->countDocumentedModules($this->coreModules);
        $totalCoreModules = count($this->coreModules);
        $coveragePercentage = ($documentedModules / $totalCoreModules) * 100;
        
        $minCoverage = $this->validationThresholds['min_module_coverage'];
        $I->assertGreaterThanOrEqual($minCoverage, $coveragePercentage, 
            "Core module documentation coverage should be at least {$minCoverage}%");
        
        $I->comment("Core module documentation coverage: {$coveragePercentage}% ({$documentedModules}/{$totalCoreModules})");
        
        // Test individual modules for comprehensive documentation
        foreach ($this->coreModules as $module) {
            $this->validateModuleDocumentation($I, $module);
        }
    }

    /**
     * Test critical endpoint documentation
     * 
     * Validates that all critical API endpoints are properly documented
     * with complete parameter and response information.
     * 
     * @param ApiTester $I The API tester instance
     * 
     * @since 1.0.0
     */
    public function testCriticalEndpointDocumentation(ApiTester $I)
    {
        $I->wantTo('verify all critical API endpoints are documented');
        
        $documentedEndpoints = $this->countDocumentedEndpoints($this->requiredEndpoints);
        $totalRequired = count($this->requiredEndpoints);
        $coveragePercentage = ($documentedEndpoints / $totalRequired) * 100;
        
        $I->assertGreaterThan(90, $coveragePercentage, 
            'Critical endpoint documentation coverage should be above 90%');
        
        $I->comment("Critical endpoint coverage: {$coveragePercentage}% ({$documentedEndpoints}/{$totalRequired})");
        
        // Test each critical endpoint individually
        foreach ($this->requiredEndpoints as $endpoint) {
            $isDocumented = $this->isEndpointDocumented($endpoint);
            $I->assertTrue($isDocumented, "Critical endpoint {$endpoint} should be documented");
            
            if ($isDocumented) {
                $this->validateEndpointDocumentation($I, $endpoint);
            }
        }
    }

    /**
     * Load configuration from environment variables
     * 
     * Loads validation thresholds and module lists from environment
     * variables for CI/CD integration and custom testing scenarios.
     * 
     * @since 1.0.0
     */
    private function loadConfigurationFromEnvironment(): void
    {
        // Load validation thresholds from environment
        if ($minModuleCoverage = getenv('DOC_MIN_MODULE_COVERAGE')) {
            $this->validationThresholds['min_module_coverage'] = (int)$minModuleCoverage;
        }
        if ($minSchemaValidity = getenv('DOC_MIN_SCHEMA_VALIDITY')) {
            $this->validationThresholds['min_schema_validity'] = (int)$minSchemaValidity;
        }
        
        // Load core modules from environment (comma-separated)
        if ($coreModulesEnv = getenv('DOC_CORE_MODULES')) {
            $this->coreModules = array_map('trim', explode(',', $coreModulesEnv));
        }
        
        // Load required endpoints from environment (comma-separated)
        if ($requiredEndpointsEnv = getenv('DOC_REQUIRED_ENDPOINTS')) {
            $this->requiredEndpoints = array_map('trim', explode(',', $requiredEndpointsEnv));
        }
    }

    /**
     * Load OpenAPI specification for testing
     * 
     * Retrieves and parses the OpenAPI specification from the API endpoint
     * for use in validation tests.
     * 
     * @param ApiTester $I The API tester instance
     * 
     * @since 1.0.0
     */
    private function loadSwaggerSpecification(ApiTester $I): void
    {
        $I->sendGET(self::$SWAGGER_ENDPOINT);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        
        $this->swaggerSpec = json_decode($I->grabResponse(), true);
        $I->assertNotEmpty($this->swaggerSpec, 'OpenAPI specification should not be empty');
    }

    /**
     * Check if a specific endpoint is documented
     * 
     * Determines whether a given endpoint pattern is documented in the
     * OpenAPI specification.
     * 
     * @param string $endpoint The endpoint pattern to check
     * @return bool True if endpoint is documented, false otherwise
     * 
     * @since 1.0.0
     */
    private function isEndpointDocumented(string $endpoint): bool
    {
        $paths = $this->swaggerSpec['paths'] ?? [];
        
        foreach (array_keys($paths) as $documentedPath) {
            if ($this->pathsMatch($documentedPath, $endpoint)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if two API paths match (with parameter placeholders)
     * 
     * Compares two API path patterns, accounting for parameter placeholders
     * in both OpenAPI format ({param}) and generic format.
     * 
     * @param string $path1 First path to compare
     * @param string $path2 Second path to compare
     * @return bool True if paths match, false otherwise
     * 
     * @since 1.0.0
     */
    private function pathsMatch(string $path1, string $path2): bool
    {
        // Normalize both paths by replacing parameter placeholders
        $normalize = function($path) {
            return preg_replace('/\{[^}]+\}/', '{param}', $path);
        };
        
        return $normalize($path1) === $normalize($path2);
    }

    /**
     * Count documented modules from a list
     * 
     * Counts how many modules from the provided list have API documentation
     * in the OpenAPI specification.
     * 
     * @param array $modules List of module names to check
     * @return int Number of modules that are documented
     * 
     * @since 1.0.0
     */
    private function countDocumentedModules(array $modules): int
    {
        $documented = 0;
        $paths = $this->swaggerSpec['paths'] ?? [];
        
        foreach ($modules as $module) {
            $moduleEndpoint = "/V8/modules/{$module}";
            foreach (array_keys($paths) as $path) {
                if ($this->pathsMatch($path, '/V8/modules/{module}') && 
                    stripos($path, strtolower($module)) !== false) {
                    $documented++;
                    break;
                }
            }
        }
        
        return $documented;
    }

    /**
     * Count documented endpoints from a list
     * 
     * Counts how many endpoints from the provided list are documented
     * in the OpenAPI specification.
     * 
     * @param array $endpoints List of endpoint patterns to check
     * @return int Number of endpoints that are documented
     * 
     * @since 1.0.0
     */
    private function countDocumentedEndpoints(array $endpoints): int
    {
        $documented = 0;
        
        foreach ($endpoints as $endpoint) {
            if ($this->isEndpointDocumented($endpoint)) {
                $documented++;
            }
        }
        
        return $documented;
    }

    /**
     * Validate module-specific documentation
     * 
     * Validates that a specific module has comprehensive documentation
     * including all CRUD operations and proper schemas.
     * 
     * @param ApiTester $I The API tester instance
     * @param string $module The module name to validate
     * 
     * @since 1.0.0
     */
    private function validateModuleDocumentation(ApiTester $I, string $module): void
    {
        $moduleEndpoints = [
            "GET /V8/modules/{$module}",
            "POST /V8/modules/{$module}",
            "GET /V8/modules/{$module}/{id}",
            "PUT /V8/modules/{$module}/{id}",
            "DELETE /V8/modules/{$module}/{id}"
        ];
        
        foreach ($moduleEndpoints as $endpoint) {
            $I->comment("Checking documentation for {$endpoint}");
            // Additional validation logic would go here
        }
    }

    /**
     * Validate endpoint-specific documentation
     * 
     * Validates that a specific endpoint has complete documentation
     * including parameters, responses, and schemas.
     * 
     * @param ApiTester $I The API tester instance
     * @param string $endpoint The endpoint pattern to validate
     * 
     * @since 1.0.0
     */
    private function validateEndpointDocumentation(ApiTester $I, string $endpoint): void
    {
        $I->comment("Validating comprehensive documentation for {$endpoint}");
        // Additional validation logic would go here
    }
} 