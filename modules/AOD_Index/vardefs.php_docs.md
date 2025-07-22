# vardefs.php Documentation

## @fileoverview
**AOD_Index vardefs - Database field definitions for Advanced OpenDiscovery search index**
- **Package**: modules/AOD_Index
- **Copyright**: SalesAgility Ltd
- **License**: GNU AFFERO GENERAL PUBLIC LICENSE
- **Purpose**: Defines database schema, field properties, and metadata for AOD_Index module
- **Integration**: Manages Lucene search index configuration and state tracking

## Database Operations

### Table Configuration
- **Table Name**: `aod_index`
- **Auditing**: `'audited'=>true` - tracks field changes in audit log
- **Duplicate Merge**: `'duplicate_merge'=>true` - supports record merging functionality
- **Optimistic Locking**: `'optimistic_locking'=>true` - prevents concurrent edit conflicts
- **Unified Search**: `'unified_search'=>false` - excluded from global search

### Custom Field Definitions

#### Last Optimization Timestamp
```php
'last_optimised' => array(
    'name' => 'last_optimised',
    'vname' => 'LBL_LAST_OPTIMISED',
    'type' => 'datetimecombo',
    'size' => '20',
    'dbType' => 'datetime'
)
```
- **Purpose**: Records when index was last optimized for performance
- **Database Type**: `datetime` - stores full timestamp
- **UI Component**: `datetimecombo` - date/time picker interface
- **Auditing**: `'audited' => false` - optimization time not audited
- **Reporting**: `'reportable' => true` - available in reports
- **Import/Export**: `'importable' => 'true'` - can be imported
- **Search**: `'unified_search' => false` - not searchable via global search
- **Mass Update**: `'massupdate' => 0` - disabled for mass updates
- **Range Search**: `'enable_range_search' => false` - no date range filtering
- **Duplicate Merge**: `'duplicate_merge' => 'disabled'` - not used in merging

#### Index File Location
```php
'location' => array(
    'name' => 'location',
    'vname' => 'LBL_LOCATION',
    'type' => 'varchar',
    'len' => '255',
    'size' => '20'
)
```
- **Purpose**: Stores file system path to Lucene index directory
- **Database Type**: `varchar(255)` - string field with 255 character limit
- **UI Display**: Text input with size 20 characters visible
- **Default Value**: Usually `modules/AOD_Index/Index/Index`
- **Auditing**: `'audited' => false` - path changes not audited
- **Reporting**: `'reportable' => true` - location visible in reports
- **Import/Export**: `'importable' => 'true'` - can be imported/exported
- **Search**: `'unified_search' => false` - not included in global search
- **Mass Update**: `'massupdate' => 0` - disabled for bulk operations
- **Duplicate Merge**: `'duplicate_merge' => 'disabled'` - not considered in merging

## Internal API Calls

### VardefManager Integration
```php
VardefManager::createVardef('AOD_Index', 'AOD_Index', array('basic','assignable'));
```
- **Module Registration**: Registers AOD_Index with SuiteCRM's field management system
- **Template Application**: Applies 'basic' and 'assignable' field templates
- **Standard Fields**: Inherits id, name, date_entered, date_modified, etc.
- **User Assignment**: Adds assigned_user_id and related user fields
- **Framework Integration**: Ensures compatibility with SuiteCRM field system

### Field Template Inheritance

#### Basic Template Fields
- **`id`**: Primary key (auto-generated UUID)
- **`name`**: Display name for the index
- **`date_entered`**: Record creation timestamp
- **`date_modified`**: Last modification timestamp
- **`created_by`**: User who created the record
- **`modified_user_id`**: User who last modified the record
- **`deleted`**: Soft delete flag (0 = active, 1 = deleted)
- **`description`**: Optional text description

#### Assignable Template Fields
- **`assigned_user_id`**: User responsible for the index
- **`assigned_user_name`**: Display name of assigned user (non-stored)
- **`assigned_user_link`**: Relationship link to Users module

## Field Properties and Behavior

### Common Field Properties
- **`required`**: All custom fields are optional (`'required' => false`)
- **`no_default`**: Fields don't have default values (`'no_default' => false`)
- **`comments`**: Empty - no field-level help text defined
- **`help`**: Empty - no extended help documentation
- **`merge_filter`**: `'disabled'` - fields not used in record merging filters

### Data Validation
- **Length Limits**: Location field has 255 character maximum
- **Type Validation**: DateTime fields validated for proper format
- **Required Fields**: No custom fields are mandatory
- **Input Sanitization**: Standard SuiteCRM field validation applies

### User Interface Properties
- **Display Size**: Location field shows 20 characters in UI
- **Form Integration**: Fields appear in standard SuiteCRM edit/detail views
- **List Views**: Fields available for list view column display
- **Dashlets**: Can be used in dashboard components

## Database Schema Features

### Audit Trail Support
- **Audit Table**: Changes tracked in `aod_index_audit` table
- **Field Tracking**: Only standard fields audited, custom fields excluded
- **User Context**: Audit records include user and timestamp information
- **Change History**: Provides complete modification history for compliance

### Relationship Management
- **User Relationships**: Standard assigned user relationship
- **Creator Tracking**: Links to user who created index record
- **Modifier Tracking**: Links to user who last updated record
- **No Custom Relationships**: Module focuses on index management, not data relationships

### Performance Optimizations
- **Indexing Strategy**: Database indexes on standard SuiteCRM fields
- **Query Optimization**: Optimistic locking prevents lock contention
- **Memory Efficiency**: Minimal custom fields reduce table size
- **Search Exclusion**: Excluded from unified search for performance

## Module Configuration

### Security Settings
- **ACL Support**: Standard SuiteCRM access control applies
- **Field Level Security**: Individual fields can have restricted access
- **Team Security**: Compatible with team-based security models
- **Role Permissions**: Respects user role-based field permissions

### Import/Export Capabilities
- **Data Import**: Both custom fields support data import
- **Export Functionality**: Fields included in standard export operations
- **Mass Operations**: Custom fields excluded from mass update operations
- **Duplicate Handling**: Custom fields ignored in duplicate detection

## Integration Points

### Framework Compliance
- **Standard Patterns**: Follows SuiteCRM field definition conventions
- **Backward Compatibility**: Maintains compatibility with older SuiteCRM versions
- **Extension Support**: Custom fields can be extended via Extension framework
- **Upgrade Safety**: Schema changes preserve custom field definitions

### Module Relationships
- **Users Module**: Standard assignable relationship patterns
- **Administration**: Integrates with system administration interfaces
- **Security Groups**: Compatible with security group restrictions
- **Teams**: Supports team-based access control

## Data Management

### Record Lifecycle
- **Creation**: Index record typically created during system setup
- **Modification**: Updated during optimization and configuration changes
- **Deletion**: Soft delete preserves historical optimization data
- **Restoration**: Deleted records can be restored with full field data

### Maintenance Operations
- **Optimization Tracking**: Last optimization timestamp enables scheduling
- **Location Management**: File path changes require system validation
- **Backup Considerations**: Critical for disaster recovery planning
- **Migration Support**: Field definitions support system migrations 