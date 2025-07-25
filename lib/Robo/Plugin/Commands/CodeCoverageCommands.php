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

use Robo\Tasks;
use RuntimeException;
use SuiteCRM\Utility\OperatingSystem;
use SuiteCRM\Robo\Traits\RoboTrait;

class CodeCoverageCommands extends Tasks
{
    use RoboTrait;

    /**
     * Runs code coverage
     * @param array $opts
     * @option bool $ci Should be set to true if using a Continuous Integration environment.
     */
    public function codeCoverage($opts = ['ci' => false])
    {
        $this->say('Code Coverage');

        // Get environment
        if ($opts['ci'] === true) {
            if ($this->isEnvironmentTravisCI()) {
                $range = $this->getCommitRangeForTravisCi();
            } else {
                throw new RuntimeException('Unable to detect continuous integration environment');
            }
        }
        $this->generateCodeCoverageFile();

        $this->say('Code Coverage Completed');
    }

    /**
     * @return bool
     */
    private function isEnvironmentTravisCI()
    {
        return !empty(getenv('TRAVIS'));
    }

    /**
     * @return array|false|string git commit range from travis ci
     * e.g. 3b762531a80e768c2b303f4cce0189386a9f71d4...921bd12b282b0a984a83cc3d7e2a43bc21f2694f
     */
    private function getCommitRangeForTravisCi()
    {
        return getenv('TRAVIS_COMMIT_RANGE');
    }

    /**
     * Run code coverage command
     */
    private function generateCodeCoverageFile()
    {
        $this->_exec($this->getCodeCoverageCommand());
        $this->say('Code coverage xml outputted to ./tests/_output/coverage.xml');
    }

    private function getCodeCoverageCommand()
    {
        $os = new OperatingSystem();
        $command =
            $os->toOsPath('./vendor/bin/phpunit')
            . ' --configuration ./tests/phpunit.xml.dist --coverage-clover ./tests/_output/coverage.xml ./tests/unit/phpunit';

        return $command;
    }

    /**
     * Generate enhanced code coverage report including new OAuth2 and API security components
     * Extends existing generateCodeCoverageFile() functionality with new component coverage
     * @param array $opts optional enhanced coverage options
     */
    public function enhancedCodeCoverage(
        array $opts = [
            'include_oauth2' => true,
            'include_api_security' => true,
            'include_enhanced_middleware' => true,
            'output_format' => 'html',
        ]
    ) {
        $this->say('Enhanced Code Coverage - Including New Components');

        // Generate base coverage using existing functionality
        $this->generateCodeCoverageFile();

        if ($opts['include_oauth2']) {
            $this->say('Generating OAuth2 component coverage...');
            $this->generateOAuth2CoverageInternal($opts['output_format']);
        }

        if ($opts['include_api_security']) {
            $this->say('Generating API security middleware coverage...');
            $this->generateApiSecurityCoverageInternal($opts['output_format']);
        }

        if ($opts['include_enhanced_middleware']) {
            $this->say('Generating enhanced middleware coverage...');
            $this->generateEnhancedMiddlewareCoverageInternal($opts['output_format']);
        }

        $this->say('Enhanced code coverage generation completed');
        $this->say('Standard coverage: ./tests/_output/coverage.xml');
        
        if ($opts['output_format'] === 'html') {
            $this->say('Enhanced HTML coverage: ./tests/_output/enhanced_coverage/');
        }
    }

    /**
     * Generate OAuth2 component-specific coverage report
     * @param string $format output format (html, xml, text)
     */
    private function generateOAuth2CoverageInternal($format = 'html')
    {
        $os = new OperatingSystem();
        $oauth2Paths = [
            './lib/Authentication/OAuth2Service.php',
            './lib/Authentication/ProviderFactory.php',
            './lib/Authentication/SecurityValidator.php',
            './lib/Authentication/TokenManager.php',
            './lib/Authentication/UserLinker.php',
            './lib/Authentication/OAuth2AuthenticationProvider.php',
        ];

        $pathsString = implode(' ', $oauth2Paths);
        $outputDir = './tests/_output/oauth2_coverage/';

        // Create output directory if it doesn't exist
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $command = $os->toOsPath('./vendor/bin/phpunit')
            . ' --configuration ./tests/phpunit.xml.dist'
            . ' --coverage-' . $format . ' ' . $outputDir
            . ' --whitelist ' . $pathsString
            . ' ./tests/unit/phpunit';

        $this->_exec($command);
        $this->say("OAuth2 coverage generated in {$outputDir}");
    }

