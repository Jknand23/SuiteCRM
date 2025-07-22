# SuiteCRM Theme Rules & Color System

## Overview

This document defines the comprehensive theming system for the SuiteCRM modernization project, establishing consistent colors, styling variables, and visual treatments across all components. Built upon the existing SuiteP theme foundation, this system supports multiple sub-themes while maintaining modern design standards optimized for marketing agency workflows.

## 🎨 Color Philosophy

### Adaptive Color System
The SuiteCRM theme system supports five distinct sub-themes, each optimized for different working conditions and user preferences:

- **Dawn**: Warm, welcoming tones for morning productivity
- **Day**: Bright, high-contrast colors for active work periods  
- **Dusk**: Muted, sophisticated palette for evening sessions
- **Night**: Dark mode with reduced eye strain for low-light environments
- **Noon**: Professional, corporate-friendly appearance

---

## 🌈 Core Color Palettes

### Primary Brand Colors

#### Universal Brand Colors (Theme-Independent)
```scss
// Core brand identity - consistent across all themes
$brand-primary: #177EE5;       // SuiteCRM Blue
$brand-secondary: #534D64;     // Professional Purple
$brand-success: #3C763D;       // Success Green
$brand-warning: #E6D5A5;       // Warning Amber
$brand-danger: #E61718;        // Error Red
$brand-info: #31708F;          // Info Blue
```

#### Theme-Specific Primary Colors
```scss
// Dawn Theme
$dawn-primary: #778591;        // Soft Blue-Gray
$dawn-accent: #F08377;         // Warm Coral
$dawn-highlight: #A5E8D6;      // Mint Green

// Day Theme  
$day-primary: #4B97C4;         // Bright Blue
$day-accent: #378CBE;          // Ocean Blue
$day-highlight: #CCE3F0;       // Light Blue

// Dusk Theme
$dusk-primary: #4C4C4C;        // Charcoal
$dusk-accent: #ff7467;         // Sunset Orange
$dusk-highlight: #70D1DF;      // Cyan

// Night Theme
$night-primary: #2E4F5C;       // Deep Teal
$night-accent: #E6A06D;        // Golden Orange
$night-highlight: #76BEC1;     // Aqua

// Noon Theme
$noon-primary: #001E40;        // Navy Blue
$noon-accent: #FFDE00;         // Corporate Yellow
$noon-highlight: #7BDBFF;      // Sky Blue
```

---

## 🎭 Theme-Specific Color Systems

### Dawn Theme - Warm & Welcoming
```scss
// Background Colors
$dawn-main-bg: #F5F5F5;           // Main background
$dawn-panel-bg: #FFFFFF;          // Panel background
$dawn-sidebar-bg: #534D64;        // Sidebar background

// Text Colors
$dawn-text-primary: #534D64;      // Primary text
$dawn-text-secondary: #778591;    // Secondary text
$dawn-text-muted: #A2A5AF;        // Muted text

// Interactive Colors
$dawn-link-color: #F08377;        // Link color
$dawn-link-hover: #E56455;        // Link hover
$dawn-button-primary: #778591;    // Primary button
$dawn-button-secondary: #94A6B5;  // Secondary button

// State Colors
$dawn-border-color: #E6E6E6;      // Default borders
$dawn-focus-color: #F08377;       // Focus states
$dawn-selection-bg: #F08377;      // Text selection
$dawn-selection-color: #FFFFFF;   // Selection text
```

### Day Theme - Bright & Energetic
```scss
// Background Colors
$day-main-bg: #F5F5F5;            // Main background
$day-panel-bg: #FFFFFF;           // Panel background
$day-sidebar-bg: #4B97C4;         // Sidebar background

// Text Colors
$day-text-primary: #333333;       // Primary text
$day-text-secondary: #707D84;     // Secondary text
$day-text-muted: #929798;         // Muted text

// Interactive Colors
$day-link-color: #4B97C4;         // Link color
$day-link-hover: #378CBE;         // Link hover
$day-button-primary: #4B97C4;     // Primary button
$day-button-secondary: #707D84;   // Secondary button

// State Colors
$day-border-color: #E6E6E6;       // Default borders
$day-focus-color: #4B97C4;        // Focus states
$day-selection-bg: #4B97C4;       // Text selection
$day-selection-color: #FFFFFF;    // Selection text
```

