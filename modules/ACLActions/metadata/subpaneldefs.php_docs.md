# ACLActions Module Subpanel Definitions

/**
 * @fileoverview Subpanel configuration definitions for ACLActions module, managing user-role relationships in ACL action contexts
 * @package SuiteCRM.modules.ACLActions.metadata
 * @copyright SugarCRM Inc. 2004-2013, SalesAgility Ltd. 2011-2018
 * @license GNU Affero General Public License version 3
 */

## Overview

This metadata file defines subpanel configurations for the ACLActions module, providing the same user-role relationship management as the ACL module but within the context of ACL action management. It establishes how users and roles are presented in subpanel views when working with ACL actions and permission configurations.

## Core Configuration

### ACL Module Subpanel Configuration
Identical to the ACL module's subpanel definitions, providing consistency across ACL management interfaces:

#### Users Subpanel
- **Purpose** - Displays users associated with ACL roles in action contexts
- **Module** - Users module integration
- **Order** - Priority 20 in subpanel display order
- **Title** - Localized via 'LBL_USERS_SUBPANEL_TITLE' language key

### UserRoles Module Subpanel Configuration
Manages role display within user management contexts:

#### ACL Roles Subpanel
- **Purpose** - Shows ACL roles associated with users from ACLActions perspective
- **Module** - ACL module integration
- **Order** - Priority 20 in subpanel display order
- **Title** - Localized via 'LBL_ROLES_SUBPANEL_TITLE' language key

## Configuration Details

### Data Relationship Configuration

#### Users Subpanel Data
- **get_subpanel_data** - 'users' relationship link for data retrieval
- **add_subpanel_data** - 'user_id' field for creating new relationships
- **subpanel_name** - 'ForSubModules' template for consistent display
- **top_buttons** - SubPanelTopSubModuleSelectButton for user selection popup

#### ACL Roles Subpanel Data
- **get_subpanel_data** - 'roles' relationship link for role data
- **add_subpanel_data** - 'role_id' field for role relationship creation
- **subpanel_def_path** - 'modules/ACL/Roles/subpanels/default.php' custom path
- **subpanel_name** - 'default' template for role display
- **top_buttons** - SubPanelTopSubModuleSelectButton for ACL selection popup

## UI Functionality

### Subpanel Interface Elements
Each subpanel configuration provides consistent user interface:

#### Button Configuration
```php
'top_buttons' => array(
    array(
        'widget_class' => 'SubPanelTopSubModuleSelectButton',
        'popup_module' => 'Users'  // or 'ACL'
    )
)
```

#### Display Properties
- **Order** - Consistent priority 20 for both configurations
- **Module** - Target module for subpanel data (Users or ACL)
- **Title Key** - Language key for localized subpanel titles
- **Template** - Rendering template for subpanel display

### User Interface Integration
- **Popup Selection** - Modal dialogs for adding user-role relationships
- **Relationship Management** - Visual interface for managing associations
- **Consistent Styling** - Uses standard SuiteCRM subpanel styling
- **Administrative Tools** - Management interfaces for ACL relationships

## Database Operations

### Relationship Management
Same relationship structure as ACL module but accessed through ACLActions:

#### User-Role Associations
- **acl_roles_users** - Many-to-many relationship table
- **Foreign Keys** - user_id and role_id for relationship management
- **Bidirectional Access** - Users in ACL views, roles in user views

#### Data Retrieval Operations
- **Relationship Framework** - SuiteCRM's standard relationship system
- **Efficient Loading** - Optimized queries for subpanel data
- **Real-time Updates** - Dynamic relationship management

## Internal API Calls

### Subpanel Framework Integration
- **SubPanelTopSubModuleSelectButton** - Standard popup selection widget
- **$layout_defs** - Global subpanel configuration registry
- **Language System** - Localized title and label resolution

### Module Integration
- **ACL Module** - Integration with core ACL functionality
- **Users Module** - User data and relationship management
- **Role Management** - ACL role configuration and assignment

## Integration Points

### Related ACLActions Components
- **ACLAction.php** - Core class that these subpanels support administratively
- **Menu.php** - Navigation that leads to interfaces using these subpanels
- **actiondefs.php** - Configuration that informs permission options
- **Role Management System** - Administrative interfaces utilizing subpanels

