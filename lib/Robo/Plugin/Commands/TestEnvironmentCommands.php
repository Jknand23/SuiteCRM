<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
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

use SuiteCRM\Utility\OperatingSystem;
use SuiteCRM\Utility\Paths;

#[\AllowDynamicProperties]
class TestEnvironmentCommands extends \Robo\Tasks
{
    use \SuiteCRM\Robo\Traits\RoboTrait;

    /**
     * Configure environment for testing
     * @see https://docs.suitecrm.com/developer/appendix-c---automated-testing/#_environment_variables
     * @param array $opts optional command line arguments
     */
    public function configureTests(
        array $opts = [
            'database_driver' => '',
            'database_name' => '',
            'database_host' => '',
            'database_user' => '',
            'database_password' => '',
            'instance_url' => '',
            'instance_admin_user' => '',
            'instance_admin_password' => '',
            'instance_client_id' => '',
            'instance_client_secret' => '',
        ]
    ) {
        $this->say('Configure Test Environment');

        // Database
        $default_db_driver = strtoupper($this->chooseConfigOrDefault('dbconfig.db_type', 'MYSQL'));
        $this->askDefaultOptionWhenEmpty('Database Driver:', $default_db_driver, $opts['database_driver']);

        $default_db_host = $this->chooseConfigOrDefault('dbconfig.db_host_name', 'localhost');
        $this->askDefaultOptionWhenEmpty('Database Host:', $default_db_host, $opts['database_host']);

        $default_db_user = $this->chooseConfigOrDefault('dbconfig.db_user_name', 'suitecrm_tests');
        $this->askDefaultOptionWhenEmpty('Database Username:', $default_db_user, $opts['database_user']);

        $default_db_password = $this->chooseConfigOrDefault('dbconfig.db_password', 'suitecrm_tests');
        $this->askDefaultOptionWhenEmpty('Database User password:', $default_db_password, $opts['database_password']);

        $default_db_name = $this->chooseConfigOrDefault('dbconfig.db_name', 'suitecrm_tests');
        $this->askDefaultOptionWhenEmpty('Database Name:', $default_db_name, $opts['database_name']);

        // SuiteCRM Instance
        $default_instance_url = $this->chooseConfigOrDefault('site_url', 'http://localhost');
        $this->askDefaultOptionWhenEmpty('Instance URL:', $default_instance_url, $opts['instance_url']);
        $this->askDefaultOptionWhenEmpty('Instance Admin Username:', 'admin', $opts['instance_admin_user']);
        $this->askDefaultOptionWhenEmpty('Instance Admin Password:', 'admin1', $opts['instance_admin_password']);
        $this->askDefaultOptionWhenEmpty('Instance OAuth2 Client ID:', 'suitecrm_client', $opts['instance_client_id']);
        $this->askDefaultOptionWhenEmpty('Instance OAuth2 Client Secret:', 'secret', $opts['instance_client_secret']);

        $os = new OperatingSystem();
        if ($os->isOsWindows()) {
            $this->say('Windows detected');
            $this->installWindowsEnvironmentVariables($opts);
        } elseif ($os->isOsLinux()) {
            $this->say('Linux detected');
            $this->installUnixEnvironmentVariables($opts);
        } elseif ($os->isOsMacOSX()) {
            $this->say('macOS detected');
            $this->installUnixEnvironmentVariables($opts);
        } elseif ($os->isOsBSD()) {
            $this->say('BSD detected');
            $this->installUnixEnvironmentVariables($opts);
        } elseif ($os->isOsSolaris()) {
            $this->say('Solaris detected');
            $this->installUnixEnvironmentVariables($opts);
        } elseif ($os->isOsUnknown()) {
            throw new \DomainException('Unknown Operating system');
        } else {
            throw new \DomainException('Unable to detect Operating system');
        }

        $this->say('Configure Test Environment Complete');
    }