### Dusk Theme - Sophisticated & Muted
```scss
// Background Colors
$dusk-main-bg: #F5F5F5;           // Main background
$dusk-panel-bg: #FFFFFF;          // Panel background
$dusk-sidebar-bg: #EFEFEF;        // Sidebar background

// Text Colors
$dusk-text-primary: #54505c;      // Primary text
$dusk-text-secondary: #454545;    // Secondary text
$dusk-text-muted: #787878;        // Muted text

// Interactive Colors
$dusk-link-color: #ff7467;        // Link color
$dusk-link-hover: #e65c47;        // Link hover
$dusk-button-primary: #ff7467;    // Primary button
$dusk-button-secondary: #636363;  // Secondary button

// State Colors
$dusk-border-color: #E6E6E6;      // Default borders
$dusk-focus-color: #ff7467;       // Focus states
$dusk-selection-bg: #EFEFEF;      // Text selection
$dusk-selection-color: #54505c;   // Selection text
```

### Night Theme - Dark & Eye-Friendly
```scss
// Background Colors
$night-main-bg: #253F4A;          // Main background
$night-panel-bg: #2E4F5C;         // Panel background
$night-sidebar-bg: #416F81;       // Sidebar background

// Text Colors
$night-text-primary: #ffffff;     // Primary text
$night-text-secondary: #ffffff;   // Secondary text
$night-text-muted: #9BA3A7;       // Muted text

// Interactive Colors
$night-link-color: #E6A06D;       // Link color
$night-link-hover: #d49366;       // Link hover
$night-button-primary: #2E4F5C;   // Primary button
$night-button-secondary: #4E869C; // Secondary button

// State Colors
$night-border-color: #305160;     // Default borders
$night-focus-color: #E6A06D;      // Focus states
$night-selection-bg: #E6A06D;     // Text selection
$night-selection-color: #ffffff;  // Selection text
```

### Noon Theme - Professional & Corporate
```scss
// Background Colors
$noon-main-bg: #FFFFFF;           // Main background
$noon-panel-bg: #FFFFFF;          // Panel background
$noon-sidebar-bg: #032241;        // Sidebar background

// Text Colors
$noon-text-primary: #000000;      // Primary text
$noon-text-secondary: #333333;    // Secondary text
$noon-text-muted: #666666;        // Muted text

// Interactive Colors
$noon-link-color: #001E40;        // Link color
$noon-link-hover: #001A36;        // Link hover
$noon-button-primary: #001E40;    // Primary button
$noon-button-secondary: #E5E8EB;  // Secondary button

// State Colors
$noon-border-color: #DDDDDD;      // Default borders
$noon-focus-color: #FFDE00;       // Focus states
$noon-selection-bg: #7BDBFF;      // Text selection
$noon-selection-color: #001E40;   // Selection text
```

---

## 🎛️ Semantic Color Variables

### Status & Feedback Colors
```scss
// Success States
$success-color: #3C763D;
$success-bg: #DFF0D8;
$success-border: #D6E9C6;
$success-text: #3C763D;

// Warning States  
$warning-color: #8A6D3B;
$warning-bg: #FCF8E3;
$warning-border: #FAEBCC;
$warning-text: #8A6D3B;

// Error States
$error-color: #A94442;
$error-bg: #F2DEDE;
$error-border: #EBCCD1;
$error-text: #A94442;

// Info States
$info-color: #31708F;
$info-bg: #D9EDF7;
$info-border: #BCE8F1;
$info-text: #31708F;
```