### Cross-Module Integration
- **ACL Module** - Shared subpanel configuration for consistency
- **ACLRoles Module** - Target for role management operations
- **Users Module** - User management and role assignment
- **Administrative Framework** - System administration interface integration

### Subpanel Framework Integration
- **SuiteCRM Subpanel System** - Core subpanel rendering and management
- **Relationship Framework** - Database relationship management
- **Language System** - Internationalization and localization support
- **Administrative UI** - Management interface components

## Security Considerations

### Access Control
- **Permission-Based Display** - Subpanels respect ACL permissions
- **Administrative Access** - Relationship management requires proper permissions
- **User Authorization** - Role assignment controlled by user permissions
- **Data Security** - Secure handling of user-role relationship data

### Permission Integration
- **ACL Compliance** - Subpanels respect existing ACL restrictions
- **Role-Based Access** - Display based on user's role permissions
- **Security Group Integration** - Supports security group permissions
- **Administrative Oversight** - Proper authorization for relationship changes

## Localization Support

### Multi-Language Integration
- **Language Keys** - Standard SuiteCRM language key usage
- **Translation System** - Integration with translation framework
- **International Support** - Multi-language deployment support
- **Consistent Terminology** - Shared language keys with ACL module

### Title Configuration
- **LBL_USERS_SUBPANEL_TITLE** - "Users" - User subpanel title
- **LBL_ROLES_SUBPANEL_TITLE** - "User Roles" - Role subpanel title
- **Dynamic Translation** - Runtime language selection support

## Administrative Workflow Support

### Role Management Workflows
Subpanels support common administrative tasks:

#### User-Role Assignment
- **Role Assignment** - Assigning roles to users through subpanels
- **Role Removal** - Removing role assignments via interface
- **Bulk Operations** - Mass user-role management capabilities
- **Audit Trail** - Tracking role assignment changes

#### Permission Administration
- **Permission Review** - Viewing user permissions through role assignments
- **Access Analysis** - Understanding user access through roles
- **Administrative Oversight** - Managing organizational access control
- **Security Auditing** - Reviewing and validating permission structures

## Performance Considerations

### Efficient Data Loading
- **Relationship Optimization** - Efficient loading of related data
- **Selective Display** - Only necessary relationship data loaded
- **Caching Integration** - Works with SuiteCRM's caching system
- **Minimal Queries** - Optimized database query patterns

### Scalability Features
- **User Scalability** - Handles large numbers of users efficiently
- **Role Scalability** - Supports complex role hierarchies
- **Relationship Efficiency** - Optimized many-to-many relationship handling
- **Interface Performance** - Responsive subpanel interfaces

## Customization Support

### Configuration Flexibility
- **Template Customization** - Custom subpanel templates
- **Button Configuration** - Configurable top button options
- **Display Options** - Customizable field display and ordering
- **Integration Points** - Support for custom module integration

### Extension Framework
- **Custom Relationships** - Support for additional relationship types
- **Enhanced Functionality** - Extended subpanel capabilities
- **Third-Party Integration** - External system integration support
- **Enterprise Features** - Advanced relationship management

## Consistency with ACL Module

### Shared Configuration
This file maintains identical configuration to `modules/ACL/metadata/subpaneldefs.php`:
- **Same Structure** - Identical array structure and keys
- **Same Relationships** - Same database relationships and paths
- **Same UI Elements** - Consistent user interface components
- **Same Language Keys** - Shared localization strings

### Administrative Consistency
- **Unified Experience** - Consistent interface across ACL modules
- **Shared Workflows** - Same administrative procedures
- **Common Templates** - Shared subpanel templates and styling
- **Integrated Management** - Seamless movement between ACL interfaces

## Notes

- Provides consistent subpanel interface for ACLActions module
- Maintains identical configuration to ACL module for uniformity
- Essential for administrative workflow continuity
- Supports comprehensive user-role relationship management
- Integrates seamlessly with SuiteCRM's subpanel framework
- Critical for ACL administration and role management workflows 