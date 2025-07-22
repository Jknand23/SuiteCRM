# AOR_Field Model Class Documentation

**@fileoverview** Advanced OpenReports Field model class extending Basic bean. Provides field entity management and form data processing for report output field configuration including display options, formatting, and aggregation settings.

**@package** SuiteCRM Advanced OpenReports  
**@copyright** SugarCRM Inc. & SalesAgility Ltd  
**@license** GNU Affero General Public License version 3

## Overview

The `AOR_Field` class serves as the primary model for managing report output fields within the Advanced OpenReports framework. It extends the Basic SugarBean class and provides specialized functionality for processing complex field configuration forms including grouping, sorting, formatting, and aggregation options.

## Database Operations

### Entity Configuration
- **Module Directory**: `AOR_Fields`
- **Object Name**: `AOR_Field`
- **Table Name**: `aor_fields` (as defined in vardefs.php)
- **Schema Version**: New schema enabled for modern SuiteCRM compatibility
- **Security**: Row-level security disabled for performance, importable for data migration

### Core Properties
The class declares public properties matching the database schema:
- `$aor_report_id`: Foreign key linking to parent AOR_Reports record
- `$field_order`: Integer defining field display sequence in report output
- `$field`: Target field name for report column
- `$display`: Boolean controlling field visibility in report output
- `$label`: Custom display label for report column header
- `$field_function`: Database function applied to field (COUNT, SUM, AVG, etc.)
- `$sort_by`: Sorting criteria inclusion flag
- `$sort_order`: Ascending or descending sort direction
- `$format`: Field-specific formatting options
- `$group_by`: Grouping criteria inclusion flag
- `$group_order`: Sort direction for grouped results
- `$group_display`: Display position within grouped results

### Entity Lifecycle
- **Tracker Visibility**: Disabled to prevent field changes from appearing in activity streams
- **Importable**: Enabled for bulk field loading and data migration
- **Row-Level Security**: Disabled for improved query performance

## Internal API Operations

### Form Data Processing

#### save_lines() Method
**Purpose**: Processes POST data from field configuration forms and saves multiple field definitions

**Parameters**:
- `$post_data` (array): Complete form submission data
- `$parent` (object): Parent AOR_Reports bean instance
- `$key` (string): Optional prefix for form field names (typically 'aor_fields_')

**Processing Logic**:
1. **Data Validation**: Validates presence of required field arrays in POST data
2. **Line Count Calculation**: Determines number of field lines to process
3. **Deletion Handling**: Processes deleted fields by calling `mark_deleted()`
4. **Field Creation**: Instantiates new AOR_Field beans for each valid field
5. **Group Display Processing**: Handles special group display ordering logic
6. **Field Mapping**: Maps form fields to bean properties with type-specific processing

#### Group Display Management
**Special Processing for Group Display Fields**:
- **Group Display Array**: Processes `aor_fields_group_display` for field ordering within groups
- **Position Assignment**: Maps array positions to group display order values
- **Default Handling**: Sets `group_display = false` when not specified

#### Data Type Processing
- **Array Values**: Base64-encoded serialized arrays for complex field values
- **Module Paths**: Base64-encoded serialized path arrays for relationship traversal
- **Value Formatting**: Field-specific formatting via `fixUpFormatting()` utility
- **Type Validation**: Ensures proper data types for form field processing

### Error Handling and Validation

#### Data Integrity Checks
- **Field Validation**: Ensures non-empty field values before saving
- **Array Validation**: Validates expected array structures in POST data
- **Type Safety**: Exception handling for unexpected data types
- **Index Validation**: Bounds checking for array access operations

#### Logging Integration
- **Warning Logs**: Issues warnings for missing POST data arrays
- **Error Context**: Provides detailed context for debugging form processing issues
- **Index Reporting**: Includes array indices in error messages for precise debugging
- **Logger Manager**: Utilizes SuiteCRM's centralized logging system

## External API Integration

### AOW_WorkFlow Utilities
- **Module Integration**: Requires `modules/AOW_WorkFlow/aow_utils.php` for field processing
- **Field Formatting**: Leverages `fixUpFormatting()` for value standardization
- **Consistency**: Maintains consistency with workflow field processing patterns

### BeanFactory Integration
- **Bean Creation**: Uses `BeanFactory::newBean('AOR_Fields')` for new instances
- **Proper Instantiation**: Ensures correct bean setup with all required properties
- **Memory Management**: Efficient bean creation for form processing

## UI Functionality

### Form Integration Support

#### POST Data Structure
Expected form structure with indexed arrays:
```
aor_fields_field[0] = "account_name"
aor_fields_display[0] = "1"
aor_fields_label[0] = "Account Name"
aor_fields_field_function[0] = ""
aor_fields_sort_by[0] = "ASC"
aor_fields_group_by[0] = "0"
aor_fields_deleted[0] = "0"
aor_fields_group_display = [0, 1] // Special array for group ordering
```

#### Dynamic Field Processing
- **Field Type Detection**: Determines processing method based on field metadata
- **Function Support**: Handles database functions and aggregations
- **Formatting Options**: Supports field-specific formatting and display options

### Advanced Configuration Support

#### Grouping and Aggregation
- **Group Display Logic**: Complex logic for managing field display order within groups
- **Multi-Field Grouping**: Supports hierarchical grouping with multiple fields
- **Aggregation Functions**: Handles SUM, COUNT, AVG, and other database functions

#### Sorting and Ordering
- **Multi-Field Sorting**: Processes multiple sort criteria with proper precedence
- **Order Preservation**: Maintains field order for consistent report output
- **Sort Direction**: Handles ascending and descending sort configurations

## Integration Points

### AOR_Reports Integration
- **Parent Relationship**: Links fields to specific report configurations
- **Report Context**: Inherits module context from parent report for field validation
- **Lifecycle Management**: Fields automatically associate with parent report

### Module Field Discovery
- **Dynamic Fields**: Supports any SuiteCRM module field for report columns
- **Relationship Fields**: Enables fields from related modules through relationship chains
- **Custom Fields**: Full support for custom field inclusion in reports

### Report Generation Integration
- **Output Mapping**: Direct mapping to report column structure
- **Format Application**: Field formatting applied during report generation
- **Aggregation Processing**: Database functions executed during query building

### Data Validation Integration
- **Field Existence**: Validates field existence in target modules
- **Type Compatibility**: Ensures function compatibility with field types
- **Relationship Validation**: Validates relationship paths for cross-module fields

This model class provides comprehensive field configuration management while maintaining data integrity and supporting sophisticated report output customization including grouping, sorting, formatting, and aggregation capabilities required for professional reporting scenarios. 