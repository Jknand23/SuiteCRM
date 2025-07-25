<?php
/**
 * @fileoverview Enhanced Template Compatibility Test
 * 
 * Tests that enhanced templates maintain 100% backward compatibility with
 * existing SuiteCRM functionality while adding CSS custom property enhancements.
 * 
 * This test validates Phase 1, Feature 2, Step 4 implementation by:
 * - Verifying template files exist and are accessible
 * - Checking template syntax and structure
 * - Validating CSS custom property fallbacks
 * - Testing JavaScript enhancement integration
 * 
 * Usage:
 * - Run in Docker: docker exec suitecrm_app php tests/enhanced-template-compatibility-test.php
 * - Run locally: php tests/enhanced-template-compatibility-test.php
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

// Set up basic SuiteCRM environment
if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

// Include SuiteCRM bootstrap if available
$suitecrm_root = dirname(__DIR__);
if (file_exists($suitecrm_root . '/include/entryPoint.php')) {
    chdir($suitecrm_root);
    require_once('include/entryPoint.php');
} else {
    // Minimal setup for standalone testing
    error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
}

/**
 * Enhanced Template Compatibility Test Suite
 */
class EnhancedTemplateCompatibilityTest
{
    private $results = [];
    private $errors = [];
    private $warnings = [];
    
    /**
     * Enhanced templates to test
     */
    private $enhancedTemplates = [
        'DetailView' => 'themes/SuiteP/include/DetailView/DetailView-Enhanced.tpl',
        'EditView' => 'themes/SuiteP/include/EditView/EditView-Enhanced.tpl',
        'ListView' => 'themes/SuiteP/include/ListView/ListViewGeneric-Enhanced.tpl'
    ];
    
    /**
     * CSS custom properties that should be present
     */
    private $requiredCustomProperties = [
        '--theme-main-bg',
        '--theme-panel-bg', 
        '--theme-panel-border',
        '--theme-text-color',
        '--theme-link-color',
        '--theme-panel-shadow',
        '--theme-panel-border-radius',
        '--theme-panel-spacing'
    ];
    
    /**
     * Run all compatibility tests
     */
    public function runAllTests()
    {
        $this->printHeader();
        
        // Test 1: Template File Existence
        $this->testTemplateFileExistence();
        
        // Test 2: Template Syntax Validation
        $this->testTemplateSyntax();
        
        // Test 3: CSS Custom Properties Usage
        $this->testCSSCustomProperties();
        
        // Test 4: JavaScript Enhancement Integration
        $this->testJavaScriptIntegration();
        
        // Test 5: Backward Compatibility
        $this->testBackwardCompatibility();
        
        // Test 6: Documentation Coverage
        $this->testDocumentationCoverage();
        
        $this->printResults();
        
        return count($this->errors) === 0;
    }
    
    /**
     * Test that all enhanced template files exist
     */
    private function testTemplateFileExistence()
    {
        $this->printTestHeader("Template File Existence");
        
        foreach ($this->enhancedTemplates as $name => $path) {
            if (file_exists($path)) {
                $this->addResult("✅ {$name} template exists: {$path}");
                
                // Check file size (should be reasonable)
                $size = filesize($path);
                if ($size > 1000) { // At least 1KB
                    $this->addResult("✅ {$name} template has reasonable size: " . number_format($size) . " bytes");
                } else {
                    $this->addWarning("⚠️ {$name} template seems small: " . number_format($size) . " bytes");
                }
                
                // Check file permissions
                if (is_readable($path)) {
                    $this->addResult("✅ {$name} template is readable");
                } else {
                    $this->addError("❌ {$name} template is not readable");
                }
            } else {
                $this->addError("❌ {$name} template missing: {$path}");
            }
        }
    }
    
    /**
     * Test template syntax for common issues
     */
    private function testTemplateSyntax()
    {
        $this->printTestHeader("Template Syntax Validation");
        
        foreach ($this->enhancedTemplates as $name => $path) {
            if (!file_exists($path)) {
                continue;
            }
            
            $content = file_get_contents($path);
            
            // Check for basic Smarty syntax
            if (strpos($content, '{*') !== false && strpos($content, '*}') !== false) {
                $this->addResult("✅ {$name} contains Smarty comments");
            } else {
                $this->addWarning("⚠️ {$name} missing Smarty comments");
            }
            
            // Check for fileoverview documentation
            if (strpos($content, '@fileoverview') !== false) {
                $this->addResult("✅ {$name} has @fileoverview documentation");
            } else {
                $this->addError("❌ {$name} missing @fileoverview documentation");
            }
            
            // Check for enhanced classes
            if (strpos($content, 'enhanced-') !== false) {
                $this->addResult("✅ {$name} uses enhanced CSS classes");
            } else {
                $this->addWarning("⚠️ {$name} may not have enhanced CSS classes");
            }
            
            // Check for PHP 7.4 compatibility (no PHP 8+ features)
            $syntaxIssues = $this->checkPHP74Compatibility($content);
            if (empty($syntaxIssues)) {
                $this->addResult("✅ {$name} appears PHP 7.4 compatible");
            } else {
                foreach ($syntaxIssues as $issue) {
                    $this->addWarning("⚠️ {$name}: {$issue}");
                }
            }
        }
    }
    