### Component-Specific Colors
```scss
// Navigation
$navbar-height: 44px;
$navbar-bg: var(--theme-sidebar-bg);
$navbar-link-color: #FFFFFF;
$navbar-link-hover: var(--theme-accent);

// Sidebar
$sidebar-width: 240px;
$sidebar-bg: var(--theme-sidebar-bg);
$sidebar-border: var(--theme-border-color);
$sidebar-item-height: 48px;
$sidebar-item-hover: rgba(255, 255, 255, 0.1);

// Panels
$panel-bg: var(--theme-panel-bg);
$panel-border: var(--theme-border-color);
$panel-header-bg: var(--theme-primary);
$panel-header-color: #FFFFFF;
$panel-header-height: 44px;
$panel-radius: 4px;

// Forms
$input-bg: #FFFFFF;
$input-border: #CCCCCC;
$input-focus-border: var(--theme-focus-color);
$input-disabled-bg: #F5F5F5;
$input-height: 36px;
$input-padding: 8px 12px;

// Buttons
$button-height: 36px;
$button-padding: 0 20px;
$button-radius: 3px;
$button-font-weight: 500;
$button-text-transform: uppercase;

// Data Tables
$table-header-bg: var(--theme-primary);
$table-header-color: #FFFFFF;
$table-row-bg: #FFFFFF;
$table-row-alternate: #F9F9F9;
$table-row-hover: #F0F8FF;
$table-border: var(--theme-border-color);
```

---

## 🔧 CSS Custom Properties

### Dynamic Theme Variables
```scss
:root {
  // Theme-specific variables (set dynamically)
  --theme-primary: #{$dawn-primary};
  --theme-accent: #{$dawn-accent};
  --theme-highlight: #{$dawn-highlight};
  --theme-main-bg: #{$dawn-main-bg};
  --theme-panel-bg: #{$dawn-panel-bg};
  --theme-sidebar-bg: #{$dawn-sidebar-bg};
  --theme-text-primary: #{$dawn-text-primary};
  --theme-text-secondary: #{$dawn-text-secondary};
  --theme-text-muted: #{$dawn-text-muted};
  --theme-link-color: #{$dawn-link-color};
  --theme-link-hover: #{$dawn-link-hover};
  --theme-border-color: #{$dawn-border-color};
  --theme-focus-color: #{$dawn-focus-color};
  
  // Universal brand colors
  --brand-primary: #{$brand-primary};
  --brand-secondary: #{$brand-secondary};
  --brand-success: #{$brand-success};
  --brand-warning: #{$brand-warning};
  --brand-danger: #{$brand-danger};
  --brand-info: #{$brand-info};
  
  // Status colors
  --success-color: #{$success-color};
  --success-bg: #{$success-bg};
  --warning-color: #{$warning-color};
  --warning-bg: #{$warning-bg};
  --error-color: #{$error-color};
  --error-bg: #{$error-bg};
  --info-color: #{$info-color};
  --info-bg: #{$info-bg};
  
  // Component sizing
  --navbar-height: #{$navbar-height};
  --sidebar-width: #{$sidebar-width};
  --panel-radius: #{$panel-radius};
  --button-height: #{$button-height};
  --input-height: #{$input-height};
}
```

### Theme Switching
```scss
// Dawn Theme
[data-theme="dawn"] {
  --theme-primary: #{$dawn-primary};
  --theme-accent: #{$dawn-accent};
  --theme-highlight: #{$dawn-highlight};
  // ... all dawn variables
}

// Day Theme
[data-theme="day"] {
  --theme-primary: #{$day-primary};
  --theme-accent: #{$day-accent};
  --theme-highlight: #{$day-highlight};
  // ... all day variables
}

// Dusk Theme
[data-theme="dusk"] {
  --theme-primary: #{$dusk-primary};
  --theme-accent: #{$dusk-accent};
  --theme-highlight: #{$dusk-highlight};
  // ... all dusk variables
}

// Night Theme
[data-theme="night"] {
  --theme-primary: #{$night-primary};
  --theme-accent: #{$night-accent};
  --theme-highlight: #{$night-highlight};
  // ... all night variables
}

// Noon Theme
[data-theme="noon"] {
  --theme-primary: #{$noon-primary};
  --theme-accent: #{$noon-accent};
  --theme-highlight: #{$noon-highlight};
  // ... all noon variables
}
```

