# ACL Module Subpanel Definitions

/**
 * @fileoverview Subpanel configuration definitions for ACL and UserRoles modules, managing user-role relationships in SuiteCRM
 * @package SuiteCRM.modules.ACL.metadata
 * @copyright SugarCRM Inc. 2004-2013, SalesAgility Ltd. 2011-2018
 * @license GNU Affero General Public License version 3
 */

## Overview

This metadata file defines subpanel configurations for the ACL (Access Control List) system, specifically managing the display and interaction of user-role relationships. It establishes how users and roles are presented in subpanel views within the SuiteCRM interface.

## Core Configuration

### ACL Module Subpanels
The file defines subpanel layouts for the ACL module that control how related data is displayed:

#### Users Subpanel
- **Purpose** - Displays users associated with ACL roles
- **Module** - Users module integration
- **Order** - Priority 20 in subpanel display order
- **Title** - Localized via 'LBL_USERS_SUBPANEL_TITLE' language key

### UserRoles Module Subpanels
Configures subpanel display for UserRoles management:

#### ACL Roles Subpanel  
- **Purpose** - Shows ACL roles associated with users
- **Module** - ACL module integration
- **Order** - Priority 20 in subpanel display order
- **Title** - Localized via 'LBL_ROLES_SUBPANEL_TITLE' language key

## UI Functionality

### Subpanel Interface Elements
Each subpanel configuration includes:

#### Top Buttons Configuration
```php
'top_buttons' => array(
    array(
        'widget_class' => 'SubPanelTopSubModuleSelectButton',
        'popup_module' => 'Users'  // or 'ACL'
    )
)
```

#### Display Properties
- **Order** - Subpanel display priority (20 for both configurations)
- **Module** - Target module for subpanel data
- **Subpanel Name** - Template name for subpanel rendering
- **Title Key** - Language key for subpanel title

### Data Relationship Configuration

#### Users Subpanel Data
- **get_subpanel_data** - 'users' relationship link
- **add_subpanel_data** - 'user_id' field for adding relationships
- **subpanel_name** - 'ForSubModules' template

#### ACL Roles Subpanel Data
- **get_subpanel_data** - 'roles' relationship link  
- **add_subpanel_data** - 'role_id' field for adding relationships
- **subpanel_def_path** - Custom path to 'modules/ACL/Roles/subpanels/default.php'
- **subpanel_name** - 'default' template

## Internal API Calls

### Subpanel System Integration
- **SubPanelTopSubModuleSelectButton** - Widget class for popup selection buttons
- **$layout_defs** - Global array for subpanel configuration registration
- Language system integration for localized titles

### Data Access Methods
- **get_subpanel_data** - Specifies relationship method for data retrieval
- **add_subpanel_data** - Defines field for relationship creation
- Module-specific subpanel definition files

## Database Operations

### Relationship Management
- Links ACL roles with users through database relationships
- Supports bidirectional relationship display (users in ACL, roles in UserRoles)
- Manages foreign key relationships for user_id and role_id

### Data Retrieval
- Uses SuiteCRM's relationship framework for subpanel data
- Leverages existing user and role relationships
- Integrates with subpanel data loading mechanisms

## Integration Points

### Related ACL Components
- **modules/ACL/Roles/subpanels/default.php** - Custom subpanel definition for roles
- **ACL module controllers** - Manage ACL role operations
- **Users module** - Provides user data for relationships

### Subpanel Framework
- **SuiteCRM Subpanel System** - Core subpanel rendering and management
- **SubPanelTopSubModuleSelectButton** - Standard popup selection widget
- **Language System** - Provides localized subpanel titles

### User Interface Integration
- **Detail Views** - Subpanels appear in record detail views
- **Popup Selection** - Allows adding/removing user-role relationships
- **Administrative Interface** - Supports ACL management workflows

## Localization Support

### Language Keys
- **LBL_USERS_SUBPANEL_TITLE** - Title for users subpanel display
- **LBL_ROLES_SUBPANEL_TITLE** - Title for roles subpanel display
- Integrates with module language files for multi-language support

### Title Configuration
- Uses language key system for internationalization
- Supports dynamic title generation based on user locale
- Maintains consistency with overall SuiteCRM localization

## Customization Points

### Subpanel Templates
- **ForSubModules** - Standard template for users subpanel
- **default** - Custom template for ACL roles subpanel
- **Custom Paths** - Supports module-specific subpanel definitions

### Button Configuration
- Top buttons can be customized for different selection methods
- Popup modules can be modified for different relationship types
- Widget classes can be extended for custom functionality

## Security Considerations

### Access Control
- Subpanel visibility controlled by ACL permissions
- User access to relationship management governed by role permissions
- Administrative functions protected by proper authorization

### Data Integrity
- Relationship constraints maintained through proper foreign key usage
- Subpanel operations respect existing ACL restrictions
- User-role assignments validated through ACL system

## Performance Considerations

- **Efficient Data Loading** - Uses relationship-based data retrieval
- **Minimal Database Queries** - Leverages SuiteCRM's optimized subpanel system
- **Selective Display** - Only loads necessary relationship data

## Notes

- Essential component of ACL user interface management
- Provides bidirectional view of user-role relationships
- Integrates seamlessly with SuiteCRM's subpanel framework
- Supports both standard and custom subpanel templates
- Critical for administrative ACL management workflows 