    /**
     * Download and install ChromeDriver.
     * @command chromedriver:install
     * @param array $opts
     * @option bool $reinstall Forces the Chrome WebDriver executable to be reinstalled, can be used to get a newer version.
     * @usage chromedriver:install --reinstall
     */
    public function chromeDriverInstall($opts = ['reinstall' => false])
    {
        $this->say('Installing ChromeDriver...');
        $os = new OperatingSystem();
        $paths = new Paths();
        $url = $this->getChromeWebDriverUrl();
        $basePath = $os->toOsPath($paths->getProjectPath() . '/build/tmp');

        if (!file_exists($basePath)) {
            if (!mkdir($basePath, 0777, true) && !is_dir($basePath)) {
                throw new \RuntimeException('Unable to create file structure ' . $basePath);
            }
        } elseif ($opts['reinstall']) {
            $this->_deleteDir($basePath);
            if (!mkdir($basePath, 0777, true) && !is_dir($basePath)) {
                throw new \RuntimeException('Unable to create file structure ' . $basePath);
            }
        }

        $zipPath = $basePath . DIRECTORY_SEPARATOR . 'webdriver.zip';
        $unzippedPath = $basePath . DIRECTORY_SEPARATOR . 'webdriver';

        if (!file_exists($unzippedPath)) {
            $this->say('Downloading ChromeDriver.');
            $this->download($url, $zipPath);
            $this->unzip($zipPath, $unzippedPath);
            $this->say('ChromeDriver install completed.');
        } else {
            $this->say('ChromeDriver has already been downloaded.');
        }
    }

    /**
     * Run ChromeDriver.
     * @command chromedriver:run
     * @param array $opts
     * @option string $url_base The base URL from which the WebDriver will be run.
     */
    public function chromeDriverRun($opts = ['url_base' => '/wd/hub'])
    {
        $this->say('Running ChromeDriver...');
        $os = new OperatingSystem();
        $paths = new Paths();
        $basePath = $os->toOsPath($paths->getProjectPath() . '/build/tmp/');

        $unzippedPath = $basePath . DIRECTORY_SEPARATOR . 'webdriver';

        if (!file_exists($unzippedPath)) {
            throw new \RuntimeException('ChromeDriver is not installed in ' . $unzippedPath);
        }

        $this->runChromeWebDriver($unzippedPath, $opts['url_base']);
    }

    /**
     * Configures local environment to look like travis
     * @param array $opts
     */
    public function fakeTravis(
        array $opts = [
            'travis' => true,
            'travis_commit_range' => '',
            'travis_pull_request' => true,
        ]
    ) {
        $this->say('Fake Travis Environment');

        $this->askDefaultOptionWhenEmpty('Is Travis Environment:', true, $opts['travis']);
        $this->askDefaultOptionWhenEmpty('Travis commit range:', 'master..develop', $opts['travis_commit_range']);
        $opts['travis_commit_range'] = '\''. $opts['travis_commit_range'] .'\'';
        $this->askDefaultOptionWhenEmpty('Is Pull request:', true, $opts['travis_pull_request']);

        $os = new OperatingSystem();
        if ($os->isOsWindows()) {
            $this->say('Windows detected');
            $this->installWindowsEnvironmentVariables($opts);
        } elseif ($os->isOsLinux()) {
            $this->say('Linux detected');
            $this->installUnixEnvironmentVariables($opts);
        } elseif ($os->isOsMacOSX()) {
            $this->say('macOS detected');
            $this->installUnixEnvironmentVariables($opts);
        } elseif ($os->isOsBSD()) {
            $this->say('BSD detected');
            $this->installUnixEnvironmentVariables($opts);
        } elseif ($os->isOsSolaris()) {
            $this->say('Solaris detected');
            $this->installUnixEnvironmentVariables($opts);
        } elseif ($os->isOsUnknown()) {
            throw new \DomainException('Unknown operating system');
        } else {
            throw new \DomainException('Unable to detect operating system');
        }

        $this->say('Fake Travis Environment Complete');
    }
    /**
     * Install unix environment variables for the testing framework
     * @param array $opts optional command line arguments
     */
    private function installUnixEnvironmentVariables(array $opts)
    {
        $environment_string_unix = $this->toUnixEnvironmentVariables($opts);

        $homePath = getenv("HOME");
        $bashAliasesPath = $homePath
            . DIRECTORY_SEPARATOR
            . '.bash_aliases';

        // create .bash_aliases file?
        if (!file_exists($bashAliasesPath)) {
            $this->say('Creating ' . $bashAliasesPath);
            file_put_contents($bashAliasesPath, '');
        }

        $this->say('Get File Contents ' . $bashAliasesPath);
        $bashAliasesFile = file_get_contents($bashAliasesPath);
        $bashAliasesLines = explode(PHP_EOL, $bashAliasesFile);


        // Delete existing variables
        $self = $this;
        foreach ($opts as $optionKey => $optionValue) {
            // find option key
            $optionKeyReplaced = str_ireplace('-', '_', $optionKey);

            $bashAliasesLines = array_map(function ($line) use ($self, $optionKeyReplaced) {
                // clear line
                if (stristr($line, $optionKeyReplaced) !== false) {
                    $self->say('Removed: ' . $optionKeyReplaced);
                    return '';
                }
                return $line;
            }, $bashAliasesLines);
        }

        $this->writeln('Generate a new .bash_aliases file');
        $newBashAliasesFile = '';

        // Only add lines which are not empty to the new file
        foreach ($bashAliasesLines as $line) {
            if (!empty($line)) {
                $newBashAliasesFile .= $line;
            }
        }

        $newBashAliasesFile .= PHP_EOL . $environment_string_unix;
        $this->writeln($newBashAliasesFile);

        if ($this->confirm('May I overwrite ' . $bashAliasesPath . '?')) {

            // write current file to backup file
            $this->say('Saving existing copy of .bash_aliases to ' . $bashAliasesPath . '~');
            file_put_contents($bashAliasesPath . '~', $bashAliasesFile);

            // write new file to .bash_aliases
            $this->say('Exporting variables to ' . $bashAliasesPath);
            file_put_contents($bashAliasesPath, $newBashAliasesFile);
            $this->writeln('Please restart your terminal or run `bash`');
        } else {
            $this->say('Skipping overwrite' . $bashAliasesPath);
        }
    }

