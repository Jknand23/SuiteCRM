# Menu.php Documentation

/**
 * @fileoverview Menu configuration for Knowledge Base module defining navigation items with ACL integration
 * @package modules/AOK_KnowledgeBase
 * @copyright SugarCRM Inc. & SalesAgility Ltd.
 * @license AGPL v3
 */

## Overview
The Menu.php file defines the navigation menu items for the Knowledge Base module. It configures the module's menu entries with appropriate access control checks to ensure users only see options they have permission to access for knowledge base article management.

## Security Integration

### Access Control Checks
The menu system implements security through ACLController checks before displaying menu items:

#### Create Article Access
```php
ACLController::checkAccess('AOK_KnowledgeBase', 'edit', true)
```
- **Action**: `edit` - Requires edit permission to create new knowledge base articles
- **Module**: `AOK_KnowledgeBase`
- **User Check**: `true` - Validates current user permissions

#### List Articles Access
```php
ACLController::checkAccess('AOK_KnowledgeBase', 'list', true)
```
- **Action**: `list` - Requires list permission to view knowledge base articles
- **Module**: `AOK_KnowledgeBase`
- **User Check**: `true` - Validates current user permissions

## Menu Items Configuration

### Create New Article Item
- **URL**: `index.php?module=AOK_KnowledgeBase&action=EditView`
- **Label**: `$mod_strings['LNK_NEW_RECORD']` - "Create Knowledge Base"
- **Icon**: `Create` - Standard creation icon
- **Module Reference**: `AOK_KnowledgeBase`

### View Articles List Item
- **URL**: `index.php?module=AOK_KnowledgeBase&action=index`
- **Label**: `$mod_strings['LNK_LIST']` - "View Knowledge Base"
- **Icon**: `List` - Standard list view icon
- **Module Reference**: `AOK_KnowledgeBase`

## UI Functionality
- Provides module navigation through SuiteCRM's standard menu system
- Integrates with global navigation structure for knowledge base access
- Displays menu items conditionally based on user permissions
- Uses localized labels from module language strings
- Supports knowledge base workflow through creation and listing interfaces

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
- **Knowledge Base Workflow**: Provides access to article creation and management

## Security Features
- Menu items only appear if user has appropriate permissions
- Prevents unauthorized access through UI navigation
- Follows principle of least privilege for menu display
- Integrates with SuiteCRM's role-based security model
- Supports knowledge base-specific access control

## Workflow Integration
- Create menu item leads to EditView for new article creation
- List menu item provides access to knowledge base article management
- Menu structure supports knowledge base content lifecycle
- Integration with module workflow through standard SuiteCRM patterns 