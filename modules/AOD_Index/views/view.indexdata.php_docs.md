# view.indexdata.php Documentation

## @fileoverview
**AOD_IndexViewIndexData - Administrative view for search index statistics and management**
- **Package**: modules/AOD_Index/views
- **Copyright**: SalesAgility Ltd
- **License**: GNU AFFERO GENERAL PUBLIC LICENSE
- **Purpose**: Displays search index statistics, status, and failed indexing records for administrators
- **Deprecation**: Deprecated since v7.12.0

## Class Overview
The `AOD_IndexViewIndexData` class extends `SugarView` and provides the administrative interface for viewing search index status, statistics, and managing failed indexing operations. It combines database queries with template rendering to show comprehensive index information.

## Database Operations

### Statistics Queries

#### Module Record Counts
```php
$query = "SELECT COUNT(DISTINCT b.id) FROM ".$bean->getTableName()." b WHERE b.deleted = 0";
```
- **Purpose**: Counts active records in each indexable module
- **Scope**: Iterates through all modules returned by `getIndexableModules()`
- **Filtering**: Excludes soft-deleted records (`deleted = 0`)
- **Aggregation**: Sums individual module counts for total records

#### Index Event Statistics
- **Indexed Records**: `SELECT COUNT(*) FROM aod_indexevent WHERE deleted = 0 AND success = 1`
- **Failed Records**: `SELECT COUNT(*) FROM aod_indexevent WHERE deleted = 0 AND success = 0`
- **Purpose**: Tracks indexing success/failure rates for monitoring

#### File System Statistics
```php
$indexFiles = is_countable(glob($index->location."/*.cfs")) ? count(glob($index->location."/*.cfs")) : 0;
```
- **Purpose**: Counts Lucene compound index files (.cfs) in index directory
- **Location**: Uses index location from database configuration
- **Safety**: Handles cases where directory doesn't exist or contains no files

## Internal API Calls

### Framework Integration

#### Bean Factory Usage
- **Index Retrieval**: `BeanFactory::getBean("AOD_Index")->getIndex()`
- **Module Instantiation**: `BeanFactory::getBean($beanModule)` for each indexable module
- **Event Bean**: `BeanFactory::newBean("AOD_IndexEvent")` for failed records display

#### Database Manager
- **Instance**: `DBManagerFactory::getInstance()` for database operations
- **Query Execution**: `$db->getOne($query)` for single-value statistics
- **Cross-Platform**: Uses database manager for SQL compatibility

### Module Management

#### Indexable Module Discovery
```php
$beanList = $index->getIndexableModules();
```
- **Purpose**: Gets list of modules available for search indexing
- **Filtering**: Only includes modules that pass searchability criteria
- **Validation**: Checks each module for valid bean and table name

#### Module Validation
- **Bean Existence**: Validates bean can be instantiated
- **Table Validation**: Ensures `getTableName()` method exists and returns valid table
- **Error Handling**: Skips invalid modules with `continue` statement

## UI Functionality

### Template System Integration

#### Main Statistics Display
```php
$this->ss->assign("revisionCount", $revisionCount);
$this->ss->assign("indexedCount", $indexedCount);
$this->ss->assign("failedCount", $failedCount);
$this->ss->assign("index", $index);
$this->ss->assign("indexFiles", $indexFiles);
echo $this->ss->fetch('modules/AOD_Index/tpls/indexdata.tpl');
```
- **Smarty Integration**: Uses SugarView's Smarty template system
- **Data Passing**: Assigns statistics to template variables
- **Template Rendering**: Renders main admin interface template

#### Template Variables
- **`revisionCount`**: Total active records across all indexable modules
- **`indexedCount`**: Number of successfully indexed records
- **`failedCount`**: Number of failed indexing attempts
- **`index`**: Main index object with configuration data
- **`indexFiles`**: Count of physical index files on disk

### Failed Records Management

#### ListView Integration
```php
$lv = new ListViewSmarty();
$lv->setup($seed, 'include/ListView/ListViewNoMassUpdate.tpl', 'success = 0', '', 0, 10);
```
- **Purpose**: Displays detailed list of failed indexing records
- **Filtering**: Shows only records where `success = 0`
- **Pagination**: Displays 10 records per page
- **Template**: Uses specialized no-mass-update template

