# Link.php Documentation

/**
 * @fileoverview Relationship management class for manipulating relationships between SugarBean object instances
 * @package SuiteCRM.Data
 * @copyright SugarCRM Inc. 2004-2013, SalesAgility Ltd. 2011-2018
 * @license GNU Affero General Public License version 3
 */

## Overview

The Link class is a fundamental component of SuiteCRM's relationship management system. It provides comprehensive functionality for creating, querying, and managing relationships between different bean objects, supporting all relationship cardinalities: one-to-one, one-to-many, many-to-one, and many-to-many.

The class handles the complex SQL generation and data manipulation required for relationship operations across different table structures, including direct foreign key relationships and join table-based many-to-many relationships.

## Database Operations

### Relationship Retrieval

#### `get($role = false)`
Primary method for retrieving related record IDs or full relationship data.

**Parameters:**
- `$role` (bool): If true, returns array of arrays with ID and role fields; if false, returns simple ID array

**Return Values:**
- **One-to-many/Many-to-many**: Array of related IDs or full records with role data
- **One-to-one/Many-to-one**: Single ID value or null if no relationship exists

**Database Behavior:**
- Executes optimized queries based on relationship type
- Handles role-based filtering for many-to-many relationships
- Supports distinct clauses when `add_distinct` is enabled

#### `getBeans($template, $sort_array, $begin_index, $end_index, $deleted, $optional_where)`
Retrieves related bean objects instead of just IDs by utilizing the parent bean's `build_related_list()` method.

**Process:**
1. Generates relationship query via `getQuery()`
2. Delegates to parent bean for object instantiation
3. Returns array of populated bean objects

### Query Generation

#### `getQuery($return_as_array, $sort_array, $deleted, $optional_where, $return_join, $bean_filter, $role, $for_subpanels)`
Generates optimized SQL queries for relationship retrieval based on relationship type and bean position.

**Query Construction Logic:**

**One-to-One/Many-to-One Relationships:**
- Simple SELECT with direct foreign key joins
- Handles role column filtering when present
- Applies deleted record filtering

**One-to-Many Relationships:**
- Queries child table with parent ID filter
- Different logic for LHS vs RHS bean positions
- Supports custom table exclusion from deleted filtering

**Many-to-Many Relationships:**
- Complex join table queries with dual key matching
- Handles relationship role columns and values
- Supports bidirectional relationship queries
- Implements side swapping for self-referencing relationships

**Parameters:**
- `$return_as_array` (bool): Returns query components separately vs concatenated string
- `$deleted` (int): Filter deleted records (0=active, 1=deleted, other=all)
- `$optional_where` (array): Additional WHERE conditions with lhs_field, operator, rhs_value
- `$bean_filter` (string): Custom bean ID filter override
- `$role` (string): Specific role field to include in SELECT
- `$for_subpanels` (bool): Modifies side swapping behavior for subpanel contexts

### Relationship Modification

#### `add($rel_keys, $additional_values)`
Creates new relationships between the current bean and specified related records.

**Relationship Type Handling:**

**One-to-One/One-to-Many:**
- Updates foreign key in target table via `_add_one_to_many_table_based()`
- Sets role column values when defined
- Handles bidirectional key assignment based on bean position

**Many-to-One:**
- Updates current bean's foreign key field via `_add_many_to_one_bean_based()`
- Creates temporary bean copy to avoid recursion
- Triggers bean save operation

**Many-to-Many:**
- Inserts records into join table via `_add_many_to_many()`
- Handles duplicate detection and resolution
- Supports true one-to-one relationships via join table removal
- Implements bidirectional insertion for self-referencing relationships

**Features:**
- Validates bean ID existence before processing
- Supports bulk key addition for compatible relationship types
- Handles role column assignment automatically
- Manages relationship side swapping for self-referencing cases
- Triggers custom logic hooks for relationship changes

#### `delete($id, $related_id)`
Removes relationships between specified records based on relationship type.

**Deletion Strategies:**

**One-to-One/One-to-Many:**
- Sets foreign keys to NULL in target table
- Updates date_modified timestamp
- Applies role column filtering when present

**Many-to-One:**
- No action required as parent record deletion handles cleanup
- Logs informational messages for tracking

