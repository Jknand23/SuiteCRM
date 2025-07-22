# export_utils.php Documentation

/**
 * @fileoverview Comprehensive data export utilities for SuiteCRM CSV generation
 * @package SuiteCRM
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `export_utils.php` file provides comprehensive data export functionality for SuiteCRM. It handles CSV generation, field formatting, data type conversion, ACL permissions, search integration, and sample data creation. This file is essential for all data export operations throughout the application.

## Dependencies

- `SuiteCRM\CleanCSV` - CSV field cleaning and escaping
- Global configuration arrays (`$sugar_config`, `$beanList`, `$beanFiles`)
- Database management (`DBManagerFactory`)
- Internationalization (`$locale`, `$timedate`)
- Access control (`ACLController`)
- Bean Factory pattern (`BeanFactory`)

## Core Export Functions

### Configuration Functions

#### getDelimiter()
Retrieves the CSV delimiter based on system configuration and user preferences.
- **Returns:** string - The delimiter character (default: comma)
- **Priority Order:**
  1. User preference (`export_delimiter`)
  2. System configuration (`$sugar_config['export_delimiter']`)
  3. Default value (comma)

#### printCSV($csv, $name)
Outputs CSV data with proper HTTP headers for file download.
- **Parameters:**
  - `$csv` (string) - UTF-8 encoded CSV content
  - `$name` (string) - Download filename (without extension)
- **Features:**
  - Excel compatibility with UTF-8 BOM when configured
  - Character set conversion for legacy systems
  - Proper HTTP headers for download
  - Content-Length calculation for progress indicators

### Main Export Function

#### export($type, $records = null, $members = false, $sample = false)
Primary function for generating CSV export data from SuiteCRM modules.
- **Parameters:**
  - `$type` (string) - Module name to export
  - `$records` (string|null) - Comma-separated record IDs for selective export
  - `$members` (boolean) - Export member/relationship data
  - `$sample` (boolean) - Generate sample data instead of real data
- **Returns:** string - Complete CSV content with headers and data
- **Features:**
  - **Security:** Module validation and ACL permission checking
  - **Query Building:** Dynamic WHERE clause generation from search criteria
  - **Field Processing:** Type-specific data formatting and conversion
  - **Relationship Handling:** Complex relationship exports for member data
  - **Non-primary Email Support:** Special handling for multiple email addresses
  - **Custom Related Fields:** Dynamic resolution of custom relate fields

### Search Integration

#### generateSearchWhere($module, $query)
Generates SQL WHERE clauses from search form criteria.
- **Parameters:**
  - `$module` (string) - Module name for search context
  - `$query` (string) - JSON-encoded search criteria
- **Returns:** array - Contains 'where' clause and 'searchFields' array
- **Features:**
  - SearchForm and SearchForm2 compatibility
  - Custom metadata file loading
  - Session-based WHERE clause fallback
  - JSON query parameter parsing

### Sample Data Generation

#### exportSample($type)
Creates sample CSV files with instructional content for import templates.
- **Parameters:**
  - `$type` (string) - Module name for sample generation
- **Returns:** string - CSV content with sample data and instructions
- **Features:**
  - Fake data generation using demo data arrays
  - Instructional text for import guidance
  - Limited record count (5 records) for templates

#### returnFakeDataRow($focus, $field_array, $rowsToReturn = 5)
Generates realistic fake data for CSV samples based on field types.
- **Parameters:**
  - `$focus` (SugarBean) - Bean object for field definitions
  - `$field_array` (array) - Array of field names to populate
  - `$rowsToReturn` (integer) - Number of sample records to create
- **Returns:** string - CSV data rows with fake content
- **Field Type Support:**
  - **IDs:** GUID generation
  - **Names:** Random selection from demo data arrays
  - **Dates/DateTime:** Current date/time with proper formatting
  - **Phones:** Realistic phone number patterns
  - **Addresses:** Random address components
  - **Enums:** Random selection from dropdown options
  - **Boolean:** Random true/false values
  - **Text:** Lorem ipsum content

## Data Processing Functions

### Field Translation

#### translateForExport($field_db_name, $focus)
Translates database field names to user-friendly labels for export headers.
- **Parameters:**
  - `$field_db_name` (string) - Database field name
  - `$focus` (SugarBean) - Bean object for context
- **Returns:** string - Translated field label
- **Translation Priority:**
  1. Module-specific export override (`LBL_EXPORT_FIELDNAME`)
  2. Application-wide export override
  3. Field definition vname from module strings
  4. Field definition vname from app strings
  5. Standard label format (`LBL_FIELDNAME`)
  6. Database field name as fallback

### Field Ordering

#### get_field_order_mapping($name='', $reorderArr = '', $exclude = true)
Controls field ordering and exclusion for consistent export layouts.
- **Parameters:**
  - `$name` (string) - Module name or empty for all mappings
  - `$reorderArr` (array) - Array to reorder according to module preferences
  - `$exclude` (boolean) - Apply field exclusion rules
- **Returns:** array - Ordered field mapping or reordered input array
- **Module Support:**
  - Predefined ordering for core modules (Accounts, Contacts, Leads, etc.)
  - Bean type detection for custom modules (Person, Company, Sale, Issue)
  - Field exclusion lists for problematic fields
  - Fallback ordering for unknown modules

### Custom Field Processing

#### parseRelateFields($line, $record, $customRelateFields)
Processes custom related field markers in CSV output.
- **Parameters:**
  - `$line` (string) - CSV line containing relate field markers
  - `$record` (array) - Current record data
  - `$customRelateFields` (array) - Pre-resolved custom field values
- **Returns:** string - CSV line with markers replaced by actual values
- **Features:**
  - Pattern matching for relate field markers
  - Dynamic value resolution from relationship data
  - Module and field validation

## Data Type Conversion

### Field Type Handling

The export system provides specialized processing for different field types:

#### Currency Fields
- Applies user locale formatting
- Uses `currency_format_number()` for proper display

#### Decimal/Float Fields
- Respects user decimal separator preferences
- Handles system default vs. user preferences

#### DateTime Fields
- Converts from database format to user display format
- Adds proper AM/PM spacing for Excel compatibility
- Timezone conversion support

#### Date Fields
- Database to display format conversion
- Handles timezone considerations

#### Enum/Dynamic Enum Fields
- Translates option keys to display labels
- Uses `$app_list_strings` for translation
- Handles missing translations gracefully

#### Multi-Enum Fields
- Decodes multi-value selections
- Translates individual values
- Joins with comma separator

#### Boolean Fields
- Proper true/false representation
- Option-based translation when available

## Security and Access Control

### ACL Integration
- Module-level access checking (`export` permission)
- Bean-level access control for records
- User ownership validation
- Administrative override capabilities

### Input Validation
- Module name validation against `$beanList`
- Record ID sanitization and quoting
- Search parameter validation
- SQL injection prevention

## Performance Optimizations

### Query Optimization
- Chunked processing for large record sets
- Optimized relationship queries
- Query length limitations for database compatibility
- Union query optimization for custom fields

### Memory Management
- Streaming CSV generation
- Limited sample record generation
- Efficient field processing
- Resource cleanup after operations

## Integration Points

### Search System Integration
- SearchForm and SearchForm2 compatibility
- Metadata file loading and processing
- Session-based search criteria
- Custom search field definitions

### Bean Factory Integration
- Dynamic bean instantiation
- Proper object lifecycle management
- Module validation through bean registry

### Internationalization Integration
- Locale-aware data formatting
- Character set conversion
- BOM handling for Excel compatibility
- Multi-language field label support

### CleanCSV Integration
- Field escaping and sanitization
- Quote handling and special characters
- CSV format compliance

## Common Usage Patterns

### Standard Module Export
```php
// Export all records from Accounts module
$csv = export('Accounts');
printCSV($csv, 'accounts_export');
```

### Selective Record Export
```php
// Export specific records
$recordIds = 'id1,id2,id3';
$csv = export('Contacts', $recordIds);
printCSV($csv, 'selected_contacts');
```

### Sample File Generation
```php
// Generate import template
$sampleCsv = exportSample('Leads');
printCSV($sampleCsv, 'leads_import_template');
```

### Search-Based Export
```php
// Export with search criteria
$_REQUEST['current_post'] = json_encode($searchCriteria);
$csv = export('Opportunities');
printCSV($csv, 'filtered_opportunities');
```

## Configuration Options

### System Configuration
- `$sugar_config['export_delimiter']` - Default CSV delimiter
- `$sugar_config['export_excel_compatible']` - Excel compatibility mode
- `$sugar_config['default_decimal_seperator']` - Decimal separator

### User Preferences
- `export_delimiter` - Personal delimiter preference
- `dec_sep` - Decimal separator preference

## Error Handling

- Database connection validation
- Module existence verification
- ACL permission enforcement
- Query execution error handling
- Field type validation
- Character encoding error management

## Sample Data Configuration

The system includes comprehensive demo data arrays for realistic sample generation:
- Personal names (first_name_array, last_name_array)
- Company names (company_name_array)
- Addresses (street_address_array, city_array)
- Module-specific data (bug_seed_names, task_seed_data_names)
- Professional titles and roles
- Team names and organizational data

## Security Considerations

- All user input is properly sanitized and quoted
- Module access is validated before export
- Record-level permissions are enforced
- SQL injection prevention through parameterized queries
- Output encoding for CSV safety

## Performance Notes

- Large exports are handled efficiently through streaming
- Query optimization for relationship data
- Memory usage minimization through chunked processing
- Database-specific optimizations for different engines 