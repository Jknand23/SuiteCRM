# en_us.lang.php Documentation

## @fileoverview
English language strings for the ACLRoles module. This file defines all user-facing text, labels, and messages used throughout the role management interface in SuiteCRM.

## @package
SuiteCRM ACLRoles Module - English language definitions

## @copyright
Copyright (C) 2004-2013 SugarCRM Inc., 2011-2019 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Language String Categories

### Module Identity Strings

#### Core Module Labels
- **LBL_MODULE_NAME**: 'Roles' - Primary module name displayed in navigation
- **LBL_MODULE_TITLE**: 'Roles: Home' - Module title for home page display
- **LBL_ROLE**: 'Role' - Singular reference to a role
- **LBL_LIST_FORM_TITLE**: 'Roles' - Title for list view forms

#### Field Labels
- **LBL_NAME**: 'Name' - Label for role name field
- **LBL_DESCRIPTION**: 'Description' - Label for role description field

### Navigation and Action Strings

#### Menu and Navigation Labels
- **LIST_ROLES**: 'List Roles' - Navigation link to role list view
- **LIST_ROLES_BY_USER**: 'List Roles By User' - Navigation link to user-organized role view
- **LBL_CREATE_ROLE**: 'Create Role' - Button/link text for role creation

#### Search Interface Labels
- **LBL_SEARCH_FORM_TITLE**: 'Search' - Title for search form sections

### UI Functionality Strings

#### Subpanel Titles
- **LBL_USERS_SUBPANEL_TITLE**: 'Users' - Title for users subpanel in role detail view
- **LBL_ROLES_SUBPANEL_TITLE**: 'User Roles' - Title for roles subpanel in user views

#### Interactive Elements
- **LBL_EDIT_VIEW_DIRECTIONS**: 'Double click on a cell to change value.' - User instruction for permission matrix editing
- **LBL_DUPLICATE_OF**: 'Duplicate Of ' - Prefix text for duplicated role names

### Permission Management Strings

#### Access Control Labels
- **LBL_ACCESS_DEFAULT**: 'Not Set' - Display text for default/unset permissions
- **LBL_ACTION_ADMIN**: 'Access Type' - Label for permission type selection
- **LBL_ALL**: 'All' - Label for "all access" permission level

### Integration Strings

#### SecurityGroups Integration
- **LBL_SECURITYGROUPS**: 'Security Groups' - Label for SecurityGroups relationship display

## Internal API Calls

### Language System Integration
- **$mod_strings**: Global array containing all module language strings
- Integration with SuiteCRM's translation framework
- Support for language pack overrides and customization

### Template Integration
- Strings automatically available in Smarty templates through MOD variable
- Integration with form generation and field labeling
- Support for dynamic string selection based on context

### Internationalization Support
- Base English strings for translation reference
- Language pack extension support
- UTF-8 encoding for international character support

## UI Functionality

### Display Context Usage

#### Navigation Menus
- Module navigation using LBL_MODULE_NAME
- Action buttons using CREATE_ROLE and list view labels
- Breadcrumb navigation with module title strings

#### Form Interface
- Field labels for role name and description
- Search form titles and placeholders
- Button labels for form actions

#### List and Detail Views
- Column headers using field label strings
- Subpanel titles for related record sections
- Navigation breadcrumbs with module context

#### Permission Matrix Interface
- Permission level labels for dropdown selections
- Access type descriptions for user guidance
- Interactive element instructions for usability

### User Interaction Strings

#### Guidance and Instructions
- Edit view directions for permission matrix interaction
- Search form guidance for role lookup
- Navigation hints for user workflow

#### Status and State Indicators
- Default permission state labels
- Access level descriptors
- Duplication indicators for role copying

## Security and Access Control

### Entry Point Validation
```php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
```

### Content Security
- All strings properly escaped for XSS prevention
- UTF-8 encoding for safe international character handling
- Consistent formatting for security scanning

### Access Level Descriptions
- Clear labeling of permission levels for user understanding
- Descriptive text for access control options
- User-friendly explanations of security concepts

## Integration Points

### Module Cross-Reference
- **Users Module**: User role subpanel titles
- **SecurityGroups Module**: Integration labels
- **Administration Module**: Access control terminology

### Template System Integration
- Automatic template variable assignment
- Smarty template engine compatibility
- Theme-independent string handling

### Search and Filter Integration
- Search form field labels
- Filter option descriptions
- Sort column headers

## Customization Support

### Language Pack Extension
- Support for custom language packs
- Override capability for organization-specific terminology
- Professional translation framework compatibility

### Custom Field Labels
- Extension points for custom field labeling
- Integration with custom module strings
- Dynamic label generation support

### Branding Customization
- Modifiable module names for white-label installations
- Customizable navigation labels
- Flexible terminology adaptation

## Performance Considerations

### Language Loading Optimization
- Efficient string array structure
- Minimal memory footprint
- Fast string lookup performance

### Caching Integration
- Language string caching support
- Template compilation optimization
- Reduced file system access

### Internationalization Performance
- UTF-8 encoding efficiency
- Character set conversion optimization
- Locale-specific string handling

## Translation Framework

### Base Language Support
- English as primary language reference
- Complete string coverage for all UI elements
- Consistent terminology across module

### Translation Keys
- Descriptive key naming for translator reference
- Logical grouping of related strings
- Clear context indicators in key names

### Regional Variations
- Support for regional English variations
- Extensible framework for locale-specific strings
- Cultural adaptation support

## String Usage Context

### Administrative Interface
- Role management terminology
- Permission configuration labels
- System administration guidance

### End-User Interface
- Role assignment descriptions
- Permission level explanations
- User-friendly navigation labels

### Developer Interface
- Technical field labels
- System configuration strings
- Development mode indicators

## Quality Assurance

### String Consistency
- Consistent terminology across all labels
- Proper capitalization and formatting
- Grammatically correct text strings

### Completeness Verification
- All UI elements have corresponding strings
- No missing labels in user interface
- Complete coverage of user workflows

### Accessibility Compliance
- Screen reader friendly text
- Clear and descriptive labels
- Proper semantic labeling for assistive technologies 