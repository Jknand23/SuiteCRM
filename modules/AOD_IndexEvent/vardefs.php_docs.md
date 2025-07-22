# vardefs.php Documentation

## @fileoverview
**AOD_IndexEvent vardefs - Database field definitions for Advanced OpenDiscovery index event tracking**
- **Package**: modules/AOD_IndexEvent
- **Copyright**: SalesAgility Ltd
- **License**: GNU AFFERO GENERAL PUBLIC LICENSE
- **Purpose**: Defines database schema and field properties for tracking search indexing events and their outcomes
- **Integration**: Enables monitoring and debugging of search index operations

## Database Operations

### Table Configuration
- **Table Name**: `aod_indexevent`
- **Auditing**: `'audited'=>true` - tracks all field changes for compliance
- **Duplicate Merge**: `'duplicate_merge'=>true` - supports record merging functionality
- **Optimistic Locking**: `'optimistic_locking'=>true` - prevents concurrent edit conflicts
- **Unified Search**: `'unified_search'=>false` - excluded from global search for performance

### Custom Field Definitions

#### Error Message Storage
```php
'error' => array(
    'name' => 'error',
    'vname' => 'LBL_ERROR',
    'type' => 'varchar',
    'len' => '255',
    'size' => '20'
)
```
- **Purpose**: Stores error messages from failed indexing operations
- **Database Type**: `varchar(255)` - accommodates detailed error descriptions
- **UI Component**: Text input field with 20 character display width
- **Debugging**: Critical for troubleshooting indexing failures
- **Auditing**: `'audited' => false` - error messages not tracked in audit
- **Reporting**: `'reportable' => true` - available for error analysis reports
- **Import/Export**: `'importable' => 'true'` - can be imported for data migration
- **Search**: `'unified_search' => false` - not included in global search
- **Mass Update**: `'massupdate' => 0` - disabled for bulk operations

#### Success Status Tracking
```php
'success' => array(
    'name' => 'success',
    'vname' => 'LBL_SUCCESS',
    'type' => 'bool',
    'default' => '0',
    'size' => '20'
)
```
- **Purpose**: Boolean flag indicating whether indexing operation succeeded
- **Database Type**: `bool` - standard boolean field
- **Default Value**: `'0'` (false) - assumes failure until proven successful
- **UI Component**: Checkbox or boolean select widget
- **Auditing**: `'audited' => false` - status changes not audited
- **Reporting**: `'reportable' => true` - essential for success rate analysis
- **Import/Export**: `'importable' => 'true'` - supports data import
- **Search**: `'unified_search' => false` - not searchable via global search
- **Mass Update**: `'massupdate' => 0` - not available for bulk updates

#### Record Identification Fields
```php
'record_id' => array(
    'name' => 'record_id',
    'type' => 'id',
    'reportable' => false,
    'vname' => 'LBL_RECORD_ID'
)
```
- **Purpose**: Stores ID of the record that was indexed
- **Database Type**: `id` - standard SuiteCRM ID field format
- **Privacy**: `'reportable' => false` - not exposed in reports for privacy
- **Reference**: Links event to specific SuiteCRM record
- **Usage**: Enables tracking which records had indexing issues

```php
'record_module' => array(
    'name' => 'record_module',
    'vname' => 'LBL_RECORD_MODULE',
    'type' => 'varchar',
    'len' => '255',
    'size' => '20'
)
```
- **Purpose**: Identifies the module of the indexed record
- **Database Type**: `varchar(255)` - accommodates module name strings
- **UI Display**: Text input with 20 character visible width
- **Analysis**: Enables module-specific indexing performance analysis
- **Auditing**: `'audited' => false` - module changes not audited
- **Reporting**: `'reportable' => true` - available for module-based reports
- **Import/Export**: `'importable' => 'true'` - supports data import/export

## Internal API Calls

### VardefManager Integration
```php
VardefManager::createVardef('AOD_IndexEvent', 'AOD_IndexEvent', array('basic','assignable'));
```
- **Module Registration**: Registers with SuiteCRM's field management system
- **Template Application**: Applies 'basic' and 'assignable' field templates
- **Standard Fields**: Inherits standard SugarBean fields (id, name, dates, etc.)
- **User Assignment**: Adds user assignment capabilities with assigned_user_id
- **Framework Compliance**: Ensures compatibility with SuiteCRM field system

### Field Template Inheritance

#### Basic Template Fields
- **`id`**: Primary key (auto-generated UUID)
- **`name`**: Display name for the event (typically record summary)
- **`date_entered`**: Event creation timestamp
- **`date_modified`**: Last modification timestamp
- **`created_by`**: User who created the event record
- **`modified_user_id`**: User who last modified the event
- **`deleted`**: Soft delete flag (0 = active, 1 = deleted)
- **`description`**: Optional text description of the event

