<?php
/**
 * @fileoverview Enhanced Template Integration Test
 * 
 * Tests that the enhanced template integration logic is working correctly
 * by verifying that view classes properly switch to enhanced templates when
 * configuration is enabled and fall back to standard templates when disabled.
 * 
 * This validates Phase 1, Feature 2, Step 4 implementation by testing:
 * - Configuration system functionality
 * - Template switching logic in view classes
 * - Fallback behavior when templates are missing
 * - Module-specific enabling/disabling
 * 
 * Usage:
 * - Run in Docker: docker exec suitecrm_app php tests/enhanced-template-integration-test.php
 * - Run locally: php tests/enhanced-template-integration-test.php
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-16
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
 * Enhanced Template Integration Test Suite
 */
class EnhancedTemplateIntegrationTest
{
    private $results = [];
    private $errors = [];
    private $warnings = [];
    
    /**
     * Modules with enhanced template support
     */
    private $testModules = [
        'Accounts',
        'Contacts',
        'Leads'
    ];
    
    /**
     * Run all integration tests
     */
    public function runAllTests()
    {
        $this->printHeader();
        
        // Test 1: Configuration System
        $this->testConfigurationSystem();
        
        // Test 2: View Class Integration
        $this->testViewClassIntegration();
        
        // Test 3: Template Switching Logic
        $this->testTemplateSwitchingLogic();
        
        // Test 4: Fallback Behavior
        $this->testFallbackBehavior();
        
        // Test 5: Module-Specific Configuration
        $this->testModuleSpecificConfiguration();
        
        $this->printResults();
        
        return count($this->errors) === 0;
    }
    
    /**
     * Test configuration system functionality
     */
    private function testConfigurationSystem()
    {
        $this->printTestHeader('Configuration System Tests');
        
        global $sugar_config;
        
        // Test global configuration
        if (isset($sugar_config['enhanced_templates_enabled'])) {
            $this->addResult('✅ enhanced_templates_enabled configuration exists');
            
            if ($sugar_config['enhanced_templates_enabled'] === true) {
                $this->addResult('✅ Enhanced templates are enabled in configuration');
            } else {
                $this->addWarning('⚠️ Enhanced templates are disabled in configuration');
            }
        } else {
            $this->addError('❌ enhanced_templates_enabled configuration not found');
        }
        
        // Test module-specific configuration
        if (isset($sugar_config['enhanced_templates_modules'])) {
            $modules = $sugar_config['enhanced_templates_modules'];
            $this->addResult('✅ enhanced_templates_modules configuration exists');
            
            if (is_array($modules) && count($modules) > 0) {
                $this->addResult('✅ Module list contains ' . count($modules) . ' modules: ' . implode(', ', $modules));
                
                // Check if test modules are included
                foreach ($this->testModules as $module) {
                    if (in_array($module, $modules)) {
                        $this->addResult('✅ ' . $module . ' is in enabled modules list');
                    } else {
                        $this->addWarning('⚠️ ' . $module . ' is NOT in enabled modules list');
                    }
                }
            } else {
                $this->addWarning('⚠️ Module list is empty - all modules will be enabled');
            }
        } else {
            $this->addWarning('⚠️ enhanced_templates_modules not configured - all modules will be enabled');
        }
        
        // Test additional configuration options
        if (isset($sugar_config['enhanced_templates_config'])) {
            $this->addResult('✅ enhanced_templates_config options exist');
        } else {
            $this->addWarning('⚠️ enhanced_templates_config options not configured');
        }
    }
    
    /**
     * Test view class integration
     */
    private function testViewClassIntegration()
    {
        $this->printTestHeader('View Class Integration Tests');
        
        foreach ($this->testModules as $module) {
            $viewFile = "modules/{$module}/views/view.detail.php";
            
            if (file_exists($viewFile)) {
                $this->addResult("✅ {$module} detail view file exists");
                
                // Read file content
                $content = file_get_contents($viewFile);
                
                // Check for enhanced template methods
                if (strpos($content, 'shouldUseEnhancedTemplate') !== false) {
                    $this->addResult("✅ {$module} has shouldUseEnhancedTemplate method");
                } else {
                    $this->addError("❌ {$module} missing shouldUseEnhancedTemplate method");
                }
                
                if (strpos($content, 'getDetailViewTemplate') !== false) {
                    $this->addResult("✅ {$module} has getDetailViewTemplate method");
                } else {
                    $this->addError("❌ {$module} missing getDetailViewTemplate method");
                }
                
                if (strpos($content, 'preDisplay') !== false) {
                    $this->addResult("✅ {$module} has preDisplay method override");
                } else {
                    $this->addError("❌ {$module} missing preDisplay method override");
                }
                
                // Check for enhanced template switching logic
                if (strpos($content, 'Enhanced template switching') !== false) {
                    $this->addResult("✅ {$module} has enhanced template switching logic");
                } else {
                    $this->addError("❌ {$module} missing enhanced template switching logic");
                }
                
            } else {
                $this->addError("❌ {$module} detail view file not found");
            }
        }
    }
    