---

## 📏 Spacing & Typography System

### Spacing Scale
```scss
// Base spacing unit: 4px
$space-1: 4px;    // 0.25rem
$space-2: 8px;    // 0.5rem
$space-3: 12px;   // 0.75rem
$space-4: 16px;   // 1rem
$space-5: 20px;   // 1.25rem
$space-6: 24px;   // 1.5rem
$space-8: 32px;   // 2rem
$space-10: 40px;  // 2.5rem
$space-12: 48px;  // 3rem
$space-16: 64px;  // 4rem
$space-20: 80px;  // 5rem

// Component-specific spacing
$component-padding: $space-4;
$component-margin: $space-5;
$section-spacing: $space-8;
$page-spacing: $space-10;
```

### Typography Scale
```scss
// Font sizes
$font-size-xs: 11px;
$font-size-sm: 12px;
$font-size-base: 13px;
$font-size-md: 14px;
$font-size-lg: 16px;
$font-size-xl: 18px;
$font-size-2xl: 20px;
$font-size-3xl: 24px;
$font-size-4xl: 28px;
$font-size-5xl: 32px;

// Font weights
$font-weight-light: 300;
$font-weight-normal: 400;
$font-weight-medium: 500;
$font-weight-semibold: 600;
$font-weight-bold: 700;
$font-weight-extrabold: 800;

// Line heights
$line-height-tight: 1.2;
$line-height-normal: 1.4;
$line-height-relaxed: 1.6;
$line-height-loose: 1.8;
```

---

## 🎯 Component Theme Specifications

### Buttons
```scss
// Primary Button
.btn-primary {
  background-color: var(--theme-primary);
  border-color: var(--theme-primary);
  color: #ffffff;
  
  &:hover {
    background-color: color-mix(in srgb, var(--theme-primary) 85%, black);
    border-color: color-mix(in srgb, var(--theme-primary) 85%, black);
  }
  
  &:focus {
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--theme-primary) 30%, transparent);
  }
}

// Secondary Button  
.btn-secondary {
  background-color: transparent;
  border-color: var(--theme-primary);
  color: var(--theme-primary);
  
  &:hover {
    background-color: var(--theme-primary);
    color: #ffffff;
  }
}

// Danger Button
.btn-danger {
  background-color: var(--error-color);
  border-color: var(--error-color);
  color: #ffffff;
  
  &:hover {
    background-color: color-mix(in srgb, var(--error-color) 85%, black);
  }
}
```

### Form Elements
```scss
// Input Fields
.form-control {
  background-color: var(--input-bg);
  border: 1px solid var(--theme-border-color);
  color: var(--theme-text-primary);
  
  &:focus {
    border-color: var(--theme-focus-color);
    box-shadow: 0 0 0 2px color-mix(in srgb, var(--theme-focus-color) 20%, transparent);
  }
  
  &:disabled {
    background-color: var(--input-disabled-bg);
    color: var(--theme-text-muted);
  }
  
  &.is-invalid {
    border-color: var(--error-color);
  }
}

// Select Elements
.form-select {
  background-image: url("data:image/svg+xml,..."); // Custom dropdown arrow
  background-color: var(--input-bg);
  border: 1px solid var(--theme-border-color);
  color: var(--theme-text-primary);
}

// Checkboxes & Radio Buttons
.form-check-input {
  background-color: var(--input-bg);
  border: 1px solid var(--theme-border-color);
  
  &:checked {
    background-color: var(--theme-primary);
    border-color: var(--theme-primary);
  }
  
  &:focus {
    box-shadow: 0 0 0 2px color-mix(in srgb, var(--theme-focus-color) 20%, transparent);
  }
}
```

