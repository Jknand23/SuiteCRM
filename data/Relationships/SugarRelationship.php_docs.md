# SugarRelationship.php Documentation

/**
 * @fileoverview Abstract base class defining the interface and common functionality for all relationship types in SuiteCRM. Provides the foundation for the relationship hierarchy including M2MRelationship, One2MRelationship, and other specialized relationship implementations.
 * @package SuiteCRM.Data.Relationships
 * @copyright SugarCRM Inc. 2004-2013, SalesAgility Ltd. 2011-2018
 * @license GNU Affero General Public License version 3
 */

## Overview

SugarRelationship is the abstract base class that defines the fundamental interface and shared functionality for all relationship types in SuiteCRM. It establishes the contract that concrete relationship implementations must follow while providing common database operations, logic hook management, and utility methods used across all relationship types.

**Concrete Implementations:**
- **M2MRelationship**: Many-to-many relationships using join tables (most common implementation)
- **One2MRelationship**: One-to-many relationships with foreign key references  
- **One2OneRelationship**: One-to-one relationships
- **EmailAddressRelationship**: Specialized many-to-many for email address handling
- **One2MBeanRelationship**: Bean-based one-to-many relationships
- **One2OneBeanRelationship**: Bean-based one-to-one relationships

**Factory Integration:**
- Created by `RelationshipFactory::getRelationship()` based on relationship type and configuration
- Used by `Link2` objects for relationship operations through delegation pattern
- Integrates with `BeanFactory` for bean instantiation during relationship operations

The class manages bidirectional relationships between modules through links, supports role-based filtering, and provides comprehensive logic hook integration for relationship lifecycle events.

## Abstract Interface Definition

### Core Relationship Operations

#### `add($lhs, $rhs, $additionalFields = array())`
**Abstract method** for creating new relationships between left-hand side and right-hand side beans.

**Parameters:**
- `$lhs` (SugarBean): Left-hand side bean object
- `$rhs` (SugarBean): Right-hand side bean object  
- `$additionalFields` (array): Additional field values for relationship tables

**Implementation Requirements:**
- Validate bean objects and IDs
- Handle relationship-specific table operations
- Trigger appropriate logic hooks
- Return success/failure status

#### `remove($lhs, $rhs)`
**Abstract method** for removing existing relationships between beans.

**Parameters:**
- `$lhs` (SugarBean): Left-hand side bean object
- `$rhs` (SugarBean): Right-hand side bean object

**Implementation Requirements:**
- Soft delete relationship records (set deleted=1)
- Update date_modified timestamps
- Trigger before/after delete logic hooks
- Return boolean success indicator

#### `load($link, $params = array())`
**Abstract method** for loading relationship data for a specific link context.

**Parameters:**
- `$link` (Link2): Link object providing focus bean context
- `$params` (array): Query parameters for filtering and limiting results

**Implementation Requirements:**
- Execute database queries based on relationship type
- Apply role filtering when appropriate
- Return structured data for link consumption

### Query Generation Interface

#### `getQuery($link, $params = array())`
**Abstract method** for generating SQL queries to retrieve related record IDs.

**Parameters:**
- `$link` (Link2): Link object for context and configuration
- `$params` (array): Query parameters including where conditions, limits, deleted filtering

**Return Value:**
- String SQL query or array of query components
- Must support relationship-specific query logic

#### `getJoin($link, $params = array(), $return_array = false)`
**Abstract method** for generating SQL JOIN clauses for relationship queries.

**Parameters:**
- `$link` (Link2): Link object providing relationship context
- `$params` (array): Join parameters including table aliases and join types
- `$return_array` (bool): Return components separately vs concatenated string

**Implementation Requirements:**
- Generate appropriate JOIN syntax for relationship type
- Handle table aliases and join conditions
- Support both array and string return formats

#### `relationship_exists($lhs, $rhs)`
**Abstract method** for checking if a relationship already exists between two beans.

**Parameters:**
- `$lhs` (SugarBean): Left-hand side bean
- `$rhs` (SugarBean): Right-hand side bean

**Return Value:**
- Boolean indicating relationship existence
- Used for duplicate prevention and validation

#### `getRelationshipTable()`
**Abstract method** returning the primary table name for the relationship.

**Return Value:**
- String table name used for relationship storage
- May be join table for M2M or main table for direct relationships

## Database Operations

### Row Management

#### `addRow($row)`
Inserts new relationship records or updates existing ones based on duplicate detection.

**Process:**
1. **Duplicate Check**: Uses `checkExisting()` to find existing relationships
2. **Update Path**: Merges new values with existing record via `updateRow()`
3. **Insert Path**: Creates new relationship record with all field values
4. **Field Mapping**: Maps provided values to defined relationship fields

**Database Operations:**
```sql
-- New record insertion
INSERT INTO {relationship_table} (field1, field2, ...) VALUES ('value1', 'value2', ...)

-- Existing record update  
UPDATE {relationship_table} SET field1='value1', field2='value2' WHERE id='existing_id'
```

