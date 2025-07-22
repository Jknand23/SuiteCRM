# ACLActions Module English Language Definitions

/**
 * @fileoverview English language strings for ACLActions module including access levels, action types, and user interface elements
 * @package SuiteCRM.modules.ACLActions.language
 * @copyright SugarCRM Inc. 2004-2013, SalesAgility Ltd. 2011-2019
 * @license GNU Affero General Public License version 3
 */

## Overview

This language file defines comprehensive English text strings for the ACLActions module in SuiteCRM. It provides localized labels for access levels, action types, administrative interfaces, and user-facing messages that appear throughout the ACL management system.

## Language String Categories

### Access Level Labels
The file defines human-readable labels for all ACL access levels:

#### Standard Access Levels
- **LBL_ACCESS_ALL** - 'All' - Full unrestricted access to all records
- **LBL_ACCESS_NONE** - 'None' - Complete denial of access
- **LBL_ACCESS_OWNER** - 'Owner' - Access limited to record owners
- **LBL_ACCESS_NORMAL** - 'Normal' - Standard user access level
- **LBL_ACCESS_DEFAULT** - 'Not Set' - Default/unspecified access level

#### Administrative Access Levels
- **LBL_ACCESS_ADMIN** - 'Admin' - Administrative access level
- **LBL_ACCESS_DEV** - 'Developer' - Developer-level access
- **LBL_ACCESS_ADMIN_DEV** - 'Admin & Developer' - Combined admin and developer access

#### Module Control Levels
- **LBL_ACCESS_ENABLED** - 'Enabled' - Module access enabled
- **LBL_ACCESS_DISABLED** - 'Disabled' - Module access disabled

#### Security Group Integration
- **LBL_ACCESS_GROUP** - 'Group' - Security group-based access level

### Action Type Labels
Defines labels for standard ACL actions:

#### Record Operations
- **LBL_ACTION_VIEW** - 'View' - Record viewing permission
- **LBL_ACTION_EDIT** - 'Edit' - Record modification permission
- **LBL_ACTION_DELETE** - 'Delete' - Record deletion permission
- **LBL_ACTION_LIST** - 'List' - List view access permission

#### Data Management Actions
- **LBL_ACTION_IMPORT** - 'Import' - Data import permission
- **LBL_ACTION_EXPORT** - 'Export' - Data export permission
- **LBL_ACTION_MASSUPDATE** - 'Mass Update' - Bulk update permission

#### Access Control Actions
- **LBL_ACTION_ACCESS** - 'Access' - Basic module access permission
- **LBL_ACTION_ADMIN** - 'Access Type' - Administrative access type control

### Core Entity Labels
Standard entity and interface labels:

#### Basic Entity Labels
- **LBL_NAME** - 'Name' - Generic name field label
- **LBL_DESCRIPTION** - 'Description' - Generic description field label

#### List and Navigation Labels
- **LIST_ROLES** - 'List Roles' - Role listing interface title
- **LIST_ROLES_BY_USER** - 'List Roles By User' - User-centric role view title
- **LBL_SEARCH_FORM_TITLE** - 'Search' - Search form interface title

### Subpanel Integration Labels
Labels for relationship subpanels:

#### User-Role Relationship Labels
- **LBL_USERS_SUBPANEL_TITLE** - 'Users' - Users subpanel title
- **LBL_ROLES_SUBPANEL_TITLE** - 'User Roles' - Roles subpanel title

## UI Functionality

### Access Level Visualization
These language strings support visual access level indication:

#### Permission Matrix Display
- **Color-Coded Labels** - Labels correspond to color-coded access levels
- **Clear Distinction** - Descriptive labels for easy understanding
- **Administrative Clarity** - Clear labeling for administrative interfaces
- **User-Friendly Terms** - Non-technical language for broad usability

#### Form Integration
- **Dropdown Options** - Labels used in access level selection dropdowns
- **Radio Buttons** - Clear options for permission selection forms
- **Checkbox Labels** - Descriptive text for bulk permission operations
- **Validation Messages** - User-friendly error and confirmation messages

### Administrative Interface Support
- **Permission Configuration** - Labels for complex permission setup interfaces
- **Role Management** - Clear labeling for role assignment and management
- **User Administration** - Descriptive labels for user permission management
- **Audit Interfaces** - Labels for permission review and auditing tools

## Internal API Calls

### Translation System Integration
- **$mod_strings** - Global array containing module-specific translations
- **translate()** - Function integration for retrieving localized strings
- **Language Framework** - Integration with SuiteCRM's internationalization system