    /**
     * Test template switching logic by instantiating view classes
     */
    private function testTemplateSwitchingLogic()
    {
        $this->printTestHeader('Template Switching Logic Tests');
        
        foreach ($this->testModules as $module) {
            try {
                // Include the view class
                $viewFile = "modules/{$module}/views/view.detail.php";
                if (file_exists($viewFile)) {
                    require_once($viewFile);
                    
                    $className = $module . 'ViewDetail';
                    if (class_exists($className)) {
                        $this->addResult("✅ {$module} view class loads successfully");
                        
                        // Test if methods exist via reflection
                        $reflection = new ReflectionClass($className);
                        
                        if ($reflection->hasMethod('shouldUseEnhancedTemplate')) {
                            $this->addResult("✅ {$module} shouldUseEnhancedTemplate method exists");
                        } else {
                            $this->addError("❌ {$module} shouldUseEnhancedTemplate method not found");
                        }
                        
                        if ($reflection->hasMethod('getDetailViewTemplate')) {
                            $this->addResult("✅ {$module} getDetailViewTemplate method exists");
                        } else {
                            $this->addError("❌ {$module} getDetailViewTemplate method not found");
                        }
                        
                    } else {
                        $this->addError("❌ {$module} view class {$className} not found");
                    }
                } else {
                    $this->addError("❌ {$module} view file not found");
                }
                
            } catch (Exception $e) {
                $this->addError("❌ Error loading {$module} view: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Test fallback behavior when enhanced templates are disabled
     */
    private function testFallbackBehavior()
    {
        $this->printTestHeader('Fallback Behavior Tests');
        
        global $sugar_config;
        
        // Temporarily disable enhanced templates
        $originalSetting = $sugar_config['enhanced_templates_enabled'] ?? false;
        $sugar_config['enhanced_templates_enabled'] = false;
        
        // Test that standard templates are used when disabled
        foreach ($this->testModules as $module) {
            try {
                $viewFile = "modules/{$module}/views/view.detail.php";
                if (file_exists($viewFile)) {
                    require_once($viewFile);
                    
                    $className = $module . 'ViewDetail';
                    if (class_exists($className)) {
                        $view = new $className();
                        $view->module = $module;
                        
                        // Use reflection to test private method
                        $reflection = new ReflectionClass($className);
                        if ($reflection->hasMethod('shouldUseEnhancedTemplate')) {
                            $method = $reflection->getMethod('shouldUseEnhancedTemplate');
                            $method->setAccessible(true);
                            
                            $shouldUse = $method->invoke($view);
                            if ($shouldUse === false) {
                                $this->addResult("✅ {$module} correctly returns false when enhanced templates disabled");
                            } else {
                                $this->addError("❌ {$module} incorrectly returns true when enhanced templates disabled");
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                $this->addWarning("⚠️ Error testing {$module} fallback: " . $e->getMessage());
            }
        }
        
        // Restore original setting
        $sugar_config['enhanced_templates_enabled'] = $originalSetting;
        
        $this->addResult('✅ Configuration restored after fallback test');
    }
    
    /**
     * Test module-specific configuration
     */
    private function testModuleSpecificConfiguration()
    {
        $this->printTestHeader('Module-Specific Configuration Tests');
        
        global $sugar_config;
        
        // Test that modules not in the list return false
        $originalModules = $sugar_config['enhanced_templates_modules'] ?? [];
        $sugar_config['enhanced_templates_modules'] = ['Accounts']; // Only Accounts
        
        foreach ($this->testModules as $module) {
            try {
                $viewFile = "modules/{$module}/views/view.detail.php";
                if (file_exists($viewFile)) {
                    require_once($viewFile);
                    
                    $className = $module . 'ViewDetail';
                    if (class_exists($className)) {
                        $view = new $className();
                        $view->module = $module;
                        
                        // Use reflection to test private method
                        $reflection = new ReflectionClass($className);
                        if ($reflection->hasMethod('shouldUseEnhancedTemplate')) {
                            $method = $reflection->getMethod('shouldUseEnhancedTemplate');
                            $method->setAccessible(true);
                            
                            $shouldUse = $method->invoke($view);
                            
                            if ($module === 'Accounts') {
                                if ($shouldUse === true) {
                                    $this->addResult("✅ {$module} correctly enabled when in modules list");
                                } else {
                                    $this->addError("❌ {$module} incorrectly disabled when in modules list");
                                }
                            } else {
                                if ($shouldUse === false) {
                                    $this->addResult("✅ {$module} correctly disabled when NOT in modules list");
                                } else {
                                    $this->addError("❌ {$module} incorrectly enabled when NOT in modules list");
                                }
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                $this->addWarning("⚠️ Error testing {$module} module-specific config: " . $e->getMessage());
            }
        }
        
        // Restore original setting
        $sugar_config['enhanced_templates_modules'] = $originalModules;
        
        $this->addResult('✅ Module configuration restored after test');
    }
    
    /**
     * Add successful result
     */
    private function addResult($message)
    {
        $this->results[] = $message;
        echo $message . "\n";
    }
    
    /**
     * Add error message
     */
    private function addError($message)
    {
        $this->errors[] = $message;
        echo $message . "\n";
    }
    
    /**
     * Add warning message
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
        echo "🔧 ENHANCED TEMPLATE INTEGRATION TEST SUITE\n";
        echo "Phase 1, Feature 2, Step 4 Integration Validation\n";
        echo "================================================================\n";
        echo "Testing enhanced template integration logic...\n\n";
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
        echo "📊 INTEGRATION TEST RESULTS SUMMARY\n";
        echo "================================================================\n";
        
        $totalResults = count($this->results);
        $totalErrors = count($this->errors);
        $totalWarnings = count($this->warnings);
        
        echo "✅ Successful tests: {$totalResults}\n";
        echo "⚠️ Warnings: {$totalWarnings}\n";
        echo "❌ Errors: {$totalErrors}\n";
        
        if ($totalErrors === 0) {
            echo "\n🎉 ALL INTEGRATION TESTS PASSED!\n";
            echo "Enhanced template switching is working correctly.\n";
        } else {
            echo "\n❌ INTEGRATION TESTS FAILED!\n";
            echo "Please address the following errors:\n";
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

// Run the integration test suite
$testSuite = new EnhancedTemplateIntegrationTest();
$testSuite->runAllTests(); 