**Many-to-Many:**
- Soft deletes join table records (sets deleted=1)
- Handles bidirectional deletion for reversible relationships
- Supports single relationship or bulk deletion

**Parameters:**
- `$id` (string): Primary bean ID
- `$related_id` (string): Specific related record ID for single relationship removal

### Join Table Management

#### `_add_many_to_many($add_values)`
Handles many-to-many relationship record insertion with duplicate detection.

**Process:**
1. Adds date_modified timestamp
2. Checks for existing relationships via `relationship_exists()`
3. Updates existing records or inserts new ones
4. Manages GUID generation for new records

#### `relationship_exists($table_name, $join_key_values)`
Detects duplicate relationships using alternate key definitions.

**Duplicate Detection:**
- Searches for alternate_key indices in table definition
- Falls back to primary key components if no alternate key found
- Builds WHERE clause from key field values
- Returns existing record ID for updates

#### Database Utility Methods

**`_insert_row($value_array)`**: Inserts new join table records with GUID generation
**`_update_row($value_array, $table_name, $where)`**: Updates existing relationship records
**`_delete_row($table_name, $key)`**: Soft deletes relationship records

## Internal API Calls

### Relationship Metadata

#### `getRelatedTableName()` / `getRelatedModuleName()`
Determines target table and module names based on bean position in relationship.

**Logic:**
- Uses `_get_bean_position()` to determine LHS/RHS placement
- Returns appropriate rhs_table/lhs_table and rhs_module/lhs_module
- Handles side swapping for self-referencing relationships

#### `getRelatedFields()` / `getRelatedField($name)`
Provides access to relationship field definitions from vardef configuration.

#### `getRelationshipObject()`
Returns the underlying relationship bean object for direct access to relationship metadata.

### Bean Position Detection

#### `_get_bean_position()`
Determines whether the current bean is on the left-hand side (LHS) or right-hand side (RHS) of the relationship.

**Detection Logic:**
1. Compares bean table/key with relationship lhs_table/lhs_key
2. Compares bean table/key with relationship rhs_table/rhs_key
3. Applies side swapping when `_swap_sides` is enabled
4. Returns boolean: true for LHS, false for RHS

#### `_is_self_relationship()`
Identifies self-referencing relationships where LHS and RHS tables are identical.

### Join Clause Generation

#### `getJoin($params, $return_array)`
Generates SQL JOIN clauses for relationship queries with extensive configuration options.

**Parameters Support:**
- `join_type`: INNER JOIN, LEFT JOIN, etc.
- `join_table_alias`: Alias for target table
- `left_join_table_alias` / `right_join_table_alias`: LHS/RHS table aliases
- `join_table_link_alias`: Alias for join table in many-to-many

**Join Generation by Type:**

**Direct Relationships (One-to-One, Many-to-One, Some One-to-Many):**
- Simple table joins using foreign key relationships
- Handles alias assignment and deleted record filtering

**One-to-Many (Bean is LHS):**
- Joins child table to parent using foreign key
- Supports table aliasing for complex queries

**Many-to-Many:**
- Dual join structure: parent → join table → child
- Handles role column filtering in join conditions
- Supports key override scenarios

### Metadata Access

#### `_get_link_table_definition($table_name, $def_name)`
Retrieves table definitions from global dictionary or metadata files.

**Search Locations:**
1. Global `$dictionary` array (pre-loaded definitions)
2. Standard metadata directory: `metadata/{relationship_name}MetaData.php`
3. Custom metadata directory: `custom/metadata/{relationship_name}MetaData.php`

**Definition Types:**
- `fields`: Column definitions and data types
- `indices`: Index definitions including alternate keys
- `relationships`: Relationship-specific metadata

#### `_get_alternate_key_fields($table_name)`
Extracts alternate key field definitions for duplicate detection.

**Search Priority:**
1. Alternate key indices from table definition
2. Join key fields from relationship metadata (legacy support)
3. Returns null if no suitable keys found

#### `_get_link_table_role_field($table_name)`
Identifies role column names in join tables by searching for fields containing '_role' suffix.

### Configuration Properties