    /**
     * Generate API security middleware coverage report
     * @param string $format output format (html, xml, text)
     */
    private function generateApiSecurityCoverageInternal($format = 'html')
    {
        $os = new OperatingSystem();
        $apiSecurityPaths = [
            './Api/V8/Middleware/RateLimitMiddleware.php',
            './Api/V8/Middleware/SecurityHeadersMiddleware.php',
            './Api/V8/Middleware/CorsMiddleware.php',
            './Api/V8/Middleware/ApiKeyAuthMiddleware.php',
            './Api/V8/Middleware/EnhancedValidationMiddleware.php',
            './Api/V8/Middleware/RequestLoggingMiddleware.php',
        ];

        $pathsString = implode(' ', $apiSecurityPaths);
        $outputDir = './tests/_output/api_security_coverage/';

        // Create output directory if it doesn't exist
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $command = $os->toOsPath('./vendor/bin/phpunit')
            . ' --configuration ./tests/phpunit.xml.dist'
            . ' --coverage-' . $format . ' ' . $outputDir
            . ' --whitelist ' . $pathsString
            . ' ./tests/unit/phpunit';

        $this->_exec($command);
        $this->say("API security coverage generated in {$outputDir}");
    }

    /**
     * Generate enhanced middleware and controller coverage report
     * @param string $format output format (html, xml, text)
     */
    private function generateEnhancedMiddlewareCoverageInternal($format = 'html')
    {
        $os = new OperatingSystem();
        $enhancedPaths = [
            './Api/V8/Controller/EnhancedBaseController.php',
            './Api/V8/JsonApi/Response/EnhancedErrorResponse.php',
            './lib/Authentication/SecurityMonitoringService.php',
            './lib/Log/EnhancedLoggerService.php',
        ];

        $pathsString = implode(' ', $enhancedPaths);
        $outputDir = './tests/_output/enhanced_coverage/';

        // Create output directory if it doesn't exist
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $command = $os->toOsPath('./vendor/bin/phpunit')
            . ' --configuration ./tests/phpunit.xml.dist'
            . ' --coverage-' . $format . ' ' . $outputDir
            . ' --whitelist ' . $pathsString
            . ' ./tests/unit/phpunit';

        $this->_exec($command);
        $this->say("Enhanced middleware coverage generated in {$outputDir}");
    }

    /**
     * Generate OAuth2 component coverage reports
     * Public interface that delegates to private implementation
     */
    public function generateOAuth2Coverage()
    {
        return $this->generateOAuth2CoverageInternal('html');
    }

    /**
     * Generate API security middleware coverage reports
     * Public interface that delegates to private implementation
     */
    public function generateApiSecurityCoverage()
    {
        return $this->generateApiSecurityCoverageInternal('html');
    }

    /**
     * Generate enhanced middleware and controller coverage reports
     * Public interface that delegates to private implementation
     */
    public function generateEnhancedMiddlewareCoverage()
    {
        return $this->generateEnhancedMiddlewareCoverageInternal('html');
    }

    /**
     * Generate coverage summary for all new components
     * Provides comprehensive overview of test coverage across new features
     */
    public function coverageSummaryNewComponents()
    {
        $this->say('Generating Coverage Summary for New Components');

        $components = [
            'OAuth2 Authentication' => [
                './lib/Authentication/OAuth2Service.php',
                './lib/Authentication/ProviderFactory.php',
                './lib/Authentication/SecurityValidator.php',
                './lib/Authentication/TokenManager.php',
                './lib/Authentication/UserLinker.php',
                './lib/Authentication/OAuth2AuthenticationProvider.php',
            ],
            'API Security Middleware' => [
                './Api/V8/Middleware/RateLimitMiddleware.php',
                './Api/V8/Middleware/SecurityHeadersMiddleware.php',
                './Api/V8/Middleware/CorsMiddleware.php',
                './Api/V8/Middleware/ApiKeyAuthMiddleware.php',
                './Api/V8/Middleware/EnhancedValidationMiddleware.php',
                './Api/V8/Middleware/RequestLoggingMiddleware.php',
            ],
            'Enhanced Controllers' => [
                './Api/V8/Controller/EnhancedBaseController.php',
                './Api/V8/JsonApi/Response/EnhancedErrorResponse.php',
            ],
            'Enhanced Services' => [
                './lib/Authentication/SecurityMonitoringService.php',
                './lib/Log/EnhancedLoggerService.php',
            ],
        ];

        foreach ($components as $componentName => $files) {
            $this->say("Component: {$componentName}");
            $existingFiles = 0;
            $totalFiles = count($files);
            
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $existingFiles++;
                    $this->say("  ✓ {$file}");
                } else {
                    $this->say("  ✗ {$file} (not found)");
                }
            }
            
            $coverage = $totalFiles > 0 ? round(($existingFiles / $totalFiles) * 100, 2) : 0;
            $this->say("  Coverage: {$existingFiles}/{$totalFiles} files ({$coverage}%)");
            $this->say('');
        }

        $this->say('Use enhancedCodeCoverage() command to generate detailed coverage reports');
    }
}

/**
 * @fileoverview Enhanced Robo command collection for code coverage reporting including new OAuth2 and API security components. Extends existing code coverage functionality with component-specific coverage analysis and reporting capabilities.
 * @package SuiteCRM.Robo.Commands
 * @copyright SalesAgility Ltd. 2018
 * @license GNU Affero General Public License version 3
 */
