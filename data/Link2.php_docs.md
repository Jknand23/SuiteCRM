/**
 * @fileoverview Modern relationship management class that delegates work to specialized relationship classes. This is the preferred relationship interface in SuiteCRM, superseding the original Link class with improved architecture and better integration with the factory pattern.
 * @package SuiteCRM.Data
 * @copyright SugarCRM Inc. 2004-2013, SalesAgility Ltd. 2011-2018
 * @license GNU Affero General Public License version 3
 */

## Overview

Link2 represents the modern, preferred approach to relationship management in SuiteCRM, providing a cleaner interface for manipulating relationships between SugarBean objects. Unlike the original Link class which implements relationship logic directly, Link2 follows the delegation pattern, deferring complex relationship operations to specialized relationship classes from the RelationshipFactory while maintaining a simplified API for bean-level relationship manipulation.

**Link vs Link2 Usage:**
- **Link2 (Preferred)**: Modern implementation using delegation pattern, better memory management, cleaner API
- **Link (Legacy)**: Original implementation with direct SQL generation, still used in legacy modules
- **Migration**: New development should use Link2; existing Link usage can be gradually migrated

**Key Integration Points:**
- **RelationshipFactory**: Creates specialized relationship objects (M2MRelationship, One2MRelationship, etc.)
- **BeanFactory**: Uses `BeanFactory::getBean()` for all bean instantiation and caching
- **SugarBean**: Integrates with bean relationship loading via `load_relationship()`
- **SugarRelationship Hierarchy**: Delegates to relationship objects extending SugarRelationship

This class represents a relationship from a single bean's perspective and serves as a facade for the underlying relationship implementation, providing type-safe operations and improved memory management.

## Database Operations

### Data Loading and Querying

#### `load($params = array())`
Loads relationship data from the database into the link object's internal storage.

**Process:**
1. Executes query via `query()` method
2. Stores raw relationship data in `$this->rows`
3. Clears bean cache to force fresh loading
4. Sets loaded flag to true

**Parameters:**
- `$params` (array): Query parameters passed to underlying relationship

#### `query($params)`
Performs database queries through the underlying relationship object.

**Supported Parameters:**
- `where` (array): Search conditions with lhs_field, operator, rhs_value
- `limit` (int): Maximum number of rows to return
- `deleted` (int): Filter for deleted records (1=deleted only, 0=active only)

**Example:**
```php
$params = array(
    'where' => array(
        'lhs_field' => 'source',
        'operator' => '=',
        'rhs_value' => 'external'
    ),
    'limit' => 100
);
```

**Delegation Pattern:**
- Delegates to `$this->relationship->load($this, $params)`
- Returns null if relationship object lacks load() method
- Logs fatal errors for incomplete relationship implementations

#### `get($params = array())`
Retrieves array of related record IDs with optional lazy loading.

**Behavior:**
- Triggers `load()` if data not already loaded
- Returns `array_keys($this->rows)` for ID extraction
- Supports all query parameters via load operation

### Bean Management

#### `getBeans($params = array())`
Retrieves fully instantiated SugarBean objects for related records.

**Loading Strategy:**
1. **Temporary Bean Integration**: Merges `$tempBeans` for unsaved relationships
2. **Lazy Loading**: Loads data if not cached or parameters specified
3. **Bean Instantiation**: Uses `BeanFactory::getBean()` for object creation
4. **Caching**: Stores results in `$this->beans` for future access

**Parameters:**
- Same as `query()` method
- When params provided, performs fresh query without caching

**Memory Optimization:**
- Reuses cached beans when available
- Clears temporary beans after successful operations
- Handles bean instantiation failures gracefully

**Return Value:**
- Array of SugarBean objects indexed by ID
- Empty array if no relationships exist

## Internal API Calls

### Relationship Metadata Access

#### `getRelatedModuleName()`
Determines the module name on the opposite side of the relationship.

**Logic:**
- Uses `getSide()` to determine current position
- Returns `relationship->getRHSModule()` for LHS links
- Returns `relationship->getLHSModule()` for RHS links