#### Core Properties
- `$_relationship_name`: Relationship identifier for metadata lookup
- `$_bean`: Reference to parent bean object
- `$_relationship`: Relationship bean object containing metadata
- `$_bean_table_name` / `$_bean_key_name`: Bean table and key information

#### Behavior Modifiers
- `$ignore_role_filter`: Disables role-based filtering when true
- `$add_distinct`: Adds DISTINCT clause to queries when true
- `$_swap_sides`: Reverses LHS/RHS logic for self-referencing relationships
- `$_rhs_key_override`: Uses alternative key mapping in many-to-many joins
- `$_bean_filter_field`: Custom field for bean filtering instead of ID

#### Caching Properties
- `$_duplicate_key`: Stores ID of existing relationship for updates
- `$_duplicate_where`: Cached WHERE clause for duplicate detection

### Custom Logic Integration

#### Logic Hook Triggers
The class automatically triggers custom logic hooks for relationship changes:

**After Relationship Add (`after_relationship_add`):**
- Triggered on both sides of relationship
- Provides module names and record IDs
- Supports custom business logic execution

**After Relationship Delete (`after_relationship_delete`):**
- Triggered during relationship removal
- Includes related record context
- Enables cleanup operations and notifications

**Hook Arguments:**
- `id`: Primary record ID
- `related_id`: Related record ID
- `module`: Primary module name
- `related_module`: Related module name

### Utility Methods

#### `loadedSuccesfully()`
Validates that the relationship object was properly initialized with a valid relationship ID.

#### `_add_deleted_clause($deleted, $add_and, $prefix)`
Generates standardized deleted record filtering SQL with proper AND/OR logic and table prefixes.

#### `_add_optional_where_clause($optional_array, $add_and, $prefix)`
Constructs additional WHERE conditions from structured parameter arrays.

## Global Dependencies

### Required Globals
- `$dictionary`: Table and relationship definitions
- `$beanList`: Module to bean class mappings for logic hooks
- `$timedate`: Date/time formatting for database operations
- `$log`: Logging instance for debug and error messages

### Database Dependencies
- `DBManagerFactory::getInstance()`: Database connection management
- Database tables must include `deleted` and `date_modified` columns for soft deletion
- Join tables require `id` column for GUID primary keys

### Module Dependencies
- `modules/TableDictionary.php`: Table definition loading
- `BeanFactory`: Relationship bean instantiation
- Custom metadata files in `metadata/` and `custom/metadata/` directories

## Error Handling

### Validation
- Validates relationship object initialization before operations
- Checks bean ID existence before relationship modifications
- Validates relationship type compatibility with requested operations
- Logs warnings for missing metadata or invalid configurations

### Logging
- Debug logging for query generation and execution
- Fatal error logging for critical failures
- Informational logging for relationship state changes

### Return Values
- Returns `null` for invalid relationship configurations
- Returns `false` for failed relationship existence checks
- Returns empty arrays for no-data scenarios in multi-result operations

## Performance Considerations

### Query Optimization
- Generates type-specific queries to minimize database overhead
- Uses indexed fields (foreign keys) for optimal join performance
- Supports DISTINCT clauses only when necessary to reduce result set processing

### Memory Management
- Uses references for bean objects to prevent memory duplication
- Minimal object instantiation during query generation
- Efficient array handling for bulk operations

### Caching Strategy
- Caches duplicate detection WHERE clauses to avoid regeneration
- Leverages global dictionary for metadata access
- Stores relationship metadata in bean object for reuse

## Thread Safety
The Link class maintains instance state and is not thread-safe. Each relationship operation should use a dedicated Link instance tied to a specific bean and relationship context.

## Best Practices

### Relationship Operations
- Always verify `loadedSuccesfully()` before performing operations
- Use appropriate relationship methods based on cardinality requirements
- Handle return values appropriately for different relationship types

### Performance Optimization
- Enable `add_distinct` only when duplicate results are problematic
- Use specific relationship queries rather than generic bean retrieval when possible
- Leverage join generation for complex reporting queries

### Error Prevention
- Validate bean IDs before relationship modifications
- Check relationship type compatibility before bulk operations
- Use appropriate deletion methods (soft vs hard delete) based on requirements 