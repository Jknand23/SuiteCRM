# QuickRepairAndRebuild.php

## Overview
**@fileoverview** Comprehensive system maintenance and repair utility class that provides database repair, cache clearing, extension rebuilding, and audit table management for SuiteCRM.

**@package** Administration  
**@copyright** SugarCRM Inc. / SalesAgility Ltd.  
**@license** AGPL v3  

The RepairAndClear class serves as the central hub for SuiteCRM's system maintenance operations. It provides automated and manual tools for database schema repair, cache management, extension rebuilding, and audit table maintenance while supporting both full system and module-specific operations.

## Database Operations

### Database Schema Repair
Comprehensive database repair and synchronization capabilities:
- **Schema Comparison**: Compares current database structure with vardefs definitions
- **SQL Generation**: Creates ALTER TABLE statements for schema differences
- **Module Selection**: Supports repair of specific modules or entire system
- **Execution Control**: Allows preview or automatic execution of repair operations

#### repairDatabase()
Full system database repair operation:
- **Global Repair**: Processes all modules using existing repair infrastructure
- **Execution Mode**: Supports both preview and execution modes
- **Output Control**: Configurable display of repair progress and results
- **Integration**: Uses existing `repairDatabase.php` infrastructure

#### repairDatabaseSelectModules()
Selective module database repair:
- **Module Filtering**: Repairs only specified modules from selection list
- **Bean Processing**: Iterates through selected bean classes for repair operations
- **Schema Analysis**: Loads vardefs and compares with actual database structure
- **SQL Output**: Generates and displays SQL statements for required changes

### Audit Table Management

#### rebuildAuditTables()
Reconstructs audit tables for auditable modules:
- **Audit Detection**: Checks `is_AuditEnabled()` for each module
- **Table Creation**: Creates missing audit tables using `create_audit_table()`
- **Existence Check**: Validates existing audit tables before creation
- **Module Selection**: Supports specific modules or system-wide rebuilding

## Internal API Calls

### Cache Management Operations
Comprehensive cache clearing across multiple system components:

#### clearVardefs()
Clears compiled vardefs cache files:
- **Module Specific**: Can target individual modules or clear all vardefs
- **File Pattern**: Removes `vardefs.php` files from cache directories
- **Path Resolution**: Uses `sugar_cached('modules/')` for cache location

#### clearTpls()
Removes cached template files:
- **Template Cache**: Clears compiled Smarty template files
- **Module Support**: Selective clearing for specific modules
- **File Extension**: Targets `.tpl` files in cache directories

#### clearJsFiles()
Clears cached JavaScript files:
- **JavaScript Cache**: Removes compiled JS files from module cache
- **Minification**: Clears minified and compiled JavaScript assets
- **Module Filtering**: Supports module-specific or global clearing

#### clearJsLangFiles()
Removes cached JavaScript language files:
- **Language Cache**: Clears JS language string cache files
- **Localization**: Supports multiple language cache clearing
- **Path Structure**: Uses `jsLanguage/` cache directory structure

#### clearLanguageCache()
Comprehensive language cache management:
- **Language Manager**: Uses `LanguageManager::clearLanguageCache()`
- **Module Support**: Can clear specific module language caches
- **Sugar Cache**: Clears `app_strings` and `app_list_strings` cache entries
- **Multi-Language**: Processes all configured system languages

#### clearDashlets()
Removes cached dashlet files:
- **Dashlet Cache**: Clears compiled dashlet PHP files
- **Widget System**: Maintains dashlet performance through cache management

#### clearSugarFeedCache()
Clears SugarFeed backend cache:
- **Feed Cache**: Uses `SugarFeed::flushBackendCache()` for clearing
- **Social Features**: Maintains feed system performance

#### clearThemeCache()
Removes theme-related cache files:
- **Theme Registry**: Uses `SugarThemeRegistry::clearAllCaches()`
- **CSS Cache**: Clears compiled theme stylesheets and assets

#### clearSearchCache()
Removes search-related cache files:
- **Unified Search**: Clears `unified_search_modules.php` cache file
- **Search Performance**: Maintains search system responsiveness

#### clearExternalAPICache()
Clears external API integration cache:
- **API Factory**: Uses `ExternalAPIFactory::clearCache()`
- **Integration Cache**: Maintains external service connections

### Extension Management

