# controller.php Documentation

## @fileoverview
**AOD_IndexController - MVC controller for Advanced OpenDiscovery search index management**
- **Package**: modules/AOD_Index
- **Copyright**: SalesAgility Ltd
- **License**: GNU AFFERO GENERAL PUBLIC LICENSE
- **Purpose**: Handles HTTP requests and user actions for AOD search index administration
- **Deprecation**: Deprecated since v7.12.0

## Class Overview
The `AOD_IndexController` class extends `SugarController` and provides web interface functionality for managing the Lucene search index. It handles administrative actions like viewing index data and performing optimization operations.

## Internal API Calls

### MVC Framework Integration
- **Parent Class**: `SugarController` (core SuiteCRM MVC controller)
- **Framework**: Integrates with SuiteCRM's Model-View-Controller architecture
- **Request Handling**: Processes HTTP requests for AOD_Index module

### Action Routing

#### Action Remapping
```php
protected $action_remap = array('index'=>'indexdata');
```
- **Purpose**: Redirects default `index` action to `indexdata`
- **Behavior**: When users access module without specific action, shows index data view
- **MVC Pattern**: Follows SuiteCRM convention for default module actions

### Controller Actions

#### `action_indexdata(): void`
- **Purpose**: Displays search index information and statistics
- **View Assignment**: Sets `$this->view = 'indexdata'`
- **Template**: Renders via `view.indexdata.php` view class
- **Functionality**: Shows index status, statistics, and management options
- **User Interface**: Primary administrative interface for AOD system

#### `action_optimise(): void`
- **Purpose**: Performs Lucene index optimization for improved performance
- **Process**:
  1. Sets execution time limit to 6000 seconds (100 minutes)
  2. Retrieves singleton index instance via `BeanFactory::getBean()`
  3. Calls `$index->optimise()` to perform optimization
  4. Redirects back to module main page
- **Performance**: Long-running operation requiring extended execution time
- **User Feedback**: Redirects to show updated index status after completion

## UI Functionality

### Administrative Interface
- **Index Overview**: Displays current index status and statistics
- **Management Actions**: Provides buttons/links for optimization and maintenance
- **Status Information**: Shows last optimization time and index health
- **Navigation**: Integrates with SuiteCRM module navigation system

### User Actions
- **View Index Data**: Default action showing index information
- **Optimize Index**: Manual trigger for index optimization
- **Module Access**: Standard SuiteCRM module interface patterns

## Performance Considerations

### Execution Time Management
- **Optimization Timeout**: Extended to 6000 seconds for large indices
- **Background Processing**: Optimization runs synchronously (may block UI)
- **Resource Usage**: Index optimization is memory and CPU intensive

### User Experience
- **Immediate Feedback**: Redirect provides confirmation of action completion
- **Progress Indication**: No real-time progress updates during optimization
- **Error Handling**: Relies on underlying index classes for error management

## Security and Validation

### Entry Point Security
```php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
```
- **Purpose**: Prevents direct script execution outside SuiteCRM framework
- **Security**: Standard SuiteCRM entry point validation
- **Protection**: Blocks unauthorized access attempts

### Access Control
- **Framework Integration**: Uses SuiteCRM's standard ACL system
- **Module Permissions**: Respects user access rights for AOD_Index module
- **Administrative Functions**: Optimization typically requires admin access

## Integration Points

### Bean Factory Usage
- **Index Retrieval**: `BeanFactory::getBean("AOD_Index")->getIndex()`
- **Singleton Pattern**: Gets or creates the primary search index
- **Framework Compliance**: Uses standard SuiteCRM bean instantiation

### Navigation and Redirects
- **Module Navigation**: `SugarApplication::redirect('index.php?module=AOD_Index')`
- **User Flow**: Returns users to module main page after actions
- **URL Structure**: Standard SuiteCRM module URL patterns

## View Integration

### Template System
- **View Class**: Uses corresponding view classes for rendering
- **Index Data View**: `view.indexdata.php` handles display logic
- **Template Files**: Smarty templates in `tpls/` directory
- **Layout Integration**: Follows SuiteCRM module layout standards

### Data Passing
- **Controller to View**: Data passed via view object properties
- **Index Statistics**: Status information available to view layer
- **User Context**: Standard SuiteCRM user session integration

## Error Handling

### Exception Management
- **Framework Handling**: Relies on SuiteCRM error handling mechanisms
- **Index Errors**: Underlying index operations handle their own exceptions
- **User Notification**: Framework provides standard error display

### Graceful Degradation
- **Action Failures**: Framework handles action execution failures
- **Redirect Safety**: Always attempts to redirect on completion
- **Fallback Behavior**: Standard module behavior if actions fail

## Configuration Dependencies

### Global Requirements
- **SuiteCRM Framework**: Requires full framework initialization
- **AOD_Index Module**: Dependent on AOD_Index bean and functionality
- **Bean Factory**: Uses standard SuiteCRM dependency injection

### Module Settings
- **AOD Configuration**: Respects `$sugar_config['aod']['enable_aod']` setting
- **Performance Limits**: Uses PHP execution time limits appropriately
- **Access Permissions**: Standard module permission checks

## Deprecation Impact

### Current Status
- **Deprecated Since**: v7.12.0
- **Functionality**: Controller actions still functional but not recommended
- **UI Access**: Administrative interface remains available

### Migration Considerations
- **Modern Search**: Newer search implementations don't use this controller
- **Administrative Tools**: Modern systems may provide different management interfaces
- **Legacy Support**: Maintained for backward compatibility with existing installations 