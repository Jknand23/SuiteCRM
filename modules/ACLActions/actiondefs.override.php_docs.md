# ACL Action Definitions Override Configuration

/**
 * @fileoverview Extended ACL action definitions with Security Groups integration for enhanced permission management
 * @package SuiteCRM.modules.ACLActions
 * @copyright SugarCRM Inc. 2004-2013, SalesAgility Ltd. 2011-2018
 * @license GNU Affero General Public License version 3
 */

## Overview

This override configuration file extends the base ACL action definitions with Security Groups functionality, providing enhanced permission management capabilities. It maintains backward compatibility while adding group-based access control features that enable more sophisticated organizational permission models.

## Security Groups Integration

### Enhanced Access Level Constants
Extends the base access level hierarchy with group-based permissions:

#### Group-Specific Access Level
- **ACL_ALLOW_GROUP** (80) - Security group-based access
  - Positioned between ACL_ALLOW_ENABLED (89) and ACL_ALLOW_OWNER (75)
  - Enables group membership-based permission control
  - Provides middle-tier access between full access and owner-only

#### Complete Hierarchy (Override Version)
- **ACL_ALLOW_ADMIN_DEV** (100) - Combined administrator and developer
- **ACL_ALLOW_ADMIN** (99) - Full administrative access
- **ACL_ALLOW_DEV** (95) - Developer-level access
- **ACL_ALLOW_ALL** (90) - Full access to all records
- **ACL_ALLOW_ENABLED** (89) - Module access enabled
- **ACL_ALLOW_GROUP** (80) - **NEW** Security group access
- **ACL_ALLOW_OWNER** (75) - Owner-only access
- **ACL_ALLOW_NORMAL** (1) - Normal user access
- **ACL_ALLOW_DEFAULT** (0) - Default/not set access
- **ACL_ALLOW_DISABLED** (-98) - Module access disabled
- **ACL_ALLOW_NONE** (-99) - No access permitted

### Conditional Override Protection
- **Duplicate Prevention** - Same constant protection as base definitions
- **Graceful Extension** - Maintains compatibility with existing installations
- **Version Independence** - Can be loaded with or without security groups

## UI Functionality

### Enhanced Visual Configuration
Extends `$GLOBALS['ACLActionAccessLevels']` with group visualization:

#### Group Access Styling
- **Color** - #0000A0 (Dark Blue) - Distinguishes group access from other levels
- **Label** - 'LBL_ACCESS_GROUP' - Translatable group access label
- **Text Color** - White - Maintains consistency with other access levels

