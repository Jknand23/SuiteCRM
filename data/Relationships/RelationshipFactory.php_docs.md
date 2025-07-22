# RelationshipFactory.php Documentation

/**
 * @fileoverview Singleton factory for creating and managing specialized relationship objects with caching support
 * @package SuiteCRM.Data.Relationships
 * @copyright SugarCRM Inc. 2004-2013, SalesAgility Ltd. 2011-2018
 * @license GNU Affero General Public License version 3
 */

## Overview

SugarRelationshipFactory is a singleton factory class that manages the creation of specialized relationship objects in SuiteCRM. It implements comprehensive caching mechanisms for relationship definitions and provides type-specific relationship object instantiation based on relationship metadata. This factory is the central point for relationship object creation, used extensively by Link2 objects and other relationship management components.

The factory supports the creation of different relationship implementations: Many-to-Many (M2M), One-to-Many (One2M), One-to-One (One2One), and specialized variants like EmailAddressRelationship.

## Database Operations

### Relationship Cache Management

#### `buildRelationshipCache()`
Constructs comprehensive relationship cache from module definitions and dictionary metadata.

**Process:**
1. **Global State Management**: Sets `$buildingRelCache` flag to prevent recursion
2. **Module Loading**: Includes TableDictionary.php and modules.php for base definitions
3. **Vardef Loading**: Loads all module vardefs with `ignore_rel_calc_fields` flag initially
4. **Dictionary Processing**: Extracts relationships from global `$dictionary` array
5. **Cache Generation**: Creates atomic cache file with exported relationship data
6. **Secondary Loading**: Reloads vardefs to populate calculated relationship fields

**Relationship Definition Assembly:**
```php
// Primary relationship entry
$relationships[$key] = array_merge(
    array('name' => $key), 
    (array) $def, 
    (array) $relDef
);

// Join table field integration
if (!empty($relationships[$relKey]['join_table']) && 
    isset($dictionary[$relationships[$relKey]['join_table']]['fields'])) {
    $relationships[$relKey]['fields'] = 
        $dictionary[$relationships[$relKey]['join_table']]['fields'];
}
```

**Performance Optimizations:**
- Atomic file writing via `sugar_file_put_contents_atomic()`
- Directory creation with `sugar_mkdir()` for cache location
- Two-phase vardef loading for calculated fields

#### `loadRelationships()`
Loads relationship definitions from cache file or rebuilds cache if needed.

**Loading Strategy:**
1. **Cache Check**: Validates existence of cache file via `getCacheFile()`
2. **Cache Loading**: Includes cache file and assigns to `$this->relationships`
3. **Fallback**: Calls `buildRelationshipCache()` if cache missing

#### Cache File Management

**`getCacheFile()`**: Returns standardized cache file path via `sugar_cached('Relationships/relationships.cache.php')`

**`deleteCache()`**: Static method for cache invalidation, removes cache file if exists

**`rebuildCache()`**: Static method to force cache rebuild via singleton instance

## Internal API Calls

### Relationship Object Creation

#### `getRelationship($relationshipName)`
Primary factory method for creating specialized relationship objects based on type and configuration.

**Type Detection Logic:**
```php
$type = isset($def['true_relationship_type']) 
    ? $def['true_relationship_type'] 
    : $def['relationship_type'];
```

**Object Creation by Type:**

**Many-to-Many Relationships:**
- **EmailAddressRelationship**: Special handling for EmailAddresses module relationships
- **M2MRelationship**: Standard many-to-many implementation
- Requires: `data/Relationships/M2MRelationship.php` or `data/Relationships/EmailAddressRelationship.php`

**One-to-Many Relationships:**
- **One2MBeanRelationship**: Bean-based relationships (no join table/keys)
- **One2MRelationship**: Table-based relationships with proper join configuration
- Detection: Empty `true_relationship_type`, `table`/`join_table`, or `join_key_rhs`

**One-to-One Relationships:**
- **One2OneBeanRelationship**: Bean-based one-to-one relationships
- **One2OneRelationship**: Table-based one-to-one relationships
- Requires: `data/Relationships/One2OneBeanRelationship.php` or `data/Relationships/One2OneRelationship.php`

**Error Handling:**
- Returns `false` for missing relationship definitions
- Logs errors for unknown relationship names
- Logs fatal errors for unrecognized relationship types

#### `getRelationshipDef($relationshipName)`
Retrieves raw relationship definition array without object instantiation.

**Return Values:**
- Relationship definition array if found
- `false` if relationship doesn't exist
- Logs error for missing relationships

### Singleton Pattern Implementation

#### `getInstance()`
Static method implementing singleton pattern for factory access.

**Implementation:**
```php
if (is_null(self::$rfInstance)) {
    self::$rfInstance = new self();
}
return self::$rfInstance;
```

**Benefits:**
- Ensures single factory instance across application
- Prevents multiple cache loading operations
- Maintains relationship definition consistency

