# Standalone Theme Compiler Workflow

## Overview
This document outlines the standalone SCSS compilation workflow for SuiteCRM theme development, designed to bypass Robo dependency issues in PHP 7.4 environments.

## Quick Start

### Compile All Themes
```bash
docker exec suitecrm_app php compile-themes.php all
```

### Compile Specific Theme
```bash
docker exec suitecrm_app php compile-themes.php Dawn
```

### List Available Themes
```bash
docker exec suitecrm_app php compile-themes.php list
```

## Available Themes
- Dawn
- Day  
- Dusk
- Night
- Noon

## What Gets Compiled
Each theme compilation includes:
- ✅ **89 CSS Custom Properties** (--theme-primary-color, --theme-background, etc.)
- ✅ **SCSS Variable Integration** (#{$variable} syntax)
- ✅ **Bootstrap 3.3.7 Compatibility**
- ✅ **SuiteP Theme Structure**

## File Locations

### Source Files
```
themes/SuiteP/css/{Theme}/custom-properties.scss
themes/SuiteP/css/{Theme}/style.scss
```

### Compiled Output
```
themes/SuiteP/css/{Theme}/style.css
```

## Development Workflow

### 1. Edit Custom Properties
Modify theme variables in:
```scss
// themes/SuiteP/css/Dawn/custom-properties.scss
:root {
  --theme-primary-color: #{$primary-color};
  --theme-secondary-color: #{$secondary-color};
  // ... 89 total properties
}
```

### 2. Compile Changes
```bash
docker exec suitecrm_app php compile-themes.php Dawn
```

### 3. Verify Output
Check compiled CSS:
```bash
docker exec suitecrm_app head -50 themes/SuiteP/css/Dawn/style.css
```

## Advantages Over Robo
- ✅ **No PHP 8+ Dependencies**: Works with PHP 7.4
- ✅ **Faster Compilation**: Direct SCSS processing
- ✅ **Reliable**: No dependency conflicts
- ✅ **Docker Compatible**: Runs in existing environment

## Limitations
- ❌ **No JS Bundling**: Use separate tools if needed
- ❌ **No Image Optimization**: Handle separately  
- ❌ **No File Watching**: Manual compilation required

## Troubleshooting

### ScssPhp Not Found
```bash
docker exec suitecrm_app composer install --dev
```

### Permission Issues
```bash
docker exec suitecrm_app chown -R www-data:www-data themes/
```

### Compilation Errors
Check SCSS syntax in source files and ensure all variables are defined.

## Integration with Git
The compiled CSS files should be committed to git for deployment:
```bash
git add themes/SuiteP/css/*/style.css
git commit -m "Update compiled theme CSS with custom properties"
```

## Future Migration Path
When ready to upgrade to PHP 8+, simply:
1. Update PHP version
2. Run `composer update`  
3. Switch back to `vendor/bin/robo build:suiteP`
4. Remove `compile-themes.php` 