#### rebuildExtensions()
Rebuilds system extensions and customizations:
- **Module Installer**: Uses `ModuleInstaller::rebuild_all()` method
- **Extension Processing**: Rebuilds all extension points and hooks
- **Session Management**: Clears rebuild warnings from admin sessions
- **Relationship Updates**: Triggers relationship cache rebuilding

### Utility Operations

#### clearAll()
Comprehensive system cache clearing:
- **Complete Clearing**: Executes all cache clearing operations
- **Performance Reset**: Full system cache refresh
- **Maintenance Mode**: Typically used during major system maintenance

## External API Calls

### Database Layer Integration
- **DBManagerFactory**: Gets database instance for repair operations
- **Table Repair**: Uses `$db->repairTable()` for schema comparison
- **SQL Execution**: Integrates with database layer for repair execution

### Module System Integration
- **Bean Files**: Loads module bean files for repair operations
- **Module Discovery**: Uses global `$beanFiles` and `$beanList` arrays
- **Vardefs Loading**: Includes module vardefs for schema comparison

### File System Operations
- **Cache Directories**: Uses `sugar_cached()` for cache path resolution
- **File Operations**: `unlink()`, `opendir()`, `readdir()` for cache management
- **Directory Traversal**: Recursive directory processing for cache clearing

### Template System Integration
- **Smarty Integration**: Clears Smarty template compilation cache
- **Template Processing**: Manages compiled template performance

## UI Functionality

### Progress Display
Comprehensive user feedback during maintenance operations:
- **Output Control**: `show_output` parameter controls display verbosity
- **Progress Indicators**: Real-time feedback during long-running operations
- **Module Status**: Per-module progress reporting for selective operations

#### Module Title Generation
- **Classic Titles**: Uses `getClassicModuleTitle()` for consistent headers
- **Localization**: Translates all progress messages and headers
- **Loading Indicators**: JavaScript-based loading status updates

#### SQL Preview Interface
Interactive SQL preview and execution:
- **Textarea Display**: Shows generated SQL in editable textarea
- **Execution Options**: Provides execute or export buttons
- **Form Integration**: Uses POST forms for SQL execution workflow

### Error Handling and Logging
- **Logger Integration**: Uses `LoggerManager::getLogger()` for warnings
- **Directory Validation**: Checks directory existence before operations
- **File Validation**: Validates file existence and permissions

## Security Features

### Administrative Access Control
- **Admin Verification**: Uses `is_admin()` and `is_admin_for_any_module()`
- **Sugar Die**: Terminates unauthorized access with `sugar_die()`
- **Operation Restriction**: Limits repair operations to authorized users

### Safe Operations
- **Preview Mode**: Allows SQL preview before execution
- **Backup Recommendations**: Encourages database backups before repairs
- **Transaction Safety**: Database operations use proper transaction handling

## Performance Considerations

### Module Selection Optimization
- **Selective Processing**: Supports module-specific operations for performance
- **Memory Management**: Uses `set_time_limit(3600)` for long operations
- **Cache Efficiency**: Targets specific cache types for minimal disruption

### Batch Processing
- **Bulk Operations**: Processes multiple modules in single operation
- **Resource Management**: Handles large cache clearing operations efficiently
- **Output Buffering**: Uses `ob_flush()` for real-time progress updates

## Helper Methods

### Cache Management Helpers

#### _clearCache($thedir, $extension)
Recursive cache clearing implementation:
- **Directory Traversal**: Recursively processes subdirectories
- **File Filtering**: Uses extension matching for targeted clearing
- **Error Handling**: Validates directories and logs warnings
- **Pattern Matching**: Uses `substr_count()` for file extension matching

#### _rebuildAuditTablesHelper($focus)
Audit table reconstruction helper:
- **SugarBean Validation**: Ensures object is valid SugarBean instance
- **Audit Detection**: Uses `is_AuditEnabled()` to check audit capability
- **Table Existence**: Validates audit table existence before creation
- **Progress Reporting**: Provides detailed feedback on audit operations

#### _getModuleNamePlural($module_name_singular)
Module name resolution utility:
- **Bean List Search**: Searches global `$beanList` for module names
- **Singular to Plural**: Converts singular bean names to plural module names
- **Array Navigation**: Uses `current()` and `next()` for array traversal

The RepairAndClear class provides essential system maintenance capabilities for SuiteCRM, ensuring database integrity, optimal performance through cache management, and proper system configuration through extension rebuilding. 