### ACL System Integration
- **Access Level Rendering** - Labels used in visual access level displays
- **Action Configuration** - Action labels used in permission configuration
- **Administrative Tools** - Labels for ACL management interfaces
- **User Interface Components** - Form and display element labeling

## Integration Points

### Related ACLActions Components
- **actiondefs.php** - Uses language keys for action and access level labels
- **actiondefs.override.php** - References language keys for security group features
- **ACLAction.php** - Uses translation system for access level names
- **Menu.php** - References language keys for navigation labels

### Cross-Module Integration
- **ACL Module** - Shares common language keys for consistency
- **ACLRoles Module** - References role-related language strings
- **Users Module** - Integration with user management language strings
- **Administrative Framework** - Provides labels for admin interfaces

### Localization Framework
- **SuiteCRM Language System** - Part of broader localization infrastructure
- **Multi-language Support** - Foundation for additional language translations
- **Language Pack Integration** - Compatible with SuiteCRM language pack system
- **Translation Management** - Supports professional translation workflows

## Security and Access Control Integration

### Permission Level Communication
- **Clear Security Messaging** - Unambiguous permission level descriptions
- **Access Denial Information** - Clear communication about access restrictions
- **Administrative Feedback** - Descriptive labels for permission operations
- **User Guidance** - Helpful labels for understanding access levels

### Administrative Communication
- **Permission Configuration** - Clear labels for complex permission setups
- **Access Level Selection** - Descriptive options for permission choices
- **Role Management** - Clear communication about role-based permissions
- **Security Group Integration** - Labels supporting group-based access control

## Localization Support

### Translation Framework
- **Base Language** - Serves as English foundation for other language files
- **Consistent Terminology** - Standardized language across ACL components
- **Professional Translation** - Clear, professional terminology for business use
- **Technical Accuracy** - Precise language for technical permission concepts

### International Deployment
- **Unicode Support** - Proper encoding for international character sets
- **Cultural Adaptation** - Language suitable for various English-speaking regions
- **Professional Standards** - Business-appropriate terminology and phrasing
- **Accessibility** - Clear language for users with varying technical backgrounds

## Administrative Workflow Support

### Permission Management Workflows
Language strings support typical ACL administration:

#### Access Level Configuration
- **Clear Options** - Unambiguous access level choices
- **Descriptive Labels** - Self-explanatory permission descriptions
- **Administrative Clarity** - Clear language for complex permission operations
- **User Education** - Labels that help users understand permission implications

#### Role and User Management
- **Role Assignment** - Clear language for role-based permission management
- **User Access Review** - Descriptive labels for user permission auditing
- **Permission Analysis** - Clear terminology for access level analysis
- **Administrative Oversight** - Professional language for management interfaces

## Performance Considerations

### Efficient Language Loading
- **Lightweight Structure** - Simple array-based language configuration
- **Fast Access** - Direct array key access for quick translation
- **Memory Efficient** - Minimal memory footprint for language data
- **Caching Compatible** - Works with SuiteCRM's language caching systems

### Scalability Features
- **Module Independence** - Language strings independent of data volume
- **User Scalability** - Performance unaffected by number of users or permissions
- **Interface Efficiency** - Quick language resolution for responsive interfaces
- **Minimal Processing** - Direct string access without complex processing

## Customization Support

### Language Customization
- **Easy Modification** - Simple array structure for label customization
- **Organizational Terminology** - Can be adapted for organization-specific terms
- **Professional Branding** - Labels can reflect organizational communication style
- **Custom Access Levels** - Support for custom permission level descriptions

### Extension Framework
- **Additional Labels** - Can be extended with custom action or access level labels
- **Module Integration** - Support for custom ACL module language needs
- **Third-Party Compatibility** - Compatible with external ACL system extensions
- **Enterprise Customization** - Support for enterprise-specific terminology

## Integration with Action Definitions

### Action Configuration Support
- **Action Labels** - Corresponds to actiondefs.php action configurations
- **Access Level Labels** - Matches access level constants in configuration files
- **Consistent Mapping** - Direct correlation between configuration and language
- **Visual Integration** - Labels support color-coded visual access level displays

### Configuration Consistency
- **Standard Actions** - Labels for all standard ACL actions (view, edit, delete, etc.)
- **Access Hierarchy** - Labels reflect permission hierarchy and relationships
- **Administrative Actions** - Special labels for administrative permission types
- **Security Integration** - Labels support security group and role-based features

## Notes

- Essential component of ACL user interface and administration
- Provides foundation for multi-language ACL functionality
- Supports both technical and non-technical users
- Critical for clear communication about security permissions
- Maintains consistency with broader SuiteCRM language standards
- Enables professional, business-appropriate ACL management interfaces 