### Panels & Cards
```scss
.panel {
  background-color: var(--theme-panel-bg);
  border: 1px solid var(--theme-border-color);
  border-radius: var(--panel-radius);
  
  .panel-header {
    background-color: var(--theme-primary);
    color: #ffffff;
    border-bottom: 1px solid var(--theme-border-color);
    
    &.collapsed {
      background-color: color-mix(in srgb, var(--theme-primary) 80%, white);
    }
  }
  
  .panel-body {
    background-color: var(--theme-panel-bg);
    color: var(--theme-text-primary);
  }
}
```

### Navigation
```scss
.navbar {
  background-color: var(--theme-sidebar-bg);
  border-bottom: 1px solid var(--theme-border-color);
  
  .navbar-brand {
    color: #ffffff;
  }
  
  .navbar-nav .nav-link {
    color: rgba(255, 255, 255, 0.8);
    
    &:hover, &:focus {
      color: #ffffff;
    }
    
    &.active {
      color: var(--theme-accent);
    }
  }
}

.sidebar {
  background-color: var(--theme-sidebar-bg);
  border-right: 1px solid var(--theme-border-color);
  
  .sidebar-item {
    color: rgba(255, 255, 255, 0.8);
    
    &:hover {
      background-color: rgba(255, 255, 255, 0.1);
      color: #ffffff;
    }
    
    &.active {
      background-color: var(--theme-accent);
      color: #ffffff;
    }
  }
}
```

### Data Tables
```scss
.table {
  background-color: var(--theme-panel-bg);
  color: var(--theme-text-primary);
  
  thead th {
    background-color: var(--theme-primary);
    color: #ffffff;
    border-bottom: 2px solid var(--theme-border-color);
  }
  
  tbody tr {
    &:nth-child(even) {
      background-color: var(--table-row-alternate);
    }
    
    &:hover {
      background-color: var(--table-row-hover);
    }
  }
  
  td, th {
    border-color: var(--theme-border-color);
    padding: $space-3 $space-4;
  }
}
```

---

## 🎨 Special Effects & Animations

### Shadow System
```scss
// Elevation shadows
$shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
$shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
$shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
$shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.1);

// Component shadows
$button-shadow: $shadow-sm;
$panel-shadow: $shadow-md;
$modal-shadow: $shadow-xl;
$dropdown-shadow: $shadow-lg;
```

### Border Radius
```scss
$radius-sm: 2px;
$radius-base: 3px;
$radius-md: 4px;
$radius-lg: 6px;
$radius-xl: 8px;
$radius-full: 50%;

// Component radius
$button-radius: $radius-base;
$panel-radius: $radius-md;
$input-radius: $radius-base;
$modal-radius: $radius-lg;
```

### Transitions
```scss
// Duration
$transition-fast: 150ms;
$transition-base: 200ms;
$transition-slow: 300ms;

// Easing
$ease-out: cubic-bezier(0.25, 0.46, 0.45, 0.94);
$ease-in: cubic-bezier(0.55, 0.055, 0.675, 0.19);
$ease-in-out: cubic-bezier(0.645, 0.045, 0.355, 1);

// Common transitions
$transition-color: color $transition-base $ease-out;
$transition-background: background-color $transition-base $ease-out;
$transition-border: border-color $transition-base $ease-out;
$transition-shadow: box-shadow $transition-base $ease-out;
```

---

## 🔄 Theme Implementation

### SCSS Architecture
```scss
// 1. Variables and mixins
@import 'variables/colors';
@import 'variables/typography';
@import 'variables/spacing';
@import 'mixins/themes';
@import 'mixins/utilities';

// 2. Base styles
@import 'base/reset';
@import 'base/typography';
@import 'base/layout';

// 3. Components
@import 'components/buttons';
@import 'components/forms';
@import 'components/panels';
@import 'components/navigation';
@import 'components/tables';

// 4. Themes
@import 'themes/dawn';
@import 'themes/day';
@import 'themes/dusk';
@import 'themes/night';
@import 'themes/noon';

// 5. Utilities
@import 'utilities/spacing';
@import 'utilities/text';
@import 'utilities/colors';
```