**Return Values:**
- `null`: New record was inserted
- `true`: Existing record was updated

#### `updateRow($id, $values)`
Updates specific relationship record with new field values.

**Parameters:**
- `$id` (string): Record ID to update
- `$values` (array): Field name/value pairs to update

**Process:**
1. Removes ID from values array to prevent overwrite
2. Constructs SET clause from field/value pairs
3. Executes UPDATE query with WHERE id clause

#### `removeRow($where)`
Soft deletes relationship records matching specified criteria.

**Parameters:**
- `$where` (array): Field/value pairs for WHERE clause construction

**Process:**
1. **Validation**: Returns false if no WHERE conditions provided
2. **Soft Delete**: Sets deleted=1 and updates date_modified
3. **Query Construction**: Builds WHERE clause from provided conditions

**SQL Pattern:**
```sql
UPDATE {relationship_table} 
SET deleted=1, date_modified='{current_timestamp}' 
WHERE field1='value1' AND field2='value2'
```

### Relationship Validation

#### `checkExisting($row)`
Checks for existing relationship records based on join keys and role filtering.

**Parameters:**
- `$row` (array): Row data containing join key values

**Validation Logic:**
1. **Key Validation**: Ensures both `join_key_lhs` and `join_key_rhs` values present
2. **Role Filtering**: Applies relationship role constraints via `getRoleWhere()`
3. **Deleted Filter**: Excludes soft-deleted records (deleted=0)

**Query Pattern:**
```sql
SELECT * FROM {relationship_table} 
WHERE {join_key_lhs}='{left_id}' 
AND {join_key_rhs}='{right_id}' 
{role_conditions} 
AND deleted=0
```

**Return Values:**
- `false`: No existing relationship found
- `array`: Existing relationship record data

#### `getRoleWhere($table = '', $ignore_role_filter = false)`
Generates WHERE clause conditions for relationship role filtering.

**Parameters:**
- `$table` (string): Table alias for role column qualification
- `$ignore_role_filter` (bool): Override role filtering when true

**Role Filtering Logic:**
- Applies when `relationship_role_column` and `relationship_role_column_value` defined
- Supports NULL value checking for empty role values
- Respects `ignore_role_filter` property and parameter override

**Generated Conditions:**
```sql
-- With role value
AND {table}.{role_column} = '{role_value}'

-- NULL role value  
AND {table}.{role_column} IS NULL
```

## Internal API Calls

### Metadata Access

#### `getRHSModule()` / `getLHSModule()`
Returns the module names for right-hand side and left-hand side of the relationship.

**Return Values:**
- String module names from relationship definition
- Used for bean instantiation and module validation

#### `getRHSLink()` / `getLHSLink()`
Returns the link field names used on each side of the relationship.

**Return Values:**
- String link names from relationship definition
- Enables bidirectional relationship navigation

#### `getFields()`
Returns array of field definitions for the relationship table.

**Return Value:**
- Array of field definitions with name, type, and other metadata
- Empty array if no custom fields defined
- Used for dynamic field handling in addRow operations

### Bulk Operations

#### `removeAll($link)`
Removes all relationships associated with a specific link's focus bean.

**Process:**
1. **Related Bean Retrieval**: Gets all related beans via `link->getBeans()`
2. **Iterative Removal**: Calls `remove()` for each related bean
3. **Side-Aware Processing**: Uses `link->getSide()` to determine parameter order
4. **Result Aggregation**: Returns combined success status

**Use Cases:**
- Bean deletion cleanup
- Mass relationship removal
- Link clearing operations

#### `removeById($rowID)`
Convenience method for removing relationships by record ID.

**Parameters:**
- `$rowID` (string): ID of relationship record to remove

**Implementation:**
- Delegates to `removeRow(array('id' => $rowID))`
- Provides simpler interface for ID-based removal

### Bean Resave Management

#### `addToResaveList($bean)`
**Static method** for queuing beans that need to be resaved after relationship operations.

**Parameters:**
- `$bean` (SugarBean): Bean object to queue for resaving

**Management Strategy:**
- Groups beans by module for efficient processing
- Prevents duplicate entries using bean ID as key
- Maintains static array across all relationship operations

#### `resaveRelatedBeans()`
**Static method** for processing queued bean resave operations.

**Process:**
1. **Global Flag**: Sets `$GLOBALS['resavingRelatedBeans']` to prevent recursion
2. **Save Filtering**: Skips deleted beans and beans currently in save operation
3. **Workflow Integration**: Preserves workflow alert IDs for in-save beans
4. **Cleanup**: Resets resave list and global flag after processing

**Use Cases:**
- Calculated field updates after relationship changes
- Workflow trigger processing
- Audit trail maintenance

### Logic Hook Integration

#### `callBeforeAdd($focus, $related, $link_name = '')`
Triggers `before_relationship_add` logic hooks for relationship creation.