#### Assignable Template Fields
- **`assigned_user_id`**: User responsible for following up on the event
- **`assigned_user_name`**: Display name of assigned user (non-stored)
- **`assigned_user_link`**: Relationship link to Users module

## Database Performance Features

### Index Optimization
```php
'indices' => array(
    array('name' =>'idx_record_module', 'type'=>'index', 'fields'=>array('record_module')),
    array('name' =>'idx_record_id', 'type' =>'index', 'fields'=>array('record_id')),
)
```
- **Module Index**: `idx_record_module` enables fast filtering by module
- **Record Index**: `idx_record_id` supports efficient record lookups
- **Query Performance**: Optimizes common administrative queries
- **Reporting**: Accelerates statistics and analytics queries

### Query Optimization Patterns
- **Module Analysis**: `WHERE record_module = 'Accounts'` uses module index
- **Record Events**: `WHERE record_id = 'xyz'` uses record index
- **Success Filtering**: `WHERE success = 1` for success rate calculations
- **Composite Queries**: Combining indexes for complex analytics

## Field Properties and Behavior

### Common Field Properties
- **`required`**: All custom fields are optional (`'required' => false`)
- **`no_default`**: Most fields don't have default values except success field
- **`comments`**: Empty - no field-level help text defined
- **`help`**: Empty - no extended help documentation
- **`merge_filter`**: `'disabled'` - fields not used in record merging

### Data Validation and Constraints
- **Length Limits**: Error and module fields limited to 255 characters
- **Type Validation**: Boolean field enforces true/false values
- **ID Validation**: Record ID follows standard SuiteCRM ID format
- **Input Sanitization**: Standard SuiteCRM field validation applies

### User Interface Properties
- **Display Sizes**: Text fields show 20 characters in forms
- **Form Integration**: Fields appear in standard edit/detail views
- **List Views**: Fields available for list view column display
- **Dashlets**: Can be used in dashboard components for monitoring

## Event Tracking Integration

### Indexing Workflow Integration
1. **Event Creation**: Created during indexing operations by logic hooks
2. **Status Setting**: Success field updated based on indexing outcome
3. **Error Capture**: Error field populated for failed operations
4. **Record Linking**: Record ID and module fields identify source record

### Administrative Monitoring
- **Success Statistics**: Boolean field enables success rate calculations
- **Error Analysis**: Error field provides debugging information
- **Module Performance**: Module field enables per-module analysis
- **Historical Tracking**: Date fields provide temporal analysis

## Reporting and Analytics

### Statistical Queries
- **Success Rates**: `SELECT COUNT(*) FROM aod_indexevent WHERE success = 1`
- **Module Performance**: `SELECT record_module, AVG(success) FROM aod_indexevent GROUP BY record_module`
- **Error Frequency**: `SELECT error, COUNT(*) FROM aod_indexevent WHERE success = 0 GROUP BY error`
- **Temporal Analysis**: Success rates over time using date fields

### Administrative Reports
- **Failed Operations**: Lists records that failed indexing with error details
- **Module Health**: Shows indexing success rates by module
- **Error Patterns**: Identifies common indexing problems
- **Performance Trends**: Historical indexing performance analysis

## Security and Access Control

### Field-Level Security
- **Record ID Privacy**: Not reportable to protect record privacy
- **Error Information**: May contain sensitive file paths or system details
- **Administrative Access**: Typically restricted to system administrators
- **Audit Compliance**: Standard fields audited for compliance tracking

### Data Protection Considerations
- **Sensitive Errors**: Error messages may reveal system information
- **Access Restrictions**: Should be limited to authorized technical personnel
- **Privacy**: Events track metadata, not actual record content
- **Retention**: Consider archiving old events for privacy compliance

## Data Management and Maintenance

### Record Lifecycle
- **Automatic Creation**: Events created by indexing system automatically
- **Status Updates**: Success field updated when indexing completes
- **Error Updates**: Error field populated when problems occur
- **Cleanup**: Old events may be archived or purged periodically

### Maintenance Operations
- **Volume Management**: Table can grow large with active indexing
- **Performance Monitoring**: Success rates indicate system health
- **Error Resolution**: Error analysis guides troubleshooting efforts
- **Archival Strategy**: Consider moving old events to archive tables

## Integration Points

### System Components
- **AOD_Index**: Main indexing system that generates events
- **AOD_LogicHooks**: Creates events in response to record changes
- **Administrative Views**: Display events for monitoring and debugging
- **Reporting System**: Uses events for performance and error analysis

### Framework Integration
- **SugarBean Compatibility**: Full integration with SuiteCRM framework
- **Workflow Support**: Can be used in workflows and business processes
- **API Access**: Available through standard SuiteCRM REST/SOAP APIs
- **Extension Framework**: Can be extended through custom fields and logic 