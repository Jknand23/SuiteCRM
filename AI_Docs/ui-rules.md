# SuiteCRM UI Design Rules

## Overview

This document establishes comprehensive UI/UX design principles for the SuiteCRM modernization project, focusing on creating a cohesive, accessible, and modern user experience for marketing agencies. These rules build upon the existing SuiteP theme foundation while introducing contemporary design patterns optimized for our modernized technology stack.

## 🎯 Core Design Philosophy

### User-Centered Design
- **Primary Users**: Marketing agency professionals (sales reps, account managers, administrators)
- **Context**: Desktop-first with responsive considerations for tablet/mobile workflows
- **Goals**: Efficiency, clarity, and reduced cognitive load in daily CRM operations

### Modernization Principles
- **Progressive Enhancement**: Build upon existing SuiteP patterns rather than replacing wholesale
- **Backward Compatibility**: Maintain visual consistency with legacy components
- **Performance-First**: Lightweight, fast-loading interfaces using modern web standards
- **AI-Optimized**: Code structure that facilitates semantic search and automated understanding

---

## 📐 Layout & Structure

### Grid System
- **Base**: Bootstrap 5 grid system with 12-column layout
- **Breakpoints**: 
  - `xs`: < 576px (mobile)
  - `sm`: ≥ 576px (mobile landscape)
  - `md`: ≥ 768px (tablet)
  - `lg`: ≥ 992px (desktop)
  - `xl`: ≥ 1200px (large desktop)
  - `xxl`: ≥ 1400px (extra large)

### Container Standards
```scss
.main-content {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 20px;
}

.panel-container {
  margin-bottom: 20px;
}
```

### Spacing System
- **Base Unit**: 4px
- **Common Spacing**: 8px, 12px, 16px, 20px, 24px, 32px, 40px
- **Component Padding**: 16px standard, 20px for larger components
- **Section Margins**: 20px between major sections

---

## 🎨 Typography

### Font Hierarchy
- **Primary Font**: System font stack for performance
  ```css
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  ```
- **Fallback**: Helvetica Neue, Helvetica, Arial, sans-serif

### Text Sizing
- **Base Font Size**: 13px (existing SuiteP standard)
- **Body Text**: 14px for improved readability
- **Small Text**: 12px for metadata and secondary information
- **Large Text**: 16px+ for emphasis and headers

### Heading Scale
```scss
h1 { font-size: 26px; font-weight: 300; letter-spacing: 2px; text-transform: uppercase; }
h2 { font-size: 20px; font-weight: 500; }
h3 { font-size: 16px; font-weight: 600; }
h4 { font-size: 14px; font-weight: 600; }
h5 { font-size: 13px; font-weight: 700; }
h6 { font-size: 12px; font-weight: 700; text-transform: uppercase; }
```

### Text Treatments
- **Links**: Underlined by default, branded color
- **Labels**: Uppercase for form labels, 12px font size
- **Emphasis**: Bold weight (600-700), not italic
- **Code/Data**: Monospace font for data fields and IDs

---

## 🧩 Component Design Patterns

### Buttons

#### Primary Actions
```scss
.btn-primary {
  background: var(--primary-color);
  color: white;
  border: none;
  border-radius: 3px;
  padding: 0 20px;
  height: 36px;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 1px;
}
```

#### Secondary Actions
```scss
.btn-secondary {
  background: var(--main-bg);
  color: var(--primary-color);
  border: 1px solid var(--primary-color);
  // ... rest of button styles
}
```

#### Button Sizing
- **Standard**: 36px height, 20px horizontal padding
- **Large**: 44px height, 24px horizontal padding
- **Small**: 28px height, 16px horizontal padding
- **Icon Buttons**: 32px × 32px for icon-only actions

### Form Elements

#### Input Fields
```scss
.form-control {
  background: var(--input-bg);
  border: 1px solid var(--input-border);
  border-radius: 3px;
  padding: 8px 12px;
  height: 36px;
  font-size: 13px;
}
```

#### Input States
- **Focus**: Border color change, no box-shadow (performance)
- **Error**: Red border, error message below
- **Disabled**: Muted background and text
- **Required**: Red asterisk, clear visual indicator

### Panels & Cards

