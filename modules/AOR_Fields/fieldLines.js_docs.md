# AOR_Fields Client-Side JavaScript Documentation

**@fileoverview** Advanced client-side JavaScript functionality for dynamic field line management in Advanced OpenReports. Provides interactive form handling, drag-and-drop field ordering, grouping configuration, and comprehensive field formatting options.

**@package** Advanced OpenReports for SugarCRM  
**@copyright** SalesAgility Ltd http://www.salesagility.com  
**@license** GNU AFFERO GENERAL PUBLIC LICENSE

## Overview

The `fieldLines.js` file implements comprehensive client-side functionality for managing report output fields. It provides interactive form elements, drag-and-drop reordering, grouping management, field function selection, and dynamic field discovery based on module relationships.

## UI Functionality

### Global Variables and State Management

#### Core State Variables
- `fieldln` (integer): Current field line counter for unique element IDs
- `fieldln_count` (integer): Total count of active field lines
- `report_rel_modules` (array): Available related modules for cross-module field access
- `report_fields` (array): Available fields for current report module
- `report_module` (string): Current report's base module name

### Field Line Handler (FieldLineHandler)

#### Group Display Management
**makeGroupDisplaySelectOptions() Method**:
- **Purpose**: Manages dropdown options for field grouping display order
- **Dynamic Updates**: Automatically updates available options based on existing fields
- **Label Integration**: Uses field labels for intuitive option display
- **Dual Select Support**: Manages both primary and secondary group display selects

**getFieldNth() Method**:
- **Purpose**: Retrieves field line number for a given field object
- **ID Matching**: Matches field IDs to form element IDs for precise identification
- **Array Navigation**: Efficient lookup through form elements

### Dynamic Form Generation

#### Field Line Creation
**insertFieldLine() Function**:
- **Table Structure**: Creates comprehensive table rows with multiple configuration columns
- **Column Generation**: Systematic creation of display, link, label, function, sort, group, format, and total columns
- **Event Binding**: Attaches event handlers for dynamic behavior
- **Sortable Integration**: Enables drag-and-drop reordering with jQuery UI sortable

#### Field Loading and Population
**loadFieldLine() Function**:
- **Data Population**: Loads existing field configuration data into form elements
- **Type-Specific Handling**: Handles different input types (text, checkbox, select)
- **Order Management**: Sets proper field order values
- **Option Display**: Shows field-specific options based on configuration

### Field Configuration Management

#### Field Selection and Discovery
**showFieldCurrentModuleFields() Function**:
- **AJAX Field Loading**: Dynamically loads available fields for selected modules
- **Relationship Traversal**: Supports fields from related modules via module paths
- **Option Population**: Updates select elements with available field choices
- **Value Preservation**: Maintains selected values during field updates

**showFieldModuleField() Function**:
- **Function Discovery**: Loads available database functions for selected field
- **Label Management**: Automatically generates or preserves field labels
- **Type Awareness**: Provides functions appropriate for field data types
- **Error Handling**: Graceful degradation on field loading failures

#### Field Reset and Cleanup
**fieldResetLine() Function**:
- **Default Values**: Resets field configuration to default state
- **Display Settings**: Sets display checkbox to checked, link to unchecked
- **Label Clearing**: Removes custom labels to enable auto-generation
- **Function Clearing**: Clears field functions and sort options

### Advanced Features

#### Drag-and-Drop Ordering
**jQuery UI Sortable Integration**:
- **Field Reordering**: Enables intuitive drag-and-drop field reordering
- **Sort Callback**: `fieldSort()` function handles order updates after sorting
- **Containment**: Restricts sorting to field table boundaries
- **Axis Constraint**: Vertical-only sorting for clean user experience

#### Field Order Management
**fieldSort() Function**:
- **Order Updates**: Automatically updates field order values after drag operations
- **Chart Integration**: Updates chart dimension selects to reflect new order
- **Group Display Refresh**: Refreshes group display options after reordering
- **Temporary Value Handling**: Manages temporary values during sort operations

### Form Integration