#### Visual Hierarchy
- **Administrative** - Blue shades (#0000FF, #0000A0) for admin and group access
- **Positive Access** - Green (#008000) for unrestricted access
- **Restrictive Access** - Red (#FF0000) for denied access
- **Owner Access** - Dark yellow (#6F6800) for ownership-based access

### Group Access Integration
- **Clear Visual Distinction** - Group access has unique color coding
- **Hierarchical Positioning** - Visual placement reflects permission hierarchy
- **Consistent Styling** - Follows established UI patterns

## Module Action Framework Enhancement

### Extended Action Configurations
All standard module actions now include group-based access options:

#### Enhanced Action Access Levels
Each applicable action now supports group access:

##### Record Operations with Group Support
- **view** - Levels: ALL, **GROUP**, OWNER, DEFAULT, NONE
- **list** - Levels: ALL, **GROUP**, OWNER, DEFAULT, NONE  
- **edit** - Levels: ALL, **GROUP**, OWNER, DEFAULT, NONE
- **delete** - Levels: ALL, **GROUP**, OWNER, DEFAULT, NONE
- **export** - Levels: ALL, **GROUP**, OWNER, DEFAULT, NONE

##### Actions Maintaining Original Configuration
- **access** - Levels: ENABLED, DEFAULT, DISABLED (no group option)
- **import** - Levels: ALL, DEFAULT, NONE (no ownership/group concept)
- **massupdate** - Levels: ALL, DEFAULT, NONE (bulk operations)

### Permission Logic Enhancement
- **Three-Tier Access** - All/Group/Owner/None model for record operations
- **Group Membership** - Users gain access through security group membership
- **Flexible Configuration** - Administrators can set group-level permissions per action
- **Backward Compatibility** - Existing permissions continue to function

## Database Operations

### Security Groups Integration
Enhanced permission resolution through additional database relationships:

#### Group-Based Queries
- **securitygroups_users** - User-to-group membership
- **securitygroups_acl_roles** - Group-to-role assignments
- **Complex Joins** - Multi-table permission resolution
- **Hierarchical Access** - Group permissions combined with role permissions

#### Permission Resolution Priority
1. **User Roles** - Direct user role assignments (highest priority)
2. **Group Roles** - Security group role assignments
3. **Default Actions** - System default permissions (lowest priority)

## Internal API Calls

### Enhanced Permission Framework
- **Group Membership Checking** - Additional validation for group access
- **Complex Permission Resolution** - Multi-source permission aggregation
- **Role Inheritance** - Group-based role assignment support
- **Security Group Integration** - Full integration with security groups module

### System Integration Points
- **ACLAction Class** - Enhanced to support group-based permission checking
- **Security Groups Module** - Direct integration with group management
- **User Management** - Group membership affects user permissions
- **Role Management** - Groups can be assigned roles for permission inheritance

## Integration Points

### Related Security Components
- **Security Groups Module** - Core group management functionality
- **User Management** - Group membership affects user access
- **Role-Based Access Control** - Groups inherit role-based permissions
- **Enterprise Security** - Advanced organizational permission models

### Module Framework Extensions
- **Group-Aware Modules** - Modules can implement group-based permissions
- **Administrative Interface** - Enhanced ACL configuration with group options
- **Permission Inheritance** - Complex permission resolution from multiple sources
- **Multi-Tenant Support** - Group-based organizational separation

## Security Considerations

### Enhanced Security Model
- **Group-Based Isolation** - Users only access records within their groups
- **Organizational Security** - Department/team-based access control
- **Hierarchical Groups** - Support for nested organizational structures
- **Multi-Dimensional Access** - Combined role and group-based permissions

### Permission Resolution
- **Additive Security** - Multiple permission sources can grant access
- **Priority-Based Resolution** - Clear hierarchy for permission conflicts
- **Group Membership Validation** - Real-time group membership checking
- **Administrative Override** - Admin access transcends group restrictions

## UI Functionality

### Enhanced Permission Configuration
- **Group Selection** - Users can select group-based access for actions
- **Visual Distinction** - Group access clearly distinguished in interfaces
- **Permission Matrix** - Enhanced display of group-based permissions
- **Administrative Tools** - Group management integration in ACL interfaces

### User Experience
- **Transparent Operation** - Group permissions work seamlessly with existing UI
- **Clear Feedback** - Users understand their group-based access levels
- **Administrative Control** - Administrators can easily configure group permissions
- **Audit Capability** - Group-based access changes are tracked

## Performance Considerations

### Optimized Group Queries
- **Efficient Joins** - Optimized database queries for group permission resolution
- **Caching Integration** - Group permissions cached with user sessions
- **Minimal Overhead** - Group checking adds minimal performance impact
- **Scalable Design** - Handles large numbers of groups and users efficiently

### Database Optimization
- **Index Strategy** - Proper indexing for group-related tables
- **Query Optimization** - Efficient permission resolution queries
- **Memory Management** - Reasonable memory usage for group permission caching
- **Performance Monitoring** - Group operations don't significantly impact system performance

## Extension and Customization

### Group Permission Customization
- **Custom Group Types** - Support for application-specific group models
- **Permission Extensions** - Additional group-based permission types
- **Integration APIs** - External system integration for group management
- **Enterprise Features** - Advanced group hierarchy and inheritance models

### Development Support
- **Backward Compatibility** - Existing ACL code continues to function
- **Migration Support** - Smooth transition from role-only to group+role permissions
- **Testing Framework** - Group permission testing and validation tools
- **Documentation** - Comprehensive group permission implementation guides

## Notes

- Extends base ACL system with powerful group-based permissions
- Maintains full backward compatibility with existing role-based permissions
- Provides enterprise-level organizational permission control
- Integrates seamlessly with existing SuiteCRM security framework
- Essential for organizations requiring department/team-based access control 