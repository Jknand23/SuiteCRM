# Menu.php Documentation

## @fileoverview
Navigation menu configuration for the ACLRoles module. This file defines the module's menu items, including role management, user role listings, and integration with SecurityGroups functionality.

## @package
SuiteCRM ACLRoles Module - Navigation menu configuration

## @copyright
Copyright (C) 2004-2013 SugarCRM Inc., 2011-2018 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Menu Structure Definition

### Core Menu Items

The `$module_menu` array defines the primary navigation options for the ACLRoles module:

#### Create Role MenuItem
```php
array("index.php?module=ACLRoles&action=EditView", $mod_strings['LBL_CREATE_ROLE'], "Create")
```
- **URL:** `index.php?module=ACLRoles&action=EditView`
- **Label:** `$mod_strings['LBL_CREATE_ROLE']`
- **Icon:** "Create"
- **Purpose:** Allows administrators to create new ACL roles

#### List Roles MenuItem
```php
array("index.php?module=ACLRoles&action=index", $mod_strings['LIST_ROLES'], "Role_Management")
```
- **URL:** `index.php?module=ACLRoles&action=index`
- **Label:** `$mod_strings['LIST_ROLES']`
- **Icon:** "Role_Management"
- **Purpose:** Displays list view of all existing roles

#### List Roles by User MenuItem
```php
array("index.php?module=ACLRoles&action=ListUsers", $mod_strings['LIST_ROLES_BY_USER'], "List")
```
- **URL:** `index.php?module=ACLRoles&action=ListUsers`
- **Label:** `$mod_strings['LIST_ROLES_BY_USER']`
- **Icon:** "List"
- **Purpose:** Shows role assignments organized by user

## Internal API Calls

### Language Integration
- `$mod_strings`: ACLRoles module language strings
- `return_module_language($current_language, 'SecurityGroups')`: Dynamic language loading for SecurityGroups
- `return_module_language($current_language, 'Administration')`: Dynamic language loading for Administration

### Global Variable Access
- `global $mod_strings`: Module-specific language strings
- `global $current_language`: Current user's language preference
- `global $current_user`: Current user object for permission checking

### Permission Checking
- `is_admin($current_user)`: Validates administrative privileges for conditional menu items

## SecurityGroups Integration

### SecurityGroups Menu Items
The menu dynamically adds SecurityGroups-related functionality:

#### Create SecurityGroup MenuItem
```php
$module_menu[] = array(
    "index.php?module=SecurityGroups&action=EditView&return_module=SecurityGroups&return_action=DetailView", 
    $sg_mod_strings['LNK_NEW_RECORD'], 
    "Create"
);
```
- **Purpose:** Create new security groups
- **Return Navigation:** Returns to SecurityGroups DetailView after creation

#### List SecurityGroups MenuItem
```php
$module_menu[] = array(
    "index.php?module=SecurityGroups&action=ListView&return_module=SecurityGroups&return_action=ListView", 
    $sg_mod_strings['LBL_LIST_FORM_TITLE'], 
    "Security_Groups"
);
```
- **Purpose:** Display list of all security groups
- **Return Navigation:** Maintains SecurityGroups ListView context

### Administrative Menu Items
Additional menu items are added for administrators:

#### Manage Users MenuItem
```php
$module_menu[] = array(
    "index.php?module=Users&action=index&return_module=SecurityGroups&return_action=ListView", 
    $admin_mod_strings['LBL_MANAGE_USERS_TITLE'], 
    "List"
);
```
- **Condition:** Only visible to administrators
- **Purpose:** Direct access to user management from role context

#### SecurityGroups Configuration MenuItem
```php
$module_menu[] = array(
    "index.php?module=SecurityGroups&action=config&return_module=SecurityGroups&return_action=ListView", 
    $admin_mod_strings['LBL_CONFIG_SECURITYGROUPS_TITLE'], 
    "Security_Groups"
);
```
- **Condition:** Only visible to administrators
- **Purpose:** Access SecurityGroups configuration settings

## UI Functionality

### Menu Item Structure
Each menu item follows the standard SuiteCRM menu format:
1. **URL**: Complete navigation path with module, action, and parameters
2. **Label**: Localized text from language files
3. **Icon**: CSS class or icon identifier for visual representation

### Dynamic Menu Generation
- Menu items are conditionally added based on user permissions
- Language strings are dynamically loaded for SecurityGroups integration
- Administrative privileges control visibility of advanced options

### Return Navigation
Menu items include return navigation parameters:
- `return_module`: Module to return to after action completion
- `return_action`: Specific action to execute on return
- Maintains user context and workflow continuity

## Security and Access Control

### Entry Point Validation
```php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
```

### Permission-Based Menu Items
- Administrative functions only visible to users with `is_admin()` privileges
- SecurityGroups integration respects module-level permissions
- Menu items filtered based on user's role assignments

### Cross-Module Security
- SecurityGroups menu items respect SecurityGroups module permissions
- Users module access controlled through administrative privileges
- Configuration options limited to system administrators

## Integration Points

### Module Cross-Reference
The menu creates navigation links between related modules:
- **ACLRoles ↔ SecurityGroups**: Bidirectional navigation for access control management
- **ACLRoles ↔ Users**: Direct access to user management for role assignment
- **ACLRoles ↔ Administration**: Access to system configuration options

### Language File Integration
- `modules/ACLRoles/language/en_us.lang.php`: Primary module labels
- `modules/SecurityGroups/language/en_us.lang.php`: SecurityGroups labels  
- `modules/Administration/language/en_us.lang.php`: Administrative function labels

### Icon and Styling Integration
- Menu icons integrate with SuiteCRM's theme system
- Icon classes: "Create", "Role_Management", "List", "Security_Groups"
- Icons automatically adapt to active theme styling

## Performance Considerations

### Language Loading Optimization
- Language files loaded only when needed
- Uses `return_module_language()` for dynamic loading
- Avoids loading unnecessary language strings

### Conditional Loading
- Administrative menu items loaded only for admin users
- SecurityGroups integration loaded for all users (security model dependent)
- Menu array built incrementally to minimize memory usage

### Caching Integration
- Menu structure benefits from SuiteCRM's caching system
- Language strings cached by language loading system
- Permission checks cached per user session

## Menu Item URL Parameters

### Standard Parameters
- `module`: Target module name
- `action`: Specific action to execute
- `return_module`: Module to return to after completion
- `return_action`: Action to execute on return

### ACLRoles-Specific Parameters
- `record`: Role ID for edit/detail operations
- `isDuplicate`: Flag for role duplication workflow

### SecurityGroups-Specific Parameters
- Return navigation maintains SecurityGroups context
- Configuration actions include proper return paths

## Internationalization Support

### Language String Sources
- ACLRoles module strings from `$mod_strings`
- SecurityGroups strings from `$sg_mod_strings`
- Administration strings from `$admin_mod_strings`

### Multi-Language Support
- All menu labels support full internationalization
- Language selection based on `$current_language` global
- Fallback to English if translations unavailable

### Label Consistency
- Menu labels consistent with corresponding page titles
- Icon names standardized across SuiteCRM interface
- Return navigation labels maintain context clarity 