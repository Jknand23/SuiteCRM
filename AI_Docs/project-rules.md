# SuiteCRM Modernization Project Rules

## Overview

This document establishes comprehensive development rules and conventions for the SuiteCRM modernization project, ensuring an AI-first codebase that is modular, scalable, and easy to understand. These rules optimize the codebase for both human developers and AI tools, facilitating efficient navigation, understanding, and automated assistance.

## 🎯 Core Principles

### AI-First Development
- **Code Readability**: Every file and function must be immediately understandable by AI tools
- **Semantic Structure**: File organization and naming optimized for semantic and grep/regex searches
- **Self-Documenting**: Code structure and naming should convey intent without additional explanation
- **Modular Architecture**: Break complex functionality into small, focused, reusable components

### Modernization Philosophy
- **Progressive Enhancement**: Build upon existing SuiteCRM patterns rather than wholesale replacement
- **Backward Compatibility**: Maintain compatibility with existing SuiteCRM functionality
- **Minimal Viable Implementation**: Focus on functional slices that prove concepts and demonstrate value
- **Performance-First**: Lightweight, efficient implementations using modern web standards

---

## 📁 Directory Structure & Organization

### Primary Directory Categories

#### `/AI_Docs/` - Project Documentation
```
AI_Docs/
├── project-overview.md          # Executive summary and scope
├── project-rules.md            # This file - development rules
├── tech-stack.md               # Technology specifications
├── user-flow.md                # User journey documentation
├── ui-rules.md                 # UI/UX design guidelines
└── theme-rules.md              # Color and theming system
```

#### `/plans/` - Implementation Planning
```
plans/
├── feature-implementation.md   # Detailed feature breakdown
├── technology-migration.md     # Upgrade strategies
└── testing-strategy.md         # QA and testing approaches
```

#### Feature-Specific Directories
```
modules/
├── [ModuleName]/
│   ├── controllers/            # Business logic controllers
│   ├── models/                 # Data models and entities
│   ├── views/                  # Template files and view logic
│   ├── api/                    # API endpoints and routes
│   ├── services/               # Business service classes
│   ├── components/             # Reusable UI components
│   └── assets/                 # Module-specific CSS/JS
```

#### Shared Components
```
include/
├── components/                 # Shared UI components
│   ├── forms/                  # Form-related components
│   ├── lists/                  # List view components
│   ├── dashboards/             # Dashboard widgets
│   └── notifications/          # Notification components
├── services/                   # Shared business services
├── utilities/                  # Helper functions and utilities
└── validation/                 # Input validation classes
```

### Theme and Asset Organization
```
themes/
├── SuiteP-AI/                  # Modernized theme
│   ├── css/
│   │   ├── components/         # Component-specific styles
│   │   ├── themes/             # Theme variants (dawn, day, dusk, night, noon)
│   │   ├── utilities/          # Utility classes
│   │   └── variables/          # SCSS variables and mixins
│   ├── js/
│   │   ├── components/         # Alpine.js components
│   │   ├── services/           # Frontend services
│   │   └── utilities/          # JavaScript utilities
│   └── images/                 # Theme-specific images and icons
```

---

## 📝 File Naming Conventions

### General Naming Rules
- **Descriptive Names**: File names must clearly indicate their purpose and contents
- **Consistent Casing**: Use appropriate casing for the file type and context
- **No Abbreviations**: Avoid abbreviations unless they're widely understood (API, UI, etc.)
- **Hierarchical Naming**: Include context in the name when files serve specific purposes

### File Type Conventions

#### PHP Files
```
// Controllers
CampaignController.php
LeadListController.php
NotificationController.php

// Models
CampaignModel.php
LeadModel.php
UserPreferenceModel.php

// Services
CampaignService.php
NotificationService.php
AuthenticationService.php

// API Endpoints
CampaignApiController.php
LeadCreationApi.php
NotificationStreamApi.php

// Utilities
ValidationHelper.php
DatabaseUtility.php
CacheManager.php
```

#### JavaScript Files
```
// Alpine.js Components
lead-list-filter.js
campaign-dashboard-widget.js
notification-bell.js

// Services
api-client.js
notification-service.js
theme-manager.js

// Utilities
dom-helper.js
validation-utils.js
date-formatter.js
```

#### CSS/SCSS Files
```
// Component Styles
_button-component.scss
_panel-component.scss
_lead-list.scss

// Theme Files
_dawn-theme.scss
_night-theme.scss
_color-variables.scss

// Utilities
_spacing-utilities.scss
_text-utilities.scss
_responsive-mixins.scss
```

