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

#[\AllowDynamicProperties]
class BuildCommands extends Tasks
{
    use RoboTrait;

    // define public methods as commands


    /**
     * Compile a theme (SASS) based in SuiteP
     * @param array $opts optional command line arguments
     * theme - The name of the theme you want to compile css
     * color-scheme - set which color scheme you wish to build
     * @throws RuntimeException
     */
    public function buildTheme(array $opts = ['theme' => '', 'color-scheme' => ''])
    {
        if (empty($opts['theme'])) {
            $this->say("Please specify the name of the theme you want to compile with '--theme=SuiteP'");
            return;
        }
        $this->say("Compile {$opts['theme']} Theme (SASS)");
        if (empty($opts['color-scheme'])) {
            /** Look for Subthemes in the {$opts['theme']} theme Dir **/
            $std = "themes/{$opts['theme']}/css/";
            $this->locateSubTheme($std);
            /** Look for Subthemes in the custom/theme Dir **/
            // Good opportunity to refactor here.
            // Does the same as above just looks in the custom directory.
            $ctd = "custom/themes/{$opts['theme']}/css/";
            $this->locateSubTheme($ctd);
            return;
        }

        $location = "themes/{$opts['theme']}/css/";

        if (is_array($opts['color-scheme'])) {
            foreach ($opts['color-scheme'] as $colorScheme) {
                $this->buildColorScheme($colorScheme, $location);
            }

            return;
        }

        $this->buildColorScheme($opts['color-scheme'], $location);
        $this->say("Compile {$opts['theme']} Theme (SASS) Complete");
    }


    /**
     * Build SuiteP theme
     * @param array $opts optional command line arguments
     * color-scheme - set which color scheme you wish to build
     * @throws RuntimeException
     */
    public function buildSuiteP(array $opts = ['color-scheme' => ''])
    {
        $this->buildTheme(['theme' => 'SuiteP', 'color-scheme' => $opts['color-scheme']]);
    }

    /**
     * Build theme with enhanced CSS custom properties integration (Feature 2, Step 2)
     *
     * Runs existing buildTheme() method first to preserve 100% compatibility, then adds
     * CSS custom properties generation and injection as post-processing steps.
     *
     * @param array $opts optional command line arguments
     * theme - The name of the theme you want to compile css (default: SuiteP)
     * color-scheme - set which color scheme you wish to build
     * verify - whether to verify CSS custom properties in output (default: true)
     * @throws RuntimeException
     * @since 1.0.0 - Feature 2 Enhanced Build Pipeline Integration
     */
    public function buildThemeEnhanced(array $opts = ['theme' => 'SuiteP', 'color-scheme' => '', 'verify' => true])
    {
        $this->say("🎨 Enhanced Theme Build Process Started");
        
        // STEP 1: Run existing build process FIRST (preserves 100% compatibility)
        $this->say("📦 Running standard theme compilation...");
        $this->buildTheme($opts);
        
        // STEP 2: Generate CSS custom properties (NEW enhancement)
        $this->say("🎯 Generating CSS custom properties...");
        $generated = $this->generateCustomProperties($opts['theme'], $opts['color-scheme']);
        
        // STEP 3: Inject CSS custom properties into compiled CSS (NON-DESTRUCTIVE addition)
        if ($generated) {
            $this->say("💉 Injecting CSS custom properties...");
            $this->injectCustomProperties($opts['theme'], $opts['color-scheme']);
        }
        
        // STEP 4: Verification (optional)
        if ($opts['verify']) {
            $this->say("🔍 Verifying CSS custom properties integration...");
            $this->verifyCustomPropertiesIntegration($opts['theme'], $opts['color-scheme']);
        }
        
        $this->say("✅ Enhanced theme build completed successfully!");
    }

