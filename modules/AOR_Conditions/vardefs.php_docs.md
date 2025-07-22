# AOR_Conditions Variable Definitions Documentation

**@fileoverview** Database schema and field definitions for Advanced OpenReports (AOR) Condition entities. Defines the structure for conditional logic used in SuiteCRM reporting system.

**@package** Advanced OpenReports for SugarCRM  
**@copyright** SalesAgility Ltd http://www.salesagility.com  
**@license** GNU AFFERO GENERAL PUBLIC LICENSE

## Overview

The `vardefs.php` file defines the complete database schema for the `aor_conditions` table and provides field definitions for AOR_Condition entities. These conditions are used within the Advanced OpenReports module to create complex filtering logic for reports.

## Database Operations

### Table Definition
- **Table Name**: `aor_conditions`
- **Auditing**: Disabled (`audited => false`)
- **Duplicate Merge**: Enabled for data consolidation
- **Optimistic Locking**: Enabled for concurrency control
- **Unified Search**: Enabled for searchability

### Key Database Fields

#### Report Relationship Fields
- `aor_report_id` (ID, 36 chars): Links condition to parent AOR_Reports record
- `condition_order` (INT): Defines execution order of conditions within a report

#### Logic Control Fields
- `logic_op` (VARCHAR): Logical operator ('AND'/'OR') connecting conditions, defaults to 'AND'
- `parenthesis` (VARCHAR): Grouping parentheses for complex logical expressions

#### Condition Definition Fields
- `module_path` (LONGTEXT): Serialized path defining relationship chain from base module
- `field` (ENUM): Target field name for condition evaluation
- `operator` (ENUM): Comparison operator (equals, contains, greater than, etc.)
- `value_type` (ENUM): Type of comparison value (static, field reference, etc.)
- `value` (VARCHAR, 255 chars): Actual comparison value or field reference
- `parameter` (BOOL): Flag indicating if condition accepts runtime parameters

### Database Indexes
- `aor_conditions_index_report_id`: Index on `aor_report_id` for efficient report-based queries

## Internal API Integration

### Relationship Definitions
- **aor_reports**: Link relationship to parent AOR_Reports module
  - Relationship: `aor_reports_aor_conditions`
  - Bean: `AOR_Reports`
  - Source: Non-database link

### VardefManager Integration
- Utilizes `VardefManager::createVardef()` to apply basic field definitions
- Extends with 'basic' template providing standard SugarCRM fields
- Automatically includes: id, name, date_entered, date_modified, created_by, etc.

### Field Options Integration
- `field`: References `user_type_dom` for field selection options
- `operator`: References `aor_operator_list` for available comparison operators  
- `value_type`: References `aor_condition_type_list` for value type selection

## UI Functionality

### Mass Update Support
Most fields support mass update operations for bulk condition modifications:
- `condition_order`: Enabled for reordering multiple conditions
- Logic and comparison fields: Enabled for bulk logic changes
- `aor_report_id`: Disabled to prevent accidental report reassignment

### Form Integration
- All fields marked as importable for data migration scenarios
- Duplicate merge settings prevent data conflicts during imports
- Studio visibility controlled per field for admin customization

### Search and Reporting
- `condition_order`, `operator`, `value_type`, `value`: Marked as reportable
- Field-level search capabilities for condition management interfaces
- Unified search integration for global condition discovery

## Field Validation and Constraints

### Required Field Validation
- No fields marked as strictly required (allowing flexible condition creation)
- Default values provided for critical fields (`logic_op` defaults to 'AND')

### Data Integrity Controls
- `aor_report_id`: 36-character UUID constraint
- `condition_order`: Integer validation with range search capability
- Enum fields: Constrained to predefined option lists for data consistency

### Serialization Handling
- `module_path`: Stores base64-encoded serialized arrays for complex relationship chains
- Supports nested module relationships through path traversal

## Integration Points

### Related Module Dependencies
- **AOR_Reports**: Parent module providing report context and configuration
- **AOW_WorkFlow**: Shares utility functions for field processing and validation
- **Module Metadata**: Dynamic field discovery based on SuiteCRM module definitions

### Cross-Module Field References
- Dynamic field enumeration based on selected report module
- Operator availability determined by target field type
- Value validation against field-specific constraints and formats

This vardefs structure enables the creation of sophisticated reporting conditions while maintaining database integrity and providing intuitive UI interactions for report builders. 