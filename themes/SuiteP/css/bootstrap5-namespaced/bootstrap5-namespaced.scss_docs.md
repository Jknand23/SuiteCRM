# Bootstrap 5 Namespaced System Documentation

## Overview

The Bootstrap 5 Namespaced System provides modern UI components alongside existing Bootstrap 3.3.7 without any conflicts. All Bootstrap 5 classes use the `.bs5-` prefix to ensure complete isolation from existing styles.

**Status**: ✅ **Step 5 Implementation Complete** - Ready for use in new components

## 🎯 Key Features

### ✅ Complete Isolation
- **Zero Conflicts**: All Bootstrap 5 classes prefixed with `.bs5-`
- **Preserved Functionality**: Existing `.btn`, `.panel`, `.container` classes unchanged
- **Side-by-Side Usage**: Mix Bootstrap 3.3.7 and Bootstrap 5 components seamlessly

### ✅ Theme Integration
- **CSS Custom Properties**: Integrates with SuiteCRM's theme system
- **Dynamic Theming**: Responds to existing theme switching automatically
- **Color Consistency**: Uses theme colors (--theme-primary, --theme-success, etc.)

### ✅ Modern Components
- **Flexbox Grid**: Modern responsive grid system
- **Enhanced Buttons**: Improved button variants and states
- **Card System**: Modern replacement for panels
- **Utility Classes**: Comprehensive spacing and layout utilities

### ✅ Selective Loading
- **Performance Optimized**: Load only needed components
- **Priority-Based**: Critical components loaded first
- **Modular Architecture**: Import individual components as needed

## 📁 File Structure

```
themes/SuiteP/css/bootstrap5-namespaced/
├── bootstrap5-namespaced.scss      # Main entry point
├── _variables.scss                 # Core variables and theme integration
├── _grid.scss                      # Modern flexbox grid system
├── _buttons.scss                   # Enhanced button components
├── _cards.scss                     # Card and accordion components
└── bootstrap5-namespaced.scss_docs.md  # This documentation
```

## 🚀 Quick Start

### 1. Import Into Existing Themes

Add to your theme's main SCSS file:

```scss
// In themes/SuiteP/css/Dawn/style.scss (and other themes)
@import "../bootstrap5-namespaced/bootstrap5-namespaced";
```

### 2. Use Namespaced Classes

```html
<!-- Existing Bootstrap 3.3.7 (preserved) -->
<div class="panel panel-default">
  <div class="panel-body">
    <button class="btn btn-primary">Old Button</button>
    
    <!-- NEW: Bootstrap 5 components -->
    <button class="bs5-btn bs5-btn-primary">Modern Button</button>
  </div>
</div>
```

### 3. Selective Component Loading

```scss
// Load only what you need
$bs5-enable-grid: true;
$bs5-enable-buttons: true;
$bs5-enable-cards: false;  // Disable if not needed
@import "../bootstrap5-namespaced/bootstrap5-namespaced";
```

## 🏗️ Component Usage Guide

### Grid System

Modern flexbox-based responsive grid:

```html
<div class="bs5-container">
  <div class="bs5-row">
    <div class="bs5-col-md-6">
      <div class="bs5-card">
        <div class="bs5-card-body">Left Content</div>
      </div>
    </div>
    <div class="bs5-col-md-6">
      <div class="bs5-card">
        <div class="bs5-card-body">Right Content</div>
      </div>
    </div>
  </div>
</div>
```

**Key Features**:
- **Responsive**: xs, sm, md, lg, xl, xxl breakpoints
- **Flexbox**: Modern alignment and distribution
- **Gap Control**: `.bs5-g-*` classes for spacing
- **Auto Columns**: `.bs5-col-auto` for content-based sizing

### Button System

Enhanced buttons with modern states:

```html
<!-- Solid buttons -->
<button class="bs5-btn bs5-btn-primary">Primary</button>
<button class="bs5-btn bs5-btn-secondary">Secondary</button>
<button class="bs5-btn bs5-btn-success">Success</button>

<!-- Outline buttons -->
<button class="bs5-btn bs5-btn-outline-primary">Outline Primary</button>
<button class="bs5-btn bs5-btn-outline-danger">Outline Danger</button>

<!-- Sizes -->
<button class="bs5-btn bs5-btn-primary bs5-btn-sm">Small</button>
<button class="bs5-btn bs5-btn-primary">Default</button>
<button class="bs5-btn bs5-btn-primary bs5-btn-lg">Large</button>

<!-- Button groups -->
<div class="bs5-btn-group">
  <button class="bs5-btn bs5-btn-primary">Left</button>
  <button class="bs5-btn bs5-btn-primary">Middle</button>
  <button class="bs5-btn bs5-btn-primary">Right</button>
</div>
```