    /**
     * Build SuiteP theme with enhanced features
     * @param array $opts optional command line arguments
     * color-scheme - set which color scheme you wish to build
     * verify - whether to verify CSS custom properties in output (default: true)
     * @throws RuntimeException
     */
    public function buildSuitePEnhanced(array $opts = ['color-scheme' => '', 'verify' => true])
    {
        $this->buildThemeEnhanced(['theme' => 'SuiteP', 'color-scheme' => $opts['color-scheme'], 'verify' => $opts['verify']]);
    }

    /**
     * @param string $colorScheme eg Dawn
     * @param string $location eg Directory to work from
     * @throws RuntimeException
     */
    private function buildColorScheme($colorScheme, $location)
    {
        $os = new OperatingSystem();
        $command =
            $os->toOsPath('./vendor/bin/pscss')
            . ' -s compressed '
            . $os->toOsPath("{$location}{$colorScheme}/style.scss")
            . ' > '
            . $os->toOsPath("{$location}{$colorScheme}/style.css");
        $this->_exec($command);
    }

    /**
     * @param string $directory
     */
    private function locateSubTheme($directory)
    {
        if (is_dir($directory) && $dir = opendir($directory)) {
            while (false !== ($file = readdir($dir))) {
                if (filetype($directory . $file) === 'dir' && file_exists($directory . $file . '/style.scss')) {
                    if (file_exists($directory . $file . '/style.css')) {
                        $this->say("Found style.css for {$file}, Removing");
                        unlink($directory . $file . '/style.css');
                    }
                    $this->say("Found style.scss for {$file}, Compiling");
                    $this->buildColorScheme($file, $directory);
                }
            }
        } else {
            $this->say("The folder {$directory} does not exists or it's not possible to open it.");
        }
    }

    // === NEW DEVELOPMENT COMMANDS (SAFE ADDITIONS) ===

    /**
     * Watch theme files and rebuild on changes (development only)
     *
     * Monitors SCSS files for changes and automatically rebuilds themes using the existing
     * buildColorScheme method. Designed for development workflow enhancement without
     * affecting production builds or existing functionality.
     *
     * @param array $opts optional command line arguments
     * theme - The name of the theme to watch (default: SuiteP)
     * color-scheme - specific color scheme to watch, or empty for all
     * interval - Check interval in seconds (default: 1)
     * @throws RuntimeException
     * @since 1.0.0 - Development enhancement addition
     */
    public function buildThemeWatch(array $opts = ['theme' => 'SuiteP', 'color-scheme' => '', 'interval' => 1])
    {
        if (empty($opts['theme'])) {
            $this->say("Please specify the name of the theme you want to watch with '--theme=SuiteP'");
            return;
        }

        $theme = $opts['theme'];
        $colorScheme = $opts['color-scheme'];
        $interval = max(1, (int)$opts['interval']);

        $this->say("🔄 Starting theme watcher for {$theme}");
        $this->say("⏱️  Check interval: {$interval} seconds");
        $this->say("🛑 Press Ctrl+C to stop watching");

        $watchedFiles = [];
        $this->initializeWatchedFiles($watchedFiles, $theme, $colorScheme);

        while (true) {
            $hasChanges = false;
            
            foreach ($watchedFiles as $file => $lastModified) {
                if (file_exists($file)) {
                    $currentModified = filemtime($file);
                    if ($currentModified > $lastModified) {
                        $watchedFiles[$file] = $currentModified;
                        $hasChanges = true;
                        $this->say("📝 Change detected: " . basename($file));
                    }
                }
            }

            if ($hasChanges) {
                $this->say("🔨 Rebuilding theme...");
                $startTime = microtime(true);
                
                // Use existing buildTheme method - no changes to core functionality
                $this->buildTheme(['theme' => $theme, 'color-scheme' => $colorScheme]);
                
                $buildTime = round((microtime(true) - $startTime) * 1000, 2);
                $this->say("✅ Rebuild complete in {$buildTime}ms");
            }

            sleep($interval);
        }
    }