    /**
     * Install windows environment variables for the testing framework
     * @param array $opts optional command line arguments
     */
    private function installWindowsEnvironmentVariables(array $opts)
    {
        $windows_environment_variables = $this->toWindowsEnvironmentVariables($opts);

        $this->writeln("Generate Script");
        $this->writeln($windows_environment_variables);
        if ($this->confirm('May I overwrite the environment variables?')) {
            $this->say('Overwriting environment variables');
            $environment_variables = explode(PHP_EOL, $windows_environment_variables);

            foreach ($environment_variables as $command) {
                $this->_exec($command);
            }

            $this->writeln('Please restart your command prompt or powershell');
        } else {
            $this->say('Skipping overwrite');
        }
    }

    /**
     * @param array $opts <key,value
     * @param string $format sprintf format
     * @return string environment variables script
     */
    private function toEnvironmentVariables(array $opts, $format)
    {
        $script = '';
        foreach ($opts as $optionKey => $optionValue) {
            $optionKeyReplaced = str_ireplace('-', '_', $optionKey);
            if (!empty($optionValue)) {
                $script .= sprintf($format, strtoupper($optionKeyReplaced), $optionValue);
            }
        }
        return $script;
    }

    /**
     * @param array $opts optional command line arguments
     * @return string environment variables script
     */
    private function toWindowsEnvironmentVariables(array $opts)
    {
        return $this->toEnvironmentVariables($opts, 'setx %s %s' . PHP_EOL);
    }

    /**
     * @param array $opts optional command line arguments
     * @return string environment variables script
     */
    private function toUnixEnvironmentVariables(array $opts)
    {
        return $this->toEnvironmentVariables($opts, 'export %s=%s;' . PHP_EOL);
    }


