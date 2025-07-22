# SearchModules.php Documentation

/**
 * @fileoverview SearchModules class - Manages searchable modules configuration, builds search metadata cache, and handles unified search module settings for the SuiteCRM search framework
 * @package SuiteCRM\Search
 * @copyright SalesAgility Ltd
 * @license AGPL-3.0
 */

## Overview

The SearchModules class is responsible for managing which modules are searchable in SuiteCRM's unified search system. It handles module configuration, builds and maintains search metadata caches, processes search field definitions, and provides administrative functions for enabling/disabling modules in global search.

## Class Structure

```php
namespace SuiteCRM\Search;

class SearchModules
{
    // Static class with module management methods
    // Integrates with BeanFactory, VardefManager, UnifiedSearchAdvanced
}
```

## Core Functionality

### Module List Management

#### Module Retrieval
- **getModulesList()**: Returns complete module list as associative array
  - Combines enabled and disabled modules
  - Format: ['module_name' => 'translated_label']
  - Merges data from getAllModules() enabled/disabled arrays

- **getEnabledModules()**: Returns array of enabled module names only
  - Extracts module names from enabled modules list
  - Used by search engines to determine searchable modules

#### Module Configuration Access  
- **getAllModules()**: Retrieves enabled and disabled module configurations
  - Returns structured array: ['enabled' => [...], 'disabled' => [...]]
  - Each module entry: ['module' => 'name', 'label' => 'translated_name']
  - Integrates display settings with base module definitions
  - Handles new modules not yet in display configuration

### Configuration Management

#### Display Settings
- **getUnifiedSearchModulesDisplay()**: Manages module visibility settings
  - Reads from `custom/modules/unified_search_modules_display.php`
  - Creates default configuration if file doesn't exist
  - Structure: `['module_name' => ['visible' => boolean]]`
  - Auto-generates based on module defaults for first run

- **saveGlobalSearchSettings()**: Processes administrator module selection
  - Reads `$_REQUEST['enabled_modules']` comma-separated list
  - Updates visibility settings for all modules
  - Calls writeUnifiedSearchModulesDisplayFile() to persist changes

#### File Management
- **writeUnifiedSearchModulesDisplayFile()**: Writes display configuration to file
  - Uses write_array_to_file() for atomic file operations
  - Target: `custom/modules/unified_search_modules_display.php`
  - Logs errors and throws RuntimeException on write failure
  - Returns boolean success indicator

### Metadata and Cache Management

#### Search Module Definitions
- **getUnifiedSearchModules()**: Returns module search field metadata
  - Reads from cached file: `cache/modules/unified_search_modules.php`
  - Builds cache via buildCache() if file missing
  - Creates cache directory structure if needed
  - Contains field definitions for searchable modules

#### Cache Building Process
- **buildCache()**: Builds comprehensive search module cache
  - Processes all modules from global $beanList
  - Loads vardefs for each module via VardefManager
  - Reads SearchFields.php metadata for each module
  - Processes custom SearchFields.php overlays
  - Identifies unified_search enabled fields in vardefs
  - Handles special email/phone field processing
  - Supports force_unifiedsearch field override
  - Determines default enabled status from vardefs
  - Writes sorted results to cache file

### Field Definition Processing

#### Vardef Integration
- **Field Detection**: Processes `$dictionary[$beanName]['fields']` 
  - Checks for `unified_search` attribute in field definitions
  - Maps vardef fields to SearchFields.php entries
  - Handles email and phone field special cases (partial name matching)

#### Search Fields Processing  
- **SearchFields.php Loading**: Loads module-specific search configurations
  - Checks for custom metadata overrides first
  - Falls back to default module metadata
  - Processes metafiles.php for search field definitions
  - Merges custom SearchFields.php with defaults

#### Custom Module Support
- **Custom Module Detection**: Identifies custom modules via regex pattern
  - Pattern: `/^([a-z0-9]{1,5})_([a-z0-9_]+)$/i`
  - Enables unified search by default for custom modules
  - Processes same field definition logic as standard modules

## Database Operations

### File System Integration
- **Configuration Files**: 
  - `cache/modules/unified_search_modules.php` - Module field metadata cache
  - `custom/modules/unified_search_modules_display.php` - Display settings

- **Cache Directory Management**:
  - Uses sugar_cached() for consistent cache path handling
  - Creates directory structure via mkdir_recursive()
  - Atomic file writes with error handling

## Administrative Interface

### Global Search Settings
- **Module Selection Processing**: Handles admin form submissions
  - Processes comma-separated enabled module list
  - Updates visibility for all modules (enabled/disabled)
  - Persists changes to configuration files

### Error Handling
- **File Write Errors**: Comprehensive error handling
  - Logs errors via LoggerManager
  - Throws RuntimeException with descriptive messages
  - Uses global $app_strings for localized error messages

## Integration Points

### Core Framework Integration  
- **BeanFactory**: Module existence and object name resolution
- **VardefManager**: Field definition loading and processing
- **UnifiedSearchAdvanced**: Legacy search system integration
- **LoggerManager**: Error logging and debugging

### Global Variables
- **$beanList**: Available module list from SuiteCRM core
- **$beanFiles**: Module file mappings
- **$dictionary**: Vardef metadata definitions  
- **$app_list_strings**: Module name translations
- **$app_strings**: Error message translations

### Configuration System
- **Global Search Settings**: Admin panel module selection
- **Module Metadata**: SearchFields.php and metafiles.php
- **Cache Management**: Automatic cache building and refresh

## Cache Performance

### Optimization Strategies
- **Lazy Loading**: Cache built only when missing
- **Sorted Output**: Modules sorted alphabetically for consistency
- **Conditional Processing**: Skip modules without SearchFields
- **Custom Override Support**: Layered metadata loading

### Cache Invalidation
- **Manual Rebuild**: Via buildCache() method
- **Missing File Detection**: Automatic rebuild on cache miss
- **Admin Updates**: Display settings persist independently of cache

## Usage Patterns

### Basic Module Listing
```php
$modules = SearchModules::getModulesList();
foreach ($modules as $name => $label) {
    echo "$name: $label\n";
}
```

### Search Engine Integration
```php
$enabledModules = SearchModules::getEnabledModules();
$searchEngine->setSearchableModules($enabledModules);
```

### Administrative Configuration
```php
// Process admin form submission
SearchModules::saveGlobalSearchSettings();

// Get current settings for admin display
$allModules = SearchModules::getAllModules();
$enabled = $allModules['enabled'];
$disabled = $allModules['disabled'];
```

## Dependencies

### Core Dependencies
- BeanFactory for module object management
- VardefManager for field definition loading
- LoggerManager for error reporting
- write_array_to_file() for configuration persistence
- sugar_cached() for cache path management

### File Dependencies
- UnifiedSearchAdvanced.php for legacy integration
- Module-specific SearchFields.php and metafiles.php
- Global configuration and translation files 