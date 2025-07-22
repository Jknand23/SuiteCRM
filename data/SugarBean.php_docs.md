# SugarBean.php Documentation

/**
 * @fileoverview Core base class for all business objects in SuiteCRM providing CRUD operations, 
 * relationship management, field handling, auditing, caching, and data access layer functionality.
 * This is the foundation class that all module-specific beans extend and integrates with BeanFactory 
 * for object creation and Link2 for relationship management.
 * @package SuiteCRM.Data
 * @copyright SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

SugarBean is the foundational base class for all business objects in SuiteCRM. It implements the primary functionality needed for manipulating business objects including create, retrieve, update, and delete operations (CRUD). The class provides comprehensive data access layer functionality, relationship management, field processing, auditing capabilities, and caching mechanisms.

**Key Integration Points:**
- **BeanFactory Integration**: All beans are instantiated through `BeanFactory::getBean()` for consistent creation and caching
- **Link2 Relationship System**: Integrates with Link2 objects for modern relationship management via `load_relationship()`
- **RelationshipFactory**: Relationship objects created by the factory operate on SugarBean instances
- **Module System**: Each module extends SugarBean to provide module-specific functionality
- **Auditing System**: Built-in audit trail functionality for tracking data changes

### Key Design Principles
- One bean per module folder with consistent naming conventions
- Bean names are singular (e.g., Contact), table names are plural (e.g., contacts)
- Extensive use of vardefs (variable definitions) for field metadata
- Support for custom fields and dynamic field definitions
- Built-in auditing and change tracking capabilities
- Optimistic locking for concurrent access control
- Integration with BeanFactory caching for performance optimization

## Core Properties

### Database and Identity
- `$db` (DBManager): Database connection object
- `$id` (string): Unique object identifier
- `$new_with_id` (boolean): Force insert instead of update when ID exists
- `$table_name` (string): Database table name for this bean type
- `$object_name` (string): Singular name of the bean
- `$module_dir` (string): Module folder name
- `$module_name` (string): Module name

### Field Management
- `$field_name_map` (array): Mapping of field names to definitions
- `$field_defs` (array): Complete field definitions from vardefs
- `$column_fields` (array): Database column fields
- `$list_fields` (array): Fields displayed in list views
- `$additional_column_fields` (array): Extra fields for queries
- `$relationship_fields` (array): Fields that handle relationships
- `$custom_fields` (DynamicField): Custom field definitions

### State Management
- `$deleted` (integer): Soft delete flag (0=active, 1=deleted)
- `$fetched_row` (array): Original data retrieved from database for change tracking
- `$new_assigned_user_name` (string): Username for notification purposes
- `$processed_dates_times` (array): Tracks which date/time fields have been processed
- `$disable_vardefs` (boolean): Disables vardef processing for special cases

### Auditing and Tracking
- `$update_date_modified` (boolean): Whether to update date_modified on save
- `$update_modified_by` (boolean): Whether to update modified_by on save
- `$update_date_entered` (boolean): Whether to allow date_entered updates
- `$set_created_by` (boolean): Whether to set created_by on new records
- `$optimistic_lock` (boolean): Enables optimistic locking
- `$createdAuditRecords` (boolean): Tracks if audit records were created

## Database Operations

### Core CRUD Methods

#### save($check_notify = false)
**Primary method for persisting bean data to database**
- Automatically determines if operation is INSERT or UPDATE based on ID presence
- Handles XSS cleaning and data formatting
- Sets audit fields (date_modified, modified_user_id, etc.)
- Processes custom business logic via before_save/after_save hooks
- Manages relationship changes
- Handles email address processing for beans with email capabilities
- Supports notification system for assignment changes
- Implements optimistic locking validation
- Returns boolean success/failure status

**Process Flow:**
1. Data cleaning and validation
2. Determine if INSERT or UPDATE operation
3. Set audit trail fields (dates, user IDs)
4. Process relationship changes
5. Execute before_save custom logic
6. Perform database operation
7. Handle audit record creation
8. Execute after_save custom logic
9. Process notifications if enabled

#### retrieve($id = -1, $encode = true, $deleted = true)
**Loads bean data from database by ID**
- Supports custom field joins for complete data retrieval
- Handles data encoding/decoding
- Populates all bean properties from database row
- Loads relationship data and custom fields
- Implements optimistic locking preparation
- Processes date/time fields for timezone conversion
- Returns null if record not found

**Parameters:**
- `$id`: Record ID to retrieve (-1 uses current bean's ID)
- `$encode`: Whether to HTML encode retrieved data
- `$deleted`: Whether to include soft-deleted records

#### mark_deleted($id)
**Implements soft delete functionality**
- Sets deleted flag to 1 instead of physically removing record
- Maintains referential integrity
- Preserves audit trail
- Handles relationship cleanup
- Supports recovery via mark_undeleted()

#### mark_undeleted($id)
**Restores soft-deleted records**
- Sets deleted flag back to 0
- Restores record to active state
- Used for data recovery operations

### Query Generation and List Operations

#### get_list($order_by = "", $where = "", $check_dates = false, $show_deleted = 0)
**Retrieves paginated list of records with optional filtering**
- Supports complex WHERE clauses
- Handles ORDER BY with multiple columns
- Implements ACL (Access Control List) filtering
- Processes custom field joins
- Returns array with 'list' (records) and 'row_count' (total)
- Optimized for large datasets with pagination

#### get_full_list($order_by = "", $where = "", $check_dates = false, $show_deleted = 0)
**Retrieves complete list without pagination**
- Similar to get_list() but returns all matching records
- Used for dropdown population and data export
- Should be used carefully with large datasets
- Returns simple array of bean objects

#### create_new_list_query($order_by, $where, $filter = array(), $params = array(), $show_deleted = 0, $join_type = '', $return_array = false, $parentbean = null, $singleSelect = false, $ifListForExport = false)
**Generates optimized SQL queries for list operations**
- Constructs complex SELECT statements with JOINs
- Handles ACL security filtering
- Processes custom field integration
- Supports relationship-based filtering
- Optimizes performance with selective field loading
- Returns SQL query string or array components

### Relationship Management

#### load_relationship($rel_name)
**Dynamically loads relationship objects for link fields**
- Initializes Link2 objects for relationship management
- Supports one-to-many, many-to-many, and one-to-one relationships
- Handles custom relationship definitions
- Required before accessing relationship data
- Returns boolean success status

#### get_linked_beans($field_name, $bean_name = '', $sort_array = array(), $begin_index = 0, $end_index = -1, $deleted = 0, $optional_where = "")
**Retrieves related records through relationships**
- Returns array of related bean objects
- Supports pagination with begin/end indices
- Allows additional WHERE clause filtering
- Handles soft-deleted related records
- Used for subpanel data population

#### save_relationship_changes($is_update, $exclude = array())
**Persists relationship modifications to database**
- Processes relationship field changes
- Handles many-to-many relationship table updates
- Manages foreign key references
- Excludes specified relationships from processing
- Called automatically during save() operation

### Advanced Query Features

#### get_union_related_list($parentbean, $query, $row_offset, $limit, $max_per_page, $where, $subpanel_def, $get_count_only = false, $queryArray = array())
**Handles complex subpanel queries with UNION operations**
- Combines multiple relationship queries
- Supports datasource functions for custom data
- Implements pagination for related lists
- Handles collection relationships
- Used extensively in subpanel displays

#### build_related_list($query, &$template, $row_offset = 0, $limit = -1)
**Constructs queries for related record lists**
- Processes relationship-based WHERE clauses
- Handles template object population
- Supports pagination and limiting
- Used for one-to-many relationship displays

## Internal API Operations

### Field Processing and Validation

#### populateFromRow($row)
**Populates bean properties from database row array**
- Maps database columns to bean properties
- Handles data type conversions
- Processes custom field values
- Manages encrypted field decryption
- Triggers field-specific processing hooks

#### convertField($fieldValue, $fieldDef)
**Converts field values based on field definitions**
- Handles data type-specific conversions
- Processes enum and multienum fields
- Manages date/time formatting
- Handles currency field conversions
- Applies field-level encryption/decryption

#### populateDefaultValues($force = false)
**Sets default values for fields based on vardefs**
- Processes 'default' property in field definitions
- Handles function-based defaults
- Supports date/time calculations
- Respects force parameter for overriding existing values

#### fixUpFormatting()
**Applies data formatting and cleanup**
- Processes currency fields
- Handles date/time formatting
- Manages text field encoding
- Legacy compatibility method

#### cleanBean()
**Security method to prevent XSS attacks**
- Strips potentially dangerous HTML/JavaScript
- Processes all string fields
- Maintains data integrity while ensuring security
- Called automatically before save operations

### Custom Field Integration

#### setupCustomFields($module_name)
**Initializes custom field definitions for the module**
- Creates DynamicField object
- Loads custom field metadata
- Integrates custom fields with standard vardefs
- Required for custom field functionality

#### hasCustomFields()
**Checks if module has custom field definitions**
- Returns boolean indicating custom field presence
- Used for optimization in field processing
- Determines if custom table joins are needed

#### getCustomJoin($expandedList = false, $includeRelates = false, &$where = false)
**Generates JOIN clauses for custom field tables**
- Constructs SQL JOIN statements for custom fields
- Handles relate field integration
- Supports expanded field lists
- Returns array with 'select' and 'join' components

### Auditing System

#### is_AuditEnabled()
**Determines if auditing is enabled for this bean type**
- Checks global audit settings
- Verifies module-specific audit configuration
- Returns boolean audit status

#### getAuditEnabledFieldDefinitions()
**Returns array of fields that have auditing enabled**
- Filters field definitions for audit-enabled fields
- Used for audit record creation
- Supports field-level audit control

#### auditBean($isUpdate)
**Creates audit records for bean changes**
- Compares current values with fetched_row
- Identifies changed fields
- Creates audit table entries
- Prevents duplicate audit records

#### createAuditRecord(array $auditDataChanges)
**Processes array of changes and creates audit entries**
- Saves individual field changes to audit table
- Updates fetched_row to prevent duplicates
- Manages audit record metadata

### Caching and Performance

#### Static Field Caching
**Implements static caching for field metadata**
- Caches vardefs across requests
- Reduces database queries for field definitions
- Improves performance for repeated operations

#### Bean Registration
**Integrates with BeanFactory for instance management**
- Registers bean instances during save operations
- Enables cross-bean access and caching
- Supports transaction-level bean sharing

### Security and Access Control

#### isOwner($user_id)
**Determines if specified user owns this record**
- Checks assigned_user_id field
- Used for access control decisions
- Supports ownership-based security

#### buildAccessWhere($view, $user = null)
**Constructs WHERE clauses for ACL filtering**
- Implements row-level security
- Processes team-based access control
- Handles role-based permissions
- Used in list and detail queries

#### getOwnerWhere($user_id)
**Generates ownership-based WHERE clause**
- Creates SQL conditions for user ownership
- Used in access control implementations
- Supports assigned_user_id filtering

## External API Integration

### Email System Integration

#### hasEmails()
**Checks if bean supports email address functionality**
- Returns boolean indicating email capability
- Used for email-related processing decisions

#### Email Address Processing
**Handles multiple email addresses for beans**
- Processes primary and non-primary email addresses
- Integrates with EmailAddress module
- Manages email validation and formatting

### Import/Export Support

#### get_import_required_fields()
**Returns array of required fields for import operations**
- Identifies mandatory fields from vardefs
- Used by import module for validation
- Supports required field checking

#### get_importable_fields()
**Returns array of fields available for import**
- Filters fields suitable for import operations
- Excludes auto-generated and calculated fields
- Used by import mapping interface

#### toArray($dbOnly = false, $stringOnly = false, $upperKeys = false)
**Converts bean to associative array**
- Exports bean data for various purposes
- Supports database-only field filtering
- Handles string-only conversion
- Used for API responses and data export

### Workflow and Business Logic

#### call_custom_logic($event, $arguments = null)
**Executes custom business logic hooks**
- Triggers logic_hooks at specified events
- Supports before_save, after_save, before_retrieve, after_retrieve
- Passes contextual arguments to hook functions
- Enables custom business rule implementation

**Common Events:**
- before_save: Pre-save processing and validation
- after_save: Post-save operations and notifications
- before_retrieve: Pre-retrieval modifications
- after_retrieve: Post-retrieval field processing
- before_delete: Pre-deletion cleanup
- after_delete: Post-deletion operations

### Notification System

#### get_notification_recipients()
**Identifies users who should receive notifications**
- Processes assignment changes
- Handles escalation rules
- Returns array of User objects
- Used for workflow notifications

#### send_assignment_notifications($notify_user, $admin)
**Sends email notifications for assignment changes**
- Creates notification emails
- Handles user preferences
- Processes notification templates
- Integrates with email delivery system

#### create_notification_email($notify_user)
**Generates notification email content**
- Uses email templates
- Populates dynamic content
- Handles user-specific formatting
- Returns formatted email array

## UI Functionality

### List View Support

#### get_list_view_data()
**Prepares data for list view display**
- Formats fields for list presentation
- Handles link generation
- Processes custom list fields
- Returns formatted array for templates

#### get_list_view_array()
**Alternative method for list view data preparation**
- Similar to get_list_view_data() with different format
- Used by specific UI components
- Handles legacy compatibility

#### fill_in_additional_list_fields()
**Populates additional fields needed for list views**
- Loads calculated fields
- Processes display-only fields
- Handles virtual field population

### Detail View Support

#### get_detail($user_id, $admin = false, $developer = false)
**Retrieves record for detail view display**
- Loads complete record data
- Handles ACL checking
- Processes related field population
- Returns populated bean or null

#### fill_in_additional_detail_fields()
**Loads additional fields for detail view**
- Populates relationship fields
- Loads custom field values
- Processes calculated fields
- Handles parent field relationships

#### get_summary_text()
**Returns summary text for record identification**
- Generates human-readable record description
- Used in dropdowns and relationship displays
- Should be overridden in subclasses for specific formatting

### Search and Filter Support

#### retrieve_by_string_fields($fields_array, $encode = true, $deleted = true)
**Retrieves records matching field criteria**
- Searches by multiple field values
- Supports exact match operations
- Returns first matching record
- Used for duplicate checking and data validation

#### get_where($fields_array, $deleted = true)
**Generates WHERE clause from field array**
- Converts associative array to SQL WHERE clause
- Handles proper quoting and escaping
- Supports deleted record filtering
- Used in search operations

#### build_generic_where_clause($value)
**Creates generic WHERE clause for text search**
- Generates LIKE clauses for text fields
- Handles multiple field searching
- Used in global search functionality

## File Management

### File Field Support

#### haveFiles()
**Checks if bean has file upload fields**
- Scans field definitions for file types
- Returns boolean indicating file field presence
- Used for file processing decisions

#### getFiles()
**Returns array of uploaded files for this record**
- Retrieves file information from upload directory
- Returns array of file metadata
- Used for file listing and management

#### getFilesFields($resetCache = false)
**Returns array of field definitions for file fields**
- Caches file field definitions
- Supports cache reset for dynamic updates
- Used for file field processing

#### deleteFiles()
**Removes uploaded files when record is deleted**
- Cleans up file system when records are removed
- Handles multiple file fields
- Maintains file system integrity

#### deleteFileDirectory()
**Removes entire file directory for this record**
- Cleans up record-specific file directories
- Used during complete record deletion
- Handles nested directory structures

## Performance Optimizations

### Query Optimization
- Selective field loading based on view context
- Intelligent JOIN generation for custom fields
- Pagination support for large datasets
- Index utilization for common queries

### Caching Strategies
- Static caching of vardefs and field definitions
- Bean instance caching through BeanFactory
- Relationship metadata caching

### Memory Management
- Efficient object cloning with __clone() method
- Proper cleanup of large data structures
- Memory-conscious list processing

## Integration Points

### Module System Integration
- Seamless integration with SuiteCRM module architecture
- Support for custom module development
- Consistent API across all modules

### Database Layer Integration
- Works with multiple database types through DBManager
- Handles database-specific optimizations
- Supports transaction management

### Security Framework Integration
- ACL system integration for access control
- Team-based security support
- Role-based permission handling

### Workflow Engine Integration
- Logic hooks system for custom business rules
- Workflow process integration
- Assignment rule processing

## Error Handling and Logging

### Exception Management
- Graceful error handling throughout class
- Proper logging of critical operations
- Fallback mechanisms for common failures

### Debug Support
- Extensive logging for development and debugging
- Query logging for performance analysis
- State tracking for troubleshooting

## Best Practices

### Extending SugarBean
- Override specific methods rather than modifying core class
- Use logic hooks for custom business logic
- Implement proper field definitions in vardefs

### Performance Considerations
- Use get_list() instead of get_full_list() for large datasets
- Implement proper indexing for custom fields
- Cache expensive operations when possible

### Security Guidelines
- Always validate input data
- Use proper ACL checking
- Implement field-level security where needed

### Data Integrity
- Use transactions for complex operations
- Implement proper relationship management
- Maintain audit trails for sensitive data

---

*This documentation covers the comprehensive functionality of SugarBean.php, the foundational class for all business objects in SuiteCRM. The class provides a complete data access layer with CRUD operations, relationship management, security, auditing, and UI integration capabilities.* 