#### `getRelatedModuleLinkName()`
Retrieves the link field name used on the related module side.

**Usage:**
- Enables bidirectional relationship navigation
- Supports relationship validation and consistency checks

#### `getType()`
Returns relationship cardinality from the current link's perspective.

**Return Values:**
- `"many"`: Multiple records can be related (many-to-many, one-to-many from many side)
- `"one"`: At most one record can be related (one-to-one, many-to-one from many side)

**Implementation:**
```php
switch ($this->relationship->type) {
    case REL_MANY_MANY: return 'many';
    case REL_ONE_ONE: return 'one';
    case REL_ONE_MANY: return $this->getSide() == REL_LHS ? 'many' : 'one';
}
```

### Side Detection

#### `getSide()`
Determines whether the current bean represents the left-hand side (LHS) or right-hand side (RHS) of the relationship.

**Detection Strategy:**
1. **Relationship Object Method**: Compares link name and module against relationship metadata
2. **Vardef Analysis**: Examines `side` definition in field configuration
3. **ID Name Matching**: Matches `id_name` against relationship join keys

**Return Values:**
- `REL_LHS`: Current bean is on left side of relationship
- `REL_RHS`: Current bean is on right side of relationship
- `null`: Unable to determine side (logs error)

**Side Determination Logic:**
```php
// Primary method: relationship object comparison
if ($this->relationship->getLHSLink() == $this->name && 
    $this->relationship->getLHSModule() == $this->focus->module_name) {
    return REL_LHS;
}

// Fallback: vardef examination
if ($this->def['side'] == 'left') {
    return REL_LHS;
}
```

### Relationship Operations

#### `add($rel_keys, $additional_values = array())`
Creates new relationships between the focus bean and specified related records.

**Parameters:**
- `$rel_keys` (array|mixed): Array of IDs or SugarBean objects to relate
- `$additional_values` (array): Additional field values for relationship tables

**Process:**
1. **Bean Resolution**: Converts IDs to SugarBean objects via `getRelatedBean()`
2. **Validation**: Ensures both focus and related beans have valid IDs
3. **Delegation**: Calls `relationship->add()` with proper parameter order based on side
4. **Memory Management**: Removes temporary beans after processing

**Return Values:**
- `true`: All relationships created successfully
- `array`: Failed record IDs if any operations failed
- `false`: Critical failure (missing bean IDs)

#### `remove($rel_keys)`
Removes existing relationships between the focus bean and specified related records.

**Process:**
- Similar to `add()` but calls `relationship->remove()`
- Handles bean loading and validation
- Returns success/failure status with failed IDs

#### `delete($id, $related_id = '')`
Removes relationships with support for bulk deletion.

**Modes:**
- **Single Relationship**: When `$related_id` provided, removes specific relationship
- **Bulk Deletion**: When `$related_id` empty, removes all relationships via `removeAll()`

### Query Generation

#### `getJoin($params, $return_array = false)`
Generates SQL JOIN clauses for relationship queries.

**Parameters:**
- `join_table_link_alias`: Alias for relationship join table (M2M)
- `join_table_alias`: Alias for final joined table
- `$return_array`: Return components separately vs concatenated string

**Delegation:**
- Forwards to `relationship->getJoin($this, $params, $return_array)`
- Maintains consistent interface across relationship types

#### `getQuery($params = array())`
Generates SQL queries for retrieving related record IDs.

**Usage:**
- Used internally by `load()` and `query()` methods
- Supports same parameters as query operations

#### `getSubpanelQuery($params = array(), $return_array = false)`
Specialized query generation for subpanel display contexts.

**Features:**
- Optimized for subpanel requirements
- Handles role filtering via `ignore_role` parameter
- Different join strategy for many-to-many relationships

### Bean Caching and State Management

#### `beansAreLoaded()`
Checks whether related beans have been loaded into memory.

**Return Value:**
- `true`: Beans are cached in `$this->beans` array
- `false`: Beans need to be loaded from database

