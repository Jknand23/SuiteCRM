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
use SuiteCRM\Robo\Traits\RoboTrait;
use SuiteCRM\Utility\Paths;

#[\AllowDynamicProperties]
class CodingStandardCommands extends Tasks
{
    use RoboTrait;

    /**
     * A tool to automatically fix all PHP coding standards issues (legacy PSR2).
     */
    public function stylePHPCSFixer()
    {
        $this->say('Coding Standards: PSR2');

        $paths = new Paths();
        $this->_exec('php vendor/bin/php-cs-fixer fix --path-mode=intersection ' . $paths->getProjectPath() . ' --verbose --show-progress=run-in --config=' . $paths->getProjectPath() . '/.php_cs.dist');
    }

    /**
     * Lints the codebase without modifying any files (legacy PSR2).
     */
    public function stylePHPCSFixerDryRun()
    {
        $this->say('Coding Standards: PSR2');

        $paths = new Paths();
        $result = $this->_exec('php vendor/bin/php-cs-fixer fix --dry-run --path-mode=intersection ' . $paths->getProjectPath() . ' --verbose --show-progress=run-in --config=' . $paths->getProjectPath() . '/.php_cs.dist');

        return $result;
    }

    /**
     * A tool to automatically fix all PHP coding standards issues in modified files (legacy PSR2).
     */
    public function stylePHPCSFixerModified()
    {
        $this->say('Coding Standards: PSR2');

        $paths = new Paths();
        $collection = $this->collectionBuilder();

        $collection->taskTmpFile()
            ->filename('diff.txt')
            ->getPath();
        $this->_exec('git diff --name-only --staged >> diff.txt');
        $this->_exec('git diff --name-only >> diff.txt');

        $lines = file('diff.txt', FILE_IGNORE_NEW_LINES);

        if (file_exists('diff.txt')) {
            foreach ($lines as $line) {
                $this->_exec('php vendor/bin/php-cs-fixer fix --path-mode=intersection ' . $paths->getProjectPath() . '/' . $line);
            }
        }
        $collection->run();
    }

    /**
     * Enhanced PHP-CS-Fixer with modern rules for new components.
     */
    public function stylePHPCSFixerModern()
    {
        $this->say('Enhanced Coding Standards: PSR12 + Modern Rules for New Components');

        $paths = new Paths();
        
        // Set environment variable to use modern rules
        putenv('PHP_CS_FIXER_MODE=modern');
        
        $this->_exec('php vendor/bin/php-cs-fixer fix --path-mode=intersection ' . $paths->getProjectPath() . ' --verbose --show-progress=run-in --config=' . $paths->getProjectPath() . '/.php_cs.dist');
        
        // Reset environment variable
        putenv('PHP_CS_FIXER_MODE=legacy');
    }

    /**
     * Enhanced PHP-CS-Fixer dry run with modern rules for new components.
     */
    public function stylePHPCSFixerModernDryRun()
    {
        $this->say('Enhanced Coding Standards Check: PSR12 + Modern Rules for New Components');

        $paths = new Paths();
        
        // Set environment variable to use modern rules
        putenv('PHP_CS_FIXER_MODE=modern');
        
        $result = $this->_exec('php vendor/bin/php-cs-fixer fix --dry-run --diff --path-mode=intersection ' . $paths->getProjectPath() . ' --verbose --show-progress=run-in --config=' . $paths->getProjectPath() . '/.php_cs.dist');
        
        // Reset environment variable
        putenv('PHP_CS_FIXER_MODE=legacy');
        
        return $result;
    }

    /**
     * Run Rector to modernize new components to PHP 7.4+ standards.
     */
    public function qualityRector()
    {
        $this->say('PHP Modernization: Rector Analysis');

        $paths = new Paths();
        $this->_exec('php vendor/bin/rector process --config=' . $paths->getProjectPath() . '/rector.php');
    }

