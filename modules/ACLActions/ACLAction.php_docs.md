# ACLAction Core Class Implementation

/**
 * @fileoverview Core ACLAction class providing comprehensive access control list functionality for SuiteCRM permission management
 * @package SuiteCRM.modules.ACLActions
 * @copyright SugarCRM Inc. 2004-2013, SalesAgility Ltd. 2011-2019
 * @license GNU Affero General Public License version 3
 */

## Overview

The ACLAction class is the cornerstone of SuiteCRM's access control system, extending SugarBean to provide comprehensive permission management functionality. It handles ACL action creation, removal, permission checking, user access validation, and complex permission resolution with support for roles, security groups, and hierarchical access control.

## Class Structure

### Core Properties
- **module_dir** - 'ACLActions' - Module directory identifier
- **object_name** - 'ACLAction' - Bean object name
- **table_name** - 'acl_actions' - Database table name
- **new_schema** - true - Uses new database schema format
- **disable_custom_fields** - true - Prevents custom field addition

### Dynamic Properties Support
- **#[\AllowDynamicProperties]** - PHP 8.2+ compatibility for dynamic property access
- Supports runtime property addition for extensibility

## Database Operations

### ACL Action Management

#### Static Method: addActions($category, $type='module')
Creates ACL actions for a module or category:
- **Purpose** - Adds all default actions for a specified category
- **Parameters** - Category (module name), type (usually 'module')
- **Process** - Checks existing actions, creates missing ones with defaults
- **Safety** - Only adds actions that don't already exist
- **Error Handling** - Dies with descriptive error if action type not defined

#### Static Method: removeActions($category, $type='module')
Removes ACL actions for a module or category:
- **Purpose** - Removes all actions for specified category/type
- **Parameters** - Category (module name), type (usually 'module')
- **Process** - Finds existing actions and marks them as deleted
- **Safety** - Uses soft deletion via mark_deleted() method
- **Error Handling** - Dies with descriptive error if action type not defined

#### Database Query Operations
- **Parameterized Queries** - Uses proper database quoting for security
- **Existence Checking** - Validates existing actions before creation/removal
- **Soft Deletion** - Maintains data integrity through deletion flags
- **Transaction Safety** - Individual action operations for data consistency

### Permission Resolution Queries

#### Complex Multi-Source Queries
The class implements sophisticated permission resolution through UNION queries:

##### User Role Permissions (Priority 1)
- **Direct User Roles** - acl_roles_users table joins
- **Highest Priority** - user_role = 1 in query results
- **Override Support** - Role-specific access level overrides

##### Security Group Permissions (Priority 2)
- **Group Membership** - securitygroups_users table joins
- **Group Role Assignment** - securitygroups_acl_roles table joins
- **Medium Priority** - user_role = 0 in query results

##### Default Actions (Priority 3)
- **System Defaults** - Base ACL action definitions
- **Fallback Values** - user_role = -1 in query results
- **Last Resort** - Used when no specific role assignments exist

## Internal API Calls

### Core Permission Methods

#### Static Method: getUserActions($user_id, $refresh=false, $category='', $action='')
Comprehensive user permission retrieval:
- **Session Caching** - Stores permissions in $_SESSION['ACL'][$user_id]
- **Selective Loading** - Can load specific categories or actions
- **Refresh Capability** - Force reload from database when needed
- **Complex Resolution** - Handles multiple permission sources with priority

#### Static Method: userHasAccess($user_id, $category, $action, $type='module', $is_owner=false, $in_group=false)
Primary access validation method:
- **Admin Override** - Administrators bypass normal ACL checks
- **Module Access** - Validates basic module access before action checking
- **Owner Recognition** - Special handling for record owners
- **Group Membership** - Security group-based access validation
- **Session Integration** - Uses cached permissions for performance

#### Static Method: hasAccess($is_owner=false, $in_group=false, $access=0, ACLAction $action=null)
Core access logic implementation:
- **Priority-Based** - Higher access levels override lower ones
- **Owner Support** - Record ownership grants enhanced access
- **Group Support** - Security group membership consideration
- **Flexible Usage** - Can be used statically or with ACLAction instance

### Utility and Helper Methods

#### Static Method: AccessName($access)
Translates access levels to human-readable names:
- **Translation Integration** - Uses SuiteCRM's translation system
- **Label Resolution** - Converts numeric access levels to text
- **Internationalization** - Supports multi-language deployments

#### Static Method: getUserAccessLevel($user_id, $category, $action, $type='module')
Retrieves specific access level for user/action combination:
- **Numeric Access** - Returns integer access level
- **Admin Override** - Administrative access level for admins
- **Session Cached** - Uses cached permissions for performance

#### Static Method: userNeedsOwnership($user_id, $category, $action, $type='module')
Determines if ownership is required for access:
- **Owner-Only Access** - Checks for ACL_ALLOW_OWNER level
- **Boolean Result** - Returns true/false for ownership requirement
- **Permission Analysis** - Analyzes user's access level for ownership needs

### Security Group Integration

#### Static Method: userNeedsSecurityGroup($user_id, $category, $action, $type='module')
Validates security group access requirements:
- **Group Access Level** - Checks for ACL_ALLOW_GROUP requirement
- **Boolean Result** - Returns true/false for group membership need
- **Session Integration** - Uses cached permission data

#### Group Permission Resolution
Enhanced permission checking with security group support:
- **Additive Security** - Multiple sources can grant access (configurable)
- **User Role Precedence** - User roles can override group roles (configurable)
- **Complex Logic** - Handles intersection of user roles and group roles

