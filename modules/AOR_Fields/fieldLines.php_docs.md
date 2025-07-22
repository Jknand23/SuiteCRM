# AOR_Fields Server-Side UI Helper Documentation

**@fileoverview** Server-side PHP function for generating field line interfaces in Advanced OpenReports. Provides dynamic HTML generation and JavaScript integration for report field configuration forms including display, sorting, grouping, and formatting options.

**@package** Advanced OpenReports for SugarCRM  
**@copyright** SalesAgility Ltd http://www.salesagility.com  
**@license** GNU AFFERO GENERAL PUBLIC LICENSE

## Overview

The `fieldLines.php` file contains the `display_field_lines()` function which generates the complete user interface for managing report output fields. It produces HTML forms, JavaScript includes, and dynamic data population for EditView contexts within the Advanced OpenReports system, focusing on field selection, formatting, and aggregation configuration.

## UI Functionality

### Primary Function: display_field_lines()

**Purpose**: Generates complete HTML interface for field configuration within report forms

**Parameters**:
- `$focus` (object): AOR_Reports bean instance providing report context
- `$field` (string): Field name for form integration (typically not used)
- `$value` (mixed): Current field value (typically not used)
- `$view` (string): View context ('EditView' supported, 'DetailView' not implemented)

**Returns**: String containing complete HTML markup for field configuration interface

### JavaScript Integration

#### Language File Loading
- **Dynamic Loading**: Automatically generates JavaScript language cache for current user locale
- **File Path**: `cache/jsLanguage/AOR_Fields/{language}.js`
- **Module Integration**: Uses `jsLanguage::createModuleStringsCache()` for label generation
- **Fallback Creation**: Creates language cache if not already present

#### Core JavaScript Inclusion
- **EditView Only**: Includes `modules/AOR_Fields/fieldLines.js` for interactive functionality
- **Script Dependencies**: Assumes global YAHOO and SUGAR JavaScript frameworks
- **Sort Options**: Populates JavaScript with sort operator options from language arrays

### HTML Structure Generation

#### EditView Interface
**Interactive Elements**:
- **Field Table**: Empty table with ID `fieldLines` for dynamic population
- **Add Button**: "Add Field" button with ID `btn_FieldLine`
- **Button State**: Initially disabled until report module is selected
- **Event Handler**: `insertFieldLine()` JavaScript function on button click

**Form Integration**:
- **Report Module Context**: Utilizes `$focus->report_module` for field discovery
- **Database Queries**: Loads existing fields ordered by `field_order`
- **JavaScript Variables**: Sets global `report_module`, `report_fields`, and `report_rel_modules`

### Database Operations

#### Field Retrieval
**Query Structure**:
```sql
SELECT id FROM aor_fields 
WHERE aor_report_id = '{report_id}' 
AND deleted = 0 
ORDER BY field_order ASC
```

**Data Processing**:
- **Bean Instantiation**: Creates AOR_Field beans for each retrieved record
- **Module Path Deserialization**: Unserializes base64-encoded module paths
- **JSON Encoding**: Converts field data to JavaScript-consumable format
- **Order Preservation**: Maintains field order for consistent display

### Internal API Integration

#### AOW_WorkFlow Utilities
- **Field Discovery**: Uses `getModuleFields()` for available field enumeration
- **Related Modules**: Leverages `getModuleRelationships()` for relationship discovery
- **Related Field Access**: Uses `getRelatedModule()` for cross-module field access

#### Language System Integration
- **Sort Options**: Populates sort operator options from `$app_list_strings['aor_sort_operator']`
- **Option Generation**: Uses `get_select_options_with_id()` for dropdown creation
- **Text Processing**: Regex cleanup for JavaScript-safe option strings

### JavaScript Variable Population

#### Global Variables Set
- **report_module**: Current report's base module for field context
- **report_fields**: Available fields formatted for JavaScript consumption
- **report_rel_modules**: Available related modules for cross-module field access
- **sort_by_values**: Formatted sort operator options for dropdown population

#### Dynamic Field Loading
- **Module-Specific Fields**: Fields available based on selected report module
- **Relationship Fields**: Fields from related modules via relationship chains
- **Field Metadata**: Comprehensive field information for JavaScript processing

### View-Specific Behavior

#### EditView Features
- **Interactive Form**: Full field creation and editing capabilities
- **Add/Remove Actions**: JavaScript-driven field line management
- **Real-time Updates**: Dynamic field and option selection based on user choices
- **Validation**: Client-side and server-side validation integration

#### Limited DetailView Support
- **Not Implemented**: DetailView functionality not provided in this version
- **EditView Focus**: Emphasis on interactive field configuration interface
- **Future Enhancement**: DetailView could be added for read-only field display

## External API Integration

#### BeanFactory Usage
- **Field Loading**: Uses `BeanFactory::newBean('AOR_Fields')` for data retrieval
- **Proper Instantiation**: Ensures correct bean setup with all relationships
- **Memory Efficiency**: Clean bean creation for each field record

#### Language System Integration
- **Localization Support**: Full multilingual support through SuiteCRM language system
- **Dynamic Cache**: JavaScript language files generated on-demand
- **Label Access**: Client-side access to localized labels and messages

## Integration Points

### AOR_Reports Module
- **Parent Context**: Inherits report configuration and module context
- **Field Scope**: Available fields determined by report's base module
- **Data Relationship**: Fields linked to specific report instances

### JavaScript Framework
- **Client-Side Processing**: Heavy reliance on JavaScript for user interaction
- **AJAX Integration**: Dynamic field loading and validation through AJAX calls
- **Event Handling**: JavaScript event handlers for user actions

### Module Relationship System
- **Cross-Module Access**: Supports fields from related modules
- **Relationship Discovery**: Automatic discovery of available relationships
- **Path Management**: Complex relationship path handling for deep field access

### SuiteCRM Core Integration
- **Language System**: Utilizes core language and localization services
- **Module System**: Integrates with SuiteCRM's module discovery and metadata
- **Security**: Respects user permissions and access controls

## Advanced Features

### Field Configuration Support
- **Display Options**: Controls field visibility in report output
- **Link Generation**: Configures clickable links for field values
- **Label Customization**: Custom column headers for report fields

### Sorting and Grouping
- **Sort Configuration**: Multiple sorting criteria with direction control
- **Group Management**: Hierarchical grouping with display order control
- **Aggregation Support**: Database functions for calculations and totals

### Formatting Integration
- **Format Options**: Field-specific formatting choices
- **Type-Aware Formatting**: Format options based on field data types
- **Display Customization**: Comprehensive presentation control

This server-side helper function provides the foundation for sophisticated field configuration interfaces while maintaining separation between data logic and presentation layers, enabling users to create complex report layouts with professional formatting and organization capabilities. 