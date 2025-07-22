# AOR_Condition Model Class Documentation

**@fileoverview** Advanced OpenReports Condition model class extending Basic bean. Provides condition entity management and form data processing for complex report filtering logic in SuiteCRM.

**@package** SuiteCRM Advanced OpenReports  
**@copyright** SugarCRM Inc. & SalesAgility Ltd  
**@license** GNU Affero General Public License version 3

## Overview

The `AOR_Condition` class serves as the primary model for managing report conditions within the Advanced OpenReports framework. It extends the Basic SugarBean class and provides specialized functionality for processing complex conditional logic forms and maintaining data integrity during condition saves.

## Database Operations

### Entity Configuration
- **Module Directory**: `AOR_Conditions`
- **Object Name**: `AOR_Condition`
- **Table Name**: `aor_conditions` (as defined in vardefs.php)
- **Schema Version**: New schema enabled for modern SuiteCRM compatibility
- **Security**: Row-level security disabled for performance, importable for data migration

### Core Properties
The class declares public properties matching the database schema:
- `$aor_report_id`: Foreign key linking to parent AOR_Reports record
- `$condition_order`: Integer defining condition execution sequence
- `$field`: Target field name for condition evaluation
- `$logic_op`: Logical operator ('AND'/'OR') for condition chaining
- `$parenthesis`: Grouping parentheses for complex expressions
- `$operator`: Comparison operator (equals, contains, greater than, etc.)
- `$value`: Comparison value or field reference
- `$value_type`: Type classification for value interpretation

### Entity Lifecycle
- **Tracker Visibility**: Disabled to prevent condition changes from appearing in activity streams
- **Importable**: Enabled for bulk condition loading and data migration
- **Row-Level Security**: Disabled for improved query performance

## Internal API Operations

### Form Data Processing

#### save_lines() Method
**Purpose**: Processes POST data from condition forms and saves multiple condition lines

**Parameters**:
- `$post_data` (array): Complete form submission data
- `$parent` (object): Parent AOR_Reports bean instance  
- `$key` (string): Optional prefix for form field names

**Processing Logic**:
1. **Data Validation**: Validates presence of required field arrays in POST data
2. **Deletion Handling**: Processes deleted conditions by calling `mark_deleted()`
3. **Condition Creation**: Instantiates new AOR_Condition beans for each valid condition
4. **Field Mapping**: Maps form fields to bean properties with type-specific processing
5. **Parenthesis Management**: Handles complex logical grouping with paired parentheses
6. **Order Assignment**: Manages condition execution order for proper logic evaluation

#### Data Type Processing
- **Array Values**: Encoded using `encodeMultienumValue()` for multi-select fields
- **Date Values**: Base64-encoded serialized arrays for complex date conditions
- **Value Formatting**: Field-specific formatting via `fixUpFormatting()` utility
- **Module Paths**: Base64-encoded serialized path arrays for relationship traversal
- **Period Values**: Special handling for period-based conditions with string encoding

#### Parenthesis Logic Handling
- **START Parentheses**: Tracked with condition IDs for proper pairing
- **END Parentheses**: Matched to opening parentheses using stored condition references
- **Exception Handling**: Throws exceptions for unpaired closing parentheses
- **Nesting Support**: Maintains stack of opening parentheses for complex expressions

### Error Handling and Validation

#### Data Integrity Checks
- **Field Validation**: Ensures non-empty field values before saving
- **Parenthesis Validation**: Validates proper parenthesis pairing and nesting
- **Order Management**: Assigns sequential order numbers for consistent execution
- **Deletion Safety**: Uses mark_deleted() instead of hard deletion for audit trails

#### Logging Integration
- **Warning Logs**: Issues warnings for missing POST data fields
- **Error Context**: Provides detailed context for debugging form processing issues
- **Logger Manager**: Utilizes SuiteCRM's centralized logging system

## External API Integration

### AOW_WorkFlow Utilities
- **Module Integration**: Requires `modules/AOW_WorkFlow/aow_utils.php` for field processing
- **Field Formatting**: Leverages `fixUpFormatting()` for value standardization
- **Multi-enum Handling**: Uses `encodeMultienumValue()` for complex field types

### BeanFactory Integration
- **Bean Creation**: Uses `BeanFactory::newBean('AOR_Conditions')` for new instances
- **Proper Instantiation**: Ensures correct bean setup with all required properties
- **Memory Management**: Efficient bean creation for form processing

## UI Functionality

### Form Integration Support

#### POST Data Structure
Expected form structure with indexed arrays:
```
aor_conditions_field[0] = "account_name"
aor_conditions_operator[0] = "equal"
aor_conditions_value[0] = "ACME Corp"
aor_conditions_deleted[0] = "0"
```

#### Dynamic Field Processing
- **Field Type Detection**: Determines processing method based on field metadata
- **Value Type Handling**: Supports static values, field references, and calculated values
- **Module Path Support**: Enables cross-module field references through relationship chains

### Complex Logic Support

#### Parenthesis Management
- **Logical Grouping**: Supports nested conditions with proper precedence
- **Visual Organization**: Maintains logical structure for user interface display
- **Execution Order**: Ensures proper condition evaluation sequence

#### Multi-Condition Processing
- **Batch Operations**: Processes multiple conditions in single form submission
- **Atomic Saves**: Ensures all conditions save successfully or none are saved
- **Order Preservation**: Maintains user-defined condition sequence

## Integration Points

### AOR_Reports Integration
- **Parent Relationship**: Links conditions to specific report configurations
- **Report Context**: Inherits module context from parent report for field validation
- **Lifecycle Management**: Conditions automatically associate with parent report

### Module Field Discovery
- **Dynamic Fields**: Supports any SuiteCRM module field for condition creation
- **Relationship Fields**: Enables conditions on related module fields
- **Custom Fields**: Full support for custom field conditions

### Workflow System Integration
- **Shared Utilities**: Leverages common utilities from AOW_WorkFlow module
- **Consistent Processing**: Maintains consistency with workflow condition processing
- **Field Validation**: Uses shared validation logic for field type handling

This model class provides robust condition management while maintaining data integrity and supporting complex logical expressions required for advanced reporting scenarios. 