#### `callAfterAdd($focus, $related, $link_name = '')`
Triggers `after_relationship_add` logic hooks after successful relationship creation.

#### `callBeforeDelete($focus, $related, $link_name = '')`
Triggers `before_relationship_delete` logic hooks before relationship removal.

#### `callAfterDelete($focus, $related, $link_name = '')`
Triggers `after_relationship_delete` logic hooks after relationship removal.

#### `getCustomLogicArguments($focus, $related, $link_name)`
Constructs standardized argument array for logic hook calls.

**Hook Arguments:**
- `id`: Focus bean ID
- `related_id`: Related bean ID
- `module`: Focus bean module
- `related_module`: Related bean module
- `related_bean`: Related bean object
- `link`: Link name being processed
- `relationship`: Relationship name

### Utility Methods

#### `getOptionalWhereClause($optional_array)`
Constructs additional WHERE clause conditions from parameter arrays.

**Required Fields:**
- `lhs_field`: Field name for condition
- `operator`: SQL operator (=, !=, LIKE, etc.)
- `rhs_value`: Value to compare against

**Generated SQL:**
```sql
{lhs_field} {operator} '{rhs_value}'
```

#### `isParentRelationship()`
Identifies flex/parent relationships used for polymorphic associations.

**Detection Criteria:**
- `relationship_role_column` = 'parent_type'
- `rhs_key` = 'parent_id'
- Role column and value are defined

**Return Value:**
- `true`: This is a parent/flex relationship
- `false`: Standard relationship type

### Magic Methods

#### `__get($name)`
Provides dynamic property access for relationship metadata.

**Special Properties:**
- `relationship_type`: Returns relationship type constant
- `relationship_name`: Returns relationship name
- `lhs_module` / `rhs_module`: Module names
- `lhs_table` / `rhs_table`: Table names
- `list_fields`: Array of core relationship fields

**Fallback Behavior:**
1. Check relationship definition array (`$this->def[$name]`)
2. Handle special property mappings
3. Return object property if exists
4. Return empty string for undefined properties

## Global Dependencies

### Required Constants
- `REL_LHS`: Left-hand side identifier ('LHS')
- `REL_RHS`: Right-hand side identifier ('RHS')  
- `REL_BOTH`: Both sides identifier ('BOTH_SIDES')
- `REL_MANY_MANY`: Many-to-many type ('many-to-many')
- `REL_ONE_MANY`: One-to-many type ('one-to-many')
- `REL_ONE_ONE`: One-to-one type ('one-to-one')

### File Dependencies
- `modules/TableDictionary.php`: Relationship metadata definitions
- `data/BeanFactory.php`: Bean instantiation and management

### Database Dependencies
- `DBManagerFactory::getInstance()`: Database connection management
- Relationship tables must include standard fields: `id`, `deleted`, `date_modified`
- Join key fields as defined in relationship metadata

### Class Properties

#### Core Properties
- `$def`: Relationship definition array containing all metadata
- `$lhsLink` / `$rhsLink`: Link names for each side of relationship
- `$type`: Relationship type constant (many-to-many, one-to-many, one-to-one)
- `$name`: Relationship identifier name

#### Behavior Modifiers
- `$ignore_role_filter`: Disables role-based filtering when true
- `$self_referencing`: Indicates if LHS and RHS modules are identical

#### Static Properties
- `$beansToResave`: Static array for bean resave queue management

## Error Handling

### Validation
- Validates required fields for database operations
- Checks bean object validity before relationship operations
- Validates relationship metadata completeness

### Database Error Handling
- Database operations may throw exceptions on constraint violations
- Foreign key constraint errors for invalid bean references
- Duplicate key errors for unique relationship constraints

### Return Value Patterns
- Boolean return values for success/failure operations
- Array returns for data retrieval operations
- Null returns for insert operations vs true for updates

## Performance Considerations

### Database Optimization
- Uses prepared statements equivalent for value quoting
- Implements soft deletion to maintain referential integrity
- Bulk operations reduce individual query overhead

### Memory Management
- Static resave list prevents memory leaks during bulk operations
- Lazy loading of relationship data through Link2 objects
- Efficient array operations for metadata access

### Caching Strategy
- Relationship definitions cached by RelationshipFactory
- Bean objects cached by BeanFactory to reduce instantiation
- Link objects maintain loaded state to prevent redundant queries

## Best Practices

### Implementation Guidelines
- Always implement all abstract methods in concrete classes
- Use provided logic hook methods for consistent behavior
- Handle role filtering appropriately for relationship types
- Implement proper error handling and logging

### Performance Optimization
- Batch relationship operations when possible
- Use resave list for deferred bean processing
- Implement efficient query generation in concrete classes
- Cache frequently accessed relationship metadata

### Error Prevention
- Validate bean objects before relationship operations
- Check for existing relationships to prevent duplicates
- Use soft deletion to maintain data integrity
- Implement proper transaction handling for complex operations 