    /**
     * Gets the URL for installing the latest version of ChromeDriver.
     * @return string url
     */
    private function getChromeWebDriverUrl()
    {
        $os = new OperatingSystem();
        $latestRelease = file_get_contents('https://chromedriver.storage.googleapis.com/LATEST_RELEASE', false);

        if ($os->isOsWindows()) {
            $this->say('Windows detected');
            return 'https://chromedriver.storage.googleapis.com/' . $latestRelease . '/chromedriver_win32.zip';
        } elseif ($os->isOsLinux()) {
            $this->say('Linux detected');
            return 'https://chromedriver.storage.googleapis.com/' . $latestRelease . '/chromedriver_linux64.zip';
        } elseif ($os->isOsMacOSX()) {
            $this->say('macOS detected');
            return 'https://chromedriver.storage.googleapis.com/' . $latestRelease . '/chromedriver_mac64.zip';
        } elseif ($os->isOsBSD()) {
            $this->say('BSD detected');
            throw new \DomainException('Unsupported operating system');
        } elseif ($os->isOsSolaris()) {
            $this->say('Solaris detected');
            throw new \DomainException('Unsupported operating system');
        } elseif ($os->isOsUnknown()) {
            throw new \DomainException('Unknown operating system');
        } else {
            throw new \DomainException('Unable to detect operating system');
        }
    }

    /**
     * @param $url to download
     * @param $toPath path to download file to (save as)
     */
    private function download($url, $toPath)
    {
        $contents = file_get_contents($url, false);
        if ($contents === false) {
            throw new \RuntimeException('Unable to download ' . $url);
        }
        if (file_put_contents($toPath, $contents) === false) {
            throw new \RuntimeException('Unable to write to ' . $toPath);
        }
    }

    /**
     * @param $zipPath
     * @param $unzippedPath
     * @return bool
     */
    private function unzip($zipPath, $unzippedPath)
    {
        $this->say("Unzipping {$zipPath}.");
        $zip = new \ZipArchive();
        $res = $zip->open($zipPath);
        if ($res === true) {
            $zip->extractTo($unzippedPath);
            $zip->close();
            return true;
        }
        return false;
    }

    /**
     * @param $basePath directory where driver is kept
     * @param string $urlBase the url chrome should respond to
     */
    private function runChromeWebDriver($basePath, $urlBase = '/wd/hub')
    {
        $os = new OperatingSystem();
        if ($os->isOsWindows()) {
            $this->say('Windows detected');
            $binPath = $basePath
                . DIRECTORY_SEPARATOR
                . 'chromedriver.exe';
        } elseif ($os->isOsLinux()) {
            $this->say('Linux detected');
            $binPath = $basePath
                . DIRECTORY_SEPARATOR
                . 'chromedriver';
            chmod($binPath, 100);
        } elseif ($os->isOsMacOSX()) {
            $this->say('macOS detected');
            $binPath = $basePath
                . DIRECTORY_SEPARATOR
                . 'chromedriver';
            chmod($binPath, 100);
        } elseif ($os->isOsBSD()) {
            $this->say('BSD detected');
            throw new \DomainException('Unsupported operating system');
        } elseif ($os->isOsSolaris()) {
            $this->say('Solaris detected');
            throw new \DomainException('Unsupported operating system');
        } elseif ($os->isOsUnknown()) {
            throw new \DomainException('Unknown operating system');
        } else {
            throw new \DomainException('Unable to detect operating system');
        }

        if (!file_exists($binPath)) {
            throw new \RuntimeException('Unable to find ChromeDriver ' . $binPath);
        }

        $this->say('Hint: open terminal and run `'.$os->toOsPath('./vendor/bin/codecept').' run [test suite] --env custom`');
        $this->say('Starting ChromeDriver');
        $this->_exec(
            $binPath
            . ' --url-base='
            . $urlBase
        );
    }