#### `addBean($bean)` / `removeBean($bean)`
Manual bean cache management for relationship implementations.

**Usage:**
- Primarily used by relationship classes
- `addBean()`: Adds to `tempBeans` or `beans` based on load state
- `removeBean()`: Removes from both bean and row caches

### Utility Methods

#### `loadedSuccesfully()`
Validates that the relationship object was properly initialized.

**Return Value:**
- `true`: Relationship object exists and is valid
- `false`: Relationship initialization failed

#### `getFocus()`
Returns the parent SugarBean object that owns this link.

#### `getRelatedFields()` / `getRelatedField($name)`
Accesses relationship field definitions from vardef configuration.

**Related Fields:**
- Additional fields stored in relationship tables (especially join tables)
- Configured via `rel_fields` in link field definitions

#### `getRelationshipObject()`
Provides direct access to the underlying relationship implementation object.

### Magic Methods

#### `__get($name)` / `__set($name, $val)`
Provides backward compatibility and convenient property access.

**Special Properties:**
- `relationship_type`: Returns relationship type constant
- `_relationship`: Returns relationship object
- `beans`: Triggers lazy loading of related beans
- `rows`: Triggers lazy loading of relationship data

## Global Dependencies

### Required Classes
- `SugarRelationshipFactory`: Creates relationship objects
- `RelationshipFactory`: Alternative relationship factory
- `BeanFactory`: Creates SugarBean instances
- `VardefManager`: Loads link field definitions
- `LoggerManager`: Logging functionality

### Relationship Constants
- `REL_LHS`: Left-hand side identifier
- `REL_RHS`: Right-hand side identifier  
- `REL_MANY_MANY`: Many-to-many relationship type
- `REL_ONE_ONE`: One-to-one relationship type
- `REL_ONE_MANY`: One-to-many relationship type

### Module Dependencies
- `data/Relationships/RelationshipFactory.php`: Factory class inclusion
- Module vardef definitions for link field configuration
- Global dictionary for relationship metadata

## Error Handling

### Validation
- Validates relationship object initialization
- Checks bean ID existence before operations
- Validates bean instantiation success
- Logs errors for incomplete relationship implementations

### Graceful Degradation
- Returns false/null for failed operations
- Continues processing after individual failures
- Provides detailed failure arrays for batch operations

### Logging
- Fatal errors for missing relationship methods
- Error logging for bean loading failures
- Warning logs for side detection issues

## Performance Considerations

### Memory Management
- Lazy loading prevents unnecessary database queries
- Bean caching reduces repeated instantiation
- Temporary bean cleanup prevents memory leaks
- Efficient array handling for large result sets

### Query Optimization
- Delegates to specialized relationship implementations
- Supports parameter-based query filtering
- Provides optimized subpanel queries
- Enables join query generation for complex reports

### Caching Strategy
- Caches loaded relationship data in `rows` property
- Caches instantiated beans in `beans` property
- Maintains temporary beans for unsaved relationships
- Invalidates cache appropriately during modifications

## Best Practices

### Relationship Operations
- Always check `loadedSuccesfully()` before operations
- Use bean objects instead of IDs when available for performance
- Handle return arrays for batch operations appropriately
- Clear temporary beans to prevent memory issues

### Performance Optimization
- Load relationships with parameters when subset needed
- Use `get()` for ID-only operations vs `getBeans()` for full objects
- Leverage join queries for reporting rather than individual bean loading

### Error Handling
- Check return values for false/null failures
- Process failure arrays for batch operations
- Use appropriate logging levels for different error types

## Migration from Link Class

### Key Differences
- **Delegation Pattern**: Work delegated to relationship objects vs internal implementation
- **Cleaner API**: Simplified method signatures and consistent return values
- **Better Memory Management**: Improved caching and temporary bean handling
- **Type Safety**: Better parameter validation and bean object handling

### Compatibility
- Maintains similar method signatures for common operations
- Provides magic methods for backward compatibility
- Supports same relationship types and configurations
- Compatible with existing vardef definitions 