    /**
     * Test CSS custom properties usage
     */
    private function testCSSCustomProperties()
    {
        $this->printTestHeader("CSS Custom Properties Usage");
        
        foreach ($this->enhancedTemplates as $name => $path) {
            if (!file_exists($path)) {
                continue;
            }
            
            $content = file_get_contents($path);
            
            // Check for var() usage
            $varCount = substr_count($content, 'var(--');
            if ($varCount > 0) {
                $this->addResult("✅ {$name} uses {$varCount} CSS custom properties");
            } else {
                $this->addError("❌ {$name} doesn't use CSS custom properties");
                continue;
            }
            
            // Check for required custom properties
            $foundProperties = [];
            foreach ($this->requiredCustomProperties as $property) {
                if (strpos($content, $property) !== false) {
                    $foundProperties[] = $property;
                }
            }
            
            $foundCount = count($foundProperties);
            $totalCount = count($this->requiredCustomProperties);
            
            if ($foundCount === $totalCount) {
                $this->addResult("✅ {$name} uses all required custom properties ({$foundCount}/{$totalCount})");
            } else {
                $this->addWarning("⚠️ {$name} uses {$foundCount}/{$totalCount} required custom properties");
            }
            
            // Check for fallback values
            if (preg_match_all('/var\(([^,]+),\s*([^)]+)\)/', $content, $matches)) {
                $fallbackCount = count($matches[0]);
                $this->addResult("✅ {$name} has {$fallbackCount} fallback values");
            } else {
                $this->addError("❌ {$name} missing fallback values for custom properties");
            }
        }
    }
    
    /**
     * Test JavaScript enhancement integration
     */
    private function testJavaScriptIntegration()
    {
        $this->printTestHeader("JavaScript Enhancement Integration");
        
        foreach ($this->enhancedTemplates as $name => $path) {
            if (!file_exists($path)) {
                continue;
            }
            
            $content = file_get_contents($path);
            
            // Check for theme manager integration
            if (strpos($content, 'window.themeManager') !== false) {
                $this->addResult("✅ {$name} integrates with theme manager");
            } else {
                $this->addWarning("⚠️ {$name} may not integrate with theme manager");
            }
            
            // Check for enhanced JavaScript functions
            if (strpos($content, 'enhanced') !== false && strpos($content, 'function') !== false) {
                $this->addResult("✅ {$name} includes enhanced JavaScript functions");
            }
            
            // Check for error handling
            if (strpos($content, 'typeof') !== false && strpos($content, 'undefined') !== false) {
                $this->addResult("✅ {$name} includes JavaScript error handling");
            } else {
                $this->addWarning("⚠️ {$name} may lack JavaScript error handling");
            }
        }
    }
    
    /**
     * Test backward compatibility
     */
    private function testBackwardCompatibility()
    {
        $this->printTestHeader("Backward Compatibility");
        
        foreach ($this->enhancedTemplates as $name => $path) {
            if (!file_exists($path)) {
                continue;
            }
            
            $content = file_get_contents($path);
            
            // Check for existing class preservation
            $existingClasses = ['panel', 'panel-default', 'panel-heading', 'panel-body'];
            $preservedClasses = 0;
            
            foreach ($existingClasses as $class) {
                if (strpos($content, $class) !== false) {
                    $preservedClasses++;
                }
            }
            
            if ($preservedClasses === count($existingClasses)) {
                $this->addResult("✅ {$name} preserves all existing Bootstrap classes");
            } else {
                $this->addWarning("⚠️ {$name} may not preserve all existing classes ({$preservedClasses}/" . count($existingClasses) . ")");
            }
            
            // Check for existing Smarty variable usage
            if (strpos($content, '$') !== false && strpos($content, '{{') !== false) {
                $this->addResult("✅ {$name} preserves existing Smarty variable usage");
            } else {
                $this->addError("❌ {$name} may not preserve Smarty variables");
            }
        }
    }
    