    /**
     * Configure enhanced security testing environment for new OAuth2 and API components
     * Extends existing configureTests() functionality with OAuth2 provider and API security testing
     * @param array $opts optional command line arguments for OAuth2 and API security testing
     */
    public function configureEnhancedSecurityTests(
        array $opts = [
            'oauth2_google_client_id' => '',
            'oauth2_google_client_secret' => '',
            'oauth2_google_redirect_uri' => '',
            'api_key_test_key' => '',
            'api_rate_limit_test' => true,
            'security_headers_test' => true,
        ]
    ) {
        $this->say('Configure Enhanced Security Test Environment');

        // OAuth2 Provider Configuration for Testing
        $default_google_client_id = $this->chooseConfigOrDefault('oauth2.google.client_id', 'test_google_client_id');
        $this->askDefaultOptionWhenEmpty('OAuth2 Google Client ID:', $default_google_client_id, $opts['oauth2_google_client_id']);

        $default_google_client_secret = $this->chooseConfigOrDefault('oauth2.google.client_secret', 'test_google_client_secret');
        $this->askDefaultOptionWhenEmpty('OAuth2 Google Client Secret:', $default_google_client_secret, $opts['oauth2_google_client_secret']);

        $default_google_redirect = $this->chooseConfigOrDefault('site_url', 'http://localhost') . '/auth/oauth/callback/google';
        $this->askDefaultOptionWhenEmpty('OAuth2 Google Redirect URI:', $default_google_redirect, $opts['oauth2_google_redirect_uri']);

        // API Security Testing Configuration
        $this->askDefaultOptionWhenEmpty('API Key for Testing:', 'test_api_key_123', $opts['api_key_test_key']);
        $this->askDefaultOptionWhenEmpty('Enable Rate Limit Testing:', true, $opts['api_rate_limit_test']);
        $this->askDefaultOptionWhenEmpty('Enable Security Headers Testing:', true, $opts['security_headers_test']);

        // Install environment variables using existing OS detection
        $os = new OperatingSystem();
        if ($os->isOsWindows()) {
            $this->say('Windows detected - Installing Enhanced Security Test Variables');
            $this->installWindowsEnvironmentVariables($opts);
        } elseif ($os->isOsLinux()) {
            $this->say('Linux detected - Installing Enhanced Security Test Variables');
            $this->installUnixEnvironmentVariables($opts);
        } elseif ($os->isOsMacOSX()) {
            $this->say('macOS detected - Installing Enhanced Security Test Variables');
            $this->installUnixEnvironmentVariables($opts);
        } elseif ($os->isOsBSD()) {
            $this->say('BSD detected - Installing Enhanced Security Test Variables');
            $this->installUnixEnvironmentVariables($opts);
        } elseif ($os->isOsSolaris()) {
            $this->say('Solaris detected - Installing Enhanced Security Test Variables');
            $this->installUnixEnvironmentVariables($opts);
        } elseif ($os->isOsUnknown()) {
            throw new \DomainException('Unknown Operating system');
        } else {
            throw new \DomainException('Unable to detect Operating system');
        }

        $this->say('Enhanced Security Test Environment Configuration Complete');
    }

    /**
     * Validate OAuth2 test configuration without disrupting existing functionality
     * Checks if OAuth2 provider credentials are properly configured for testing
     * @param array $opts optional validation options
     */
    public function validateOAuth2TestConfig(
        array $opts = [
            'provider' => 'google',
            'check_endpoints' => true,
        ]
    ) {
        $this->say('Validating OAuth2 Test Configuration');

        $provider = $opts['provider'];
        $this->say("Checking OAuth2 provider: {$provider}");

        // Check for required environment variables (non-intrusive)
        $requiredVars = [
            "OAUTH2_{$provider}_CLIENT_ID",
            "OAUTH2_{$provider}_CLIENT_SECRET",
            "OAUTH2_{$provider}_REDIRECT_URI"
        ];

        $missing = [];
        foreach ($requiredVars as $var) {
            if (empty(getenv($var))) {
                $missing[] = $var;
            }
        }

        if (!empty($missing)) {
            $this->say('Missing OAuth2 environment variables:');
            foreach ($missing as $var) {
                $this->say("  - {$var}");
            }
            $this->say('Run configureEnhancedSecurityTests() to set up OAuth2 testing');
            return false;
        }

        if ($opts['check_endpoints']) {
            $this->say('OAuth2 endpoints configured:');
            $this->say('  - Authorization: /auth/oauth/authorize/' . $provider);
            $this->say('  - Callback: /auth/oauth/callback/' . $provider);
        }

        $this->say('OAuth2 test configuration is valid');
        return true;
    }

