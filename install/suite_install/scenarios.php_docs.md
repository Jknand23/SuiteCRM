# scenarios.php Documentation

## @fileoverview
Installation scenario definitions that provide pre-configured module sets and dashlets for different business use cases during SuiteCRM setup.

## @package SuiteCRM Installation Configuration
## @copyright SalesAgility Ltd.
## @license AGPL-3.0

## Overview

This file defines installation scenarios that allow users to quickly configure SuiteCRM for specific business use cases. Each scenario includes a curated set of modules, dashlets, and grouped tab configurations tailored to common CRM deployment patterns such as Sales, Marketing, Service, and Project Management.

## Core Functionality

### Scenario Configuration Structure
The file defines the `$installation_scenarios` array containing predefined business scenarios:
- **Sales Scenario**: Focuses on opportunity and lead management
- **Marketing Scenario**: Emphasizes campaigns and prospect management
- **Service Scenario**: Concentrates on case management and support
- **Project Management**: Features project and task tracking capabilities

### Scenario Components
Each scenario includes:
```php
'key' => 'ScenarioName',           // Unique identifier
'title' => 'Display Title',        // User-visible scenario name
'description' => 'Description',    // Scenario purpose explanation
'groupedTabs' => 'TabGroup',       // Associated tab grouping
'modules' => array(),              // Included modules list
'modulesScenarioDisplayName' => array(), // Module display names
'dashlets' => array()              // Default dashlets for scenario
```

## Scenario Definitions

### Sales Scenario
- **Focus**: Lead conversion and opportunity management
- **Modules**: Opportunities, Leads
- **Dashlets**: MyOpportunitiesDashlet, MyLeadsDashlet
- **Tab Group**: Sales-focused navigation

### Marketing Scenario
- **Focus**: Campaign management and prospect nurturing
- **Modules**: Prospects, ProspectLists, Campaigns, EmailMarketing
- **Dashlets**: MyCampaignsDashlet, campaign analytics
- **Tab Group**: Marketing-focused navigation

### Service Scenario
- **Focus**: Customer support and case management
- **Modules**: Cases, KnowledgeBase, FAQ, ProjectTask
- **Dashlets**: MyCasesDashlet, service metrics
- **Tab Group**: Support-focused navigation

### Project Management Scenario
- **Focus**: Project execution and task tracking
- **Modules**: Project, ProjectTask, resource management
- **Dashlets**: Project-related dashboards
- **Tab Group**: Project-focused navigation

## Installation Integration

### Setup Process
During installation, scenarios integrate with:
- **Installation Wizard**: Provides scenario selection interface
- **Module Installation**: Automatically enables scenario-specific modules
- **Dashboard Configuration**: Sets up relevant dashlets for chosen scenario
- **Tab Management**: Configures grouped tab navigation

### Module Enablement
- Automatically enables modules based on selected scenario
- Configures module permissions and accessibility
- Sets up default module relationships and dependencies
- Establishes appropriate user interface configurations

## UI Functionality

### Scenario Selection Interface
- Provides user-friendly scenario descriptions
- Displays module and feature benefits for each scenario
- Allows single or multiple scenario selection
- Shows visual representation of included functionality

### Post-Installation Configuration
- Automatically configures dashboards with scenario-appropriate dashlets
- Sets up navigation tabs in logical groupings
- Establishes default user preferences for the selected business model
- Configures module visibility and accessibility

## Localization Support

### Language Integration
- Uses `$app_strings` for internationalized scenario names and descriptions
- Supports multiple languages through language pack integration
- Provides consistent terminology across different locales
- Enables regional business practice customization

### Display Name Management
- Maintains separate display names for modules within scenarios
- Supports context-specific naming for different business models
- Allows customization of terminology based on industry requirements
- Provides flexibility for regional business practice differences

## Business Use Case Optimization

### Sales Process Optimization
- Focuses on lead-to-opportunity conversion workflows
- Emphasizes pipeline management and sales forecasting
- Streamlines sales team collaboration and reporting
- Integrates sales-specific communication tools

### Marketing Campaign Management
- Provides comprehensive campaign lifecycle management
- Supports prospect list building and segmentation
- Enables email marketing automation and tracking
- Facilitates marketing ROI measurement and analytics

### Customer Service Excellence
- Centralizes case management and resolution tracking
- Integrates knowledge base for efficient support
- Provides FAQ management for self-service options
- Enables service level agreement monitoring

### Project Execution Framework
- Supports project planning and resource allocation
- Enables task assignment and progress tracking
- Facilitates team collaboration and communication
- Provides project timeline and milestone management

## Development Notes

### Scenario Customization
- New scenarios can be added by extending the scenarios array
- Custom module combinations can be defined for specific industries
- Dashlet configurations can be tailored to business requirements
- Tab groupings can be customized for organizational preferences

### Extension Framework
- Scenarios support additional modules through configuration extension
- Custom business workflows can be pre-configured through scenario definitions
- Industry-specific scenarios can be developed for specialized deployments
- Third-party modules can be integrated into custom scenarios

### Configuration Management
- Scenario selections are persisted throughout the installation process
- Module enablement is tracked and can be modified post-installation
- Dashboard configurations can be updated through admin interfaces
- Tab groupings remain editable through system administration 