# suite_install.php_docs.md

/**
 * @fileoverview Main SuiteCRM installation orchestrator that configures core settings and installs all component modules
 * @package SuiteCRM
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

This file serves as the central installation orchestrator for SuiteCRM, configuring core system settings and coordinating the installation of all advanced modules and features.

## Database Operations

### Configuration Management
- Updates `sugar_config` array with core SuiteCRM settings
- Writes configuration changes to `config.php` via `write_array_to_file()`
- Integrates version information from `sugar_version.php` and `suitecrm_version.php`

### System Configuration Updates
**Core Settings Applied:**
- `default_max_tabs`: Set to 10 for optimal interface performance
- `suitecrm_version` and `sugar_version`: Version tracking
- `sugarbeet`: Disabled (set to false)
- `enable_action_menu`: Enabled for enhanced UI functionality

**Search Configuration:**
- `search.controller`: Set to 'UnifiedSearch'
- `search.defaultEngine`: Set to 'BasicSearchEngine'

**Testing Configuration:**
- `imap_test`: Disabled for production environments

## Internal API Calls

### Module Installation Orchestration

**Advanced Features Installation Sequence:**

1. **Advanced Open Sales (AOS)**
   - Calls `install_aos()` from `AdvancedOpenSales.php`
   - Configures quotes, invoices, and contracts functionality

2. **Advanced Open Portal (AOP)**
   - Calls `install_aop()` from `AdvancedOpenPortal.php`
   - Sets up customer portal integration

3. **Advanced Open Discovery (AOD)**
   - Calls `install_aod()` from `AdvancedOpenDiscovery.php`
   - Configures search indexing and discovery features

4. **Advanced Open Events (AOE)**
   - Calls `install_aoe()` from `AdvancedOpenEvents.php`
   - Sets up event management and email templates

5. **Search Integration**
   - Calls `install_search()` and `install_es()` from `Search.php`
   - Configures unified search and Elasticsearch integration

6. **Project Management**
   - Calls `install_projects()` from `Projects.php`
   - Sets up project and task management features

7. **Call Rescheduling**
   - Calls `install_reschedule()` from `Reschedule.php`
   - Configures call rescheduling functionality

8. **Security Groups**
   - Calls `install_ss()` from `SecurityGroups.php`
   - Implements role-based security framework

9. **Google Maps Integration**
   - Calls `install_gmaps()` from `GoogleMaps.php`
   - Sets up geographic mapping functionality

10. **Social Features**
    - Calls `install_social()` from `Social.php`
    - Configures social media integration

11. **System Email Templates**
    - Calls `installSystemEmailTemplates()` and `setSystemEmailTemplatesDefaultConfig()`
    - Sets up default email templates and configuration

### Administrative Integration
- Calls `set_CheckUpdates_config_setting('manual')` from `updater_utils.php`
- Configures manual update checking mode for production environments

### System Maintenance
- Executes `RepairAndClear()` for complete system optimization
- Performs `repairAndClearAll()` with cache clearing actions
- Ensures all modules are properly registered and functional

## UI Functionality

### Interface Configuration
- Enables action menu functionality for enhanced user experience
- Sets optimal tab limits for interface performance
- Configures unified search interface components

### Module Integration
- Ensures all installed modules are properly integrated into UI
- Configures module-specific interface elements
- Sets up navigation and menu structures

## Installation Process Flow

### Sequential Installation Steps
1. **Core Configuration**: Basic system settings and version tracking
2. **Feature Installation**: Sequential installation of all advanced modules
3. **Search Setup**: Search engine and indexing configuration
4. **Security Implementation**: Security groups and access controls
5. **Template Configuration**: Email templates and system templates
6. **System Optimization**: Cache clearing and system repair

### Error Handling and Validation
- Each module installation is independent and isolated
- Configuration changes are validated before writing to files
- System repair ensures integrity after all installations complete

## Integration Dependencies

### Required Files
- `sugar_version.php` and `suitecrm_version.php` for version tracking
- All module-specific installation files in `install/suite_install/`
- `modules/Administration/updater_utils.php` for update configuration
- `modules/Administration/QuickRepairAndRebuild.php` for system maintenance

### Configuration Management
- Maintains sorted configuration arrays for consistency
- Preserves existing custom configurations while adding new settings
- Ensures proper integration with SuiteCRM configuration framework 