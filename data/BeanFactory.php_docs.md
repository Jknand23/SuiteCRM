# BeanFactory.php Documentation

/**
 * @fileoverview Factory class for creating and managing SugarBean objects with intelligent caching and memory management
 * @package SuiteCRM.Data
 * @copyright SugarCRM Inc. 2004-2013, SalesAgility Ltd. 2011-2018
 * @license GNU Affero General Public License version 3
 */

## Overview

BeanFactory is a core factory class that provides centralized creation and management of SugarBean objects throughout SuiteCRM. It implements intelligent caching mechanisms to prevent multiple database retrievals per request and manages memory usage by maintaining a limited cache of recently accessed beans.

## Database Operations

### Primary Bean Retrieval

#### `getBean($module, $id = null, $params = [], $deleted = true)`
Retrieves a SugarBean object by ID with caching support. This is the primary method for accessing bean objects throughout the system.

**Parameters:**
- `$module` (string): The module name for the bean type
- `$id` (string, optional): The record ID to retrieve. If null, creates new bean
- `$params` (array): Configuration parameters including 'encode' and 'deleted' flags
- `$deleted` (bool): Whether to include deleted records

**Behavior:**
- Checks cache first before database retrieval
- Creates new bean instance if ID is empty
- Uses `SugarBean::retrieve()` for database queries
- Automatically registers retrieved beans in cache
- Returns false if record not found or module invalid

**Cache Management:**
- Maintains last 10 loaded beans in memory via `$loadedBeans` array
- Tracks access frequency through `$touched` counters
- Implements LRU (Least Recently Used) eviction policy

#### `getReloadedBean($module, $id = null, $params = [], $deleted = true)`
Forces fresh retrieval from database, bypassing cache entirely. Used when current data state is critical.

**Use Cases:**
- Post-save operations requiring fresh data
- Concurrent modification scenarios
- Data integrity verification

#### `getShallowBean($module, $id = null, $params = [], $deleted = true)`
Creates shallow beans for relationship field population. These beans have incomplete relationship data but are suitable for basic field access.

**Behavior:**
- Checks main cache first, falls back to shallow cache
- Maintains separate `$shallowBeans` cache with 10-item limit
- Automatically unregisters from main cache to prevent conflicts
- Used internally by `SugarBean::fill_in_relationship_fields()`

### Bean Creation

#### `newBean($module)`
Factory method for creating new, empty bean instances. Equivalent to `getBean($module)` with no ID parameter.

## Internal API Calls

### Bean Class Management

#### `getBeanClass($module)`
Determines the appropriate bean class for a module, prioritizing custom implementations over core classes.

**Resolution Order:**
1. Custom bean class from `$customBeanList` global
2. Core bean class from `$beanList` global
3. Returns false if module not found

#### `loadBeanFile($module)`
Loads required PHP files for bean classes, handling both core and custom implementations.

**Process:**
1. Validates core bean file exists
2. Validates custom bean file if present
3. Includes core bean file via `require_once`
4. Includes custom bean file if available
5. Logs warnings/errors for missing files

### Metadata Retrieval

#### `getBeanMeta($module)`
Returns comprehensive metadata array for a module including:
- Module name and bean names (core and custom)
- Bean classes and object names
- File paths for class definitions

#### Supporting Methods:
- `getBeanName($module)` - Core bean class name
- `getCustomBeanName($module)` - Custom bean class name  
- `getObjectName($module)` - Core dictionary key
- `getCustomObjectName($module)` - Custom dictionary key
- `getBeanFile($module)` - Core bean file path
- `getCustomBeanFile($module)` - Custom bean file path

### Cache Management

#### `registerBean($module, $bean, $id = false)`
Registers a bean in the factory cache with intelligent memory management.

**Features:**
- Prevents double registration
- Implements LRU eviction when cache exceeds `$maxLoaded` (10 beans)
- Preserves beans currently being saved (`in_save` flag)
- Maintains access frequency tracking
- Uses circular index system for cache slots

#### `unregisterBean($module, $id)`
Removes a bean from cache, forcing fresh retrieval on next access.

**Use Cases:**
- Post-update operations
- Memory cleanup
- Cache invalidation

### Utility Methods

#### `initBeanRegistry($module)`
Initializes cache arrays for a module if not already present.

#### `convertParams($params)`
Converts legacy boolean parameters to modern array format for backward compatibility.

#### `hasEncodeFlag($params)` / `hasDeletedFlag($params, $deleted)`
Extract configuration flags from parameter arrays with appropriate defaults.

## Cache Performance Metrics

### Static Properties
- `$loadedBeans` - Main bean cache array indexed by module and ID
- `$shallowBeans` - Separate cache for shallow relationship beans
- `$maxLoaded` - Maximum beans in cache (default: 10)
- `$total` - Total beans loaded counter for index calculation
- `$loadOrder` - LRU tracking array with circular indexing
- `$touched` - Access frequency counters per bean
- `$hits` - Cache hit counter for performance monitoring

### Memory Management
The factory implements sophisticated memory management to prevent excessive memory usage:

1. **Cache Size Limiting**: Maximum 10 beans in primary cache
2. **LRU Eviction**: Removes least recently used beans when cache is full
3. **Save Protection**: Prevents eviction of beans currently being saved
4. **Access Tracking**: Maintains usage counters to optimize eviction decisions
5. **Circular Indexing**: Uses modular arithmetic for efficient cache slot management

## Global Dependencies

### Required Globals
- `$beanList` - Core module to bean class mappings
- `$customBeanList` - Custom module to bean class mappings  
- `$beanFiles` - Core bean class to file path mappings
- `$customBeanFiles` - Custom bean class to file path mappings
- `$objectList` - Core module to dictionary key mappings
- `$customObjectList` - Custom module to dictionary key mappings
- `$log` - Global logging instance for error reporting

### File Dependencies
- `data/SugarBean.php` - Base bean class required for all operations

## Error Handling

### Validation
- Validates module exists in global bean lists
- Checks file existence before inclusion
- Logs warnings for missing modules
- Logs fatal errors for missing files

### Return Values
- Returns `false` for invalid modules or missing records
- Returns `null` from `SugarBean::retrieve()` indicates record not found
- Returns bean instances for successful operations

## Thread Safety
BeanFactory uses static properties and is not thread-safe. It's designed for single-threaded PHP execution model where each request has isolated state.

## Performance Considerations

### Optimization Features
- Intelligent caching reduces database queries
- LRU eviction maintains optimal memory usage
- Access tracking improves cache hit rates
- Circular indexing provides O(1) cache operations

### Best Practices
- Use `getBean()` for standard retrieval operations
- Use `getReloadedBean()` only when fresh data is essential
- Register beans immediately after creation with ID
- Monitor `$hits` counter for cache effectiveness 