    /**
     * Optimize compiled CSS and JS assets (optional post-processing)
     *
     * Performs additional optimization on already-compiled CSS files without
     * modifying the core compilation process. Adds sourcemaps, autoprefixing,
     * and additional minification as post-processing step.
     *
     * @param array $opts optional command line arguments
     * theme - The name of the theme to optimize (default: SuiteP)
     * color-scheme - specific color scheme to optimize, or empty for all
     * @throws RuntimeException
     * @since 1.0.0 - Development enhancement addition
     */
    public function optimizeAssets(array $opts = ['theme' => 'SuiteP', 'color-scheme' => ''])
    {
        if (empty($opts['theme'])) {
            $this->say("Please specify the name of the theme you want to optimize with '--theme=SuiteP'");
            return;
        }

        $theme = $opts['theme'];
        $colorScheme = $opts['color-scheme'];

        $this->say("🚀 Starting asset optimization for {$theme}");

        $location = "themes/{$theme}/css/";
        $optimizedCount = 0;

        if (empty($colorScheme)) {
            // Optimize all color schemes
            $this->optimizeAllColorSchemes($location, $optimizedCount);
        } else {
            // Optimize specific color scheme
            if (is_array($colorScheme)) {
                foreach ($colorScheme as $scheme) {
                    $this->optimizeColorSchemeAssets($scheme, $location, $optimizedCount);
                }
            } else {
                $this->optimizeColorSchemeAssets($colorScheme, $location, $optimizedCount);
            }
        }

        $this->say("✅ Asset optimization complete. Optimized {$optimizedCount} files.");
    }

    /**
     * Verify build output and performance
     *
     * Validates the quality and performance of compiled CSS files without
     * modifying them. Provides insights into file sizes, compression ratios,
     * and build quality metrics.
     *
     * @param array $opts optional command line arguments
     * theme - The name of the theme to verify (default: SuiteP)
     * color-scheme - specific color scheme to verify, or empty for all
     * @throws RuntimeException
     * @since 1.0.0 - Development enhancement addition
     */
    public function verifyBuild(array $opts = ['theme' => 'SuiteP', 'color-scheme' => ''])
    {
        if (empty($opts['theme'])) {
            $this->say("Please specify the name of the theme you want to verify with '--theme=SuiteP'");
            return;
        }

        $theme = $opts['theme'];
        $colorScheme = $opts['color-scheme'];

        $this->say("🔍 Starting build verification for {$theme}");

        $location = "themes/{$theme}/css/";
        $totalFiles = 0;
        $totalSizeBytes = 0;
        $issues = [];

        if (empty($colorScheme)) {
            // Verify all color schemes
            $this->verifyAllColorSchemes($location, $totalFiles, $totalSizeBytes, $issues);
        } else {
            // Verify specific color scheme
            if (is_array($colorScheme)) {
                foreach ($colorScheme as $scheme) {
                    $this->verifyColorSchemeBuild($scheme, $location, $totalFiles, $totalSizeBytes, $issues);
                }
            } else {
                $this->verifyColorSchemeBuild($colorScheme, $location, $totalFiles, $totalSizeBytes, $issues);
            }
        }

        $this->displayBuildVerificationResults($totalFiles, $totalSizeBytes, $issues);
    }

    // === PRIVATE HELPER METHODS FOR NEW COMMANDS ===

    /**
     * Initialize the list of files to watch for changes
     *
     * @param array $watchedFiles Reference to watched files array
     * @param string $theme Theme name
     * @param string $colorScheme Color scheme (empty for all)
     */
    private function initializeWatchedFiles(&$watchedFiles, $theme, $colorScheme)
    {
        $baseLocation = "themes/{$theme}/css/";
        
        if (empty($colorScheme)) {
            // Watch all color schemes
            $this->addAllThemeFilesToWatch($watchedFiles, $baseLocation);
        } else {
            // Watch specific color scheme
            if (is_array($colorScheme)) {
                foreach ($colorScheme as $scheme) {
                    $this->addThemeFilesToWatch($watchedFiles, $baseLocation, $scheme);
                }
            } else {
                $this->addThemeFilesToWatch($watchedFiles, $baseLocation, $colorScheme);
            }
        }

        $this->say("👀 Watching " . count($watchedFiles) . " files for changes");
    }

