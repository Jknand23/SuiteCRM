# AOR_Conditions Server-Side UI Helper Documentation

**@fileoverview** Server-side PHP function for generating condition line interfaces in Advanced OpenReports. Provides dynamic HTML generation and JavaScript integration for report condition management forms.

**@package** Advanced OpenReports for SugarCRM  
**@copyright** SalesAgility Ltd http://www.salesagility.com  
**@license** GNU AFFERO GENERAL PUBLIC LICENSE

## Overview

The `conditionLines.php` file contains the `display_condition_lines()` function which generates the complete user interface for managing report conditions. It produces HTML forms, JavaScript includes, and dynamic data population for both EditView and DetailView contexts within the Advanced OpenReports system.

## UI Functionality

### Primary Function: display_condition_lines()

**Purpose**: Generates complete HTML interface for condition management within report forms

**Parameters**:
- `$focus` (object): AOR_Reports bean instance providing report context
- `$field` (string): Field name for form integration (typically not used)
- `$value` (mixed): Current field value (typically not used)
- `$view` (string): View context ('EditView' or 'DetailView')

**Returns**: String containing complete HTML markup for condition interface

### JavaScript Integration

#### Language File Loading
- **Dynamic Loading**: Automatically generates JavaScript language cache for current user locale
- **File Path**: `cache/jsLanguage/AOR_Conditions/{language}.js`
- **Module Integration**: Uses `jsLanguage::createModuleStringsCache()` for label generation
- **Fallback Creation**: Creates language cache if not already present

#### Core JavaScript Inclusion
- **EditView**: Includes `modules/AOR_Conditions/conditionLines.js` for interactive functionality
- **DetailView**: Includes same JavaScript file for consistent display behavior
- **Script Dependencies**: Assumes global YAHOO and SUGAR JavaScript frameworks

### HTML Structure Generation

#### EditView Interface
**Interactive Elements**:
- **Condition Table**: Empty table with ID `aor_conditionLines` for dynamic population
- **Add Button**: "Add Condition" button with ID `btn_ConditionLine`
- **Button State**: Initially disabled until report module is selected
- **Event Handler**: `insertConditionLine()` JavaScript function on button click

**Form Integration**:
- **Report Module Context**: Utilizes `$focus->report_module` for field discovery
- **Database Queries**: Loads existing conditions ordered by `condition_order`
- **JavaScript Variables**: Sets global `report_module` and `report_fields` variables

#### DetailView Interface
**Display Elements**:
- **Condition Table**: Read-only table for condition display
- **No Add Button**: Conditions cannot be modified in DetailView
- **Data Population**: Loads and displays existing conditions with full details

### Database Operations

#### Condition Retrieval
**Query Structure**:
```sql
SELECT id FROM aor_conditions 
WHERE aor_report_id = '{report_id}' 
AND deleted = 0 
ORDER BY condition_order ASC
```

**Data Processing**:
- **Bean Instantiation**: Creates AOR_Condition beans for each retrieved record
- **Module Path Deserialization**: Unserializes base64-encoded module paths
- **Date Value Processing**: Deserializes complex date condition values
- **JSON Encoding**: Converts condition data to JavaScript-consumable format

### Internal API Integration

#### AOW_WorkFlow Utilities
- **Field Discovery**: Uses `getModuleFields()` for available field enumeration
- **Related Modules**: Leverages `getRelatedModule()` for relationship traversal
- **Data Formatting**: Maintains consistency with workflow condition processing

#### Module Field Integration
- **Dynamic Fields**: Generates field lists based on selected report module
- **Relationship Support**: Handles cross-module field references through module paths
- **Field Metadata**: Provides comprehensive field information for JavaScript processing

### View-Specific Behavior

#### EditView Features
- **Interactive Form**: Full condition creation and editing capabilities
- **Add/Remove Actions**: JavaScript-driven condition line management
- **Real-time Updates**: Dynamic field and operator selection based on user choices
- **Validation**: Client-side and server-side validation integration

#### DetailView Features
- **Read-only Display**: Conditions shown for review without edit capabilities
- **Complete Information**: All condition details displayed including values and operators
- **Formatting**: Proper display formatting for various condition types

### JavaScript Variable Population

#### Global Variables Set
- **report_module**: Current report's base module for field context
- **report_fields**: Available fields formatted for JavaScript consumption
- **Condition Data**: Individual condition objects for form population

#### Dynamic Field Loading
- **Module-Specific Fields**: Fields available based on selected report module
- **Relationship Fields**: Fields from related modules via relationship chains
- **Field Formatting**: HTML-encoded field lists for dropdown population

## External API Integration

#### BeanFactory Usage
- **Condition Loading**: Uses `BeanFactory::newBean('AOR_Conditions')` for data retrieval
- **Proper Instantiation**: Ensures correct bean setup with all relationships
- **Memory Efficiency**: Clean bean creation for each condition record

#### Language System Integration
- **Localization Support**: Full multilingual support through SuiteCRM language system
- **Dynamic Cache**: JavaScript language files generated on-demand
- **Label Access**: Client-side access to localized labels and messages

## Integration Points

### AOR_Reports Module
- **Parent Context**: Inherits report configuration and module context
- **Field Scope**: Available fields determined by report's base module
- **Data Relationship**: Conditions linked to specific report instances

### JavaScript Framework
- **Client-Side Processing**: Heavy reliance on JavaScript for user interaction
- **AJAX Integration**: Dynamic field loading and validation through AJAX calls
- **Event Handling**: JavaScript event handlers for user actions

### SuiteCRM Core Integration
- **Language System**: Utilizes core language and localization services
- **Module System**: Integrates with SuiteCRM's module discovery and metadata
- **Security**: Respects user permissions and access controls

This server-side helper function provides the foundation for sophisticated condition management interfaces while maintaining separation between data logic and presentation layers. 