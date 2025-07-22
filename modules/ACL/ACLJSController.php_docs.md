# ACLJSController.php Documentation

## @fileoverview
JavaScript-based access control controller that generates client-side security scripts for dynamic UI element control based on user permissions and ACL definitions with form-level and field-level security enforcement.

## @package SuiteCRM\Modules\ACL
## @copyright SugarCRM Inc. & SalesAgility Ltd.
## @license AGPL-3.0

## Overview

The ACLJSController class generates JavaScript code that enforces access control restrictions on the client side, dynamically enabling or disabling form elements, buttons, and fields based on user permissions. It provides real-time security enforcement in the browser while maintaining server-side security validation.

## Database Operations

### Permission Integration
- **Module ACL Support**: Validates module ACL support before generating JavaScript
- **ACL Definition Loading**: Loads module-specific ACL definitions from metadata
- **Permission Context**: Integrates user permissions with form and field contexts

## Internal API Calls

### ACL Integration
- **`ACLController::moduleSupportsACL()`**: Validates if module implements ACL functionality
- **`ACLController::checkAccess()`**: Checks user access for specific module actions
- **ACL Definition Processing**: Processes module acldefs.php configuration files

### JavaScript Generation Core
- **`__construct($module, $form, $is_owner)`**: Initializes controller with security context
- **`getJavascript()`**: Main method generating security JavaScript code
- **Context Management**: Maintains module, form, and ownership context for script generation

### Form Element Control Methods
- **`getFieldByIdScript($field_name, $field)`**: Generates ID-based field control scripts
- **`getFieldByNameScript($field_name, $field)`**: Generates name-based field control scripts
- **Dynamic Script Generation**: Creates context-specific JavaScript for access control

## External API Calls

### ACL Definition System
- **Metadata Integration**: Loads acldefs.php files from module metadata directories
- **Definition Processing**: Processes ACL field definitions for JavaScript generation
- **Form-Specific Rules**: Applies ACL rules based on current form and action context

### Client-Side Framework
- **DOM Manipulation**: Generates JavaScript for DOM element access control
- **Form Validation**: Integrates with client-side form validation systems
- **UI State Management**: Controls UI element states based on permissions

## UI Functionality

### Dynamic Form Control

#### DetailView Security
- **Edit Button Control**: Disables Edit button when user lacks edit permission
- **Duplicate Button Control**: Disables Duplicate button when user lacks edit permission  
- **Delete Button Control**: Disables Delete button when user lacks delete permission
- **Button State Management**: Dynamic button state based on user permissions

#### Field-Level Security
- **Individual Field Control**: Controls access to specific form fields
- **Context-Aware Security**: Field security based on current action and form
- **Dynamic State Changes**: Real-time field enabling/disabling

### JavaScript Generation

#### Core Security Script Structure
```javascript
//BEGIN ACL JAVASCRIPT
if(typeof(document.DetailView) != 'undefined'){
    if(typeof(document.DetailView.elements['Edit']) != 'undefined'){
        document.DetailView.elements['Edit'].disabled = 'disabled';
    }
}
//END ACL JAVASCRIPT
```

#### Field-Specific Scripts
- **By ID Control**: `document.getElementById()` based field control
- **By Name Control**: `document.getElementsByName()` based field control
- **Form-Specific Control**: Context-aware field access control

### ACL Definition Processing

#### Definition File Structure
- **Module ACL Definitions**: Located in `modules/{module}/metadata/acldefs.php`
- **Form-Based Organization**: ACL rules organized by form type
- **Action-Specific Rules**: Rules applied based on current action context

#### Field Definition Processing
- **Form Categories**: Processes 'by_id', 'by_name', and custom form categories
- **Action Matching**: Matches field rules to current action context
- **Permission Integration**: Integrates field rules with user permissions

## Security Implementation

### Client-Side Enforcement
- **UI Element Control**: Disables UI elements based on server-side permissions
- **Complementary Security**: Supplements but doesn't replace server-side validation
- **User Experience**: Provides immediate feedback on access restrictions

### Permission Context
- **Module-Level Security**: Integrates with module-level access control
- **Action-Specific Security**: Security rules based on current user action
- **Ownership Context**: Considers record ownership in security decisions

### JavaScript Security Features
- **Safe Defaults**: Generates conservative security scripts by default
- **Error Prevention**: Prevents client-side security bypasses
- **Performance Optimization**: Efficient JavaScript generation and execution

## Integration Points

### ACL Framework
- **ACLController Integration**: Deep integration with core ACL controller
- **Permission System**: Leverages centralized permission management
- **Security Context**: Maintains security context across client and server

### Form System
- **Form Integration**: Integrates with SuiteCRM form generation system
- **Field-Level Control**: Provides granular field-level access control
- **Dynamic Forms**: Supports dynamic form generation with embedded security

### Module Framework
- **Module-Specific Security**: Generates module-specific security scripts
- **Metadata Integration**: Uses module metadata for security rule definitions
- **Action Context**: Integrates with module action processing

## Performance Optimization

### Script Generation Efficiency
- **Conditional Generation**: Only generates scripts for ACL-enabled modules
- **Minimal Output**: Generates only necessary JavaScript code
- **Cached Processing**: Leverages ACL definition caching where possible

### Client-Side Performance
- **Lightweight Scripts**: Generates efficient, minimal JavaScript
- **DOM Optimization**: Efficient DOM element selection and manipulation
- **Load Time Impact**: Minimal impact on page load performance

## Configuration and Customization

### ACL Definition Files
- **Module-Specific Configuration**: Each module can define custom ACL rules
- **Flexible Rule System**: Supports various field control mechanisms
- **Action-Based Rules**: Rules can be specific to user actions

### Form Categories
- **by_id Forms**: Rules for elements accessed by ID
- **by_name Forms**: Rules for elements accessed by name
- **Custom Categories**: Support for custom form categorization

### Field Rule Structure
- **app_action Matching**: Rules matched to current application action
- **Permission Integration**: Rules integrated with user permission system
- **Dynamic Application**: Rules applied dynamically based on context

## Security Context Management

### User Context
- **Current User Integration**: Uses global current_user for permission checking
- **Ownership Status**: Considers record ownership in security decisions
- **Permission Inheritance**: Inherits permissions from role and group memberships

### Module Context
- **Module-Specific Rules**: Security rules specific to current module
- **Cross-Module Security**: Handles security for related modules
- **Context Preservation**: Maintains security context across page elements

### Action Context
- **Action-Specific Security**: Security rules based on current user action
- **Dynamic Rule Application**: Rules applied dynamically based on action context
- **Context-Aware Generation**: JavaScript generation aware of action context

## Error Handling and Validation

### Safe JavaScript Generation
- **Syntax Validation**: Ensures generated JavaScript is syntactically correct
- **Error Prevention**: Prevents JavaScript errors from security rule application
- **Graceful Degradation**: Handles missing or invalid ACL definitions gracefully

### Security Validation
- **Permission Validation**: Validates permissions before generating control scripts
- **Context Validation**: Validates security context before script generation
- **Rule Validation**: Validates ACL rule definitions before application 