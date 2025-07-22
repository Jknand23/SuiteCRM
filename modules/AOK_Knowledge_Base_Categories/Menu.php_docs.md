# Menu.php Documentation

/**
 * @fileoverview Menu configuration for Knowledge Base Categories module defining navigation items with ACL integration
 * @package modules/AOK_Knowledge_Base_Categories
 * @copyright SugarCRM Inc. & SalesAgility Ltd.
 * @license AGPL v3
 */

## Overview
The Menu.php file defines the navigation menu items for the Knowledge Base Categories module. It configures the module's menu entries with appropriate access control checks to ensure users only see options they have permission to access.

## Security Integration

### Access Control Checks
The menu system implements security through ACLController checks before displaying menu items:

#### Create Category Access
```php
ACLController::checkAccess('AOK_Knowledge_Base_Categories', 'edit', true)
```
- **Action**: `edit` - Requires edit permission to create new categories
- **Module**: `AOK_Knowledge_Base_Categories`
- **User Check**: `true` - Validates current user permissions

#### List Categories Access
```php
ACLController::checkAccess('AOK_Knowledge_Base_Categories', 'list', true)
```
- **Action**: `list` - Requires list permission to view categories
- **Module**: `AOK_Knowledge_Base_Categories`
- **User Check**: `true` - Validates current user permissions

## Menu Items Configuration

### Create New Category Item
- **URL**: `index.php?module=AOK_Knowledge_Base_Categories&action=EditView`
- **Label**: `$mod_strings['LNK_NEW_RECORD']` - "Create KB Categories"
- **Icon**: `Create` - Standard creation icon
- **Module Reference**: `AOK_Knowledge_Base_Categories`

### View Categories List Item
- **URL**: `index.php?module=AOK_Knowledge_Base_Categories&action=index`
- **Label**: `$mod_strings['LNK_LIST']` - "View KB Categories"
- **Icon**: `List` - Standard list view icon
- **Module Reference**: `AOK_Knowledge_Base_Categories`

## UI Functionality
- Provides module navigation through SuiteCRM's standard menu system
- Integrates with global navigation structure
- Displays menu items conditionally based on user permissions
- Uses localized labels from module language strings

## Internal API Integration
- **ACLController**: Validates user permissions before displaying menu options
- **Language System**: References `$mod_strings` for localized menu labels
- **Module Framework**: Follows SuiteCRM's standard menu configuration patterns

## Global Variable Dependencies
- `$mod_strings` - Module-specific language strings for menu labels
- `$app_strings` - Application-wide language strings (imported but not used)
- `$sugar_config` - Global configuration settings (imported but not used)

## Integration Points
- **Access Control System**: Menu visibility controlled by ACL permissions
- **Language System**: Menu labels from language files
- **Navigation Framework**: Integrates with SuiteCRM's module menu system
- **User Management**: Respects user role and permission settings

## Security Features
- Menu items only appear if user has appropriate permissions
- Prevents unauthorized access through UI navigation
- Follows principle of least privilege for menu display
- Integrates with SuiteCRM's role-based security model 