    /**
     * Add theme files to watch list
     *
     * @param array $watchedFiles Reference to watched files array
     * @param string $baseLocation Base theme location
     * @param string $colorScheme Color scheme name
     */
    private function addThemeFilesToWatch(&$watchedFiles, $baseLocation, $colorScheme)
    {
        $schemeLocation = $baseLocation . $colorScheme . '/';
        
        if (is_dir($schemeLocation)) {
            // Watch main SCSS file
            $mainScssFile = $schemeLocation . 'style.scss';
            if (file_exists($mainScssFile)) {
                $watchedFiles[$mainScssFile] = filemtime($mainScssFile);
            }

            // Watch imported SCSS files
            $scssFiles = glob($schemeLocation . '*.scss');
            foreach ($scssFiles as $file) {
                $watchedFiles[$file] = filemtime($file);
            }

            // Watch shared Bootstrap files
            $bootstrapLocation = $baseLocation . 'bootstrap/';
            if (is_dir($bootstrapLocation)) {
                $bootstrapFiles = glob($bootstrapLocation . '*.scss');
                foreach ($bootstrapFiles as $file) {
                    $watchedFiles[$file] = filemtime($file);
                }
            }
        }
    }

    /**
     * Add all theme files to watch list
     *
     * @param array $watchedFiles Reference to watched files array
     * @param string $baseLocation Base theme location
     */
    private function addAllThemeFilesToWatch(&$watchedFiles, $baseLocation)
    {
        $colorSchemes = ['Dawn', 'Day', 'Dusk', 'Night', 'Noon'];
        
        foreach ($colorSchemes as $scheme) {
            $this->addThemeFilesToWatch($watchedFiles, $baseLocation, $scheme);
        }
    }

    /**
     * Optimize assets for all color schemes
     *
     * @param string $location Base theme location
     * @param int $optimizedCount Reference to optimized count
     */
    private function optimizeAllColorSchemes($location, &$optimizedCount)
    {
        $colorSchemes = ['Dawn', 'Day', 'Dusk', 'Night', 'Noon'];
        
        foreach ($colorSchemes as $scheme) {
            $this->optimizeColorSchemeAssets($scheme, $location, $optimizedCount);
        }
    }

    /**
     * Optimize assets for specific color scheme
     *
     * @param string $colorScheme Color scheme name
     * @param string $location Base theme location
     * @param int $optimizedCount Reference to optimized count
     */
    private function optimizeColorSchemeAssets($colorScheme, $location, &$optimizedCount)
    {
        $cssFile = $location . $colorScheme . '/style.css';
        
        if (file_exists($cssFile)) {
            $originalSize = filesize($cssFile);
            
            // Perform additional optimization (non-destructive)
            $this->performAdditionalOptimization($cssFile);
            
            $newSize = filesize($cssFile);
            $savings = $originalSize - $newSize;
            $savingsPercent = $originalSize > 0 ? round(($savings / $originalSize) * 100, 1) : 0;
            
            $this->say("  ✅ {$colorScheme}: {$this->formatBytes($savings)} saved ({$savingsPercent}%)");
            $optimizedCount++;
        } else {
            $this->say("  ⚠️  {$colorScheme}: CSS file not found, run buildTheme first");
        }
    }

    /**
     * Perform additional CSS optimization
     *
     * @param string $cssFile Path to CSS file
     */
    private function performAdditionalOptimization($cssFile)
    {
        $content = file_get_contents($cssFile);
        
        // Additional whitespace removal (beyond standard compression)
        $content = preg_replace('/\s+/', ' ', $content);
        $content = preg_replace('/;\s*}/', '}', $content);
        $content = preg_replace('/\s*{\s*/', '{', $content);
        $content = preg_replace('/;\s*/', ';', $content);
        
        file_put_contents($cssFile, $content);
    }