#### ListView Configuration
- **Features Disabled**: 
  - `quickViewLinks = false` - no quick view popups
  - `export = false` - no export functionality
  - `mergeduplicates = false` - no duplicate merging
  - `multiSelect = false` - no checkbox selection
  - `delete = false` - no delete operations
  - `showMassupdateFields = false` - no mass update options
  - `email = false` - no email functionality

### Language and Localization

#### Module Language Loading
```php
$mod_strings = return_module_language($current_language, $seed->module_dir);
```
- **Purpose**: Loads localized strings for AOD_IndexEvent module
- **Context**: Provides proper labels for failed records list
- **Fallback**: Uses system default if language not available

#### Custom Override Support
```php
if (file_exists('custom/modules/'.$seed->module_dir.'/metadata/listviewdefs.php')) {
    require('custom/modules/'.$seed->module_dir.'/metadata/listviewdefs.php');
}
```
- **Customization**: Supports custom listview field definitions
- **Priority**: Custom definitions override standard ones
- **Flexibility**: Allows site-specific failed record display customization

## Performance Considerations

### Query Optimization
- **Distinct Counts**: Uses `COUNT(DISTINCT b.id)` to handle potential duplicates
- **Index Usage**: Queries leverage standard SuiteCRM database indexes
- **Batch Processing**: Processes all modules in single view generation

### Memory Management
- **Lazy Loading**: Only instantiates beans when needed for validation
- **Limited Results**: Failed records list limited to 10 items per page
- **Resource Cleanup**: Relies on PHP garbage collection for bean cleanup

## Error Handling and Validation

### Module Validation
```php
if (!$bean || !method_exists($bean, "getTableName") || !$bean->getTableName()) {
    continue;
}
```
- **Bean Validation**: Ensures bean instantiation succeeded
- **Method Checking**: Validates required methods exist
- **Table Validation**: Confirms valid table name returned
- **Graceful Degradation**: Skips invalid modules without failing

### File System Safety
- **Directory Existence**: `glob()` function handles missing directories gracefully
- **Countable Check**: `is_countable()` prevents errors on non-array results
- **Default Values**: Returns 0 if no index files found

### Security Validation
```php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
```
- **Entry Point Protection**: Standard SuiteCRM security check
- **Direct Access Prevention**: Blocks direct file access outside framework
- **Framework Requirement**: Ensures proper SuiteCRM context

## Administrative Features

### Index Health Monitoring
- **Success Rate**: Compares indexed vs total records
- **Failure Tracking**: Identifies problematic modules or records
- **File System Status**: Monitors physical index integrity
- **Performance Metrics**: Provides data for optimization decisions

### Failed Record Analysis
- **Detailed View**: Shows specific records that failed indexing
- **Error Information**: Displays error messages from indexing attempts
- **Module Context**: Groups failures by module for pattern analysis
- **Retry Planning**: Provides data for manual reindexing operations

## Integration Points

### Administrative Workflow
1. **Access**: Admin navigates to AOD_Index module
2. **Statistics**: View displays comprehensive index statistics
3. **Health Check**: Admin reviews success/failure rates
4. **Problem Resolution**: Detailed failed records help identify issues
5. **Optimization**: Data informs optimization scheduling decisions

### System Monitoring
- **Performance Tracking**: Regular statistics review shows index health
- **Maintenance Planning**: Failed record trends guide maintenance schedules
- **Capacity Planning**: Record counts help predict storage needs
- **Error Resolution**: Failed record details enable targeted fixes

## Deprecation Impact

### Current Status
- **Deprecated Since**: v7.12.0
- **Functionality**: View still operational but not recommended for new implementations
- **Data Access**: Statistics queries remain functional for legacy data

### Migration Considerations
- **Modern Interfaces**: Newer search systems provide different administrative tools
- **Data Preservation**: Historical indexing data remains accessible
- **Functionality**: Modern search systems may not require similar monitoring
- **Legacy Support**: Maintained for systems still using AOD indexing 