#### Standard Panel
```scss
.panel {
  background: var(--panel-bg);
  border: 1px solid var(--panel-border);
  border-radius: 4px;
  margin-bottom: 20px;
}

.panel-header {
  background: var(--panel-header-bg);
  padding: 12px 16px;
  border-bottom: 1px solid var(--panel-border);
  font-weight: 600;
  text-transform: uppercase;
}

.panel-body {
  padding: 16px;
}
```

### Data Tables

#### List View Standards
- **Row Height**: 48px minimum for touch targets
- **Header**: Dark background, bold text, uppercase
- **Alternating Rows**: Subtle background difference
- **Hover State**: Clear visual feedback
- **Cell Padding**: 10px horizontal, 13px vertical

#### Interactive Elements
- **Checkboxes**: Custom styled, 16px × 16px
- **Sort Indicators**: Clear up/down arrows
- **Action Buttons**: Icon + text for clarity

---

## 🔄 Interactive Elements

### Navigation

#### Primary Navigation
- **Height**: 44px (existing SuiteP standard)
- **Background**: Dark theme-dependent
- **Active State**: Clear visual distinction
- **Hover**: Subtle background change

#### Sidebar Navigation
```scss
.sidebar {
  width: 240px;
  background: var(--sidebar-bg);
  border-right: 1px solid var(--sidebar-border);
}

.sidebar-item {
  height: 48px;
  padding: 0 16px;
  display: flex;
  align-items: center;
}
```

### Notifications

#### Real-time Notifications
- **Toast Position**: Top-right corner
- **Duration**: 5 seconds auto-dismiss, except errors
- **Animation**: Slide-in from right, fade-out
- **Types**: Success (green), Warning (yellow), Error (red), Info (blue)

#### Notification Bell
```scss
.notification-bell {
  position: relative;
  
  .badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: var(--danger-color);
    color: white;
    border-radius: 50%;
    min-width: 18px;
    height: 18px;
    font-size: 11px;
  }
}
```

### Loading States

#### Alpine.js Loading Patterns
```html
<!-- Skeleton loading for lists -->
<div x-show="isLoading" class="skeleton-row"></div>

<!-- Spinner for actions -->
<button x-data="{ loading: false }" 
        :disabled="loading"
        @click="loading = true">
  <span x-show="!loading">Save</span>
  <span x-show="loading" class="spinner"></span>
</button>
```

#### Progressive Loading
- **Skeleton screens** for initial page loads
- **Inline spinners** for form submissions
- **Progressive enhancement** for filter updates

---

## 📱 Responsive Design

### Mobile-First Approach
- Start with mobile constraints, enhance for desktop
- Touch targets minimum 44px × 44px
- Readable text without zooming (16px minimum on mobile)

### Breakpoint Strategy
```scss
// Mobile-first media queries
.component {
  // Mobile styles by default
  
  @media (min-width: 768px) {
    // Tablet adjustments
  }
  
  @media (min-width: 992px) {
    // Desktop enhancements
  }
}
```

### Responsive Patterns
- **Stacked Forms**: Single column on mobile, multi-column on desktop
- **Collapsible Sidebar**: Hidden by default on mobile
- **Horizontal Scrolling**: For data tables on mobile
- **Touch-Friendly**: Larger interactive elements

---

## ♿ Accessibility Guidelines

### WCAG 2.1 AA Compliance

#### Color & Contrast
- **Minimum Contrast**: 4.5:1 for normal text
- **Large Text**: 3:1 for 18px+ or 14px+ bold
- **Non-text Elements**: 3:1 for UI components and graphics

#### Keyboard Navigation
- **Tab Order**: Logical, sequential navigation
- **Focus Indicators**: Clear visual focus states
- **Skip Links**: "Skip to main content" option
- **Modal Management**: Trap focus within modals

#### Screen Reader Support
```html
<!-- Semantic HTML structure -->
<main role="main">
  <section aria-labelledby="campaign-heading">
    <h2 id="campaign-heading">Campaign Management</h2>
    <!-- content -->
  </section>
</main>

<!-- Descriptive form labels -->
<label for="campaign-name">Campaign Name (required)</label>
<input id="campaign-name" type="text" required aria-describedby="name-help">
<div id="name-help">Enter a unique name for this campaign</div>
```

