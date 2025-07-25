# Dawn Theme CSS Custom Properties Layer Documentation

## Overview

The `custom-properties.scss` file implements Feature 2, Step 1 of the SuiteCRM modernization project by creating a CSS custom properties layer that enhances the existing Dawn theme without breaking any existing functionality.

## Purpose

This file serves as an **additive enhancement** that:
- ✅ **Preserves 100% backward compatibility** with existing SCSS variables
- ✅ **Creates CSS custom properties** from established theme variables  
- ✅ **Enables instant theme switching** using Alpine.js components
- ✅ **Maintains Bootstrap 3.3.7 compatibility**
- ✅ **Provides modern browser enhancements** without affecting legacy support

## Technical Approach

### Evolutionary Design Pattern
```scss
// READS existing SCSS variables (does NOT replace them)
--theme-primary: #{$brand-primary};     // Converts $brand-primary to CSS custom property
--theme-panel-bg: #{$panel-bg};         // Converts $panel-bg to CSS custom property
```

This approach ensures that:
- All existing SCSS compilation continues to work exactly as before
- CSS custom properties are generated from the same source variables
- Modern browsers get enhanced theming capabilities
- Legacy browsers continue using compiled SCSS without issues

## Variable Categories

### 1. Brand Colors
Maps existing `$brand-*` variables to CSS custom properties:
- `--theme-primary` ← `$brand-primary` (#778591)
- `--theme-success` ← `$brand-success` (#5CB85C)
- `--theme-danger` ← `$brand-danger` (#F08377)
- `--theme-warning` ← `$brand-warning` (#E6D5A5)
- `--theme-info` ← `$brand-info` (#AA9DCC)

### 2. Background Colors  
Maps existing background variables:
- `--theme-body-bg` ← `$body-bg` (#FFFFFF)
- `--theme-main-bg` ← `$main-bg` (#F5F5F5)
- `--theme-main-alternate-bg` ← `$main-alternate-bg` (#FFFFFF)

### 3. Panel Colors
Maps existing `$panel-*` variables:
- `--theme-panel-bg` ← `$panel-bg`
- `--theme-panel-border` ← `$panel-default-border`
- `--theme-panel-heading-bg` ← `$panel-default-heading-bg`
- `--theme-panel-inner-border` ← `$panel-inner-border`

### 4. Button Colors
Maps existing `$btn-*` variables for all button types:
- `--theme-btn-primary-bg` ← `$btn-primary-bg`
- `--theme-btn-default-bg` ← `$btn-default-bg`
- `--theme-btn-success-bg` ← `$btn-success-bg`
- And corresponding color and border variables

### 5. Text Colors
Maps existing text-related variables:
- `--theme-text-color` ← `$text-color`
- `--theme-link-color` ← `$link-color`
- `--theme-link-hover-color` ← `$link-hover-color`

### 6. Gray Scale
Maps existing Bootstrap gray variables:
- `--theme-gray-base` ← `$gray-base`
- `--theme-gray-darker` ← `$gray-darker`
- `--theme-gray-light` ← `$gray-light`

### 7. State Colors
Maps existing form and alert state variables:
- `--theme-state-success-*` ← `$state-success-*`
- `--theme-state-info-*` ← `$state-info-*`
- `--theme-state-warning-*` ← `$state-warning-*`
- `--theme-state-danger-*` ← `$state-danger-*`

## Enhanced Features

### Computed Utilities
Additional custom properties that provide commonly needed color variations:
```scss
--theme-primary-hover: #{darken($brand-primary, 10%)};
--theme-primary-active: #{darken($brand-primary, 15%)};
--theme-primary-light: #{lighten($brand-primary, 30%)};
```

### Theme Metadata
JavaScript-accessible theme information:
```scss
--theme-name: 'dawn';
--theme-variant: 'light';
--theme-contrast: 'standard';
```

### Semantic Aliases  
User-friendly naming for common patterns:
```scss
--theme-surface: var(--theme-panel-bg);
--theme-on-surface: var(--theme-text-color);
--theme-on-primary: var(--theme-btn-primary-color);
```

## Progressive Enhancement

### @supports Integration
The file includes progressive enhancement using `@supports` to apply enhanced styling only when custom properties are supported:

```scss
@supports (color: var(--theme-primary)) {
  .btn-primary {
    background-color: var(--theme-btn-primary-bg);
    border-color: var(--theme-btn-primary-border);
  }
}
```

This ensures:
- ✅ **Modern browsers** get enhanced theming capabilities
- ✅ **Legacy browsers** continue using existing compiled SCSS
- ✅ **Zero breaking changes** for any browser or environment

## Integration Points

### With Existing SCSS System
- **Dependency**: Requires `variables.scss` and `color-palette.scss` to be loaded first
- **Compilation**: Must be compiled AFTER variable definitions are loaded
- **Output**: Generates additional CSS custom properties alongside existing styles

### With Alpine.js Components
The CSS custom properties enable Alpine.js components to switch themes instantly:
```javascript
// Alpine.js can now change theme by updating CSS custom properties
document.documentElement.style.setProperty('--theme-primary', newPrimaryColor);
```

### With Build Process
- **Non-Breaking**: Integrates with existing SCSS compilation pipeline
- **Additive**: Adds custom properties without modifying existing output
- **Compatible**: Works with current `pscss` build system

## Browser Support

### Modern Browsers (CSS Custom Properties Support)
- ✅ **Enhanced theming** with instant switching capabilities
- ✅ **Progressive enhancement** features enabled
- ✅ **All existing functionality** plus new capabilities

### Legacy Browsers (No CSS Custom Properties)
- ✅ **Full existing functionality** maintained
- ✅ **No degradation** of current user experience
- ✅ **Compiled SCSS** provides all styling as before

## Usage Examples

### Template Enhancement (Backward Compatible)
```smarty
{* Existing templates work exactly as before *}
<div class="panel panel-default">
  <div class="panel-heading">Title</div>
  <div class="panel-body">Content</div>
</div>

{* New templates can optionally use custom properties as fallbacks *}
<div class="panel" style="background-color: var(--theme-surface, {$panel_bg});">
  <div class="panel-heading" style="background-color: var(--theme-panel-heading-bg, {$panel_heading_bg});">
    Enhanced Title
  </div>
</div>
```

### Alpine.js Integration
```javascript
function themeManager() {
  return {
    currentTheme: 'dawn',
    switchTheme(themeName) {
      // Instantly update theme using CSS custom properties
      this.updateCustomProperties(themeName);
    }
  };
}
```

## File Dependencies

### Required Files (Must Load First)
1. `themes/SuiteP/css/Dawn/color-palette.scss` - Color definitions
2. `themes/SuiteP/css/Dawn/variables.scss` - SCSS variable definitions

### Generated Output
- CSS custom properties are added to the compiled CSS
- Existing styles remain completely unchanged
- Progressive enhancement features are conditionally applied

## Implementation Status

### ✅ Completed
- [x] Dawn theme custom properties mapping
- [x] Complete variable coverage (brand, panel, button, text, state)
- [x] Progressive enhancement with @supports
- [x] Semantic aliases and utility properties
- [x] Theme metadata for JavaScript consumption
- [x] Comprehensive documentation

### ⏳ Next Steps (Other Themes)
- [ ] Day theme custom properties
- [ ] Dusk theme custom properties
- [ ] Night theme custom properties  
- [ ] Noon theme custom properties
- [ ] Build process integration

## Security Considerations

### CSS Injection Protection
- ✅ **All values interpolated from SCSS variables** - no user input
- ✅ **No dynamic CSS generation** from untrusted sources
- ✅ **Standard SCSS compilation process** maintained

### Performance Impact
- ✅ **Minimal overhead** - only CSS custom properties added
- ✅ **No JavaScript requirements** for basic functionality
- ✅ **Progressive enhancement** - no impact on legacy browsers

## Maintenance Guidelines

### Adding New Variables
1. Add to appropriate section with clear comments
2. Reference existing SCSS variable using `#{$variable}` syntax
3. Follow `--theme-category-property` naming convention
4. Update documentation

### Modifying Existing Variables
1. **Never modify** the SCSS variable references
2. Only add new custom properties or enhance existing ones
3. Maintain backward compatibility at all times
4. Test with existing SCSS compilation

### Testing Requirements
- ✅ **SCSS compilation** must succeed without errors
- ✅ **Existing theme switching** must work exactly as before
- ✅ **CSS custom properties** must be present in compiled output
- ✅ **Progressive enhancement** features must not break legacy browsers 