#### Data Structure Management
**Expected Form Fields**:
```javascript
aor_fields_field[n] = "field_name"
aor_fields_display[n] = "1"
aor_fields_link[n] = "0"
aor_fields_label[n] = "Custom Label"
aor_fields_field_function[n] = "SUM"
aor_fields_sort_by[n] = "ASC"
aor_fields_group_by[n] = "1"
aor_fields_format[n] = "currency"
aor_fields_total[n] = "SUM"
aor_fields_field_order[n] = "0"
```

#### State Management
- **Deletion Tracking**: Hidden inputs track deleted fields
- **ID Preservation**: Maintains field IDs for existing records
- **Order Synchronization**: Keeps field order synchronized with display order

## Internal API Integration

### AJAX Communication

#### Field Discovery Endpoints
- **Module Fields**: `AOR_Reports&action=getModuleFields` for field enumeration
- **Field Functions**: `AOR_Reports&action=getModuleFunctionField` for available functions
- **Field Types**: `AOR_Reports&action=getModuleFieldType` for type-specific inputs
- **Variable Definitions**: `AOR_Reports&action=getVarDefs` for detailed field metadata

#### Asynchronous Request Management
- **YAHOO.util.Connect**: Uses SugarCRM's AJAX framework for server communication
- **Callback Handling**: Proper success/failure callback management
- **Script Evaluation**: Dynamic JavaScript execution from AJAX responses
- **Error Handling**: Graceful degradation on AJAX failures

### Field Header Generation

#### Table Header Creation
**insertFieldHeader() Function**:
- **Column Headers**: Creates comprehensive column headers for all field options
- **Localization**: Uses SUGAR.language for localized column titles
- **Table Structure**: Establishes proper table structure with thead/tbody separation
- **Sortable Initialization**: Sets up jQuery UI sortable functionality

### External Node Integration

#### Node-to-Field Conversion
**addNodeToFields() Function**:
- **Node Type Detection**: Handles different node types (field vs. relationship)
- **AJAX Metadata Loading**: Loads field metadata for proper field creation
- **Dynamic Field Creation**: Creates field lines from external data sources
- **Type-Specific Processing**: Handles field types appropriately

## External API Integration

### SugarCRM Framework Integration

#### JavaScript Framework Dependencies
- **YAHOO UI Library**: Core AJAX and UI functionality
- **SUGAR Global Object**: SuiteCRM-specific utilities and language support
- **jQuery Integration**: Modern JavaScript functionality for sorting and UI

#### QuickSearch Integration
- **enableQS() Function**: Enables QuickSearch functionality for lookup fields
- **Dynamic Activation**: QuickSearch enabled as new field inputs are created
- **Search Configuration**: Proper QuickSearch setup for different field types

### Chart Integration

#### Chart Dimension Management
- **updateChartDimensionSelects() Function**: Updates chart configuration after field changes
- **Chart Data Mapping**: Maps field order to chart dimension order
- **Visual Feedback**: Provides chart preview updates based on field configuration

### Utility Functions

#### Element Visibility Control
**hideElem()/showElem() Functions**:
- **Dynamic Visibility**: Controls element visibility based on configuration
- **Value Clearing**: Clears values when hiding elements
- **State Management**: Maintains proper form state during visibility changes

#### Date Field Management
**date_field_change() Function**:
- **Date Option Control**: Shows/hides date-specific options based on selection
- **Conditional Display**: Dynamic form adaptation for date field types
- **User Experience**: Streamlined interface for date configuration

## Performance Optimization

### Efficient DOM Manipulation
- **Minimal Reflows**: Optimized DOM operations to prevent layout thrashing
- **Event Delegation**: Efficient event handling for dynamic content
- **Memory Management**: Proper cleanup of event handlers and DOM references

### AJAX Optimization
- **Request Management**: Efficient AJAX request handling
- **Caching**: Appropriate caching of field metadata and options
- **Error Recovery**: Robust error handling and recovery mechanisms

This comprehensive JavaScript framework provides an intuitive and powerful interface for creating sophisticated report field configurations while maintaining performance and usability across different browsers and devices, enabling users to build professional reports with complex field arrangements, sorting, grouping, and formatting options. 