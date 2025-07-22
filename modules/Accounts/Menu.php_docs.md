# Menu.php Documentation

## @fileoverview
Navigation menu configuration for the Accounts module that defines user interface menu items with proper access control integration and localized menu generation.

## @package SuiteCRM\Modules\Accounts
## @copyright SugarCRM Inc. & SalesAgility Ltd.
## @license AGPL-3.0

## Overview

This file configures the navigation menu structure for the Accounts module, defining the available menu items and their associated actions. It integrates with the ACL (Access Control List) system to ensure users only see menu options they have permission to access.

## Database Operations

### Access Control Integration
- **ACL Permission Checking**: Uses ACLController::checkAccess() for permission validation
- **Module-Level Security**: Checks 'Accounts' module access permissions
- **Action-Level Security**: Validates specific action permissions (edit, list, import)

## Internal API Calls

### Access Control System
- **`ACLController::checkAccess()`**: Validates user permissions for menu item display
- **Permission Types**: Checks edit, list, and import permissions individually
- **User Context**: Operates within current user's permission context

### Localization System
- **Global String Access**: Uses $mod_strings for module-specific labels
- **Application Strings**: Accesses $app_strings for common interface elements
- **Configuration Access**: Utilizes $sugar_config for system configuration

## External API Calls

### Module Navigation Framework
- **Menu Array Population**: Populates $module_menu array with navigation items
- **URL Generation**: Creates proper module URLs with return parameters
- **Icon Integration**: Associates menu items with appropriate icons

## UI Functionality

### Menu Item Configuration

#### Create Account Menu Item
- **URL**: `index.php?module=Accounts&action=EditView&return_module=Accounts&return_action=index`
- **Label**: `$mod_strings['LNK_NEW_ACCOUNT']`
- **Icon**: `Create`
- **Module**: `Accounts`
- **Permission Required**: Edit access to Accounts module

#### List Accounts Menu Item
- **URL**: `index.php?module=Accounts&action=index&return_module=Accounts&return_action=DetailView`
- **Label**: `$mod_strings['LNK_ACCOUNT_LIST']`
- **Icon**: `List`
- **Module**: `Accounts`
- **Permission Required**: List access to Accounts module

#### Import Accounts Menu Item
- **URL**: `index.php?module=Import&action=Step1&import_module=Accounts&return_module=Accounts&return_action=index`
- **Label**: `$mod_strings['LNK_IMPORT_ACCOUNTS']`
- **Icon**: `Import`
- **Module**: `Accounts`
- **Permission Required**: Import access to Accounts module

### Navigation Integration
- **Return Parameter Management**: Proper return module and action specification
- **Context Preservation**: Maintains navigation context for user experience
- **Module Integration**: Seamless integration with SuiteCRM navigation framework

## Security Implementation

### Access Control Integration
- **Permission-Based Display**: Menu items only appear for authorized users
- **Granular Permissions**: Individual permission checking for each menu action
- **Security Context**: Operates within user's security context

### Entry Point Security
- **SugarEntry Validation**: Standard entry point security validation
- **Direct Access Prevention**: Prevents unauthorized direct file access

## Menu Structure

### Standard Menu Items
1. **Create Account**: New account creation interface
2. **List Accounts**: Account list view and management
3. **Import Accounts**: Account import functionality

### Permission Matrix
- **Edit Permission**: Required for Create Account menu item
- **List Permission**: Required for List Accounts menu item  
- **Import Permission**: Required for Import Accounts menu item

## Localization Support

### String Resources
- **Module Strings**: Uses $mod_strings for module-specific labels
- **Localized Labels**: Fully localized menu item labels
- **Multi-Language Support**: Supports all SuiteCRM language packages

### Configuration Integration
- **System Configuration**: Integrates with $sugar_config for system settings
- **Theme Integration**: Compatible with SuiteCRM theme system
- **Customization Support**: Supports menu customization through extensions

## Integration Points

### SuiteCRM Navigation Framework
- **Module Menu Integration**: Integrates with global module menu system
- **Theme Compatibility**: Works with all SuiteCRM themes
- **Extension Support**: Supports menu extensions and customizations

### ACL System Integration
- **Role-Based Access**: Integrates with SuiteCRM role-based access control
- **Permission Inheritance**: Inherits permissions from user roles and groups
- **Dynamic Menu Generation**: Dynamically generates menu based on user permissions

### Module System Integration
- **Import Module Integration**: Seamless integration with Import module
- **Return Parameter Support**: Proper integration with module navigation
- **Context Preservation**: Maintains navigation context across modules 