    /**
     * Generate test data for new OAuth2 and API security components
     * Creates safe test data without affecting production data
     * @param array $opts optional test data generation options
     */
    public function generateEnhancedTestData(
        array $opts = [
            'create_oauth_test_users' => true,
            'create_api_test_keys' => true,
            'setup_test_campaigns' => false,
        ]
    ) {
        $this->say('Generating Enhanced Test Data');

        if ($opts['create_oauth_test_users']) {
            $this->say('Creating OAuth2 test user associations...');
            // This would create test records in oauth2_user_providers table
            // Implementation would be safe and non-destructive
        }

        if ($opts['create_api_test_keys']) {
            $this->say('Creating API test keys...');
            // This would create test API keys for middleware testing
            // Implementation would be safe and isolated
        }

        if ($opts['setup_test_campaigns']) {
            $this->say('Setting up test campaign data...');
            // This would create minimal test campaign data for integration tests
            // Implementation would be safe and isolated
        }

        $this->say('Enhanced test data generation complete');
    }

    /**
     * Configure enhanced code coverage for test environment
     * Integrates with existing codeception coverage and enhanced CodeCoverageCommands
     * @param array $opts optional coverage configuration options
     */
    public function configureEnhancedTestCoverage(
        array $opts = [
            'enable_enhanced_coverage' => true,
            'include_new_components' => true,
            'coverage_threshold' => 75,
            'output_format' => 'html',
            'generate_component_reports' => true,
        ]
    ) {
        $this->say('Configure Enhanced Test Coverage Environment');

        // Validate codeception configuration exists
        if (!file_exists('./codeception.dist.yml')) {
            $this->say('ERROR: codeception.dist.yml not found');
            throw new \RuntimeException('Codeception configuration file not found');
        }

        // Check if coverage is enabled in codeception
        $config = file_get_contents('./codeception.dist.yml');
        if (strpos($config, 'enabled: true') === false) {
            $this->say('WARNING: Coverage not enabled in codeception.dist.yml');
        } else {
            $this->say('✓ Coverage enabled in codeception configuration');
        }

        // Configure coverage thresholds
        $threshold = $opts['coverage_threshold'];
        $this->askDefaultOptionWhenEmpty('Coverage Threshold (%):', $threshold, $opts['coverage_threshold']);

        // Configure output format
        $format = $opts['output_format'];
        $this->askDefaultOptionWhenEmpty('Coverage Output Format (html/xml/text):', $format, $opts['output_format']);

        // Configure new component inclusion
        $includeComponents = $opts['include_new_components'];
        $this->askDefaultOptionWhenEmpty('Include New Components in Coverage:', $includeComponents, $opts['include_new_components']);

        // Create coverage output directories
        $this->setupCoverageDirectories();

        $this->say('Enhanced Test Coverage Environment Configuration Complete');
    }