    /**
     * Run Rector dry-run to see what would be modernized.
     */
    public function qualityRectorDryRun()
    {
        $this->say('PHP Modernization Analysis: Rector Dry Run');

        $paths = new Paths();
        $result = $this->_exec('php vendor/bin/rector process --dry-run --config=' . $paths->getProjectPath() . '/rector.php');
        
        return $result;
    }

    /**
     * Run PHPStan analysis on new modernized components.
     */
    public function qualityPHPStan()
    {
        $this->say('Static Analysis: PHPStan');

        $paths = new Paths();
        $this->_exec('php vendor/bin/phpstan analyse --configuration=' . $paths->getProjectPath() . '/phpstan.neon');
    }

    /**
     * Run comprehensive quality check on new components only.
     */
    public function qualityCheckNew()
    {
        $this->say('Comprehensive Quality Check: New Components Only');

        $exitCode = 0;

        // 1. PHP Syntax Check
        $this->say('Step 1/4: PHP Syntax Check');
        if (!$this->checkPHPSyntaxNewComponents()) {
            $exitCode = 1;
        }

        // 2. Enhanced PHP-CS-Fixer Check
        $this->say('Step 2/4: Enhanced Coding Standards Check');
        $result = $this->stylePHPCSFixerModernDryRun();
        if ($result->getExitCode() !== 0) {
            $exitCode = 1;
        }

        // 3. PHPStan Analysis
        $this->say('Step 3/4: Static Analysis');
        try {
            $this->qualityPHPStan();
        } catch (\Exception $e) {
            $this->say('PHPStan not available, using alternative validation...');
            if (!$this->runAlternativeValidation()) {
                $exitCode = 1;
            }
        }

        // 4. Rector Analysis
        $this->say('Step 4/4: Modernization Analysis');
        $result = $this->qualityRectorDryRun();
        // Don't fail for Rector suggestions, just inform

        if ($exitCode === 0) {
            $this->say('✅ All quality checks passed!');
        } else {
            $this->say('❌ Some quality checks failed. Please address the issues above.');
        }

        return $exitCode;
    }

    /**
     * Run comprehensive quality check on all staged files.
     */
    public function qualityCheckStaged()
    {
        $this->say('Comprehensive Quality Check: Staged Files');

        // Get staged files
        $stagedFiles = $this->getStagedPHPFiles();
        if (empty($stagedFiles)) {
            $this->say('No PHP files staged for commit.');
            return 0;
        }

        $this->say('Found ' . count($stagedFiles) . ' staged PHP files');

        $exitCode = 0;

        // Check syntax for all files
        if (!$this->checkPHPSyntaxFiles($stagedFiles)) {
            $exitCode = 1;
        }

        // Enhanced checks for new components
        $newComponentFiles = $this->getNewComponentFiles($stagedFiles);
        if (!empty($newComponentFiles)) {
            $this->say('Running enhanced checks on ' . count($newComponentFiles) . ' new component files');
            
            // Use modern rules for new components
            putenv('PHP_CS_FIXER_MODE=modern');
            foreach ($newComponentFiles as $file) {
                $result = $this->_exec('php vendor/bin/php-cs-fixer fix --dry-run --diff ' . $file);
                if ($result->getExitCode() !== 0) {
                    $exitCode = 1;
                }
            }
            putenv('PHP_CS_FIXER_MODE=legacy');
        }

        // Basic checks for legacy files
        $legacyFiles = array_diff($stagedFiles, $newComponentFiles);
        if (!empty($legacyFiles)) {
            $this->say('Running basic checks on ' . count($legacyFiles) . ' legacy files');
            
            foreach ($legacyFiles as $file) {
                $result = $this->_exec('php vendor/bin/php-cs-fixer fix --dry-run --diff ' . $file);
                if ($result->getExitCode() !== 0) {
                    $exitCode = 1;
                }
            }
        }

        return $exitCode;
    }

