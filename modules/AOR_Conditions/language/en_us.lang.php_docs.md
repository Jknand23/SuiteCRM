# AOR_Conditions English Language Strings Documentation

**@fileoverview** English language localization strings for the Advanced OpenReports Conditions module. Provides user-facing labels, messages, and interface text for condition management functionality.

**@package** SuiteCRM Advanced OpenReports  
**@copyright** SugarCRM Inc. & SalesAgility Ltd  
**@license** GNU Affero General Public License version 3

## Overview

The `en_us.lang.php` file defines English language strings used throughout the AOR_Conditions module interface. These strings support internationalization efforts and provide consistent labeling across all condition management screens and forms.

## UI Functionality

### Standard Module Labels

#### Core Entity Labels
- **LBL_MODULE_NAME**: "Conditions" - Primary module identifier
- **LBL_MODULE_TITLE**: "Conditions" - Module title for navigation and headers
- **LBL_HOMEPAGE_TITLE**: "My Conditions" - Personal dashboard title
- **LBL_LIST_FORM_TITLE**: "Conditions List" - List view page title
- **LBL_NEW_FORM_TITLE**: "New Conditions" - Create form title
- **LBL_SEARCH_FORM_TITLE**: "Search Conditions" - Search interface title

#### Navigation and Action Labels
- **LNK_NEW_RECORD**: "Create Conditions" - Link text for creating new conditions
- **LNK_LIST**: "View Conditions" - Link text for viewing condition lists
- **LBL_EDIT_BUTTON**: "Edit" - Standard edit action button
- **LBL_REMOVE**: "Remove" - Standard remove action button

### Condition-Specific Labels

#### Logical Structure Labels
- **LBL_LOGIC_OP**: "Logic" - Label for logical operator selection (AND/OR)
- **LBL_CONDITION_AND**: "AND" - Display text for AND logical operator
- **LBL_CONDITION_OR**: "OR" - Display text for OR logical operator
- **LBL_ORDER**: "Order" - Label for condition execution order

#### Field Selection Labels
- **LBL_MODULE_PATH**: "Module" - Label for module path selection
- **LBL_FIELD**: "Field" - Label for field selection dropdown
- **LBL_OPERATOR**: "Operator" - Label for comparison operator selection
- **LBL_CONDITION_OPERATOR**: "Condition Operator" - Alternative operator label

#### Value Definition Labels
- **LBL_VALUE_TYPE**: "Type" - Label for value type selection (static, field, etc.)
- **LBL_VALUE**: "Value" - Label for condition value input
- **LBL_PARAMETER**: "Parameter" - Label for parameter checkbox

### System Integration Labels

#### Database Field Labels
- **LBL_ID**: "ID" - Standard record identifier label
- **LBL_AOR_REPORT_ID**: "Report Id" - Foreign key reference label
- **LBL_NAME**: "Name" - Standard name field label
- **LBL_DESCRIPTION**: "Description" - Standard description field label

#### Audit Trail Labels
- **LBL_DATE_ENTERED**: "Date Created" - Record creation timestamp
- **LBL_DATE_MODIFIED**: "Date Modified" - Last modification timestamp
- **LBL_CREATED**: "Created By" - Record creator reference
- **LBL_MODIFIED**: "Modified By" - Last modifier reference
- **LBL_CREATED_USER**: "Created by User" - Detailed creator information
- **LBL_MODIFIED_USER**: "Modified by User" - Detailed modifier information

#### Assignment Labels
- **LBL_ASSIGNED_TO_ID**: "Assigned User Id" - Assigned user identifier
- **LBL_ASSIGNED_TO_NAME**: "Assigned to" - Assigned user display name

### List and Display Labels

#### List View Integration
- **LBL_LIST_NAME**: "Name" - Column header for name field in lists
- **LBL_DELETED**: "Deleted" - Status indicator for soft-deleted records

#### Subpanel Integration
- **LBL_HISTORY_SUBPANEL_TITLE**: "View History" - History subpanel title
- **LBL_ACTIVITIES_SUBPANEL_TITLE**: "Activities" - Activities subpanel title

## Internal API Integration

### Language System Integration

#### Module String Array
- **$mod_strings Array**: Central repository for all module-specific translations
- **Key-Value Structure**: Consistent key naming for programmatic access
- **SugarEntry Security**: Protected by standard SuiteCRM entry point validation

#### Dynamic Access Patterns
- **JavaScript Integration**: Strings accessible via SUGAR.language.get() in client-side code
- **Template Integration**: Direct access in Smarty templates and PHP views
- **Form Integration**: Automatic label resolution in form generation

### Localization Framework

#### Translation Support
- **Base Language**: Serves as template for other language translations
- **Key Consistency**: Consistent key naming across all language files
- **Cultural Adaptation**: Text suitable for English-speaking locales

#### Extension Points
- **Custom Labels**: Framework supports additional custom labels
- **Override Capability**: Labels can be overridden in custom language files
- **Module Independence**: Self-contained language definitions

## Integration Points

### AOR_Reports Module Integration
- **Consistent Terminology**: Aligns with parent AOR_Reports module language
- **Shared Concepts**: Common terms used consistently across reporting modules
- **Context Awareness**: Labels appropriate for condition-specific contexts

### SuiteCRM Core Integration
- **Standard Patterns**: Follows SuiteCRM language file conventions
- **Core Label Reuse**: Utilizes standard SuiteCRM labels where appropriate
- **System Integration**: Properly integrated with core localization system

### JavaScript Framework Integration
- **Client-Side Access**: Labels available for dynamic JavaScript interfaces
- **Real-Time Updates**: Supports dynamic language switching in browsers
- **AJAX Integration**: Labels accessible in AJAX-loaded content

## Extensibility and Customization

### Developer Integration
- **Programmatic Access**: Labels accessible via standard SuiteCRM language functions
- **Template Usage**: Direct integration with Smarty templating system
- **API Consistency**: Consistent with SuiteCRM language handling patterns

### Administrative Customization
- **Label Override**: Administrators can customize labels via language files
- **Terminology Control**: Consistent terminology across organizational usage
- **Branding Support**: Labels can be customized for organizational branding

This language file provides comprehensive internationalization support for the AOR_Conditions module while maintaining consistency with SuiteCRM conventions and supporting both administrative customization and developer extension. 