# ACL Install Actions Setup Script

/**
 * @fileoverview Installation and setup script for automatically adding ACL actions to all ACL-enabled modules in SuiteCRM
 * @package SuiteCRM.modules.ACL
 * @copyright SugarCRM Inc. 2004-2013, SalesAgility Ltd. 2011-2021
 * @license GNU Affero General Public License version 3
 */

## Overview

This installation script automatically configures ACL (Access Control List) actions for all modules that implement ACL functionality in SuiteCRM. It's executed during system installation, upgrades, or when ACL configuration needs to be refreshed across all eligible modules.

## Core Functionality

### ACL Installation Process
The script performs these key operations:
1. **Permission Verification** - Ensures only administrators can execute ACL installation
2. **Module Discovery** - Scans all registered modules for ACL compatibility
3. **Bean Instantiation** - Creates module instances to check ACL implementation
4. **ACL Configuration** - Adds appropriate ACL actions for each eligible module
5. **Duplicate Prevention** - Tracks installed classes to avoid redundant processing

### Security Features
- **Admin-Only Access** - Requires administrator privileges using `is_admin($current_user)`
- **Entry Point Validation** - Standard SuiteCRM security check preventing direct access
- **Safe Execution** - Only processes valid, ACL-enabled modules

## Database Operations

### ACL Action Installation
- **ACLAction::addActions()** - Creates ACL actions for module categories
- **ACLAction::addActions($category, $type)** - Creates typed ACL actions when specified
- Processes each module's ACL requirements individually

### Module Processing
- Iterates through `$beanList` to discover all registered modules
- Creates bean instances using `BeanFactory::newBean()` for ACL validation
- Tracks processed classes in `$installed_classes` array to prevent duplicates

## Internal API Calls

### Module Management Functions
- **BeanFactory::newBean($module)** - Creates module bean instances for ACL checking
- **$bean->bean_implements('ACL')** - Verifies module implements ACL interface
- **$bean->getACLCategory()** - Retrieves module's ACL category identifier

### ACL System Integration
- **ACLAction::addActions()** - Adds standard ACL actions for modules
- **ACLAction::addActions($category, $type)** - Adds custom ACL action types
- **is_admin($current_user)** - Validates administrator privileges

### Logging and Translation
- **$GLOBALS['log']->debug()** - Logs ACL processing status for debugging
- **translate('LBL_ADDING', 'ACL')** - Provides localized progress messages
- Debug logging tracks which modules are being processed

## UI Functionality

### Installation Progress Display
- **Module Processing Status** - Shows which modules are having ACL actions added
- **Silent Mode Support** - Suppresses output during installation and upgrade processes
- **Localized Messages** - Uses translation system for user-facing text

### Progress Messages
```php
// Module ACL installation notification
translate('LBL_ADDING', 'ACL', '') . $bean->module_dir . '<br>'

// Debug logging for troubleshooting
$GLOBALS['log']->debug("ACL Processing: $class")
```

### Installation Context Detection
- **SUGARCRM_IS_INSTALLING** - Detects initial system installation
- **upgradeWizard** - Detects upgrade wizard execution
- Suppresses verbose output during automated processes

## Module Processing Logic

### ACL Eligibility Criteria
Modules must meet these requirements:
1. **Valid Bean** - Module must have a valid bean class
2. **ACL Implementation** - Must implement ACL interface via `bean_implements('ACL')`
3. **Not Display-Only** - Must not have `acl_display_only` flag set
4. **Not Tracker** - Explicitly excludes Tracker module from ACL processing

### Custom ACL Types
- **Standard ACL** - Uses default ACL actions for most modules
- **Custom ACL Types** - Uses `$bean->acltype` property for specialized ACL configurations
- **ACL Categories** - Each module defines its ACL category via `getACLCategory()`

### Duplicate Prevention
- **Class Tracking** - `$installed_classes` array prevents duplicate processing
- **Bean-Level Processing** - Processes at bean class level, not module level
- **Efficient Processing** - Skips already-processed bean classes

## Integration Points

### Related ACL Components
- **remove_actions.php** - Complementary script for removing obsolete ACL actions
- **ACLController.php** - Main ACL management and enforcement
- **ACLAction class** - Core ACL action database operations

### System Integration
- **Installation Process** - Executed during fresh SuiteCRM installations
- **Upgrade Workflow** - Called during system upgrades to refresh ACL configuration
- **Module Management** - Integrates with SuiteCRM's module registration system

## Execution Contexts

### Fresh Installation
- Runs during initial SuiteCRM setup
- Configures ACL for all modules included in base installation
- Establishes foundation ACL structure

### System Upgrades
- Updates ACL configuration for new or modified modules
- Ensures ACL compatibility after version upgrades
- Refreshes ACL actions for enhanced modules

### Manual Refresh
- Can be executed by administrators for ACL maintenance
- Useful after custom module installation or modification
- Safe to run multiple times without conflicts

## Error Handling

### Bean Creation Failures
- Gracefully handles modules with invalid or missing bean classes
- Continues processing other modules if individual bean creation fails
- Logs errors for debugging purposes

### ACL Implementation Validation
- Verifies ACL interface implementation before processing
- Skips modules that don't properly implement ACL functionality
- Prevents errors from incomplete ACL implementations

## Performance Considerations

- **Efficient Module Discovery** - Single iteration through module registry
- **Smart Duplicate Prevention** - Tracks processed classes to avoid redundant work
- **Lazy Bean Creation** - Only creates beans for potential ACL modules
- **Batch ACL Processing** - Groups ACL actions by module for efficient database operations

## Logging and Debugging

### Debug Information
- **Module Processing** - Logs each module being processed for ACL
- **Class Tracking** - Shows which bean classes are getting ACL configuration
- **Error Reporting** - Captures and logs processing failures

### Production Considerations
- Silent operation during installation and upgrades
- Minimal performance impact on system resources
- Comprehensive error handling for production stability

## Notes

- Essential component of SuiteCRM's security infrastructure
- Automatically maintains ACL consistency across all modules
- Designed for both fresh installations and upgrade scenarios
- Works in conjunction with remove_actions.php for complete ACL lifecycle management 