#### Template Files
```
// Smarty Templates
campaign-list-view.tpl
lead-detail-form.tpl
notification-toast.tpl

// Partial Templates
campaign-filter-bar.tpl
lead-status-badges.tpl
dashboard-metric-card.tpl
```

### Documentation Files
```
// Code Documentation (always paired with code files)
CampaignController.php          → CampaignController.php_docs.md
lead-list-filter.js            → lead-list-filter.js_docs.md
campaign-list-view.tpl         → campaign-list-view.tpl_docs.md

// Feature Documentation
interactive-lead-list.md        # Feature-specific documentation
oauth-integration.md           # Security feature docs
real-time-notifications.md     # Communication feature docs
```

---

## 🏗️ Code Organization Standards

### File Size Limits
- **Maximum Lines**: 500 lines per file (strictly enforced)
- **Function Size**: Maximum 50 lines per function
- **Class Size**: Maximum 300 lines per class
- **Breaking Down**: Split large files into focused, single-responsibility modules

### File Header Documentation
Every file must begin with a comprehensive header:

#### PHP Files
```php
<?php
/**
 * @fileoverview Campaign Lead Creation API Controller
 * 
 * Handles RESTful API endpoints for creating leads associated with specific campaigns.
 * Provides secure JSON data handling, validation, and integration with existing
 * SuiteCRM service layers. Supports real-time notification triggers and dashboard
 * metric updates upon successful lead creation.
 * 
 * Key Features:
 * - POST /api/campaigns/{id}/leads endpoint
 * - JSON payload validation and sanitization
 * - Campaign association and lead record creation
 * - Real-time notification system integration
 * - Comprehensive error handling and logging
 * 
 * Dependencies:
 * - Existing Slim 3 for routing (enhanced with documentation)
 * - League/OAuth2-Client for authentication
 * - Monolog for logging
 * - SuiteCRM BeanFactory for data persistence
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

namespace SuiteCRM\Api\Controllers;
```

#### JavaScript Files
```javascript
/**
 * @fileoverview Interactive Lead List Filter Component
 * 
 * Alpine.js reactive component for advanced lead list filtering and column
 * configuration. Provides real-time filtering capabilities without page reloads,
 * customizable column visibility, and persistent user preferences.
 * 
 * Key Features:
 * - Real-time search and filter application
 * - Advanced filter combinations (campaign, activity, industry)
 * - Customizable column show/hide functionality
 * - Local storage for user preferences
 * - Debounced input handling for performance
 * 
 * Dependencies:
 * - Alpine.js 3.x for reactivity
 * - Bootstrap 5 for styling
 * - SuiteCRM API endpoints for data fetching
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */
```

#### SCSS Files
```scss
/**
 * @fileoverview Lead List View Component Styles
 * 
 * Comprehensive styling for the interactive lead list view component including
 * table layouts, filter interfaces, responsive design, and theme compatibility.
 * Supports all five SuiteCRM theme variants with proper accessibility compliance.
 * 
 * Key Features:
 * - Responsive table design with horizontal scrolling
 * - Advanced filter bar styling
 * - Customizable column visibility states
 * - Theme-aware color and spacing variables
 * - WCAG AA accessibility compliance
 * 
 * Dependencies:
 * - Bootstrap 5 grid system
 * - SuiteCRM theme variables
 * - Custom spacing and typography utilities
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */
```

### Function Documentation Standards

#### PHP Functions
```php
/**
 * Creates a new lead record associated with a specific campaign
 * 
 * Validates incoming lead data, creates lead record in database, associates
 * with campaign, triggers real-time notifications, and updates dashboard metrics.
 * Handles all error cases with appropriate HTTP status codes and logging.
 * 
 * @param string $campaignId The UUID of the campaign to associate the lead with
 * @param array $leadData Validated lead information including name, email, source
 * @param string $userId The ID of the user creating the lead for audit trails
 * 
 * @return array Response array containing lead ID, success status, and metadata
 * 
 * @throws ValidationException When lead data fails validation rules
 * @throws DatabaseException When lead creation fails due to database issues
 * @throws AuthorizationException When user lacks permission to create leads
 * 
 * @since 1.0.0
 */
public function createCampaignLead(string $campaignId, array $leadData, string $userId): array
{
    // Implementation here
}
```