### Theme Switching JavaScript
```javascript
// Theme management with Alpine.js
Alpine.store('theme', {
  current: 'dawn',
  available: ['dawn', 'day', 'dusk', 'night', 'noon'],
  
  switch(themeName) {
    if (this.available.includes(themeName)) {
      this.current = themeName;
      document.documentElement.setAttribute('data-theme', themeName);
      localStorage.setItem('suite-theme', themeName);
    }
  },
  
  init() {
    const saved = localStorage.getItem('suite-theme');
    if (saved && this.available.includes(saved)) {
      this.switch(saved);
    }
  }
});
```

### CSS-in-JS Integration
```javascript
// For dynamic theming in Alpine.js components
function getThemeColors() {
  const style = getComputedStyle(document.documentElement);
  return {
    primary: style.getPropertyValue('--theme-primary').trim(),
    accent: style.getPropertyValue('--theme-accent').trim(),
    background: style.getPropertyValue('--theme-main-bg').trim(),
    text: style.getPropertyValue('--theme-text-primary').trim()
  };
}
```

---

## 📊 Accessibility & Contrast

### WCAG AA Compliance
```scss
// Minimum contrast ratios
$contrast-aa-normal: 4.5; // 4.5:1 for normal text
$contrast-aa-large: 3.0;  // 3:1 for large text (18px+ or 14px+ bold)
$contrast-aaa-normal: 7.0; // 7:1 for AAA compliance

// High contrast mode overrides
@media (prefers-contrast: high) {
  :root {
    --theme-border-color: #000000;
    --theme-text-primary: #000000;
    --theme-link-color: #0000EE;
  }
}
```

### Color Blind Accessibility
```scss
// Ensure information isn't conveyed by color alone
.status-indicator {
  &.success::before { content: "✓ "; }
  &.warning::before { content: "⚠ "; }
  &.error::before { content: "✕ "; }
  &.info::before { content: "ℹ "; }
}
```

### Dark Mode Considerations
```scss
@media (prefers-color-scheme: dark) {
  :root:not([data-theme]) {
    // Auto-switch to night theme if no preference set
    --theme-primary: #{$night-primary};
    --theme-accent: #{$night-accent};
    --theme-main-bg: #{$night-main-bg};
    // ... other night theme variables
  }
}
```

---

## 🚀 Performance Optimization

### CSS Loading Strategy
```scss
// Critical CSS (inlined)
@import 'critical/layout';
@import 'critical/typography';
@import 'critical/navigation';

// Non-critical CSS (loaded async)
@import 'non-critical/animations';
@import 'non-critical/advanced-components';
```

### Asset Optimization
- **SVG Icons**: Inline critical icons, sprite for others
- **Font Loading**: System fonts with web font fallbacks
- **Color Calculations**: Use CSS `color-mix()` for dynamic variants
- **CSS Custom Properties**: Minimize runtime recalculations

---

## 📋 Implementation Checklist

### Theme Setup
- [ ] Configure theme switching mechanism
- [ ] Implement CSS custom properties
- [ ] Set up SCSS compilation pipeline
- [ ] Create theme preference storage

### Component Integration
- [ ] Update all existing components to use theme variables
- [ ] Ensure proper contrast ratios across all themes
- [ ] Test interactive states (hover, focus, active)
- [ ] Validate accessibility compliance

### Testing Requirements
- [ ] Test all five sub-themes
- [ ] Verify responsive behavior
- [ ] Check contrast ratios with automated tools
- [ ] Test with screen readers
- [ ] Validate color blind accessibility

---

*This theme rules document provides the complete foundation for consistent, accessible, and beautiful theming across the modernized SuiteCRM application.* 