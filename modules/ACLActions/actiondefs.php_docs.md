# ACL Action Definitions Configuration

/**
 * @fileoverview Core ACL access level constants and action configuration definitions for SuiteCRM permission system
 * @package SuiteCRM.modules.ACLActions
 * @copyright SugarCRM Inc. 2004-2013, SalesAgility Ltd. 2011-2018
 * @license GNU Affero General Public License version 3
 */

## Overview

This configuration file establishes the foundation of SuiteCRM's Access Control List (ACL) system by defining access level constants, visual rendering properties, and default action configurations for modules. It provides the framework for granular permission control throughout the application.

## Access Level Constants

### Permission Hierarchy
The file defines a comprehensive hierarchy of access levels using PHP constants:

#### Administrative Levels
- **ACL_ALLOW_ADMIN_DEV** (100) - Combined administrator and developer access
- **ACL_ALLOW_ADMIN** (99) - Full administrative access
- **ACL_ALLOW_DEV** (95) - Developer-level access

#### Standard Access Levels  
- **ACL_ALLOW_ALL** (90) - Full access to all records
- **ACL_ALLOW_ENABLED** (89) - Module access enabled
- **ACL_ALLOW_OWNER** (75) - Owner-only access to records
- **ACL_ALLOW_NORMAL** (1) - Normal user access

#### Restrictive Levels
- **ACL_ALLOW_DEFAULT** (0) - Default/not set access level
- **ACL_ALLOW_DISABLED** (-98) - Module access disabled
- **ACL_ALLOW_NONE** (-99) - No access permitted

### Permission Resolution
- **Numeric Priority** - Higher values take precedence over lower values
- **Hierarchical Structure** - Clear permission inheritance model
- **Override Capability** - Role-specific access can override defaults
- **Conditional Protection** - Duplicate definition protection prevents conflicts

## UI Functionality

### Access Level Rendering Configuration
The `$GLOBALS['ACLActionAccessLevels']` array defines visual presentation properties:

#### Visual Elements
- **Color Coding** - Hex colors for visual permission indication
- **Text Colors** - Contrasting text colors for readability
- **Label Keys** - Translatable labels for internationalization

#### Color Scheme
- **Green (#008000)** - Positive access (All, Enabled, Normal, Default)
- **Red (#FF0000)** - Restrictive access (None, Disabled)
- **Blue (#0000FF)** - Administrative access (Admin, Developer)
- **Dark Yellow (#6F6800)** - Owner-only access
- **White Text** - Consistent text color across all levels

### Internationalization Support
- **Label Translation** - All access levels use translatable label keys
- **Consistent Naming** - Standardized label format (LBL_ACCESS_*)
- **Multi-language Ready** - Prepared for language pack translations

## Module Action Framework

### Standard Module Actions
The `$GLOBALS['ACLActions']` array defines default actions for module-type permissions:

#### Core Actions Configuration
Each action includes:
- **Access Levels** - Array of permitted access levels for the action
- **Label Key** - Translatable label for the action
- **Default Level** - Default access level when not explicitly set

#### Standard Actions

##### Module Access Control
- **access** - Basic module access permission
  - Levels: ENABLED, DEFAULT, DISABLED
  - Default: ACL_ALLOW_ENABLED
  - Controls overall module availability

##### Record Operations
- **view** - Record viewing permissions
  - Levels: ALL, OWNER, DEFAULT, NONE
  - Default: ACL_ALLOW_ALL
  - Controls individual record display

- **list** - List view access control
  - Levels: ALL, OWNER, DEFAULT, NONE
  - Default: ACL_ALLOW_ALL
  - Controls module listing capabilities

- **edit** - Record modification permissions
  - Levels: ALL, OWNER, DEFAULT, NONE
  - Default: ACL_ALLOW_ALL
  - Controls record update operations

- **delete** - Record deletion permissions
  - Levels: ALL, OWNER, DEFAULT, NONE
  - Default: ACL_ALLOW_ALL
  - Controls record removal operations

##### Data Management Actions
- **import** - Data import permissions
  - Levels: ALL, DEFAULT, NONE
  - Default: ACL_ALLOW_ALL
  - Controls bulk data import capability

- **export** - Data export permissions
  - Levels: ALL, OWNER, DEFAULT, NONE
  - Default: ACL_ALLOW_ALL
  - Controls data extraction operations

- **massupdate** - Bulk update permissions
  - Levels: ALL, DEFAULT, NONE
  - Default: ACL_ALLOW_ALL
  - Controls mass record modification

## Internal API Calls

### Global Variable Integration
- **$GLOBALS['ACLActionAccessLevels']** - Access level configuration registry
- **$GLOBALS['ACLActions']** - Action definition registry
- **Constant Definition Protection** - Prevents redefinition conflicts

### System Integration
- **ACLAction Class** - Utilizes these definitions for permission checking
- **Role Management** - Inherits action definitions for role configuration
- **Security Framework** - Foundation for all permission operations
- **UI Rendering** - Provides visual configuration for ACL interfaces

## Integration Points

### Related ACL Components
- **ACLAction.php** - Core class implementing these definitions
- **actiondefs.override.php** - Security groups extension of these definitions
- **ACL Role Management** - Uses action definitions for role configuration
- **Permission Resolution** - Foundation for all access control decisions

### Module Framework
- **Module Registration** - Automatic ACL action creation for new modules
- **Permission Inheritance** - Default permissions for all ACL-enabled modules
- **Administrative Interface** - Configuration options for ACL management
- **Security Groups** - Extended through actiondefs.override.php

## Security Considerations

### Permission Model
- **Positive Security** - Explicit permission granting model
- **Hierarchical Access** - Clear permission inheritance structure
- **Default Security** - Conservative default permission settings
- **Override Protection** - Role-based permission overrides supported

### Access Control Framework
- **Granular Permissions** - Individual action-level control
- **Owner-Based Security** - Record ownership recognition
- **Module-Level Control** - Entire module access management
- **Administrative Separation** - Clear admin/user permission distinction

## Customization Support

### Extension Framework
- **Action Addition** - Support for custom action types
- **Access Level Modification** - Configurable permission hierarchies
- **Visual Customization** - Modifiable color schemes and labels
- **Module-Specific Actions** - Custom actions per module type

### Override Mechanism
- **Security Groups** - Extended through actiondefs.override.php
- **Custom Permissions** - Application-specific access levels
- **Third-Party Integration** - External permission system compatibility
- **Enterprise Extensions** - Advanced permission models

## Performance Considerations

### Efficient Design
- **Constant Values** - Fast numeric comparison for permission checking
- **Array-Based Configuration** - Efficient lookup mechanisms
- **Minimal Processing** - Lightweight permission resolution
- **Caching Friendly** - Static configuration supports caching strategies

### Scalability Features
- **Hierarchical Numbering** - Allows for permission level expansion
- **Modular Design** - Independent action configuration per module
- **Memory Efficient** - Minimal memory footprint for configuration
- **Runtime Optimization** - Pre-compiled constant values

## Notes

- Core foundation of SuiteCRM's permission system
- Provides standard framework for all module permissions
- Supports visual consistency across ACL interfaces
- Designed for both standard and enterprise security requirements
- Extended through actiondefs.override.php for security groups functionality 