**Theme Integration**: Buttons automatically use theme colors:
- `.bs5-btn-primary` → `var(--theme-primary)`
- `.bs5-btn-success` → `var(--theme-success)`
- `.bs5-btn-danger` → `var(--theme-danger)`

### Card System

Modern replacement for Bootstrap 3.3.7 panels:

```html
<!-- Basic Card -->
<div class="bs5-card">
  <div class="bs5-card-header">
    <h5 class="bs5-card-title">Card Title</h5>
  </div>
  <div class="bs5-card-body">
    <p class="bs5-card-text">Card content goes here.</p>
    <a href="#" class="bs5-btn bs5-btn-primary">Action Button</a>
  </div>
  <div class="bs5-card-footer">
    <small class="text-muted">Last updated 3 mins ago</small>
  </div>
</div>

<!-- Card with Image -->
<div class="bs5-card">
  <img src="image.jpg" class="bs5-card-img-top" alt="Card image">
  <div class="bs5-card-body">
    <h5 class="bs5-card-title">Image Card</h5>
    <p class="bs5-card-text">Card with image content.</p>
  </div>
</div>

<!-- Card Group -->
<div class="bs5-card-group">
  <div class="bs5-card">...</div>
  <div class="bs5-card">...</div>
  <div class="bs5-card">...</div>
</div>
```

**Accordion Support**:
```html
<div class="bs5-accordion">
  <div class="bs5-accordion-item">
    <h2 class="bs5-accordion-header">
      <button class="bs5-accordion-button" type="button">
        Accordion Item #1
      </button>
    </h2>
    <div class="bs5-accordion-collapse">
      <div class="bs5-accordion-body">
        Accordion content here.
      </div>
    </div>
  </div>
</div>
```

### Utility Classes

Comprehensive spacing and layout utilities:

```html
<!-- Display -->
<div class="bs5-d-none bs5-d-md-block">Hidden on mobile</div>
<div class="bs5-d-flex">Flexbox container</div>

<!-- Flexbox -->
<div class="bs5-d-flex bs5-justify-content-between bs5-align-items-center">
  <span>Left</span>
  <span>Right</span>
</div>

<!-- Spacing -->
<div class="bs5-p-3 bs5-mb-4">Padding 3, Margin bottom 4</div>
<div class="bs5-mx-auto">Centered with auto margins</div>

<!-- Responsive utilities -->
<div class="bs5-d-none bs5-d-lg-flex bs5-justify-content-lg-center">
  Responsive flexbox
</div>
```

## 🔄 Migration Strategy

### Phase 1: New Components Only
Use Bootstrap 5 namespaced classes for new components:

```html
<!-- NEW dashboard widget -->
<div class="bs5-card">
  <div class="bs5-card-header">
    <div class="bs5-d-flex bs5-justify-content-between bs5-align-items-center">
      <h5 class="bs5-card-title">Campaign Metrics</h5>
      <button class="bs5-btn bs5-btn-sm bs5-btn-outline-secondary">
        Refresh
      </button>
    </div>
  </div>
  <div class="bs5-card-body">
    <div class="bs5-row">
      <div class="bs5-col-6">
        <div class="bs5-text-center">
          <div class="h3">1,234</div>
          <div class="text-muted">Leads</div>
        </div>
      </div>
      <div class="bs5-col-6">
        <div class="bs5-text-center">
          <div class="h3">567</div>
          <div class="text-muted">Converted</div>
        </div>
      </div>
    </div>
  </div>
</div>
```

### Phase 2: Enhanced Existing Components
Gradually enhance existing components:

```html
<!-- Existing panel with modern button -->
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="bs5-d-flex bs5-justify-content-between bs5-align-items-center">
      <h3 class="panel-title">Existing Panel</h3>
      <button class="bs5-btn bs5-btn-sm bs5-btn-primary">
        Modern Action
      </button>
    </div>
  </div>
  <div class="panel-body">
    <!-- Enhanced layout with Bootstrap 5 grid -->
    <div class="bs5-row bs5-g-3">
      <div class="bs5-col-md-8">
        <p>Main content area</p>
      </div>
      <div class="bs5-col-md-4">
        <div class="bs5-card">
          <div class="bs5-card-body">
            <h6 class="bs5-card-title">Quick Stats</h6>
            <p class="bs5-card-text">Modern sidebar content</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
```

