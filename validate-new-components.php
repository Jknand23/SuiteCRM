<?php
/**
 * @fileoverview Alternative Code Quality Validation Script
 * 
 * This script provides basic code quality validation for new SuiteCRM components
 * when PHPStan cannot be installed due to PHP 7.4 limitations. It performs
 * syntax checking, basic type analysis, and coding standard verification.
 * 
 * Usage: php validate-new-components.php [--component=name] [--verbose]
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

class ComponentValidator
{
    /** @var array $componentsToCheck List of new components to validate */
    private $componentsToCheck = [
        'OAuth2 Authentication' => [
            'lib/Authentication/OAuth2Service.php',
            'lib/Authentication/ProviderFactory.php',
            'lib/Authentication/SecurityValidator.php',
            'lib/Authentication/TokenManager.php',
            'lib/Authentication/UserLinker.php',
            'lib/Authentication/OAuth2AuthenticationProvider.php',
            'lib/Authentication/SecurityMonitoringService.php',
            'lib/Authentication/entrypoints/oauth2Authorize.php',
            'lib/Authentication/entrypoints/oauth2Callback.php',
        ],
        'Enhanced API Middleware' => [
            'Api/V8/Middleware/EnhancedValidationMiddleware.php',
            'Api/V8/Middleware/RequestLoggingMiddleware.php',
            'Api/V8/Middleware/RateLimitMiddleware.php',
            'Api/V8/Middleware/SecurityHeadersMiddleware.php',
            'Api/V8/Middleware/CorsMiddleware.php',
            'Api/V8/Middleware/ApiKeyAuthMiddleware.php',
        ],
        'Enhanced API Controllers' => [
            'Api/V8/Controller/EnhancedBaseController.php',
            'Api/V8/Controller/DocumentationController.php',
        ],
        'Enhanced API Services' => [
            'Api/V8/Service/OpenApiDocumentationService.php',
            'Api/V8/Service/MetaService.php',
        ],
        'Enhanced API Responses' => [
            'Api/V8/JsonApi/Response/EnhancedErrorResponse.php',
        ],
        'Enhanced Robo Commands' => [
            'lib/Robo/Plugin/Commands/ApiCommands.php',
            'lib/Robo/Plugin/Commands/BuildCommands.php',
        ],
    ];

    /** @var bool $verbose Enable verbose output */
    private $verbose = false;

    /** @var array $results Validation results */
    private $results = [
        'syntax_checks' => [],
        'type_analysis' => [],
        'coding_standards' => [],
        'summary' => ['passed' => 0, 'failed' => 0, 'warnings' => 0]
    ];

    /**
     * Constructor
     * 
     * @param bool $verbose Enable verbose output
     */
    public function __construct(bool $verbose = false)
    {
        $this->verbose = $verbose;
    }

    /**
     * Run validation on all components or specific component
     * 
     * @param string|null $componentFilter Filter to specific component group
     * @return array Validation results
     */
    public function validate(?string $componentFilter = null): array
    {
        $this->outputHeader();

        foreach ($this->componentsToCheck as $componentName => $files) {
            if ($componentFilter && stripos($componentName, $componentFilter) === false) {
                continue;
            }

            $this->output("\n=== Validating {$componentName} ===\n");
            
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $this->validateFile($file);
                } else {
                    $this->output("⚠️  File not found: {$file}\n", 'warning');
                    $this->results['summary']['warnings']++;
                }
            }
        }

        $this->outputSummary();
        return $this->results;
    }

    /**
     * Validate a single file
     * 
     * @param string $file Path to file to validate
     */
    private function validateFile(string $file): void
    {
        $this->output("Checking: {$file}\n");

        // 1. Syntax check
        $syntaxValid = $this->checkSyntax($file);
        
        // 2. Basic type analysis (only if syntax is valid)
        if ($syntaxValid) {
            $this->analyzeTypes($file);
            $this->checkCodingStandards($file);
        }
    }

    /**
     * Check PHP syntax
     * 
     * @param string $file File to check
     * @return bool True if syntax is valid
     */
    private function checkSyntax(string $file): bool
    {
        $output = [];
        $returnVar = 0;
        
        exec("php -l \"{$file}\" 2>&1", $output, $returnVar);
        
        if ($returnVar === 0) {
            $this->output("  ✅ Syntax: Valid\n");
            $this->results['syntax_checks'][$file] = ['status' => 'passed', 'message' => 'Valid syntax'];
            $this->results['summary']['passed']++;
            return true;
        } else {
            $errorMessage = implode("\n", $output);
            $this->output("  ❌ Syntax: Invalid - {$errorMessage}\n", 'error');
            $this->results['syntax_checks'][$file] = ['status' => 'failed', 'message' => $errorMessage];
            $this->results['summary']['failed']++;
            return false;
        }
    }

    /**
     * Analyze types using reflection
     * 
     * @param string $file File to analyze
     */
    private function analyzeTypes(string $file): void
    {
        try {
            // Include the file to load classes
            require_once $file;
            
            // Get all declared classes from the file
            $classes = $this->getClassesFromFile($file);
            
            foreach ($classes as $className) {
                if (class_exists($className)) {
                    $this->analyzeClass($className, $file);
                }
            }
        } catch (Exception $e) {
            $this->output("  ⚠️  Type Analysis: Error - {$e->getMessage()}\n", 'warning');
            $this->results['type_analysis'][$file] = ['status' => 'warning', 'message' => $e->getMessage()];
            $this->results['summary']['warnings']++;
        }
    }

    /**
     * Analyze a class for type-related issues
     * 
     * @param string $className Class to analyze
     * @param string $file Source file
     */
    private function analyzeClass(string $className, string $file): void
    {
        $reflection = new ReflectionClass($className);
        $issues = [];
        $warnings = [];

        // Check methods for return types and parameter types
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            // Skip magic methods
            if (strpos($method->getName(), '__') === 0) {
                continue;
            }

            // Check return type
            if (!$method->hasReturnType() && $method->getName() !== '__construct') {
                $warnings[] = "Method {$method->getName()} missing return type";
            }

            // Check parameter types
            foreach ($method->getParameters() as $param) {
                if (!$param->hasType() && !$param->isVariadic()) {
                    $warnings[] = "Parameter \${$param->getName()} in {$method->getName()} missing type hint";
                }
            }
        }

        // Check properties for type declarations (PHP 7.4+)
        foreach ($reflection->getProperties() as $property) {
            if (method_exists($property, 'hasType') && !$property->hasType()) {
                $warnings[] = "Property \${$property->getName()} missing type declaration";
            }
        }

        if (empty($issues) && empty($warnings)) {
            $this->output("  ✅ Type Analysis: Good type coverage\n");
            $this->results['type_analysis'][$file] = ['status' => 'passed', 'message' => 'Good type coverage'];
            $this->results['summary']['passed']++;
        } else {
            if (!empty($issues)) {
                $this->output("  ❌ Type Analysis: Issues found\n", 'error');
                $this->results['type_analysis'][$file] = ['status' => 'failed', 'issues' => $issues];
                $this->results['summary']['failed']++;
            } else {
                $this->output("  ⚠️  Type Analysis: Minor warnings\n", 'warning');
                $this->results['type_analysis'][$file] = ['status' => 'warning', 'warnings' => $warnings];
                $this->results['summary']['warnings']++;
            }

            if ($this->verbose) {
                foreach ($issues as $issue) {
                    $this->output("    • {$issue}\n", 'error');
                }
                foreach ($warnings as $warning) {
                    $this->output("    • {$warning}\n", 'warning');
                }
            }
        }
    }

    /**
     * Check basic coding standards
     * 
     * @param string $file File to check
     */
    private function checkCodingStandards(string $file): void
    {
        $content = file_get_contents($file);
        $issues = [];
        $warnings = [];

        // Check for file header documentation
        if (strpos($content, '@fileoverview') === false) {
            $warnings[] = 'Missing @fileoverview documentation';
        }

        // Check for consistent indentation (spaces vs tabs)
        $hasSpaces = strpos($content, '    ') !== false; // 4 spaces
        $hasTabs = strpos($content, "\t") !== false;
        if ($hasSpaces && $hasTabs) {
            $issues[] = 'Mixed indentation (spaces and tabs)';
        }

        // Check line length (basic check for very long lines)
        $lines = explode("\n", $content);
        $longLines = 0;
        foreach ($lines as $lineNum => $line) {
            if (strlen($line) > 120) {
                $longLines++;
            }
        }
        if ($longLines > 5) {
            $warnings[] = "Multiple lines exceed 120 characters ({$longLines} lines)";
        }

        // Check for proper namespace usage
        if (strpos($content, 'namespace ') === false && strpos($file, 'entrypoints') === false) {
            $warnings[] = 'Missing namespace declaration';
        }

        // Check for trailing whitespace (basic check)
        if (preg_match('/[ \t]+$/m', $content)) {
            $warnings[] = 'Trailing whitespace detected';
        }

        if (empty($issues) && empty($warnings)) {
            $this->output("  ✅ Coding Standards: Good\n");
            $this->results['coding_standards'][$file] = ['status' => 'passed', 'message' => 'Good coding standards'];
            $this->results['summary']['passed']++;
        } else {
            if (!empty($issues)) {
                $this->output("  ❌ Coding Standards: Issues found\n", 'error');
                $this->results['coding_standards'][$file] = ['status' => 'failed', 'issues' => $issues];
                $this->results['summary']['failed']++;
            } else {
                $this->output("  ⚠️  Coding Standards: Minor warnings\n", 'warning');
                $this->results['coding_standards'][$file] = ['status' => 'warning', 'warnings' => $warnings];
                $this->results['summary']['warnings']++;
            }

            if ($this->verbose) {
                foreach ($issues as $issue) {
                    $this->output("    • {$issue}\n", 'error');
                }
                foreach ($warnings as $warning) {
                    $this->output("    • {$warning}\n", 'warning');
                }
            }
        }
    }

    /**
     * Get classes defined in a file
     * 
     * @param string $file File to analyze
     * @return array Array of class names
     */
    private function getClassesFromFile(string $file): array
    {
        $content = file_get_contents($file);
        $classes = [];
        
        // Simple regex to find class declarations
        preg_match_all('/class\s+([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/i', $content, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $className) {
                // Try to determine the full namespace
                if (preg_match('/namespace\s+([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\\\\]*);/', $content, $nsMatches)) {
                    $classes[] = $nsMatches[1] . '\\' . $className;
                } else {
                    $classes[] = $className;
                }
            }
        }
        
        return $classes;
    }

    /**
     * Output header
     */
    private function outputHeader(): void
    {
        $this->output("🔍 SuiteCRM Component Validator (PHP 7.4 Compatible)\n");
        $this->output("=================================================\n");
        $this->output("Alternative code quality validation for new components\n");
    }

    /**
     * Output summary
     */
    private function outputSummary(): void
    {
        $summary = $this->results['summary'];
        $total = $summary['passed'] + $summary['failed'] + $summary['warnings'];
        
        $this->output("\n📊 Validation Summary\n");
        $this->output("====================\n");
        $this->output("Total Checks: {$total}\n");
        $this->output("✅ Passed: {$summary['passed']}\n");
        $this->output("❌ Failed: {$summary['failed']}\n");
        $this->output("⚠️  Warnings: {$summary['warnings']}\n");
        
        if ($summary['failed'] > 0) {
            $this->output("\n❌ VALIDATION FAILED: {$summary['failed']} critical issues found\n", 'error');
            $this->output("Please fix the issues before proceeding.\n");
        } elseif ($summary['warnings'] > 0) {
            $this->output("\n⚠️  VALIDATION PASSED WITH WARNINGS: {$summary['warnings']} warnings found\n", 'warning');
            $this->output("Consider addressing the warnings for better code quality.\n");
        } else {
            $this->output("\n✅ ALL VALIDATIONS PASSED: Code quality looks good!\n");
        }
    }

    /**
     * Output message with optional color coding
     * 
     * @param string $message Message to output
     * @param string $type Message type (error, warning, success)
     */
    private function output(string $message, string $type = 'normal'): void
    {
        echo $message;
    }
}

// CLI handling
if (php_sapi_name() === 'cli') {
    $componentFilter = null;
    $verbose = false;

    // Parse command line arguments
    $options = getopt('', ['component:', 'verbose', 'help']);
    
    if (isset($options['help'])) {
        echo "Usage: php validate-new-components.php [--component=name] [--verbose] [--help]\n";
        echo "\nOptions:\n";
        echo "  --component=name  Filter to specific component group\n";
        echo "  --verbose         Show detailed output\n";
        echo "  --help           Show this help message\n";
        echo "\nComponent groups:\n";
        echo "  - OAuth2\n";
        echo "  - Middleware\n";
        echo "  - Controllers\n";
        echo "  - Services\n";
        echo "  - Responses\n";
        echo "  - Commands\n";
        exit(0);
    }

    if (isset($options['component'])) {
        $componentFilter = $options['component'];
    }

    if (isset($options['verbose'])) {
        $verbose = true;
    }

    // Run validation
    $validator = new ComponentValidator($verbose);
    $results = $validator->validate($componentFilter);

    // Exit with appropriate code
    exit($results['summary']['failed'] > 0 ? 1 : 0);
} 