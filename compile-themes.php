<?php
/**
 * @fileoverview Standalone Theme Compiler for SuiteCRM
 * 
 * Compiles SCSS files to CSS with CSS custom properties integration.
 * Designed to bypass Robo dependency issues in PHP 7.4 environments.
 * 
 * Key Features:
 * - Compiles all 5 theme variants (Dawn, Day, Dusk, Night, Noon)
 * - Integrates CSS custom properties from custom-properties.scss
 * - Works with existing SCSS variable system
 * - Zero breaking changes to existing theme compilation
 * 
 * Usage:
 * - php compile-themes.php all        # Compile all themes
 * - php compile-themes.php Dawn       # Compile specific theme
 * - php compile-themes.php list       # List available themes
 * 
 * Dependencies:
 * - scssphp/scssphp library
 * - Existing theme SCSS files
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

require_once 'vendor/autoload.php';

use ScssPhp\ScssPhp\Compiler;

class ThemeCompiler 
{
    /** @var array Available theme names */
    private array $availableThemes = ['Dawn', 'Day', 'Dusk', 'Night', 'Noon'];
    
    /** @var string Base theme directory */
    private string $themeBaseDir = 'themes/SuiteP/css';
    
    /** @var Compiler SCSS compiler instance */
    private Compiler $compiler;
    
    /** @var array Compilation statistics */
    private array $stats = [];
    
    public function __construct() 
    {
        $this->compiler = new Compiler();
        $this->compiler->setImportPaths([$this->themeBaseDir]);
    }
    
    /**
     * Main entry point for theme compilation
     * 
     * @param string $theme Theme name or 'all' or 'list'
     * @return void
     */
    public function compile(string $theme): void 
    {
        echo "🎨 SuiteCRM Theme Compiler\n";
        echo "==========================\n\n";
        
        switch (strtolower($theme)) {
            case 'list':
                $this->listThemes();
                break;
            case 'all':
                $this->compileAllThemes();
                break;
            case '':
                $this->compileAllThemes();
                break;
            default:
                $this->compileSingleTheme($theme);
                break;
        }
        
        $this->displayStats();
    }
    
    /**
     * Lists available themes
     */
    private function listThemes(): void 
    {
        echo "📋 Available Themes:\n";
        foreach ($this->availableThemes as $themeName) {
            $exists = $this->themeExists($themeName) ? '✅' : '❌';
            echo "   $exists $themeName\n";
        }
        echo "\n";
    }
    
    /**
     * Compiles all available themes
     */
    private function compileAllThemes(): void 
    {
        echo "📦 Compiling all themes...\n\n";
        
        foreach ($this->availableThemes as $themeName) {
            if ($this->themeExists($themeName)) {
                $this->compileSingleTheme($themeName, false);
            } else {
                echo "⚠️  Skipping $themeName (theme files not found)\n";
            }
        }
    }
    
    /**
     * Compiles a single theme
     * 
     * @param string $themeName Name of the theme to compile
     * @param bool $verbose Whether to show detailed output
     */
    private function compileSingleTheme(string $themeName, bool $verbose = true): void 
    {
        $startTime = microtime(true);
        
        if (!$this->themeExists($themeName)) {
            echo "❌ Error: Theme '$themeName' not found\n";
            echo "   Available themes: " . implode(', ', $this->availableThemes) . "\n";
            return;
        }
        
        if ($verbose) {
            echo "📦 Compiling theme: $themeName\n";
        }
        
        try {
            $themeDir = "{$this->themeBaseDir}/$themeName";
            $inputFile = "$themeDir/style.scss";
            $outputFile = "$themeDir/style.css";
            
            // Read the main SCSS file
            if (!file_exists($inputFile)) {
                throw new Exception("Main SCSS file not found: $inputFile");
            }
            
            $scssContent = file_get_contents($inputFile);
            
            // Inject custom properties import if it exists
            $customPropsFile = "$themeDir/custom-properties.scss";
            if (file_exists($customPropsFile)) {
                $scssContent = "@import 'custom-properties';\n" . $scssContent;
                if ($verbose) {
                    echo "   ✅ Including custom properties\n";
                }
            }
            
            // Set import path for this theme
            $this->compiler->setImportPaths([$themeDir, $this->themeBaseDir]);
            
            // Compile SCSS to CSS
            $compiledCss = $this->compiler->compileString($scssContent)->getCss();
            
            // Write compiled CSS
            file_put_contents($outputFile, $compiledCss);
            
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            $size = round(strlen($compiledCss) / 1024, 2);
            
            $this->stats[$themeName] = [
                'duration' => $duration,
                'size' => $size,
                'success' => true
            ];
            
            if ($verbose) {
                echo "   ✅ Compiled successfully\n";
                echo "   📄 Output: $outputFile\n";
                echo "   ⏱️  Duration: {$duration}ms\n";
                echo "   📏 Size: {$size}KB\n\n";
            } else {
                echo "   ✅ $themeName ({$duration}ms, {$size}KB)\n";
            }
            
        } catch (Exception $e) {
            $this->stats[$themeName] = [
                'duration' => 0,
                'size' => 0,
                'success' => false,
                'error' => $e->getMessage()
            ];
            
            echo "   ❌ Compilation failed: " . $e->getMessage() . "\n";
            if ($verbose) {
                echo "\n";
            }
        }
    }
    
    /**
     * Checks if a theme exists
     * 
     * @param string $themeName Theme name to check
     * @return bool True if theme exists
     */
    private function themeExists(string $themeName): bool 
    {
        $themeDir = "{$this->themeBaseDir}/$themeName";
        $scssFile = "$themeDir/style.scss";
        
        return is_dir($themeDir) && file_exists($scssFile);
    }
    
    /**
     * Displays compilation statistics
     */
    private function displayStats(): void 
    {
        if (empty($this->stats)) {
            return;
        }
        
        echo "\n📊 Compilation Summary:\n";
        echo str_repeat('=', 50) . "\n";
        
        $totalDuration = 0;
        $totalSize = 0;
        $successCount = 0;
        $failureCount = 0;
        
        foreach ($this->stats as $theme => $stat) {
            $status = $stat['success'] ? '✅' : '❌';
            $duration = $stat['duration'];
            $size = $stat['size'];
            
            if ($stat['success']) {
                echo sprintf("%-8s %s %6.2fms %8.2fKB\n", $theme, $status, $duration, $size);
                $totalDuration += $duration;
                $totalSize += $size;
                $successCount++;
            } else {
                echo sprintf("%-8s %s FAILED: %s\n", $theme, $status, $stat['error']);
                $failureCount++;
            }
        }
        
        echo str_repeat('-', 50) . "\n";
        echo sprintf("Total:   ✅ %d themes  ⏱️  %.2fms  📏 %.2fKB\n", 
            $successCount, $totalDuration, $totalSize);
        
        if ($failureCount > 0) {
            echo sprintf("Failed:  ❌ %d themes\n", $failureCount);
        }
        
        echo "\n";
    }
}

// Main execution
if (php_sapi_name() === 'cli') {
    $theme = $argv[1] ?? 'all';
    $compiler = new ThemeCompiler();
    $compiler->compile($theme);
} else {
    echo "This script must be run from the command line.\n";
    exit(1);
} 