### Phase 3: Template Enhancement
Update templates with fallback support:

```smarty
{* Smarty template with Bootstrap 5 enhancements *}
<div class="panel panel-default bs5-card" style="
  background-color: var(--bs5-card-bg, {$panel_bg});
  border-color: var(--bs5-card-border-color, {$panel_border});
">
  <div class="panel-heading bs5-card-header">
    <h3 class="panel-title bs5-card-title">{$title}</h3>
  </div>
  <div class="panel-body bs5-card-body">
    {$content}
    
    <div class="bs5-d-flex bs5-gap-2 bs5-mt-3">
      <button class="btn btn-primary bs5-btn bs5-btn-primary">
        {$action_button}
      </button>
      <button class="btn btn-default bs5-btn bs5-btn-secondary">
        Cancel
      </button>
    </div>
  </div>
</div>
```

## 🎨 Theme Integration

### Automatic Theme Colors
Bootstrap 5 components automatically use SuiteCRM theme colors:

```scss
// Variables automatically integrate with themes
:root {
  --bs5-primary: var(--theme-primary, #0d6efd);
  --bs5-success: var(--theme-success, #198754);
  --bs5-danger: var(--theme-danger, #dc3545);
  --bs5-warning: var(--theme-warning, #ffc107);
  
  // Card colors
  --bs5-card-bg: var(--theme-surface, #fff);
  --bs5-card-border-color: var(--theme-border, #dee2e6);
}
```

### Theme-Aware Components
Use `.bs5-theme-aware` for automatic theme integration:

```html
<div class="bs5-card bs5-theme-aware">
  <div class="bs5-card-body">
    This card automatically adapts to theme changes
  </div>
</div>
```

### Custom Theme Variables
Create custom variables for specific components:

```scss
// In theme-specific files
:root {
  --bs5-dashboard-card-bg: var(--theme-surface-light);
  --bs5-dashboard-card-border: var(--theme-border-light);
}

.dashboard-widget {
  @extend .bs5-card;
  background-color: var(--bs5-dashboard-card-bg);
  border-color: var(--bs5-dashboard-card-border);
}
```

## ⚡ Performance Optimization

### Selective Loading
Load only components you need:

```scss
// Minimal load for simple pages
$bs5-enable-grid: true;
$bs5-enable-buttons: true;
$bs5-enable-cards: false;
@import "../bootstrap5-namespaced/bootstrap5-namespaced";

// Component-specific loading
@import "../bootstrap5-namespaced/variables";
@import "../bootstrap5-namespaced/grid";
@import "../bootstrap5-namespaced/buttons";
// Skip cards entirely
```

### Build Optimization
Optimize for production:

```scss
// Production settings
$bs5-enable-deprecation-messages: false;
$bs5-enable-important-utilities: false;  // Reduce specificity conflicts
@import "../bootstrap5-namespaced/bootstrap5-namespaced";
```

### PurgeCSS Integration
Remove unused classes in production:

```javascript
// PurgeCSS configuration
module.exports = {
  content: ['./themes/**/*.tpl', './themes/**/*.php'],
  css: ['./themes/**/style.css'],
  whitelist: [
    // Always keep Bootstrap 5 namespaced classes that might be used dynamically
    /^bs5-/,
    // Keep theme-related classes
    /^theme-/
  ]
}
```

## 🛠️ Development Guidelines

### Class Naming Convention
**ALWAYS** use the `.bs5-` prefix:

```html
<!-- ✅ CORRECT -->
<button class="bs5-btn bs5-btn-primary">Modern Button</button>
<div class="bs5-container">
  <div class="bs5-row">
    <div class="bs5-col-6">Content</div>
  </div>
</div>

<!-- ❌ WRONG -->
<button class="btn btn-primary">Would conflict with existing styles</button>
<div class="container">Would conflict with existing grid</div>
```

### Combining with Existing Classes
Safe to combine Bootstrap 3.3.7 and Bootstrap 5:

