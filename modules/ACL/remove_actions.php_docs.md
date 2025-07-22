# ACL Remove Actions Maintenance Script

/**
 * @fileoverview Administrative maintenance script for removing ACL actions associated with missing or deleted modules
 * @package SuiteCRM.modules.ACL
 * @copyright SugarCRM Inc. 2004-2013, SalesAgility Ltd. 2011-2018
 * @license GNU Affero General Public License version 3
 */

## Overview

This maintenance script automatically identifies and removes ACL (Access Control List) actions for modules that no longer exist in the SuiteCRM system. It helps maintain database integrity by cleaning up orphaned ACL entries after module removal or system upgrades.

## Core Functionality

### ACL Cleanup Process
The script performs the following operations:
1. **Permission Verification** - Ensures only administrators can execute the cleanup
2. **Module Validation** - Checks each ACL action against existing modules
3. **Orphan Detection** - Identifies ACL actions for non-existent modules
4. **Safe Removal** - Removes ACL actions for missing modules
5. **Status Reporting** - Provides feedback on cleanup operations

### Security Features
- **Admin-Only Access** - Requires administrator privileges using `is_admin($current_user)`
- **Entry Point Validation** - Standard SuiteCRM security check preventing direct access
- **Safe Execution** - Only removes ACL actions for verified missing modules

## Database Operations

### ACL Action Queries
- **ACLAction::getDefaultActions()** - Retrieves all default ACL actions from database
- **ACLAction::removeActions()** - Removes ACL actions for specified module category
- Validates module existence before performing deletions

### Module Verification
- Checks `$beanList` array for module registration
- Verifies physical file existence using `$beanFiles` array
- Ensures module files are present before deciding on ACL retention

## Internal API Calls

### ACL Management Functions
- **ACLAction::getDefaultActions()** - Fetches complete list of ACL actions
- **ACLAction::removeActions($category)** - Removes all ACL actions for module category
- **is_admin($current_user)** - Validates administrator privileges

### Module System Integration
- **$beanList** - Global registry of available modules
- **$beanFiles** - Global registry of module file paths
- **file_exists()** - Verifies physical module file presence

## UI Functionality

### Administrative Output
- **Progress Reporting** - Displays which ACL modules are being removed
- **Completion Status** - Shows final count of removed vs. retained ACL entries
- **Upgrade-Safe Mode** - Suppresses output during upgrade wizard operations

### Output Messages
```php
// Module removal notification
sprintf('Removing "%s" ACL for module "%s"<br>', $actionobj->name, $actionobj->category)

// Completion status
'No ACL modules found that needed to be removed'
```

### Upgrade Integration
- Detects upgrade wizard context via `$_REQUEST['upgradeWizard']`
- Suppresses verbose output during upgrade operations
- Maintains silent operation mode for automated processes

## Integration Points

### Related ACL Components
- **install_actions.php** - Complementary script for adding ACL actions
- **ACLController.php** - Main ACL management controller
- **ACLAction class** - Core ACL action management functionality

### System Integration
- **Module Management** - Works with SuiteCRM's module registration system
- **Upgrade Process** - Integrates with upgrade wizard workflow
- **Database Maintenance** - Part of system integrity maintenance tools

## Execution Context

### Manual Execution
- Can be run directly by administrators for maintenance
- Provides verbose output for manual operations
- Safe to run multiple times without side effects

### Automated Execution
- Called during upgrade processes
- Operates silently during automated workflows
- Integrated with system maintenance routines

## Error Handling

### Permission Validation
- Gracefully exits if non-admin user attempts execution
- Relies on `is_admin()` function for authorization
- No explicit error messages for unauthorized access

### Module Validation
- Checks both module registration and file existence
- Safe handling of missing modules or corrupted registrations
- Prevents removal of ACL actions for temporarily unavailable modules

## Performance Considerations

- **Efficient Scanning** - Single pass through all ACL actions
- **Batch Processing** - Groups removals by module category
- **Minimal Database Queries** - Uses existing global arrays for validation
- **Safe Execution** - No risk of removing valid ACL actions

## Notes

- Essential maintenance tool for ACL system integrity
- Safe to run during system maintenance windows
- Complements install_actions.php for complete ACL lifecycle management
- Designed for both manual and automated execution scenarios 