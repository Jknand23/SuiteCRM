# Night Theme CSS Custom Properties Layer Documentation

## Overview

The `custom-properties.scss` file implements Feature 2, Step 1 of the SuiteCRM modernization project by creating a CSS custom properties layer that enhances the existing Night theme without breaking any existing functionality.

## Purpose

This file serves as an **additive enhancement** that:
- ✅ **Preserves 100% backward compatibility** with existing SCSS variables
- ✅ **Creates CSS custom properties** from established theme variables  
- ✅ **Enables instant theme switching** using Alpine.js components
- ✅ **Maintains Bootstrap 3.3.7 compatibility**
- ✅ **Provides modern browser enhancements** without affecting legacy support
- ✅ **Optimized for dark theme accessibility**

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

## Night Theme Characteristics

### Color Scheme Profile
- **Design Philosophy**: Dark theme optimized for low-light environments and reduced eye strain
- **Primary Colors**: Carefully selected dark palette with sufficient contrast for accessibility
- **Background**: Dark backgrounds with appropriate contrast ratios
- **User Experience**: Optimized for extended nighttime use with reduced blue light

### Dark Theme Considerations
- **Enhanced Shadows**: Stronger shadow effects for dark backgrounds
- **Improved Focus Indicators**: Higher contrast focus states for accessibility
- **Color Adjustments**: Lighter hover states for dark theme interaction patterns

### Variable Categories

#### 1. Brand Colors
Maps existing `$brand-*` variables to CSS custom properties:
- `--theme-primary` ← `$brand-primary`
- `--theme-success` ← `$brand-success`
- `--theme-danger` ← `$brand-danger`
- `--theme-warning` ← `$brand-warning`
- `--theme-info` ← `$brand-info`

#### 2. Background Colors  
Maps existing background variables:
- `--theme-body-bg` ← `$body-bg`
- `--theme-main-bg` ← `$main-bg`
- `--theme-main-alternate-bg` ← `$main-alternate-bg`

#### 3. Panel Colors
Maps existing `$panel-*` variables:
- `--theme-panel-bg` ← `$panel-bg`
- `--theme-panel-border` ← `$panel-default-border`
- `--theme-panel-heading-bg` ← `$panel-default-heading-bg`
- `--theme-panel-inner-border` ← `$panel-inner-border`

#### 4. Button Colors
Maps existing `$btn-*` variables for all button types:
- `--theme-btn-primary-bg` ← `$btn-primary-bg`
- `--theme-btn-default-bg` ← `$btn-default-bg`
- `--theme-btn-success-bg` ← `$btn-success-bg`
- And corresponding color and border variables

#### 5. Text Colors
Maps existing text-related variables:
- `--theme-text-color` ← `$text-color`
- `--theme-link-color` ← `$link-color`
- `--theme-link-hover-color` ← `$link-hover-color`

#### 6. State Colors
Maps existing form and alert state variables:
- `--theme-state-success-*` ← `$state-success-*`
- `--theme-state-info-*` ← `$state-info-*`
- `--theme-state-warning-*` ← `$state-warning-*`
- `--theme-state-danger-*` ← `$state-danger-*`

## Enhanced Features

### Dark Theme Specific Utilities
Additional custom properties optimized for dark theme:
```scss
--theme-primary-hover: #{lighten($brand-primary, 10%)};    // Lighter for dark theme
--theme-primary-active: #{lighten($brand-primary, 15%)};   // Lighter for dark theme
--theme-panel-shadow: rgba(0, 0, 0, 0.3);                  // Stronger shadow for dark theme
--theme-panel-shadow-hover: rgba(0, 0, 0, 0.4);            // Enhanced shadow for dark theme
```

### Theme Metadata
JavaScript-accessible theme information:
```scss
--theme-name: 'night';
--theme-variant: 'dark';
--theme-contrast: 'high';
```

### Semantic Aliases  
User-friendly naming for common patterns:
```scss
--theme-surface: var(--theme-panel-bg);
--theme-surface-hover: #{lighten($panel-bg, 5%)};          // Lighter for dark theme
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

### Dark Theme Accessibility
Special accessibility enhancements for the Night theme:
```scss
.form-control:focus {
  border-color: var(--theme-input-border-focus);
  box-shadow: var(--theme-focus-shadow);
}
```

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
- ✅ **Dark theme accessibility** enhancements

### Legacy Browsers (No CSS Custom Properties)
- ✅ **Full existing functionality** maintained
- ✅ **No degradation** of current user experience
- ✅ **Compiled SCSS** provides all styling as before

## Accessibility Considerations

### Dark Theme Best Practices
- ✅ **WCAG AA Compliance**: Colors meet minimum contrast requirements
- ✅ **Enhanced Focus Indicators**: Higher contrast focus shadows for keyboard navigation
- ✅ **Reduced Eye Strain**: Optimized for low-light environments
- ✅ **Color Independence**: Information not conveyed by color alone

### Enhanced Focus Management
```scss
--theme-focus-shadow: 0 0 0 0.2rem rgba(#{red($brand-primary)}, #{green($brand-primary)}, #{blue($brand-primary)}, 0.4);
```

## File Dependencies

### Required Files (Must Load First)
1. `themes/SuiteP/css/Night/color-palette.scss` - Color definitions
2. `themes/SuiteP/css/Night/variables.scss` - SCSS variable definitions

### Generated Output
- CSS custom properties are added to the compiled CSS
- Existing styles remain completely unchanged
- Progressive enhancement features are conditionally applied
- Dark theme accessibility features are available

## Implementation Status

### ✅ Completed
- [x] Night theme custom properties mapping
- [x] Complete variable coverage (brand, panel, button, text, state)
- [x] Progressive enhancement with @supports
- [x] Semantic aliases and utility properties
- [x] Theme metadata for JavaScript consumption
- [x] Dark theme accessibility enhancements
- [x] Comprehensive documentation

## Security Considerations

### CSS Injection Protection
- ✅ **All values interpolated from SCSS variables** - no user input
- ✅ **No dynamic CSS generation** from untrusted sources
- ✅ **Standard SCSS compilation process** maintained

### Performance Impact
- ✅ **Minimal overhead** - only CSS custom properties added
- ✅ **No JavaScript requirements** for basic functionality
- ✅ **Progressive enhancement** - no impact on legacy browsers

## Testing Requirements
- ✅ **SCSS compilation** must succeed without errors
- ✅ **Existing theme switching** must work exactly as before
- ✅ **CSS custom properties** must be present in compiled output
- ✅ **Progressive enhancement** features must not break legacy browsers
- ✅ **Dark theme accessibility** features must be functional

## Related Documentation

- [Dawn Theme Custom Properties](../Dawn/custom-properties.scss_docs.md)
- [Day Theme Custom Properties](../Day/custom-properties.scss_docs.md)
- [Dusk Theme Custom Properties](../Dusk/custom-properties.scss_docs.md)
- [Noon Theme Custom Properties](../Noon/custom-properties.scss_docs.md) 