## UI Functionality

### Permission Matrix Management

#### Static Method: setupCategoriesMatrix(&$categories)
Prepares permission data for UI display:
- **Color Coding** - Assigns visual colors based on access levels
- **Access Names** - Translates access levels to readable names
- **Label Generation** - Creates access level labels for UI
- **Option Generation** - Creates dropdown options for permission forms
- **Disabled Module Handling** - Grays out disabled modules for non-admins

#### Visual Configuration
- **Access Colors** - Maps access levels to color codes for UI indication
- **Text Colors** - Ensures readable text contrast for accessibility
- **Disabled States** - Visual indication of disabled module access
- **Administrative Controls** - Special handling for administrative interfaces

### Administrative Interface Support
- **Permission Forms** - Supports complex permission configuration interfaces
- **Matrix Displays** - Grid-based permission visualization
- **Access Level Selection** - Dropdown/radio button option generation
- **Bulk Operations** - Mass permission configuration support

## External API Calls

### SuiteCRM Framework Integration

#### Database Management
- **DBManagerFactory::getInstance()** - Database connection management
- **BeanFactory::newBean()** - Bean instance creation for operations
- **Parameterized Queries** - Secure database query execution
- **Transaction Management** - Proper database transaction handling

#### Bean Framework Integration
- **SugarBean Extension** - Inherits core SuiteCRM bean functionality
- **populateFromRow()** - Standard bean population from database
- **save()** - Standard bean save operations
- **mark_deleted()** - Soft deletion through bean framework

#### Session Management
- **$_SESSION['ACL']** - User permission session storage
- **Session Caching** - Performance optimization through session storage
- **Cache Invalidation** - Proper cache clearing when permissions change

### Configuration System Integration
- **$GLOBALS['ACLActions']** - Action definition integration
- **$GLOBALS['ACLActionAccessLevels']** - Access level configuration
- **$sugar_config** - System configuration for security group behavior
- **Action Definition Loading** - Dynamic loading of action configurations

## Security Considerations

### Permission Resolution Security
- **Priority-Based Resolution** - Clear hierarchy prevents privilege escalation
- **Administrative Override** - Proper admin access validation
- **Owner Validation** - Secure record ownership checking
- **Group Membership Validation** - Real-time security group membership verification

### Data Security
- **Parameterized Queries** - SQL injection prevention
- **Input Validation** - Proper validation of all input parameters
- **Access Level Validation** - Ensures valid access level values
- **Session Security** - Secure session-based permission caching

### Access Control Security
- **Positive Security Model** - Explicit permission granting required
- **Fallback Security** - Secure defaults when permissions undefined
- **Audit Trail** - User tracking for all permission operations
- **Administrative Separation** - Clear separation of admin and user permissions

## Performance Considerations

### Caching Strategy
- **Session-Based Caching** - Reduces database queries for permission checks
- **Selective Loading** - Loads only needed permissions when possible
- **Cache Invalidation** - Proper cache clearing when permissions change
- **Memory Management** - Efficient session storage structure

### Database Optimization
- **Complex Query Optimization** - UNION queries for multi-source permissions
- **Index Utilization** - Proper use of database indexes
- **Query Minimization** - Batch loading of permissions where possible
- **Connection Efficiency** - Reuse of database connections

### Scalability Features
- **User-Specific Caching** - Scales with user base
- **Module-Specific Loading** - Selective permission loading
- **Efficient Resolution** - Optimized permission checking algorithms
- **Memory Efficient** - Reasonable memory usage for permission storage

## Advanced Features

### Multi-Dimensional Security
- **Role-Based Access** - Traditional role-based permission model
- **Security Groups** - Organization-based access control
- **Record Ownership** - Individual record-level access control
- **Administrative Access** - System administrator permission model

### Flexible Configuration
- **Additive Security** - Configurable permission accumulation
- **User Role Precedence** - Configurable priority between user and group roles
- **Custom Access Levels** - Support for organization-specific access levels
- **Dynamic Action Types** - Support for custom action types beyond modules

### Enterprise Features
- **Hierarchical Permissions** - Complex organizational permission models
- **Audit Capabilities** - Comprehensive permission change tracking
- **Multi-Tenant Support** - Security group-based organizational separation
- **Integration APIs** - External system permission integration support

## Error Handling

### Graceful Degradation
- **Permission Failures** - Secure defaults when permission resolution fails
- **Database Errors** - Proper handling of database connectivity issues
- **Session Errors** - Fallback to database when session unavailable
- **Configuration Errors** - Error handling for missing action definitions

### Logging and Debugging
- **Warning Logging** - LoggerManager integration for non-critical issues
- **Debug Information** - Comprehensive logging for troubleshooting
- **Error Reporting** - Proper error reporting for critical failures
- **Performance Monitoring** - Logging for performance analysis

## Extension and Customization

### Extensibility Framework
- **Custom Action Types** - Support for application-specific permission types
- **Custom Access Levels** - Organization-specific access level definitions
- **Hook Integration** - Logic hooks for permission operations
- **Third-Party Integration** - External permission system integration

### Development Support
- **Comprehensive API** - Rich API for custom development
- **Documentation** - Extensive inline documentation
- **Testing Support** - Methods designed for unit testing
- **Migration Support** - Smooth upgrade path for permission changes

## Notes

- Core component of SuiteCRM's security architecture
- Supports complex enterprise permission requirements
- Designed for high performance and scalability
- Integrates seamlessly with existing SuiteCRM framework
- Essential for any SuiteCRM deployment requiring access control
- Continuously evolved to support advanced security models 