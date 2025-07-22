# ACL Module English Language Definitions

/**
 * @fileoverview English language strings for ACL (Access Control List) module user interface elements and messages
 * @package SuiteCRM.modules.ACL.language
 * @copyright SugarCRM Inc. 2004-2013, SalesAgility Ltd. 2011-2019
 * @license GNU Affero General Public License version 3
 */

## Overview

This language file defines English text strings used throughout the ACL (Access Control List) module interface in SuiteCRM. It provides localized labels, titles, messages, and other text elements that appear in the user interface for ACL management functionality.

## Language String Categories

### Core Entity Labels
The file includes fundamental labels for ACL entities:

#### Role Management
- **LBL_ROLE** - 'Role' - Basic role entity label
- **LBL_NAME** - 'Name' - Generic name field label  
- **LBL_DESCRIPTION** - 'Description' - Generic description field label

#### List Views
- **LIST_ROLES** - 'List Roles' - Title for roles listing page
- **LIST_ROLES_BY_USER** - 'List Roles By User' - Title for user-centric role view

### Subpanel Titles
Defines titles for relationship subpanels:

#### User-Role Relationships
- **LBL_USERS_SUBPANEL_TITLE** - 'Users' - Title for users subpanel in ACL views
- **LBL_ROLES_SUBPANEL_TITLE** - 'User Roles' - Title for roles subpanel in user views

### Search and Navigation
Interface elements for search and navigation:

#### Search Interface
- **LBL_SEARCH_FORM_TITLE** - 'Search' - Generic search form title

### Security and Access Messages
User-facing security and access control messages:

#### Access Denied Messages
- **LBL_NO_ACCESS** - 'You do not have access to this area. Contact your site administrator to obtain access.'
- **LBL_REDIRECT_TO_HOME** - 'Redirect to Home in' - Message for automatic redirection
- **LBL_SECONDS** - 'seconds' - Time unit for redirection countdown

### Administrative Messages
Messages used in administrative operations:

#### Installation and Maintenance
- **LBL_ADDING** - 'Adding for ' - Progress message during ACL installation

## UI Functionality

### User Interface Integration
These language strings support various UI components:

#### Form Labels
- Field labels for ACL role forms
- Input field descriptions and placeholders
- Form section headers and instructions

#### List View Headers
- Column headers for role and user listings
- Sort and filter option labels
- Pagination and navigation elements

#### Subpanel Display
- Subpanel titles for related record display
- Relationship management interface labels
- Add/remove relationship button text

### Error and Status Messages
- **Access Control** - Clear messaging for permission-related restrictions
- **Navigation** - User-friendly redirection notifications with countdown
- **Progress Indication** - Installation and maintenance status updates

## Internal API Calls

### Language System Integration
- **$mod_strings** - Global array containing module-specific translations
- **translate()** - Function for retrieving localized strings in code
- Integration with SuiteCRM's internationalization framework

### Module Integration
- Used by ACL controllers for user-facing messages
- Referenced in ACL views and templates
- Integrated with form generation and validation systems

## Integration Points

### Related ACL Components
- **ACLController.php** - Uses language strings for user interface
- **subpaneldefs.php** - References subpanel title keys
- **install_actions.php** - Uses installation progress messages
- **ACL views and templates** - Display localized interface elements

### Localization Framework
- **SuiteCRM Language System** - Part of broader localization infrastructure
- **Multi-language Support** - Base for additional language translations
- **Translation Management** - Supports language pack creation and updates

### User Interface Components
- **Form Generation** - Provides labels for automated form creation
- **List Views** - Supplies column headers and navigation text
- **Subpanels** - Defines relationship display titles
- **Security Interface** - Provides access control messaging

## Localization Support

### Translation Framework
- **Base Language** - Serves as English reference for other language files
- **Language Packs** - Foundation for creating additional language translations
- **Unicode Support** - Properly encoded for international character sets

### Customization Support
- **Easily Modifiable** - Simple array structure for customization
- **Extension Friendly** - Can be extended with additional strings
- **Upgrade Safe** - Can be preserved through custom language directories

## Security and Access Control

### Access Messages
- **Clear Communication** - Explicit messaging about access restrictions
- **User Guidance** - Directs users to administrators for access requests
- **Graceful Handling** - Professional presentation of security restrictions

### Administrative Feedback
- **Progress Reporting** - Clear indication of administrative operations
- **Status Updates** - Real-time feedback during system maintenance
- **Professional Presentation** - Consistent messaging throughout ACL operations

## Customization Considerations

### String Modification
- **Simple Updates** - Easy modification of display text
- **Consistency Maintenance** - Ensure consistency across related modules
- **Character Limits** - Consider UI space constraints when modifying

### Extension Support
- **Additional Strings** - Can add new language entries for custom functionality
- **Module Extensions** - Supports custom ACL module enhancements
- **Upgrade Compatibility** - Maintain compatibility with future SuiteCRM versions

## Performance Considerations

- **Memory Efficient** - Lightweight array structure
- **Fast Loading** - Simple PHP array for quick access
- **Minimal Overhead** - No complex processing or external dependencies

## Integration with Other Languages

### Multi-language Support
- **Translation Template** - Serves as base for other language files
- **Consistent Structure** - Maintains same array keys across languages
- **Character Encoding** - Supports international character sets and symbols

### Language Pack Integration
- **Standard Format** - Compatible with SuiteCRM language pack system
- **Easy Translation** - Clear, translatable text strings
- **Cultural Adaptation** - Supports localization for different regions

## Notes

- Essential component of ACL module user interface
- Provides foundation for multi-language ACL functionality
- Supports both administrative and end-user interface elements
- Maintains professional, clear communication standards
- Critical for user experience in ACL management workflows 