    /**
     * Setup pre-commit hook for quality checks.
     */
    public function qualitySetupPreCommitHook()
    {
        $this->say('Setting up pre-commit hook for quality checks');

        $paths = new Paths();
        $hookPath = $paths->getProjectPath() . '/.git/hooks/pre-commit';

        if (file_exists($hookPath)) {
            $this->say('Pre-commit hook already exists.');
            return;
        }

        // Copy our pre-commit hook
        $sourcePath = $paths->getProjectPath() . '/.git/hooks/pre-commit';
        if (!file_exists($sourcePath)) {
            $this->say('❌ Pre-commit hook template not found. Please ensure it exists in .git/hooks/pre-commit');
            return;
        }

        // Make executable (Unix-like systems)
        if (function_exists('chmod')) {
            chmod($hookPath, 0755);
        }

        $this->say('✅ Pre-commit hook setup completed!');
        $this->say('The hook will automatically run quality checks on commit.');
        $this->say('To skip checks: SKIP_QUALITY_CHECKS=true git commit');
    }

    /**
     * Check PHP syntax for new modernized components.
     */
    private function checkPHPSyntaxNewComponents(): bool
    {
        $paths = new Paths();
        $componentPaths = [
            'lib/Authentication',
            'Api/V8/Middleware',
            'Api/V8/Controller/EnhancedBaseController.php',
            'Api/V8/Controller/DocumentationController.php',
            'Api/V8/Service/OpenApiDocumentationService.php',
            'Api/V8/JsonApi/Response/EnhancedErrorResponse.php',
        ];

        $hasErrors = false;

        foreach ($componentPaths as $path) {
            $fullPath = $paths->getProjectPath() . '/' . $path;
            if (is_file($fullPath)) {
                $result = $this->_exec('php -l ' . $fullPath);
                if ($result->getExitCode() !== 0) {
                    $hasErrors = true;
                }
            } elseif (is_dir($fullPath)) {
                $files = glob($fullPath . '/*.php');
                foreach ($files as $file) {
                    $result = $this->_exec('php -l ' . $file);
                    if ($result->getExitCode() !== 0) {
                        $hasErrors = true;
                    }
                }
            }
        }

        return !$hasErrors;
    }

    /**
     * Check PHP syntax for specific files.
     */
    private function checkPHPSyntaxFiles(array $files): bool
    {
        $hasErrors = false;

        foreach ($files as $file) {
            if (file_exists($file)) {
                $result = $this->_exec('php -l ' . $file);
                if ($result->getExitCode() !== 0) {
                    $hasErrors = true;
                }
            }
        }

        return !$hasErrors;
    }

    /**
     * Run alternative validation when PHPStan is not available.
     */
    private function runAlternativeValidation(): bool
    {
        $paths = new Paths();
        $scriptPath = $paths->getProjectPath() . '/validate-new-components.php';

        if (file_exists($scriptPath)) {
            $result = $this->_exec('php ' . $scriptPath . ' --component=all');
            return $result->getExitCode() === 0;
        }

        $this->say('Alternative validation script not found.');
        return false;
    }

    /**
     * Get list of staged PHP files.
     */
    private function getStagedPHPFiles(): array
    {
        $output = shell_exec('git diff --cached --name-only --diff-filter=ACM | grep "\.php$"');
        if (empty($output)) {
            return [];
        }

        return array_filter(explode("\n", trim($output)));
    }

    /**
     * Identify new component files from a list of files.
     */
    private function getNewComponentFiles(array $files): array
    {
        $newComponentFiles = [];
        $newComponentPatterns = [
            'lib/Authentication/',
            'Api/V8/Middleware/',
            'Api/V8/Controller/Enhanced',
            'Api/V8/Controller/Documentation',
            'Api/V8/Service/OpenApi',
            'Api/V8/JsonApi/Response/Enhanced',
        ];

        foreach ($files as $file) {
            foreach ($newComponentPatterns as $pattern) {
                if (strpos($file, $pattern) === 0) {
                    $newComponentFiles[] = $file;
                    break;
                }
            }
        }

        return $newComponentFiles;
    }
}
