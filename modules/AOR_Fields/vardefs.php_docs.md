# AOR_Fields Variable Definitions Documentation

**@fileoverview** Database schema and field definitions for Advanced OpenReports (AOR) Field entities. Defines the structure for report output field configuration including display options, formatting, sorting, and grouping capabilities.

**@package** Advanced OpenReports for SugarCRM  
**@copyright** SalesAgility Ltd http://www.salesagility.com  
**@license** GNU AFFERO GENERAL PUBLIC LICENSE

## Overview

The `vardefs.php` file defines the complete database schema for the `aor_fields` table and provides field definitions for AOR_Field entities. These fields represent the output columns in Advanced OpenReports, including their display properties, formatting options, sorting behavior, and grouping configurations.

## Database Operations

### Table Definition
- **Table Name**: `aor_fields`
- **Auditing**: Disabled (`audited => false`)
- **Duplicate Merge**: Enabled for data consolidation
- **Optimistic Locking**: Enabled for concurrency control
- **Unified Search**: Enabled for searchability

### Key Database Fields

#### Report Relationship Fields
- `aor_report_id` (ID, 36 chars): Links field to parent AOR_Reports record
- `field_order` (INT): Defines display order of fields in report output

#### Field Selection Fields
- `module_path` (LONGTEXT): Serialized path defining relationship chain from base module
- `field` (ENUM): Target field name for report column
- `label` (VARCHAR, 255 chars): Custom display label for report column

#### Display Control Fields
- `display` (BOOL): Whether field appears in report output
- `link` (BOOL): Whether field values are clickable links to record details
- `field_function` (ENUM): Database function applied to field (COUNT, SUM, AVG, etc.)

#### Formatting and Presentation Fields
- `format` (VARCHAR, 100 chars): Field-specific formatting options
- `sort_by` (ENUM): Whether field is included in sorting criteria
- `sort_order` (ENUM): Ascending or descending sort direction

#### Grouping and Aggregation Fields
- `group_by` (BOOL): Whether field is used for result grouping
- `group_order` (ENUM): Sort direction for grouped results
- `group_display` (INT): Display position within grouped results
- `total` (ENUM): Aggregation function for numeric totals

### Database Indexes
- `aor_fields_index_report_id`: Index on `aor_report_id` for efficient report-based queries

## Internal API Integration

### Relationship Definitions
- **aor_reports**: Link relationship to parent AOR_Reports module
  - Relationship: `aor_reports_aor_fields`
  - Bean: `AOR_Reports`
  - Source: Non-database link

### VardefManager Integration
- Utilizes `VardefManager::createVardef()` to apply basic field definitions
- Extends with 'basic' template providing standard SugarCRM fields
- Automatically includes: id, name, date_entered, date_modified, created_by, etc.

### Field Options Integration
- `field`: References `user_type_dom` for field selection options
- `field_function`: References `aor_function_dom` for available database functions
- `sort_by`: References `aor_sort_dom` for sorting options
- `sort_order`: References `aor_type_order_dom` for order direction
- `group_order`: References `aor_type_order_dom` for grouping direction
- `format`: References `aor_format_options` for formatting choices
- `total`: References `aor_total_options` for aggregation functions

## UI Functionality

### Mass Update Support
Most fields support mass update operations for bulk field modifications:
- `field_order`: Enabled for reordering multiple fields
- Display and formatting fields: Enabled for bulk appearance changes
- `aor_report_id`: Disabled to prevent accidental report reassignment

### Form Integration
- All fields marked as importable for data migration scenarios
- Duplicate merge settings prevent data conflicts during imports
- Studio visibility controlled per field for admin customization

### Search and Reporting
- All configuration fields marked as reportable for meta-reporting
- Field-level search capabilities for field management interfaces
- Unified search integration for global field discovery

## Field Validation and Constraints

### Required Field Validation
- No fields marked as strictly required (allowing flexible field creation)
- Core functionality depends on non-empty `field` values

### Data Integrity Controls
- `aor_report_id`: 36-character UUID constraint
- `field_order`: Integer validation with range search capability
- Enum fields: Constrained to predefined option lists for data consistency

### Serialization Handling
- `module_path`: Stores base64-encoded serialized arrays for complex relationship chains
- Supports nested module relationships through path traversal

## Advanced Features

### Database Function Support
The `field_function` enum enables advanced database operations:
- **COUNT**: Record counting for frequency analysis
- **SUM**: Numeric summation for totaling
- **AVG**: Average calculations for statistical analysis
- **MIN/MAX**: Range analysis for data boundaries
- **DISTINCT**: Unique value identification

### Grouping and Aggregation
- **Multi-Level Grouping**: Supports hierarchical data organization
- **Group Display Control**: Fine-grained control over grouped result presentation
- **Aggregation Functions**: Comprehensive total calculations across groups

### Formatting Options
- **Date/Time Formatting**: Specialized formatting for temporal data
- **Numeric Formatting**: Currency, percentage, and decimal formatting
- **Text Formatting**: String manipulation and presentation options

## Integration Points

### Related Module Dependencies
- **AOR_Reports**: Parent module providing report context and configuration
- **AOW_WorkFlow**: Shares utility functions for field processing and validation
- **Module Metadata**: Dynamic field discovery based on SuiteCRM module definitions

### Cross-Module Field References
- Dynamic field enumeration based on selected report module
- Function availability determined by target field type
- Format validation against field-specific constraints and capabilities

### Report Output Integration
- Direct mapping to report column structure
- Sort order preservation for consistent output
- Group hierarchy maintenance for structured presentation

This vardefs structure enables sophisticated report field configuration while maintaining database integrity and providing comprehensive output customization options for advanced reporting scenarios. 