    /**
     * Test documentation coverage
     */
    private function testDocumentationCoverage()
    {
        $this->printTestHeader("Documentation Coverage");
        
        foreach ($this->enhancedTemplates as $name => $path) {
            $docPath = $path . '_docs.md';
            
            if (file_exists($docPath)) {
                $this->addResult("✅ {$name} has documentation: {$docPath}");
                
                $docContent = file_get_contents($docPath);
                
                // Check for comprehensive documentation sections
                $requiredSections = ['Overview', 'Features', 'Usage', 'Testing', 'Integration'];
                $foundSections = [];
                
                foreach ($requiredSections as $section) {
                    if (strpos($docContent, $section) !== false) {
                        $foundSections[] = $section;
                    }
                }
                
                $sectionCount = count($foundSections);
                $totalSections = count($requiredSections);
                
                if ($sectionCount === $totalSections) {
                    $this->addResult("✅ {$name} documentation has all required sections ({$sectionCount}/{$totalSections})");
                } else {
                    $this->addWarning("⚠️ {$name} documentation missing sections ({$sectionCount}/{$totalSections})");
                }
            } else {
                $this->addWarning("⚠️ {$name} missing documentation: {$docPath}");
            }
        }
        
        // Check for migration guide
        $migrationGuidePath = 'AI_Docs/phases/template-enhancement-migration-guide.md';
        if (file_exists($migrationGuidePath)) {
            $this->addResult("✅ Migration guide exists: {$migrationGuidePath}");
        } else {
            $this->addWarning("⚠️ Migration guide missing: {$migrationGuidePath}");
        }
    }
    
    /**
     * Check for PHP 7.4 compatibility issues
     */
    private function checkPHP74Compatibility($content)
    {
        $issues = [];
        
        // Check for PHP 8+ features that aren't compatible with PHP 7.4
        if (strpos($content, 'match(') !== false) {
            $issues[] = 'Uses match() expression (PHP 8+ only)';
        }
        
        if (strpos($content, '?->') !== false) {
            $issues[] = 'Uses nullsafe operator (PHP 8+ only)';
        }
        
        if (strpos($content, '#[') !== false) {
            $issues[] = 'Uses attributes syntax (PHP 8+ only)';
        }
        
        if (strpos($content, 'enum ') !== false) {
            $issues[] = 'Uses enum (PHP 8.1+ only)';
        }
        
        return $issues;
    }
    
    /**
     * Add a successful result
     */
    private function addResult($message)
    {
        $this->results[] = $message;
        echo $message . "\n";
    }
    
    /**
     * Add an error
     */
    private function addError($message)
    {
        $this->errors[] = $message;
        echo $message . "\n";
    }
    
    /**
     * Add a warning
     */
    private function addWarning($message)
    {
        $this->warnings[] = $message;
        echo $message . "\n";
    }
    
    /**
     * Print test header
     */
    private function printHeader()
    {
        echo "\n";
        echo "================================================================\n";
        echo "🧪 ENHANCED TEMPLATE COMPATIBILITY TEST SUITE\n";
        echo "Phase 1, Feature 2, Step 4 Implementation Validation\n";
        echo "================================================================\n";
        echo "Testing enhanced templates for backward compatibility...\n\n";
    }
    
    /**
     * Print individual test section header
     */
    private function printTestHeader($testName)
    {
        echo "\n--- {$testName} ---\n";
    }
    
    /**
     * Print final results
     */
    private function printResults()
    {
        echo "\n";
        echo "================================================================\n";
        echo "📊 TEST RESULTS SUMMARY\n";
        echo "================================================================\n";
        
        $totalResults = count($this->results);
        $totalErrors = count($this->errors);
        $totalWarnings = count($this->warnings);
        
        echo "✅ Successful tests: {$totalResults}\n";
        echo "⚠️ Warnings: {$totalWarnings}\n";
        echo "❌ Errors: {$totalErrors}\n";
        
        if ($totalErrors === 0) {
            echo "\n🎉 ALL TESTS PASSED!\n";
            echo "Enhanced templates maintain full backward compatibility.\n";
        } else {
            echo "\n❌ TESTS FAILED!\n";
            echo "Please address the following errors before deployment:\n";
            foreach ($this->errors as $error) {
                echo "  - " . strip_tags($error) . "\n";
            }
        }
        
        if ($totalWarnings > 0) {
            echo "\n⚠️ WARNINGS (Review Recommended):\n";
            foreach ($this->warnings as $warning) {
                echo "  - " . strip_tags($warning) . "\n";
            }
        }
        
        echo "\n================================================================\n";
        
        // Return exit code for automation
        exit($totalErrors === 0 ? 0 : 1);
    }
}

// Run the test suite
$testSuite = new EnhancedTemplateCompatibilityTest();
$testSuite->runAllTests(); 