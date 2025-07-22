# modules/AM_ProjectTemplates/Menu.php Documentation

## Overview

**File**: `modules/AM_ProjectTemplates/Menu.php`  
**Type**: Module Menu Configuration  
**Purpose**: Defines the navigation menu structure for the Advanced Project Templates module with ACL-based access controls

This file configures the menu items available for the AM_ProjectTemplates module, implementing role-based access control to ensure users only see menu items they have permission to access.

## Security

- **Entry Point Validation**: Enforces `sugarEntry` validation to prevent direct file access
- **Access Control Integration**: Uses ACLController for permission-based menu display
- **Role-Based Menu Items**: Menu items appear only if user has appropriate permissions

## Menu Configuration

### ACL-Controlled Menu Items

**Create New Project Template**:
- **Permission Check**: `ACLController::checkAccess('AM_ProjectTemplates', 'edit', true)`
- **Menu Item**: Link to EditView for creating new project templates
- **Label**: `$mod_strings['LNK_NEW_RECORD']`
- **Action**: `EditView` with return parameters for navigation consistency

**List Project Templates**:
- **Permission Check**: `ACLController::checkAccess('AM_ProjectTemplates', 'list', true)`
- **Menu Item**: Link to module index for viewing project template list
- **Label**: `$mod_strings['LNK_LIST']`
- **Action**: `index` with return parameters for navigation flow

**Import Project Templates**:
- **Permission Check**: `ACLController::checkAccess('AM_ProjectTemplates', 'import', true)`
- **Menu Item**: Link to Import module for bulk project template creation
- **Label**: `$mod_strings['LNK_IMPORT_AM_PROJECTTEMPLATES']`
- **Action**: Import Step1 with module-specific parameters

## Internal API Calls

### Access Control System

**ACLController Integration**:
- `ACLController::checkAccess()` - Validates user permissions for specific actions
- **Module Parameter**: 'AM_ProjectTemplates' - Target module for permission check
- **Action Parameter**: 'edit', 'list', 'import' - Specific action permissions
- **Permission Check**: Returns boolean indicating access authorization

### Localization System

**Module Strings**:
- `$mod_strings['LNK_NEW_RECORD']` - Localized label for create action
- `$mod_strings['LNK_LIST']` - Localized label for list action
- `$mod_strings['LNK_IMPORT_AM_PROJECTTEMPLATES']` - Localized import label

**Global Resources**:
- `$app_strings` - Application-wide string resources
- `$sugar_config` - System configuration access

## UI Functionality

### Dynamic Menu Generation

**Permission-Based Display**:
- Menu items appear only when user has required permissions
- Supports role-based interface customization
- Provides consistent navigation experience

**Menu Item Structure**:
```php
array("URL", "Label", "Icon", "Module")
```
- **URL**: Direct link to specific module action
- **Label**: Localized display text
- **Icon**: Interface icon identifier
- **Module**: Module context for the action

### Navigation Integration

**Return Parameter Management**:
- **return_module**: Ensures proper navigation flow
- **return_action**: Maintains context for user experience
- **Consistent Patterns**: All menu items follow same return parameter structure

## Advanced Project Management Features

### Project Template Operations

**Template Creation**:
- Direct access to EditView for new template creation
- Supports comprehensive project template configuration
- Integrates with project management workflow

**Template Management**:
- List view access for template overview and management
- Supports template selection and modification
- Enables project template lifecycle management

**Bulk Operations**:
- Import functionality for large-scale template creation
- Supports CSV/Excel import workflows
- Enables efficient template data migration

## Integration Points

### SuiteCRM Core Integration

**ACL Framework**:
- Integrates with SuiteCRM's access control system
- Supports role-based permission management
- Maintains security consistency across modules

**Navigation System**:
- Follows SuiteCRM's standard menu patterns
- Integrates with global navigation framework
- Supports consistent user experience

**Localization Framework**:
- Uses SuiteCRM's language management system
- Supports multi-language environments
- Maintains translation consistency

### Module System Integration

**Import Framework**:
- Leverages SuiteCRM's import system
- Supports standardized import workflows
- Maintains data integrity during imports

**Module Framework**:
- Follows SuiteCRM's module architecture patterns
- Integrates with module management system
- Supports module lifecycle management

## Dependencies

### Core Framework
- **ACLController**: For access control validation
- **Module Framework**: For menu integration and navigation
- **Localization System**: For translated interface elements

### Global Objects
- **$mod_strings**: Module-specific localized strings
- **$app_strings**: Application-wide string resources
- **$sugar_config**: System configuration parameters

## Usage Context

### Project Management Workflow

**Template-Based Project Creation**:
- Provides access to project template management
- Supports standardized project creation workflows
- Enables project template reuse and consistency

**Administrative Interface**:
- Supports project template administration
- Enables template configuration and management
- Provides project management oversight capabilities

### Role-Based Access

**Permission Management**:
- Respects user role permissions for module access
- Supports granular access control for different operations
- Maintains security while enabling appropriate functionality

## Related Files

- `modules/AM_ProjectTemplates/controller.php` - Module controller handling actions
- `modules/AM_ProjectTemplates/vardefs.php` - Database field definitions
- `modules/AM_ProjectTemplates/language/en_us.lang.php` - Module localization
- `include/MVC/Controller/ControllerFactory.php` - Controller instantiation
- `modules/Import/` - Import framework for bulk operations

## Module Menu Integration

### Standard SuiteCRM Pattern

**Menu Structure**:
- Follows SuiteCRM's standard module menu pattern
- Provides consistent navigation experience
- Supports framework integration requirements

**Permission Integration**:
- Implements standard ACL checking patterns
- Maintains security consistency across system
- Supports role-based interface customization

## Notes

- Essential component for project template module navigation
- Implements comprehensive access control for secure operation
- Supports advanced project management workflows through template functionality
- Integrates seamlessly with SuiteCRM's import and navigation systems
- Provides foundation for role-based project management capabilities
- Critical for maintaining consistent user experience in project template operations 