```html
<!-- ✅ SAFE COMBINATION -->
<div class="panel panel-default">
  <div class="panel-body">
    <div class="bs5-d-flex bs5-justify-content-between">
      <span>Content</span>
      <button class="bs5-btn bs5-btn-primary">Action</button>
    </div>
  </div>
</div>
```

### Development Helpers
Use debug classes during development:

```html
<!-- Development mode indicators -->
<div class="bs5-card bs5-debug">
  <div class="bs5-card-body">
    Debug info will show component class names
  </div>
</div>
```

## 🧪 Testing

### Browser Compatibility Testing
Test across supported browsers:

```html
<!-- Test basic functionality -->
<div class="bs5-container">
  <div class="bs5-row">
    <div class="bs5-col-12">
      <div class="bs5-card">
        <div class="bs5-card-body">
          <button class="bs5-btn bs5-btn-primary">Test Button</button>
        </div>
      </div>
    </div>
  </div>
</div>
```

### Theme Switching Testing
Verify components respond to theme changes:

```javascript
// Test theme integration
function testThemeIntegration() {
  // Change theme and verify Bootstrap 5 components update
  document.documentElement.style.setProperty('--theme-primary', '#ff0000');
  
  // Check if Bootstrap 5 buttons reflect the change
  const bs5Button = document.querySelector('.bs5-btn-primary');
  const computedStyle = getComputedStyle(bs5Button);
  console.log('Button background:', computedStyle.backgroundColor);
}
```

### Conflict Testing
Ensure no conflicts with existing styles:

```html
<!-- Test side-by-side usage -->
<div class="row">
  <div class="col-6">
    <div class="panel panel-default">
      <div class="panel-body">
        <button class="btn btn-primary">Bootstrap 3.3.7</button>
      </div>
    </div>
  </div>
  <div class="col-6">
    <div class="bs5-card">
      <div class="bs5-card-body">
        <button class="bs5-btn bs5-btn-primary">Bootstrap 5</button>
      </div>
    </div>
  </div>
</div>
```

## 🔮 Future Enhancements

### Planned Components
Priority order for additional components:

1. **Forms** (`.bs5-form-control`, `.bs5-input-group`)
2. **Navigation** (`.bs5-navbar`, `.bs5-nav`, `.bs5-breadcrumb`)
3. **Modals** (`.bs5-modal`, `.bs5-modal-dialog`)
4. **Tooltips** (`.bs5-tooltip`, `.bs5-popover`)
5. **Tables** (`.bs5-table`, `.bs5-table-responsive`)

### Advanced Features
Future enhancements:

- **CSS-in-JS**: Dynamic component loading
- **Tree Shaking**: Automatic unused class removal
- **Component Library**: Vue.js/React wrapper components
- **Design Tokens**: Standardized design system integration

## 📊 Component Status

| Component | Status | Classes Available | Theme Integration |
|-----------|---------|------------------|------------------|
| **Grid** | ✅ Complete | `.bs5-container`, `.bs5-row`, `.bs5-col-*` | ✅ Full |
| **Buttons** | ✅ Complete | `.bs5-btn`, `.bs5-btn-*`, `.bs5-btn-group` | ✅ Full |
| **Cards** | ✅ Complete | `.bs5-card`, `.bs5-card-*`, `.bs5-accordion` | ✅ Full |
| **Forms** | 🔄 Planned | `.bs5-form-control`, `.bs5-input-group` | 🔄 Planned |
| **Navigation** | 🔄 Planned | `.bs5-navbar`, `.bs5-nav` | 🔄 Planned |
| **Modals** | 🔄 Planned | `.bs5-modal`, `.bs5-modal-dialog` | 🔄 Planned |

## 🎉 Success Metrics

### Implementation Goals
- [x] **Zero Conflicts**: No interference with existing Bootstrap 3.3.7
- [x] **Theme Integration**: Automatic color adaptation
- [x] **Selective Loading**: Performance-optimized component loading
- [x] **Documentation**: Comprehensive usage guidelines
- [x] **Migration Path**: Clear strategy for gradual adoption

### Quality Assurance
- [x] **Cross-browser**: Tested in Chrome, Firefox, Safari, Edge
- [x] **Theme Compatibility**: Works with all 5 SuiteCRM themes
- [x] **Performance**: Minimal impact on existing page load times
- [x] **Accessibility**: WCAG AA compliant components

---

*Bootstrap 5 Namespaced System enables modern UI development while preserving existing functionality. All components are production-ready and fully integrated with SuiteCRM's theme system.* 