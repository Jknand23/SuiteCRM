# AOD_LogicHooks.php Documentation

## @fileoverview
**AOD_LogicHooks - Logic hooks for automatic Lucene search index maintenance**
- **Package**: modules/AOD_Index
- **Copyright**: SalesAgility Ltd
- **License**: GNU AFFERO GENERAL PUBLIC LICENSE
- **Purpose**: Automatically maintains search index consistency by responding to bean lifecycle events
- **Deprecation**: Deprecated since v7.12.0

## Class Overview
The `AOD_LogicHooks` class provides automatic search index maintenance through SuiteCRM's logic hook system. It ensures that the Lucene search index stays synchronized with database changes by automatically indexing, updating, and removing records as they are modified.

## Internal API Calls

### Hook Event Handlers

#### `saveModuleChanges(SugarBean $bean, string $event, array $arguments): void`
- **Purpose**: Automatically indexes records when they are saved or modified
- **Trigger Events**: 
  - `after_save` 
  - `after_relationship_add`
  - Other save-related logic hook events
- **Process**:
  1. Skips self-referential indexing (AOD_Index module)
  2. Bypasses during installation (`SUGARCRM_IS_INSTALLING`)
  3. Retrieves singleton index instance
  4. Indexes the modified bean
- **Error Handling**: Catches and logs exceptions without interrupting save operation

#### `saveModuleDelete(SugarBean $bean, string $event, array $arguments): void`
- **Purpose**: Removes records from search index when they are deleted
- **Trigger Events**:
  - `before_delete`
  - `after_delete`
- **Process**:
  1. Validates not AOD_Index module itself
  2. Skips during installation processes
  3. Gets search index instance
  4. Removes bean from Lucene index
- **Error Handling**: Exception-safe with error logging

#### `saveModuleRestore(SugarBean $bean, string $event, array $arguments): void`
- **Purpose**: Re-indexes records when they are restored from deletion
- **Trigger Events**:
  - `after_restore`
  - Undelete operations
- **Process**:
  1. Excludes AOD_Index module processing
  2. Bypasses installation contexts
  3. Retrieves index instance
  4. Re-indexes restored bean
- **Error Handling**: Safe exception handling with logging

## Event System Integration

### Logic Hook Registration
- **Framework**: Integrates with SuiteCRM's logic hook system
- **Event Types**: Responds to standard bean lifecycle events
- **Registration**: Hooks registered in `logic_hooks.php` configuration files

### Bean Lifecycle Synchronization
- **Save Events**: New records and updates trigger indexing
- **Delete Events**: Removed records are purged from index
- **Restore Events**: Undeleted records are re-indexed
- **Relationship Events**: Related data changes update index

## Safety and Performance Features

### Installation Safety
- **Installation Check**: `defined('SUGARCRM_IS_INSTALLING')` prevents indexing during install
- **Entry Point Validation**: `defined('sugarEntry')` ensures valid SuiteCRM context
- **Purpose**: Prevents index corruption during system setup

### Module Exclusion
- **Self-Exclusion**: Prevents infinite loops by excluding AOD_Index module
- **Rationale**: AOD_Index records don't need to be searchable content
- **Safety**: Avoids recursive indexing scenarios

### Error Resilience
- **Exception Handling**: All methods wrapped in try-catch blocks
- **Non-Breaking**: Index failures don't interrupt normal bean operations
- **Logging**: Errors logged via `$GLOBALS['log']->error()`
- **Graceful Degradation**: System continues functioning if indexing fails

## Index Management Integration

### Index Instance Management
- **Singleton Pattern**: Uses `BeanFactory::getBean("AOD_Index")->getIndex()`
- **Auto-Creation**: Index created if doesn't exist
- **Consistency**: Same index instance used across all operations

### Indexing Operations
- **Add/Update**: `$index->index($bean->module_name, $bean->id)`
- **Remove**: `$index->remove($bean->module_name, $bean->id)`
- **Module Validation**: Only searchable modules get indexed

## Performance Considerations

### Asynchronous Processing
- **Real-time Updates**: Changes reflected immediately in search index
- **Performance Impact**: Indexing occurs synchronously with save operations
- **Trade-off**: Immediate consistency vs. save performance

### Error Recovery
- **Partial Failures**: Individual indexing failures don't affect other operations
- **Retry Logic**: No automatic retry - relies on subsequent updates
- **Manual Recovery**: Admin tools available for rebuilding corrupted indices

## Configuration Dependencies

### Global Variables
- **Installation Flags**: `SUGARCRM_IS_INSTALLING`, `sugarEntry`
- **Logging**: `$GLOBALS['log']` for error reporting
- **Bean Factory**: `BeanFactory` for index instance management

### Module Requirements
- **AOD_Index Module**: Must be installed and functional
- **AOD_IndexEvent Module**: Required for event tracking
- **Logic Hook System**: Framework must support logic hooks

## Hook Registration Example

### Typical Registration
```php
// In logic_hooks.php
$hook_array['after_save'][] = Array(
    1, 
    'AOD Index Save Hook', 
    'modules/AOD_Index/AOD_LogicHooks.php',
    'AOD_LogicHooks', 
    'saveModuleChanges'
);
```

### Event Coverage
- **after_save**: Index updates and new records
- **before_delete**: Remove from index before database deletion
- **after_restore**: Re-index restored records

## Deprecation Impact

### Current Status
- **Deprecated Since**: v7.12.0
- **Function**: Logic hooks still present but AOD system inactive
- **Migration**: Modern search systems handle indexing differently

### Legacy Cleanup
- **Hook Removal**: Should be removed when migrating to new search
- **Data Migration**: Index data can be safely removed
- **Performance**: Removing hooks eliminates indexing overhead 