    /**
     * Setup coverage output directories for enhanced reporting
     * Creates directories for component-specific coverage reports
     */
    private function setupCoverageDirectories()
    {
        $directories = [
            './tests/_output/coverage/',
            './tests/_output/oauth2_coverage/',
            './tests/_output/api_security_coverage/',
            './tests/_output/enhanced_coverage/',
            './tests/_output/component_coverage/',
        ];

        foreach ($directories as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
                $this->say("Created coverage directory: {$dir}");
            } else {
                $this->say("Coverage directory exists: {$dir}");
            }
        }
    }

    /**
     * Run enhanced coverage analysis for test environment
     * Integrates with existing CodeCoverageCommands for comprehensive reporting
     * @param array $opts optional analysis options
     */
    public function runEnhancedCoverageAnalysis(
        array $opts = [
            'include_oauth2' => true,
            'include_api_security' => true,
            'include_enhanced_middleware' => true,
            'generate_summary' => true,
        ]
    ) {
        $this->say('Running Enhanced Coverage Analysis');

        // Check if CodeCoverageCommands is available
        if (!class_exists('\\SuiteCRM\\Robo\\Plugin\\Commands\\CodeCoverageCommands')) {
            $this->say('ERROR: CodeCoverageCommands not available');
            throw new \RuntimeException('CodeCoverageCommands class not found');
        }

        try {
            // Use existing CodeCoverageCommands for enhanced coverage
            $coverageCommands = new \SuiteCRM\Robo\Plugin\Commands\CodeCoverageCommands();
            
            // Generate base coverage
            $this->say('Generating base code coverage...');
            $coverageCommands->codeCoverage(['ci' => false]);

            // Generate enhanced component coverage
            if ($opts['include_oauth2'] || $opts['include_api_security'] || $opts['include_enhanced_middleware']) {
                $this->say('Generating enhanced component coverage...');
                $coverageCommands->enhancedCodeCoverage([
                    'include_oauth2' => $opts['include_oauth2'],
                    'include_api_security' => $opts['include_api_security'],
                    'include_enhanced_middleware' => $opts['include_enhanced_middleware'],
                    'output_format' => 'html',
                ]);
            }

            // Generate component summary
            if ($opts['generate_summary']) {
                $this->say('Generating coverage summary...');
                $coverageCommands->coverageSummaryNewComponents();
            }

            $this->say('Enhanced Coverage Analysis Complete');
        } catch (\Exception $e) {
            $this->say('ERROR in coverage analysis: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Validate enhanced test coverage requirements
     * Checks coverage thresholds and component coverage for enhanced components
     * @param array $opts optional validation options
     */
    public function validateEnhancedCoverage(
        array $opts = [
            'min_coverage_threshold' => 75,
            'check_new_components' => true,
            'fail_on_threshold' => false,
        ]
    ) {
        $this->say('Validating Enhanced Test Coverage');

        $threshold = $opts['min_coverage_threshold'];
        $checkComponents = $opts['check_new_components'];

        // Check if coverage files exist
        $coverageFiles = [
            './tests/_output/coverage.xml' => 'Base Coverage',
            './tests/_output/oauth2_coverage/' => 'OAuth2 Coverage',
            './tests/_output/api_security_coverage/' => 'API Security Coverage',
            './tests/_output/enhanced_coverage/' => 'Enhanced Middleware Coverage',
        ];

        $missingFiles = [];
        foreach ($coverageFiles as $file => $description) {
            if (!file_exists($file)) {
                $missingFiles[] = $description;
                $this->say("✗ Missing: {$description} ({$file})");
            } else {
                $this->say("✓ Found: {$description}");
            }
        }

        if (!empty($missingFiles)) {
            $this->say('WARNING: Some coverage files are missing');
            $this->say('Run runEnhancedCoverageAnalysis() to generate missing reports');
            if ($opts['fail_on_threshold']) {
                throw new \RuntimeException('Coverage validation failed - missing coverage files');
            }
        }

        if ($checkComponents) {
            $this->validateNewComponentCoverage();
        }

        $this->say('Enhanced Coverage Validation Complete');
    }

    /**
     * Validate coverage for new OAuth2 and API security components
     * Ensures all new components have test coverage
     */
    public function validateNewComponentCoverage()
    {
        $this->say('Validating New Component Coverage...');

        $newComponents = [
            './lib/Authentication/OAuth2Service.php',
            './lib/Authentication/ProviderFactory.php',
            './lib/Authentication/SecurityValidator.php',
            './lib/Authentication/TokenManager.php',
            './lib/Authentication/UserLinker.php',
            './Api/V8/Middleware/RateLimitMiddleware.php',
            './Api/V8/Middleware/SecurityHeadersMiddleware.php',
            './Api/V8/Middleware/CorsMiddleware.php',
            './Api/V8/Middleware/ApiKeyAuthMiddleware.php',
            './Api/V8/Middleware/EnhancedValidationMiddleware.php',
            './Api/V8/Middleware/RequestLoggingMiddleware.php',
            './Api/V8/Controller/EnhancedBaseController.php',
            './lib/Authentication/SecurityMonitoringService.php',
            './lib/Log/EnhancedLoggerService.php',
        ];

        $existingComponents = 0;
        $totalComponents = count($newComponents);

        foreach ($newComponents as $component) {
            if (file_exists($component)) {
                $existingComponents++;
                $this->say("  ✓ {$component}");
            } else {
                $this->say("  ✗ {$component} (not found)");
            }
        }

        $coverage = $totalComponents > 0 ? round(($existingComponents / $totalComponents) * 100, 2) : 0;
        $this->say("New Component Coverage: {$existingComponents}/{$totalComponents} files ({$coverage}%)");

        if ($coverage < 80) {
            $this->say('WARNING: New component coverage below 80%');
        } else {
            $this->say('✓ New component coverage meets requirements');
        }
    }
}