#### JavaScript Functions
```javascript
/**
 * Applies filter criteria to the lead list and updates display
 * 
 * Processes all active filter criteria (search, campaign, activity, industry)
 * and updates the visible lead list. Maintains filter state in browser storage
 * and provides loading feedback during filter application.
 * 
 * @param {Object} filterCriteria - Filter parameters object
 * @param {string} filterCriteria.search - Text search term for name/email
 * @param {string} filterCriteria.campaign - Campaign ID filter
 * @param {number} filterCriteria.daysSinceActivity - Activity recency filter
 * @param {string} filterCriteria.industry - Industry category filter
 * 
 * @returns {Promise<Array>} Promise resolving to filtered lead array
 * 
 * @throws {Error} When API request fails or filter criteria are invalid
 * 
 * @since 1.0.0
 */
async function applyLeadFilters(filterCriteria) {
    // Implementation here
}
```

### Variable Naming Conventions

#### PHP Variables
```php
// Use descriptive names with auxiliary verbs for boolean states
$isLeadValid = true;
$hasPermission = false;
$canEditCampaign = true;
$shouldSendNotification = false;

// Use clear, specific names for data variables
$campaignLeadData = [];
$userPreferences = [];
$validationErrors = [];
$apiResponsePayload = [];

// Use consistent prefixes for related variables
$filterCampaignId = null;
$filterDateRange = [];
$filterIndustryType = '';
$filterActivityStatus = '';
```

#### JavaScript Variables
```javascript
// Use camelCase for variables and functions
const leadListData = [];
const isLoadingFilters = false;
const hasActiveFilters = true;
const shouldShowTooltip = false;

// Use descriptive names for DOM elements
const leadTableElement = document.querySelector('#lead-table');
const filterFormElement = document.querySelector('#filter-form');
const notificationBellElement = document.querySelector('#notification-bell');

// Use consistent naming for event handlers
const handleFilterChange = (event) => { /* */ };
const handleLeadSelection = (leadId) => { /* */ };
const handleNotificationClick = (notification) => { /* */ };
```

---

## 🔧 Technology-Specific Rules

### Alpine.js Components
```javascript
// Component Structure
function leadListFilter() {
    return {
        // Data properties
        leads: [],
        filteredLeads: [],
        isLoading: false,
        hasError: false,
        
        // Filter state
        searchTerm: '',
        selectedCampaign: '',
        daysSinceActivity: null,
        selectedIndustry: '',
        
        // UI state
        visibleColumns: {
            name: true,
            email: true,
            campaign: true,
            lastActivity: true,
            industry: false
        },
        
        // Lifecycle methods
        init() {
            this.loadLeads();
            this.loadUserPreferences();
        },
        
        // Actions
        async filterLeads() { /* */ },
        toggleColumn(columnName) { /* */ },
        savePreferences() { /* */ }
    };
}
```

### PHP Class Structure
```php
<?php
/**
 * @fileoverview Campaign Service Class
 * // ... header documentation
 */

namespace SuiteCRM\Services;

use SuiteCRM\Models\Campaign;
use SuiteCRM\Models\Lead;
use SuiteCRM\Exceptions\ValidationException;

class CampaignService
{
    /** @var Campaign $campaignModel Campaign data model instance */
    private Campaign $campaignModel;
    
    /** @var Lead $leadModel Lead data model instance */
    private Lead $leadModel;
    
    /**
     * Constructor with dependency injection
     * 
     * @param Campaign $campaignModel Injected campaign model
     * @param Lead $leadModel Injected lead model
     */
    public function __construct(Campaign $campaignModel, Lead $leadModel)
    {
        $this->campaignModel = $campaignModel;
        $this->leadModel = $leadModel;
    }
    
    // Public methods first
    public function createCampaignLead(string $campaignId, array $leadData): array { /* */ }
    
    public function getCampaignMetrics(string $campaignId): array { /* */ }
    
    // Protected methods
    protected function validateLeadData(array $leadData): bool { /* */ }
    
    // Private methods
    private function sendNotification(string $type, array $data): void { /* */ }
}
```

### SCSS/CSS Organization
```scss
/**
 * @fileoverview Button Component Styles
 * // ... header documentation
 */

// Component namespace
.btn-suite {
    // Base styles
    display: inline-flex;
    align-items: center;
    justify-content: center;
    
    // Size variants
    &--small {
        height: var(--button-height-sm);
        padding: 0 var(--spacing-3);
    }
    
    &--large {
        height: var(--button-height-lg);
        padding: 0 var(--spacing-6);
    }
    
    // State variants
    &--primary {
        background-color: var(--theme-primary);
        color: var(--button-text-color);
    }
    
    &--disabled {
        opacity: 0.6;
        pointer-events: none;
    }
    
    // Interactive states
    &:hover:not(.btn-suite--disabled) {
        background-color: var(--theme-primary-hover);
    }
    
    &:focus {
        outline: 2px solid var(--theme-focus-color);
        outline-offset: 2px;
    }
}
```

