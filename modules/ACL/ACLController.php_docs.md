# ACLController.php Documentation

## @fileoverview
Core access control engine for SuiteCRM that manages user permissions, role-based access control, security groups, and module-level access restrictions with comprehensive security enforcement throughout the application.

## @package SuiteCRM\Modules\ACL
## @copyright SugarCRM Inc. & SalesAgility Ltd.
## @license AGPL-3.0

## Overview

The ACLController class serves as the central access control system for SuiteCRM, providing comprehensive security management including user permissions, role-based access control, ownership requirements, security group integration, and module accessibility filtering. It enforces security policies throughout the application.

## Database Operations

### Access Control Validation
- **User Permission Checking**: Validates user access through ACLAction::userHasAccess()
- **Ownership Requirements**: Determines if ownership is required for access through ACLAction::userNeedsOwnership()
- **Security Group Integration**: Manages security group requirements through ACLAction::userNeedsSecurityGroup()
- **Module Access Control**: Filters modules based on user permissions and ACL settings

### Permission Resolution
- **Admin Override**: Administrators bypass all ACL restrictions
- **Complex Module Logic**: Special handling for composite modules (Calendar, Activities)
- **Related Module Access**: Handles access for modules with parent-child relationships
- **Dynamic Permission Evaluation**: Real-time permission evaluation based on user context

## Internal API Calls

### Core Access Control Methods

#### `checkAccess($category, $action, $is_owner, $type, $in_group)`
- **Purpose**: Primary access control validation method
- **Parameters**: Module/category, action type, ownership status, access type, group membership
- **Returns**: Boolean indicating access permission
- **Security Groups**: Enhanced with $in_group parameter for security group integration

#### `requireOwner($category, $value, $type)`
- **Purpose**: Determines if ownership is required for specific actions
- **Admin Bypass**: Administrators never require ownership
- **Delegation**: Uses ACLAction::userNeedsOwnership() for evaluation

#### `requireSecurityGroup($category, $value, $type)`
- **Purpose**: Determines if security group membership is required
- **Integration**: Part of Security Groups enhancement
- **Admin Bypass**: Administrators bypass security group requirements

### Module Management Methods

#### `filterModuleList(&$moduleList, $by_value)`
- **Purpose**: Filters available modules based on user permissions
- **Processing**: Removes inaccessible modules from navigation and lists
- **Special Cases**: Handles composite modules (Calendar, Activities)
- **Performance**: Uses reference passing for efficient list modification

#### `disabledModuleList($moduleList, $by_value, $view)`
- **Purpose**: Identifies modules that should be disabled for the user
- **View-Specific**: Considers specific view permissions (list, edit, etc.)
- **Return**: Array of disabled modules for UI processing

#### `moduleSupportsACL($module)`
- **Purpose**: Determines if a module implements ACL functionality
- **Caching**: Static caching for performance optimization
- **Bean Integration**: Checks if module bean implements ACL interface

## External API Calls

### ACLAction Integration
- **`ACLAction::userHasAccess()`**: Core permission checking
- **`ACLAction::userNeedsOwnership()`**: Ownership requirement validation
- **`ACLAction::userNeedsSecurityGroup()`**: Security group requirement checking
- **`ACLAction::getUserActions()`**: Retrieves user's available actions

### JavaScript Integration
- **ACLJSController**: Client-side access control through addJavascript()
- **Dynamic UI Control**: Enables/disables UI elements based on permissions
- **Form Security**: Integrates with form generation for field-level security

## UI Functionality

### Access Denial Interface
- **`displayNoAccess($redirect_home)`**: Displays access denied messages
- **User Feedback**: Professional error messages with localization
- **Automatic Redirect**: Optional countdown redirect to home page
- **Security Messaging**: Clear communication of access restrictions

### JavaScript Integration
- **Dynamic UI Control**: Enables/disables buttons and fields based on permissions
- **Real-Time Security**: Client-side enforcement of server-side permissions
- **Form Field Security**: Individual field access control through ACL definitions

### Module Accessibility
- **Navigation Filtering**: Removes inaccessible modules from navigation menus
- **List Filtering**: Filters module lists based on user permissions
- **Contextual Access**: Permission checking based on current user context

## Special Module Handling

### Composite Modules
#### Calendar Module
- **Multi-Module Logic**: Combines Calls, Meetings, and Tasks permissions
- **Access Requirement**: Requires access to at least one component module
- **Permission Aggregation**: OR logic for component module permissions

#### Activities Module  
- **Extended Components**: Includes Calls, Meetings, Tasks, Emails, and Notes
- **Comprehensive Access**: Broader permission set than Calendar
- **Activity Management**: Unified access control for all activity types

#### AOS Products Quotes
- **Parent Module Access**: Checks access to parent modules (Quotes, Invoices, Contracts)
- **Line Item Security**: Special handling for line item access through parent modules
- **Multi-Module Validation**: OR logic across multiple parent modules

### Special Cases
- **AOR_Reports with EmailAddresses**: Special handling for report access to email data
- **ProductTemplates**: Mapping to Products module for ACL purposes
- **Module Dependencies**: Handling modules that depend on other modules for access

## Security Implementation

### Administrative Privileges
- **Admin Bypass**: Administrators have unrestricted access to all functionality
- **Global Override**: Admin status overrides all ACL restrictions
- **Security Context**: Admin privileges checked at method entry points

### Permission Caching
- **Static Caching**: Module ACL support status cached for performance
- **User Action Caching**: User permissions cached through ACLAction system
- **Memory Efficiency**: Efficient caching to reduce database queries

### Security Group Integration
- **Enhanced Parameters**: Methods enhanced with $in_group parameter
- **Group Membership**: Security group membership affects access decisions
- **Granular Control**: Fine-grained access control through security groups

## Performance Optimization

### Caching Strategies
- **Static Module Cache**: Caches module ACL support to avoid repeated checks
- **Permission Cache**: Leverages ACLAction caching for user permissions
- **Memory Management**: Efficient memory usage through static caching

### Lazy Loading
- **Module Bean Loading**: Only loads module beans when ACL checking is required
- **Conditional Processing**: Skips expensive operations for admin users
- **Dynamic Evaluation**: Just-in-time permission evaluation

## Integration Points

### ACL Framework
- **ACLAction Integration**: Deep integration with ACLAction permission system
- **Role-Based Access**: Integrates with role management system
- **Security Groups**: Enhanced integration with Security Groups module

### SuiteCRM Core
- **Bean System**: Integrates with SugarBean ACL interface implementation
- **Module Framework**: Works with module registration and bean definitions
- **Navigation System**: Integrates with menu and navigation generation

### User Interface
- **JavaScript Security**: Client-side security through ACLJSController
- **Form Generation**: Integrates with form generation for field-level security
- **List Views**: Filters and controls list view access and functionality

## Error Handling

### Access Denial
- **Graceful Degradation**: Professional error messages instead of fatal errors
- **User Guidance**: Clear messaging about access restrictions
- **Redirect Options**: Optional redirection for better user experience

### Security Validation
- **Safe Defaults**: Denies access by default when permissions are unclear
- **Error Prevention**: Prevents security bypasses through proper validation
- **Audit Trail**: Supports audit trail through underlying ACLAction system 