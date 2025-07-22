# modules/AOBH_BusinessHours/Menu.php Documentation

## Overview

**File**: `modules/AOBH_BusinessHours/Menu.php`  
**Type**: Module Menu Configuration  
**Purpose**: Defines navigation menu items for the Business Hours module with ACL-based access controls

This file configures the menu structure for the AOBH_BusinessHours module, providing role-based access to business hours management functionality.

## Security

- **Entry Point Validation**: Enforces `sugarEntry` validation to prevent direct file access
- **Access Control Integration**: Uses ACLController for permission-based menu display
- **Role-Based Menu Items**: Menu items appear only if user has appropriate permissions

## Menu Configuration

### ACL-Controlled Menu Items

**Create Business Hours**:
- **Permission Check**: `ACLController::checkAccess($module, 'edit', true)`
- **Menu Item**: Link to EditView for creating new business hour configurations
- **Label**: `$mod_strings['LNK_NEW_RECORD']`
- **Action**: `EditView` with return parameters for navigation consistency
- **Purpose**: Enables creation of new business hour definitions

**List Business Hours**:
- **Permission Check**: `ACLController::checkAccess($module, 'list', true)`
- **Menu Item**: Link to module index for viewing business hour configurations
- **Label**: `$mod_strings['LNK_LIST']`
- **Action**: `index` for comprehensive business hours overview
- **Purpose**: Provides access to business hours management interface

## Internal API Integration

### Access Control System

**ACLController Integration**:
- `ACLController::checkAccess()` - Validates user permissions for specific actions
- **Module Parameter**: 'AOBH_BusinessHours' - Target module for permission validation
- **Action Parameters**: 'edit', 'list' - Specific operation permissions
- **Permission Validation**: Returns boolean indicating user authorization

### Localization Framework

**Module String Integration**:
- `$mod_strings['LNK_NEW_RECORD']` - Localized label for creation action
- `$mod_strings['LNK_LIST']` - Localized label for list action
- **Global Access**: `$app_strings` - Application-wide string resources

## UI Functionality

### Dynamic Menu Generation

**Permission-Based Display**:
- Menu items appear only when user has required permissions
- Supports role-based interface customization for business hours management
- Provides consistent navigation experience across user roles

**Menu Item Structure**:
```php
array("URL", "Label", "Icon", "Module")
```
- **URL**: Direct link to specific business hours action
- **Label**: Localized display text for user interface
- **Icon**: Interface icon identifier for visual consistency
- **Module**: Module context for proper navigation

### Navigation Flow

**Return Parameter Management**:
- **return_module**: Ensures proper navigation flow after operations
- **return_action**: Maintains context for optimal user experience
- **Consistent Patterns**: All menu items follow standard return parameter structure

## Business Hours Management Features

### Configuration Management

**Business Hours Creation**:
- Direct access to EditView for new business hour configuration
- Supports comprehensive business hours setup and customization
- Integrates with organizational scheduling workflow

**Business Hours Administration**:
- List view access for business hours overview and management
- Supports business hour configuration modification and maintenance
- Enables business hours lifecycle management

## Integration Points

### SuiteCRM Core Integration

**ACL Framework**:
- Integrates with SuiteCRM's access control system
- Supports role-based permission management for business hours
- Maintains security consistency across administrative modules

**Navigation System**:
- Follows SuiteCRM's standard menu patterns and conventions
- Integrates with global navigation framework
- Supports consistent user experience across modules

**Localization Framework**:
- Uses SuiteCRM's language management system for interface elements
- Supports multi-language environments with translated labels
- Maintains translation consistency across business hours interface

### Module System Integration

**Business Hours Framework**:
- Provides navigation foundation for business hours management
- Supports business hours configuration and administration
- Enables business hours integration with other modules

## Dependencies

### Core Framework
- **ACLController**: For access control validation and permission checking
- **Module Framework**: For menu integration and navigation support
- **Localization System**: For translated interface elements and labels

### Global Objects
- **$mod_strings**: Module-specific localized strings for interface elements
- **$app_strings**: Application-wide string resources for consistency

## Usage Context

### Business Operations Configuration

**Administrative Interface**:
- Provides access to business hours configuration management
- Supports organizational business operations setup
- Enables business hours administration and maintenance

**System Integration**:
- Supports business hour-aware scheduling system configuration
- Enables proper time calculation setup for organizational operations
- Provides foundation for business hours integration across modules

### Role-Based Access Control

**Permission Management**:
- Respects user role permissions for business hours module access
- Supports granular access control for business hours operations
- Maintains security while enabling appropriate business hours functionality

## Related Files

- `modules/AOBH_BusinessHours/AOBH_BusinessHours.php` - Main business hours functionality
- `modules/AOBH_BusinessHours/vardefs.php` - Database field definitions
- `modules/AOBH_BusinessHours/language/en_us.lang.php` - Module localization
- `include/MVC/Controller/ControllerFactory.php` - Controller instantiation framework

## Module Menu Integration

### Standard SuiteCRM Pattern

**Menu Structure**:
- Follows SuiteCRM's standard module menu pattern for consistency
- Provides predictable navigation experience for administrators
- Supports framework integration requirements and conventions

**Permission Integration**:
- Implements standard ACL checking patterns for security
- Maintains security consistency across business hours system
- Supports role-based interface customization and access control

## Notes

- Essential component for business hours module navigation and access control
- Implements comprehensive access control for secure business hours management
- Supports organizational business operations configuration and administration
- Integrates seamlessly with SuiteCRM's navigation and permission systems
- Provides foundation for role-based business hours management capabilities
- Critical for maintaining consistent user experience in business hours operations 