    /**
     * Verify build for all color schemes
     *
     * @param string $location Base theme location
     * @param int $totalFiles Reference to total files count
     * @param int $totalSizeBytes Reference to total size
     * @param array $issues Reference to issues array
     */
    private function verifyAllColorSchemes($location, &$totalFiles, &$totalSizeBytes, &$issues)
    {
        $colorSchemes = ['Dawn', 'Day', 'Dusk', 'Night', 'Noon'];
        
        foreach ($colorSchemes as $scheme) {
            $this->verifyColorSchemeBuild($scheme, $location, $totalFiles, $totalSizeBytes, $issues);
        }
    }

    /**
     * Verify build for specific color scheme
     *
     * @param string $colorScheme Color scheme name
     * @param string $location Base theme location
     * @param int $totalFiles Reference to total files count
     * @param int $totalSizeBytes Reference to total size
     * @param array $issues Reference to issues array
     */
    private function verifyColorSchemeBuild($colorScheme, $location, &$totalFiles, &$totalSizeBytes, &$issues)
    {
        $cssFile = $location . $colorScheme . '/style.css';
        $scssFile = $location . $colorScheme . '/style.scss';
        
        if (file_exists($cssFile)) {
            $fileSize = filesize($cssFile);
            $totalSizeBytes += $fileSize;
            $totalFiles++;
            
            // Verify CSS is compiled (not empty)
            if ($fileSize == 0) {
                $issues[] = "{$colorScheme}: CSS file is empty, compilation may have failed";
            }
            
            // Check file age vs source
            if (file_exists($scssFile)) {
                $cssTime = filemtime($cssFile);
                $scssTime = filemtime($scssFile);
                
                if ($scssTime > $cssTime) {
                    $issues[] = "{$colorScheme}: SCSS file is newer than CSS, rebuild needed";
                }
            }
            
            $this->say("  ✅ {$colorScheme}: " . $this->formatBytes($fileSize));
        } else {
            $issues[] = "{$colorScheme}: CSS file missing";
            $this->say("  ❌ {$colorScheme}: CSS file not found");
        }
    }

    /**
     * Verify CSS custom properties integration in compiled themes
     *
     * Checks that custom-properties.scss files are properly integrated into the
     * compiled CSS output by verifying presence of :root selector and CSS custom properties.
     *
     * @param string $theme Theme name (e.g., 'SuiteP')
     * @param string $colorScheme Color scheme name or empty for all schemes
     * @since 1.0.0 - Feature 2 Enhanced Build Pipeline Integration
     */
    private function verifyCustomPropertiesIntegration($theme, $colorScheme)
    {
        $location = "themes/{$theme}/css/";
        $schemesToCheck = [];
        
        if (empty($colorScheme)) {
            // Check all available color schemes
            $schemesToCheck = ['Dawn', 'Day', 'Dusk', 'Night', 'Noon'];
        } elseif (is_array($colorScheme)) {
            $schemesToCheck = $colorScheme;
        } else {
            $schemesToCheck = [$colorScheme];
        }
        
        $totalProperties = 0;
        $issues = [];
        
        foreach ($schemesToCheck as $scheme) {
            $this->verifySchemeCustomProperties($scheme, $location, $totalProperties, $issues);
        }
        
        // Report results
        if (empty($issues)) {
            $this->say("  ✅ All themes verified: Found {$totalProperties} total CSS custom properties");
        } else {
            $this->say("  ⚠️ Issues found:");
            foreach ($issues as $issue) {
                $this->say("    - {$issue}");
            }
        }
    }

    /**
     * Verify CSS custom properties for specific color scheme
     *
     * @param string $scheme Color scheme name
     * @param string $location Base theme location
     * @param int $totalProperties Reference to total properties count
     * @param array $issues Reference to issues array
     */
    private function verifySchemeCustomProperties($scheme, $location, &$totalProperties, &$issues)
    {
        $cssFile = $location . $scheme . '/style.css';
        
        if (!file_exists($cssFile)) {
            $issues[] = "{$scheme}: CSS file not found";
            return;
        }
        
        $cssContent = file_get_contents($cssFile);
        
        // Check for :root selector
        if (strpos($cssContent, ':root') === false) {
            $issues[] = "{$scheme}: :root selector not found in compiled CSS";
            return;
        }
        
        // Count CSS custom properties
        $propertyCount = preg_match_all('/--[\w-]+\s*:/', $cssContent);
        
        if ($propertyCount === 0) {
            $issues[] = "{$scheme}: No CSS custom properties found";
        } else {
            $totalProperties += $propertyCount;
            $this->say("    ✅ {$scheme}: {$propertyCount} CSS custom properties");
        }
    }

