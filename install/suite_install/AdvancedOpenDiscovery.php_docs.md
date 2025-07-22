# AdvancedOpenDiscovery.php_docs.md

/**
 * @fileoverview Advanced Open Discovery (AOD) installation module for search indexing and discovery features
 * @package SuiteCRM
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

This file provides installation functionality for the Advanced Open Discovery (AOD) module, which enables enhanced search indexing and content discovery capabilities within SuiteCRM.

## Database Operations

### Configuration Updates
- Updates global `sugar_config` array with AOD settings
- Enables AOD functionality via `aod.enable_aod = true`
- Writes configuration changes to `config.php` using `write_array_to_file()`

### System Integration
- Integrates with existing SuiteCRM configuration framework
- Maintains configuration array sorting for consistency
- Preserves existing settings while adding AOD-specific configuration

## Internal API Calls

### Module Installation Process

**install_aod() Function**
- **Purpose**: Main installation function for AOD module
- **Dependencies**: Requires `modules/Administration/Administration.php`
- **Configuration**: Enables AOD through global configuration settings
- **Hook Installation**: Calls `installAODHooks()` to set up logic hooks

**installAODHooks() Function**
- **Purpose**: Installs logic hooks for automatic index management
- **Dependencies**: Requires `ModuleInstall/ModuleInstaller.php`
- **Hook Management**: Uses `check_logic_hook_file()` for hook registration

### Logic Hook Configuration

**After Save Hook**
- **Module**: Applied globally (all modules)
- **Hook Type**: `after_save`
- **Order**: 1 (high priority)
- **Handler**: `AOD_LogicHooks::saveModuleChanges`
- **Purpose**: Automatically indexes content changes when records are saved

**After Delete Hook**
- **Module**: Applied globally (all modules)
- **Hook Type**: `after_delete`
- **Order**: 1 (high priority)
- **Handler**: `AOD_LogicHooks::saveModuleDelete`
- **Purpose**: Removes deleted records from search index

**After Restore Hook**
- **Module**: Applied globally (all modules)
- **Hook Type**: `after_restore`
- **Order**: 1 (high priority)
- **Handler**: `AOD_LogicHooks::saveModuleRestore`
- **Purpose**: Re-indexes restored records for search availability

## Search Integration

### Index Management
- Provides automatic indexing of all CRM data
- Maintains search index consistency with data changes
- Supports real-time search index updates

### Content Discovery
- Enables advanced search capabilities across all modules
- Provides foundation for full-text search functionality
- Supports complex query operations and filtering

## System Architecture

### Logic Hook Integration
- **File Location**: `modules/AOD_Index/AOD_LogicHooks.php`
- **Class**: `AOD_LogicHooks`
- **Global Application**: Hooks apply to all modules automatically
- **Performance**: Optimized for real-time index updates

### Configuration Management
- **Global Enable Flag**: `aod.enable_aod` controls module activation
- **Centralized Configuration**: All settings managed through `sugar_config`
- **Installation Integration**: Seamlessly integrates with SuiteCRM installation process

## Performance Considerations

### Indexing Strategy
- Hooks execute with order priority 1 for immediate processing
- Global application ensures comprehensive content coverage
- Efficient handling of save, delete, and restore operations

### System Impact
- Minimal overhead for normal CRM operations
- Automatic index maintenance reduces manual administration
- Real-time updates ensure search accuracy

## Usage Context

**Installation Scenarios**
- Automatically installed during SuiteCRM setup
- Can be enabled/disabled through configuration settings
- Supports both new installations and upgrades

**Search Enhancement**
- Provides foundation for advanced search features
- Enables full-text search across all CRM content
- Supports complex query operations and result ranking 