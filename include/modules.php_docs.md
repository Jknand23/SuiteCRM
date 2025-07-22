# modules.php Documentation

/**
 * @fileoverview Central module registry and configuration for SuiteCRM application
 * @package SuiteCRM
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `modules.php` file serves as the central registry for all modules in the SuiteCRM application. It defines module lists, bean class mappings, file paths, security configurations, and extension loading mechanisms. This file is critical for the modular architecture of SuiteCRM and determines which modules are available throughout the system.

## Global Module Arrays

### $moduleList
Array defining modules that appear in the top tab navigation of the application.
- **Purpose:** Controls tab order and visibility in main navigation
- **Order:** Default display order (changes require intentional modification)
- **Core Modules:** Home, Calendar, Calls, Meetings, Tasks, Notes, Leads, Contacts, Accounts, Opportunities
- **Extended Modules:** Emails, Campaigns, Documents, Cases, Project, etc.

### $beanList
Maps module names to their corresponding SugarBean class names.
- **Format:** `$beanList['ModuleName'] = 'ClassName'`
- **Purpose:** Enables dynamic bean instantiation throughout the application
- **Examples:**
  - `$beanList['Leads'] = 'Lead'`
  - `$beanList['Cases'] = 'aCase'` (special naming convention)
  - `$beanList['Accounts'] = 'Account'`

### $beanFiles
Maps bean class names to their corresponding file paths.
- **Format:** `$beanFiles['ClassName'] = 'path/to/file.php'`
- **Purpose:** Enables autoloading of bean classes
- **Path Convention:** `modules/ModuleName/ClassName.php`

### $customBeanList, $customBeanFiles, $customObjectList
Arrays for custom module definitions, initialized as empty arrays for extension loading.

## Security and Access Control

### $modInvisList
Defines modules that should not appear as individual tabs (invisible modules).
- **Includes:** Administration tools, internal utilities, relationship modules
- **Examples:** Administration, Currencies, CustomFields, Trackers, etc.
- **Purpose:** Prevents clutter in navigation while maintaining functionality

### $adminOnlyList
Defines modules and actions restricted to administrative users.
- **Format:** `'ModuleName' => ['action' => 1, 'all' => 1]`
- **Admin-Only Modules:**
  - Dropdown, Dynamic, DynamicFields
  - Currencies, EditCustomFields, FieldsMetaData
  - LabelEditor, ACL, ACLActions, ACLRoles
  - UpgradeWizard, Studio, Schedulers

### $moduleTabMap
Maps modules that appear under other modules' tabs.
- **Purpose:** Organizes related modules under parent tabs
- **Examples:**
  - `'UpgradeWizard' => 'Administration'`
  - `'DocumentRevisions' => 'Documents'`
  - `'EmailMarketing' => 'Campaigns'`

## Reporting Configuration

### $report_include_modules
Defines modules available for report generation.
- **Purpose:** Controls which modules can be used in reports
- **Includes:** Core data modules, custom modules, tracking modules

## Special Module Categories

### ACL Objects
Access Control List modules for permission management:
- ACLRoles (ACLRole)
- ACLActions (ACLAction)

### Campaign Management
Complete campaign and marketing suite:
- Campaigns, EmailMarketing, CampaignLog, CampaignTrackers
- ProspectLists, Prospects

### Advanced Modules
SuiteCRM-specific advanced functionality:
- Knowledge Base (AOK_*), Events (FP_*), Reports (AOR_*)
- Sales (AOS_*), Workflow (AOW_*), Surveys

### OAuth and Integration
External service integration modules:
- EAPM, OAuthKeys, OAuthTokens
- ExternalOAuthConnection, ExternalOAuthProvider
- OAuth2Tokens, OAuth2Clients

## Object Name Exceptions

### $objectList
Corrects modules that break the standard naming convention:
- `'Cases' => 'Case'` - Plural module name, singular object name
- `'Groups' => 'User'` - Groups use User object structure
- `'Users' => 'User'` - Standard user object mapping

## Extension Mechanisms

### modules_override.php
Optional file for overriding core module definitions.
- **Path:** `include/modules_override.php`
- **Purpose:** Allows customization without modifying core files

### modules.ext.php
Extension file for adding custom modules.
- **Path:** `custom/application/Ext/Include/modules.ext.php`
- **Purpose:** Custom module registration through extension framework

## Integration Points

### Bean Factory Integration
- Provides bean class mappings for `BeanFactory::getBean()`
- Enables dynamic module instantiation throughout application

### ACL System Integration
- Defines which modules have access restrictions
- Controls administrative vs. user access levels

### Navigation System Integration
- Determines tab structure and module visibility
- Controls module grouping and organization

### Reporting System Integration
- Specifies which modules can be used in reports
- Enables cross-module reporting functionality

## Module Categories by Functionality

### Core CRM Modules
Primary business objects:
- Accounts, Contacts, Leads, Opportunities
- Calls, Meetings, Tasks, Notes, Emails

### Marketing Modules
Campaign and marketing management:
- Campaigns, EmailTemplates, EmailMarketing
- ProspectLists, Prospects, CampaignTrackers

### Support Modules
Customer service and support:
- Cases, Bugs, Documents, Knowledge Base

### Project Management
Project tracking and management:
- Project, ProjectTask, AM_ProjectTemplates, AM_TaskTemplates

### Sales Management
Quote and contract management:
- AOS_Quotes, AOS_Contracts, AOS_Invoices
- AOS_Products, AOS_Product_Categories

### Administrative Modules
System configuration and management:
- Administration, Users, Roles, SecurityGroups
- Currencies, Schedulers, Audit

### Workflow and Automation
Process automation:
- AOW_WorkFlow, AOW_Actions, AOW_Conditions
- Schedulers, SchedulersJobs

### Analytics and Reporting
Data analysis and reporting:
- AOR_Reports, AOR_Charts, AOR_Fields
- Trackers, Charts

## Common Usage Patterns

### Adding New Modules
```php
// Bean definition
$beanList['CustomModule'] = 'CustomModule';
$beanFiles['CustomModule'] = 'modules/CustomModule/CustomModule.php';
$moduleList[] = 'CustomModule';

// Optional: Make invisible
$modInvisList[] = 'CustomModule';

// Optional: Admin only
$adminOnlyList['CustomModule'] = ['all' => 1];
```

### Module Security Configuration
```php
// Hide from navigation but keep functional
$modInvisList[] = 'InternalModule';

// Restrict to administrators
$adminOnlyList['AdminModule'] = ['all' => 1];

// Group under parent tab
$GLOBALS['moduleTabMap']['SubModule'] = 'ParentModule';
```

## Performance Considerations

- All arrays are loaded on every request
- Module definitions are cached by the system
- Extension files are processed after core definitions
- Large numbers of modules can impact navigation performance

## Security Considerations

- Module visibility controlled through multiple arrays
- Administrative restrictions prevent unauthorized access
- Extension mechanism allows safe customization
- Bean file paths validated during autoloading

## Dependencies

This file has no direct dependencies but is required by:
- Navigation generation systems
- Bean factory and autoloading
- ACL and security systems
- Module installation processes
- Reporting and analytics systems 