---

## 🎯 Feature-Specific UI Rules

### Interactive Lead List View

#### Filter Interface
```scss
.filter-bar {
  background: var(--filter-bg);
  padding: 12px 16px;
  border-bottom: 1px solid var(--filter-border);
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.filter-item {
  display: flex;
  align-items: center;
  gap: 8px;
}
```

#### Column Configuration
- **Toggle Visibility**: Checkbox-based column selector
- **Drag & Drop**: Reorder columns (future enhancement)
- **Persistent State**: Remember user preferences

### Campaign Dashboard Widget

#### Metric Display
```scss
.metric-card {
  background: var(--card-bg);
  border-radius: 6px;
  padding: 20px;
  text-align: center;
  
  .metric-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--primary-color);
  }
  
  .metric-label {
    font-size: 12px;
    text-transform: uppercase;
    color: var(--text-muted);
  }
}
```

### Rich Text Editor (TinyMCE)

#### Toolbar Configuration
- **Minimal**: Bold, Italic, Lists, Links
- **Consistent Height**: 200px default
- **Theme Integration**: Match SuiteP color scheme

### Real-time Notifications

#### Visual Hierarchy
1. **Critical**: Modal interruption (client emergencies)
2. **High**: Toast notification (new client messages)
3. **Medium**: Badge update (general notifications)
4. **Low**: Subtle indicator (system updates)

---

## 🔧 Implementation Guidelines

### Alpine.js Integration

#### Reactive Components
```html
<div x-data="leadFilter()">
  <input x-model="search" 
         @input.debounce="filterLeads"
         placeholder="Search leads...">
  
  <template x-for="lead in filteredLeads">
    <div x-text="lead.name"></div>
  </template>
</div>
```

#### State Management
- Use `Alpine.store()` for shared state
- Component-level `x-data` for isolated functionality
- Clear, descriptive variable names

### CSS Architecture

#### Custom Properties
```scss
:root {
  --primary-color: #{$primary};
  --secondary-color: #{$secondary};
  --text-color: #{$text-color};
  --bg-color: #{$main-bg};
}
```

#### Component Structure
```scss
// Component base styles
.lead-list {
  // Base component styles
  
  &__header {
    // Component part styles
  }
  
  &--compact {
    // Component modifier styles
  }
}
```

### Performance Considerations

#### CSS Optimization
- **Critical CSS**: Inline above-the-fold styles
- **Component CSS**: Load with Alpine.js components
- **Avoid**: Large CSS files, unused selectors

#### Image Optimization
- **SVG Icons**: Preferred for scalability
- **WebP Images**: With fallbacks for older browsers
- **Lazy Loading**: For dashboard images and charts

---

## 📋 Quality Assurance

### Testing Requirements

#### Cross-Browser Support
- **Chrome/Edge**: Primary testing target
- **Firefox**: Secondary testing target
- **Safari**: Mobile testing target

#### Device Testing
- **Desktop**: 1920×1080, 1366×768
- **Tablet**: iPad (1024×768), Surface (various)
- **Mobile**: iPhone SE (375×667), iPhone 12 Pro (390×844)

### Code Review Checklist

#### Accessibility
- [ ] Proper semantic HTML structure
- [ ] Keyboard navigation functional
- [ ] Screen reader friendly
- [ ] Color contrast meets requirements

#### Performance
- [ ] No layout shifts during load
- [ ] Smooth animations (60fps)
- [ ] Efficient Alpine.js reactivity
- [ ] Optimized image formats

#### Consistency
- [ ] Matches design system colors
- [ ] Follows spacing guidelines
- [ ] Uses standard component patterns
- [ ] Responsive across breakpoints

---

## 🚀 Future Considerations

### Scalability
- **Component Library**: Build reusable UI components
- **Design Tokens**: Centralized design decisions
- **Automated Testing**: Visual regression testing

### Advanced Features
- **Dark Mode**: Theme switching capability
- **Customization**: User-configurable UI elements
- **Animations**: Subtle micro-interactions
- **Progressive Web App**: Enhanced mobile experience

---

*This UI rules document serves as the foundation for consistent, accessible, and modern user interface development in the SuiteCRM modernization project.* 