---

## 📋 Quality Assurance Rules

### Code Review Checklist
- [ ] File size under 500 lines
- [ ] Comprehensive @fileoverview documentation
- [ ] All functions have complete PHPDoc/JSDoc
- [ ] Variable names are descriptive with auxiliary verbs
- [ ] No abbreviations in file or variable names
- [ ] Proper error handling and logging
- [ ] Security considerations addressed
- [ ] Performance impact minimized
- [ ] Accessibility compliance (WCAG AA)
- [ ] Cross-browser compatibility tested

### Testing Requirements
- [ ] Unit tests for all public methods
- [ ] Integration tests for API endpoints
- [ ] UI component tests for Alpine.js components
- [ ] Accessibility testing with screen readers
- [ ] Performance testing for large datasets
- [ ] Security testing for input validation

### Documentation Standards
- [ ] Feature documentation in `/plans/` directory
- [ ] Code documentation paired with implementation files
- [ ] API documentation for all endpoints
- [ ] User flow documentation updated
- [ ] Installation and setup instructions

---

## 🚀 Performance Guidelines

### File Loading Optimization
- **Critical CSS**: Inline above-the-fold styles
- **JavaScript Modules**: Load components on-demand
- **Image Optimization**: Use appropriate formats (WebP, SVG)
- **Asset Bundling**: Minimize HTTP requests

### Database Query Optimization
- **Prepared Statements**: Always use for user input
- **Query Limits**: Implement pagination for large datasets
- **Index Usage**: Ensure proper database indexing
- **N+1 Prevention**: Use eager loading for relationships

### Frontend Performance
- **Alpine.js**: Keep reactive data minimal
- **DOM Manipulation**: Batch updates to prevent reflows
- **Event Handling**: Use debouncing for frequent events
- **Memory Management**: Clean up event listeners and watchers

---

## 🔒 Security Requirements

### Input Validation
- **Server-Side**: Always validate on backend
- **Client-Side**: Provide immediate feedback only
- **Sanitization**: Clean all user input before processing
- **Type Checking**: Enforce strict data types

### Authentication & Authorization
- **OAuth2**: Implement for external authentication
- **Session Management**: Secure session handling
- **Permission Checks**: Verify user permissions for all actions
- **Audit Logging**: Log all security-relevant events

### Data Protection
- **SQL Injection**: Use prepared statements exclusively
- **XSS Prevention**: Escape all output appropriately
- **CSRF Protection**: Implement token validation
- **HTTPS**: Require secure connections in production

---

## 📈 Monitoring & Maintenance

### Logging Standards
```php
// Use structured logging with context
$this->logger->info('Lead created successfully', [
    'lead_id' => $leadId,
    'campaign_id' => $campaignId,
    'user_id' => $userId,
    'source' => 'api_endpoint',
    'execution_time_ms' => $executionTime
]);
```

### Error Handling
```php
// Provide meaningful error messages
try {
    $result = $this->createLead($data);
} catch (ValidationException $e) {
    $this->logger->warning('Lead validation failed', [
        'errors' => $e->getErrors(),
        'input_data' => $data
    ]);
    throw new ApiException('Invalid lead data provided', 400, $e);
}
```

### Maintenance Tasks
- **Code Reviews**: Required for all changes
- **Documentation Updates**: Keep docs current with code
- **Performance Monitoring**: Track key metrics
- **Security Audits**: Regular security assessments
- **Dependency Updates**: Keep libraries current

---

## 🎯 Success Metrics

### Code Quality Metrics
- **File Size**: 100% of files under 500 lines
- **Documentation Coverage**: 100% of files have comprehensive headers
- **Function Documentation**: 100% of public methods documented
- **Naming Compliance**: 100% adherence to naming conventions

### Performance Metrics
- **Page Load Time**: < 2 seconds for list views
- **API Response Time**: < 500ms for CRUD operations
- **JavaScript Bundle Size**: < 100KB per component
- **CSS Bundle Size**: < 50KB total

### Accessibility Metrics
- **WCAG AA Compliance**: 100% of UI components
- **Keyboard Navigation**: All interactive elements accessible
- **Screen Reader Compatibility**: Full functionality preserved
- **Color Contrast**: All text meets minimum requirements

---

*This project rules document serves as the definitive guide for maintaining consistency, quality, and AI-compatibility throughout the SuiteCRM modernization project.* 