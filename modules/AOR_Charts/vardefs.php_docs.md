# vardefs.php Documentation

## Overview
**File:** `modules/AOR_Charts/vardefs.php`  
**Purpose:** Variable definitions and data model for AOR_Charts module  
**Package:** Advanced OpenReports for SugarCRM  

## Data Model Definition

### Dictionary Configuration
**Array:** `$dictionary['AOR_Chart']`  
**Table:** `aor_charts`  
**Features:**
- **audited:** false (chart changes not tracked in audit)
- **duplicate_merge:** true (supports duplicate detection and merging)

## Database Operations

### Table Structure
**Primary Table:** `aor_charts`

### Field Definitions

#### Relationship Fields

##### aor_report (Link Field)
- **Type:** 'link'
- **Purpose:** Links chart to parent AOR_Report
- **Relationship:** 'aor_charts_aor_reports'
- **Module:** 'AOR_Reports'
- **Bean:** 'AOR_Report'
- **Link Type:** 'one' (one report per chart)
- **Source:** 'non-db' (virtual field)
- **Side:** 'left' (left side of relationship)
- **ID Field:** 'aor_report_id'

##### aor_report_name (Relate Field)
- **Type:** 'relate'
- **Purpose:** Display name of related report
- **Source:** 'non-db' (calculated field)
- **Vname:** 'LBL_AOR_REPORT_NAME'
- **Save:** true (saves relationship data)
- **ID Field:** 'aor_report_id'
- **Link:** 'aor_charts_aor_reports'
- **Related Table:** 'aor_reports'
- **Related Module:** 'AOR_Reports'
- **Related Field:** 'name' (rname)

##### aor_report_id (ID Field)
- **Type:** 'id'
- **Purpose:** Foreign key to AOR_Reports table
- **Reportable:** false (not included in reports)
- **Vname:** 'LBL_AOR_REPORT_ID'

#### Chart Configuration Fields

##### type (Enum Field)
- **Type:** 'enum'
- **Purpose:** Chart type selection
- **Required:** false
- **Vname:** 'LBL_TYPE'
- **Mass Update:** false (not available in mass update)
- **Length:** 100 characters
- **Size:** 20 (form field size)
- **Options:** 'aor_chart_types' (dropdown list)
- **Valid Values:** bar, line, pie, radar, rose, grouped_bar, stacked_bar

##### x_field (Integer Field)
- **Type:** 'int'
- **Purpose:** References X-axis field from report field configuration
- **Required:** false
- **Vname:** 'LBL_X_FIELD'
- **Usage:** Index into report's field array for horizontal axis data

##### y_field (Integer Field)
- **Type:** 'int'
- **Purpose:** References Y-axis field from report field configuration
- **Required:** false
- **Vname:** 'LBL_Y_FIELD'
- **Usage:** Index into report's field array for vertical axis data

## Internal API Calls

### Relationship Definition
**Relationship:** `aor_charts_aor_reports`
- **Type:** 'one-to-many'
- **Left Side (Parent):**
  - **Module:** 'AOR_Reports'
  - **Table:** 'aor_reports'
  - **Key:** 'id'
- **Right Side (Child):**
  - **Module:** 'AOR_Charts'
  - **Table:** 'aor_charts'
  - **Key:** 'aor_report_id'

### VardefManager Integration
- **Class:** VardefManager
- **Method:** `createVardef()`
- **Parameters:** 
  - Module: 'AOR_Charts'
  - Object: 'AOR_Chart'
  - Templates: ['basic'] (includes standard SugarBean fields)

### Standard Field Inheritance
**Basic Template Includes:**
- `id`: Primary key (UUID)
- `name`: Chart title/name
- `date_entered`: Creation timestamp
- `date_modified`: Last modification timestamp
- `modified_user_id`: User who last modified
- `created_by`: User who created the chart
- `deleted`: Soft delete flag
- `description`: Chart description

## UI Functionality

### Form Field Configuration

#### Chart Type Selection
- **Field:** type
- **UI Control:** Dropdown/select
- **Options Source:** $app_list_strings['aor_chart_types']
- **Validation:** Must be one of the valid chart types
- **Default:** None (user must select)

#### Axis Field Selection
- **X-Axis Field:** x_field
- **Y-Axis Field:** y_field
- **UI Control:** Dropdown populated from report field definitions
- **Validation:** Must reference valid report fields
- **Dependency:** Requires parent report to be selected first

#### Report Association
- **Field:** aor_report_name
- **UI Control:** Relate field with popup selector
- **Search:** Enables search and selection of existing reports
- **Validation:** Must select valid, accessible report

## Security Integration

### Access Control
- **Module-Level Security:** Inherits from AOR_Reports security model
- **Field-Level Security:** All fields respect user permissions
- **Relationship Security:** Users can only link to accessible reports

### Data Integrity
- **Foreign Key Constraints:** aor_report_id must reference valid report
- **Relationship Validation:** Charts cannot exist without parent report
- **Field Validation:** Chart type must be from approved list

## Performance Optimization

### Database Optimization
- **Indexes:** Primary key and foreign key indexes
- **Relationship Optimization:** Efficient one-to-many relationship structure
- **Field Types:** Appropriate field types for data storage

### Query Efficiency
- **Non-DB Fields:** Virtual fields reduce database overhead
- **Selective Loading:** Only loads necessary relationship data
- **Index Usage:** Foreign key indexes optimize relationship queries

## Integration Points

### AOR_Reports Module
- **Parent Relationship:** Charts belong to reports
- **Data Dependency:** Chart fields reference report field definitions
- **Security Inheritance:** Charts inherit report access permissions

### SuiteCRM Framework
- **VardefManager:** Uses standard vardefs management
- **Basic Template:** Inherits standard SugarBean functionality
- **Relationship Engine:** Leverages SuiteCRM's relationship system

### Chart Libraries
- **Field References:** x_field and y_field reference report data structure
- **Type Configuration:** Chart type drives rendering library selection
- **Data Mapping:** Field indexes map to actual data for chart generation

## Configuration Management

### Chart Type Options
**Enum Options:** 'aor_chart_types'
**Valid Types:**
- **bar:** Standard bar charts
- **line:** Line charts with markers
- **pie:** Circular pie charts
- **radar:** Multi-axis radar charts
- **rose:** Rose/polar charts
- **grouped_bar:** Multi-series bar charts
- **stacked_bar:** Stacked bar charts

### Field Reference System
- **Field Indexing:** Charts reference report fields by numeric index
- **Dynamic Configuration:** Field options populated based on parent report
- **Type Safety:** Integer types ensure valid field references

## Advanced Features

### Multiple Charts per Report
- **One-to-Many Relationship:** Single report can have multiple charts
- **Chart Management:** Each chart independently configured
- **Type Variety:** Different chart types can coexist

### Dynamic Field Selection
- **Report-Driven Options:** Available fields based on parent report configuration
- **Validation:** Ensures selected fields exist in report
- **User Experience:** Progressive enhancement based on report selection

### Extensibility
- **New Chart Types:** Additional types can be added to enum options
- **Field Enhancement:** Additional configuration fields can be added
- **Relationship Extension:** Additional relationships can be defined 