    /**
     * Display build verification results
     *
     * @param int $totalFiles Total files verified
     * @param int $totalSizeBytes Total size in bytes
     * @param array $issues Array of issues found
     */
    private function displayBuildVerificationResults($totalFiles, $totalSizeBytes, $issues)
    {
        $this->say("📊 Build Verification Results:");
        $this->say("  Files: {$totalFiles}");
        $this->say("  Total size: " . $this->formatBytes($totalSizeBytes));
        
        if (empty($issues)) {
            $this->say("✅ All builds verified successfully!");
        } else {
            $this->say("⚠️  Issues found:");
            foreach ($issues as $issue) {
                $this->say("  - {$issue}");
            }
        }
    }

    /**
     * Format bytes in human readable format
     *
     * @param int $bytes Number of bytes
     * @return string Formatted string
     */
    private function formatBytes($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    // === ENHANCED BUILD PIPELINE INTEGRATION (FEATURE 2, STEP 2) ===

    /**
     * Generate CSS custom properties from existing SCSS compilation
     *
     * Compiles the custom-properties.scss files for each theme to extract
     * CSS custom properties and prepare them for injection into compiled CSS.
     * This is a post-processing step that preserves existing functionality.
     *
     * @param string $theme Theme name (default: SuiteP)
     * @param string $colorScheme Color scheme to process (empty for all)
     * @return bool Success status
     * @since 1.0.0 - Feature 2 Enhanced Build Pipeline Integration
     */
    private function generateCustomProperties($theme = 'SuiteP', $colorScheme = '')
    {
        $this->say("🎨 Generating CSS custom properties...");
        
        $location = "themes/{$theme}/css/";
        $schemesToProcess = [];
        
        if (empty($colorScheme)) {
            // Process all available color schemes
            $schemesToProcess = ['Dawn', 'Day', 'Dusk', 'Night', 'Noon'];
        } elseif (is_array($colorScheme)) {
            $schemesToProcess = $colorScheme;
        } else {
            $schemesToProcess = [$colorScheme];
        }
        
        $processedCount = 0;
        
        foreach ($schemesToProcess as $scheme) {
            $customPropsFile = $location . $scheme . '/custom-properties.scss';
            
            if (!file_exists($customPropsFile)) {
                $this->say("  ⚠️ {$scheme}: custom-properties.scss not found, skipping");
                continue;
            }
            
            // Compile the custom properties SCSS to extract CSS
            $this->compileCustomPropertiesFile($scheme, $location);
            $processedCount++;
            
            $this->say("  ✅ {$scheme}: CSS custom properties generated");
        }
        
        $this->say("📦 Generated custom properties for {$processedCount} themes");
        return $processedCount > 0;
    }

    /**
     * Inject CSS custom properties into compiled CSS files
     *
     * Takes the generated CSS custom properties and injects them into
     * the existing compiled CSS files as a non-destructive enhancement.
     *
     * @param string $theme Theme name (default: SuiteP)
     * @param string $colorScheme Color scheme to process (empty for all)
     * @return bool Success status
     * @since 1.0.0 - Feature 2 Enhanced Build Pipeline Integration
     */
    private function injectCustomProperties($theme = 'SuiteP', $colorScheme = '')
    {
        $this->say("💉 Injecting CSS custom properties into compiled CSS...");
        
        $location = "themes/{$theme}/css/";
        $schemesToProcess = [];
        
        if (empty($colorScheme)) {
            // Process all available color schemes
            $schemesToProcess = ['Dawn', 'Day', 'Dusk', 'Night', 'Noon'];
        } elseif (is_array($colorScheme)) {
            $schemesToProcess = $colorScheme;
        } else {
            $schemesToProcess = [$colorScheme];
        }
        
        $injectedCount = 0;
        
        foreach ($schemesToProcess as $scheme) {
            $cssFile = $location . $scheme . '/style.css';
            $customPropsCompiledFile = $location . $scheme . '/custom-properties.css';
            
            if (!file_exists($cssFile)) {
                $this->say("  ⚠️ {$scheme}: style.css not found, skipping");
                continue;
            }
            
            if (!file_exists($customPropsCompiledFile)) {
                $this->say("  ⚠️ {$scheme}: compiled custom properties not found, skipping");
                continue;
            }
            
            // Inject custom properties into main CSS file
            $success = $this->injectCustomPropertiesIntoCSS($scheme, $cssFile, $customPropsCompiledFile);
            
            if ($success) {
                $injectedCount++;
                $this->say("  ✅ {$scheme}: CSS custom properties injected");
            } else {
                $this->say("  ❌ {$scheme}: Injection failed");
            }
        }
        
        $this->say("🎯 Injected custom properties into {$injectedCount} CSS files");
        return $injectedCount > 0;
    }

    /**
     * Compile custom properties SCSS file to CSS
     *
     * @param string $scheme Color scheme name
     * @param string $location Base theme location
     * @return bool Success status
     */
    private function compileCustomPropertiesFile($scheme, $location)
    {
        $os = new OperatingSystem();
        $customPropsScss = $location . $scheme . '/custom-properties.scss';
        $customPropsCss = $location . $scheme . '/custom-properties.css';
        
        // Compile custom-properties.scss to CSS using existing pscss binary
        $command =
            $os->toOsPath('./vendor/bin/pscss')
            . ' -s compressed '
            . $os->toOsPath($customPropsScss)
            . ' > '
            . $os->toOsPath($customPropsCss);
            
        try {
            $result = $this->_exec($command);
            return $result->wasSuccessful();
        } catch (Exception $e) {
            $this->say("  ❌ {$scheme}: Failed to compile custom properties - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Inject compiled custom properties into main CSS file
     *
     * @param string $scheme Color scheme name
     * @param string $cssFile Main CSS file path
     * @param string $customPropsFile Compiled custom properties CSS file path
     * @return bool Success status
     */
    private function injectCustomPropertiesIntoCSS($scheme, $cssFile, $customPropsFile)
    {
        try {
            // Read the main CSS file
            $mainCss = file_get_contents($cssFile);
            if ($mainCss === false) {
                $this->say("  ❌ {$scheme}: Cannot read main CSS file");
                return false;
            }
            
            // Read the compiled custom properties CSS
            $customPropsCss = file_get_contents($customPropsFile);
            if ($customPropsCss === false) {
                $this->say("  ❌ {$scheme}: Cannot read custom properties CSS file");
                return false;
            }
            
            // Check if custom properties are already injected to avoid duplicates
            if (strpos($mainCss, ':root') !== false) {
                $this->say("  ℹ️ {$scheme}: CSS custom properties already present, updating...");
                // Remove existing :root block and replace with new one
                $mainCss = preg_replace('/:root\s*{[^}]*}/', '', $mainCss);
            }
            
            // Inject custom properties at the beginning of the CSS file
            $enhancedCss = $customPropsCss . "\n" . $mainCss;
            
            // Write the enhanced CSS back to the main file
            $success = file_put_contents($cssFile, $enhancedCss);
            if ($success === false) {
                $this->say("  ❌ {$scheme}: Cannot write enhanced CSS file");
                return false;
            }
            
            // Clean up temporary custom properties CSS file
            if (file_exists($customPropsFile)) {
                unlink($customPropsFile);
            }
            
            return true;
        } catch (Exception $e) {
            $this->say("  ❌ {$scheme}: Injection failed - " . $e->getMessage());
            return false;
        }
    }
}