#### `__construct()`
Protected constructor ensuring singleton pattern enforcement.

**Initialization:**
- Calls `loadRelationships()` to populate relationship cache
- Prevents external instantiation

### Static Cache Management

#### `rebuildCache()`
Static convenience method for forcing cache reconstruction.

**Usage:**
- Called during module installation/deployment
- Triggered by metadata changes
- Used in development for relationship updates

#### `deleteCache()`
Static method for cache invalidation without rebuild.

**Use Cases:**
- Pre-deployment cache clearing
- Development environment resets
- Troubleshooting relationship issues

## Global Dependencies

### Required Globals
- `$beanList`: Module name to bean class mappings for vardef loading
- `$dictionary`: Global dictionary containing relationship definitions
- `$buildingRelCache`: Recursion prevention flag during cache building

### File Dependencies
- `modules/TableDictionary.php`: Core table and relationship definitions
- `include/modules.php`: Module registry and bean list definitions
- `data/Relationships/SugarRelationship.php`: Base relationship class
- Type-specific relationship classes based on instantiation

### Module Dependencies
- `VardefManager`: Module vardef loading and management
- `BeanFactory`: Object name resolution for modules
- File system functions: `sugar_mkdir()`, `sugar_file_put_contents_atomic()`, `sugar_cached()`

## Cache Architecture

### Cache Structure
The relationship cache is a PHP file containing an exported array of relationship definitions:

```php
<?php 
$relationships = array(
    'relationship_name' => array(
        'name' => 'relationship_name',
        'relationship_type' => 'many-to-many',
        'join_table' => 'join_table_name',
        'join_key_lhs' => 'left_key',
        'join_key_rhs' => 'right_key',
        'lhs_module' => 'ModuleA',
        'rhs_module' => 'ModuleB',
        // ... additional metadata
    ),
    // ... more relationships
);
```

### Cache Location
- **Path**: `cache/Relationships/relationships.cache.php`
- **Generation**: Automatic on first access or manual rebuild
- **Invalidation**: Manual deletion or system cache clearing

### Cache Benefits
- **Performance**: Eliminates repeated dictionary parsing
- **Consistency**: Single source of relationship definitions
- **Reliability**: Atomic file operations prevent corruption

## Error Handling

### Validation
- Validates relationship existence before object creation
- Checks required relationship metadata fields
- Validates relationship type definitions

### Logging
- **Error Level**: Missing relationship definitions
- **Fatal Level**: Unknown relationship types
- **Debug Level**: Cache operations and rebuilds

### Return Values
- Returns `false` for failed operations
- Returns proper relationship objects for successful operations
- Maintains consistent error reporting across methods

## Performance Considerations

### Memory Management
- Singleton pattern prevents multiple factory instances
- Lazy loading of relationship cache
- Efficient array operations for definition lookup

### Cache Strategy
- File-based caching reduces database queries
- Atomic cache operations prevent race conditions
- Strategic cache invalidation minimizes rebuilds

### Loading Optimization
- Two-phase vardef loading separates base definitions from calculated fields
- Conditional cache rebuilding based on file existence
- Bulk module processing reduces overhead

## Best Practices

### Factory Usage
- Always use `getInstance()` for factory access
- Cache relationship objects when multiple operations needed
- Handle false return values appropriately

### Cache Management
- Rebuild cache after relationship metadata changes
- Clear cache during development for immediate updates
- Monitor cache file permissions and accessibility

### Error Handling
- Check return values before using relationship objects
- Log relationship creation failures for debugging
- Implement fallback strategies for missing relationships

## Relationship Type Decision Matrix

| Condition | Relationship Type | Implementation Class |
|-----------|------------------|---------------------|
| Many-to-Many + EmailAddresses | many-to-many | EmailAddressRelationship |
| Many-to-Many (Standard) | many-to-many | M2MRelationship |
| One-to-Many + No Join Info | one-to-many | One2MBeanRelationship |
| One-to-Many + Join Info | one-to-many | One2MRelationship |
| One-to-One + No true_relationship_type | one-to-one | One2OneBeanRelationship |
| One-to-One + true_relationship_type | one-to-one | One2OneRelationship |

## Thread Safety
The factory implementation is not explicitly thread-safe. The singleton pattern and static caching rely on PHP's single-threaded execution model. For concurrent environments, additional synchronization mechanisms would be required.

## Development Workflow

### Adding New Relationship Types
1. Create relationship class extending SugarRelationship
2. Add type detection logic in `getRelationship()`
3. Include required file for new relationship class
4. Test with cache rebuild and validation

### Debugging Relationship Issues
1. Enable relationship logging in log settings
2. Delete cache and trigger rebuild
3. Verify relationship definitions in cache file
4. Check relationship object creation and type detection

### Performance Monitoring
- Monitor cache hit rates vs rebuilds
- Track relationship object creation times
- Analyze vardef loading performance during cache builds 