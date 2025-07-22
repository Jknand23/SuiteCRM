# ACLActions Module Variable Definitions

/**
 * @fileoverview Database field definitions and table structure for ACLAction entity managing access control permissions
 * @package SuiteCRM.modules.ACLActions
 * @copyright SugarCRM Inc. 2004-2013, SalesAgility Ltd. 2011-2018
 * @license GNU Affero General Public License version 3
 */

## Overview

This file defines the database structure and field definitions for the ACLAction entity in SuiteCRM. It establishes the schema for storing access control list (ACL) actions that determine what permissions users have for different modules and operations throughout the system.

## Database Structure

### Table Configuration
- **Table Name** - 'acl_actions'
- **Comment** - 'Determine the allowable actions available to users'
- **Entity Name** - 'ACLAction'
- **Primary Focus** - Managing granular permission settings for modules and actions

### Field Definitions

#### Core Entity Fields
- **id** - Primary key identifier (required, non-reportable)
- **date_entered** - Record creation timestamp (required)
- **date_modified** - Last modification timestamp (required)
- **deleted** - Soft deletion flag (boolean, non-reportable)

#### User Tracking Fields
- **modified_user_id** - User who last modified the record
  - Type: assigned_user_name with 36-character length
  - Links to users table for user identification
  - Reportable for audit purposes

- **created_by** - User who created the record
  - Type: assigned_user_name with 36-character length
  - Links to users table for creation tracking
  - Essential for audit trail functionality

#### ACL Configuration Fields
- **name** - Action name (varchar, 150 chars)
  - Examples: 'view', 'list', 'delete', 'edit', 'access'
  - Defines the specific operation being controlled

- **category** - Module/category name (varchar, 100 chars)
  - Usually corresponds to module names (Accounts, Contacts, etc.)
  - Reportable for permission analysis
  - Groups actions by functional area

- **acltype** - Type specifier (varchar, 100 chars)
  - Usually 'module' for standard module permissions
  - Allows for different ACL types (field-level, custom)
  - Reportable for permission categorization

- **aclaccess** - Access level priority (integer, 3 digits)
  - Numeric value specifying access level
  - Higher values indicate higher access priority
  - Used for permission hierarchy resolution

### Relationship Definitions

#### Role Relationships
- **roles** - Link to ACL roles
  - Relationship: 'acl_roles_actions'
  - Non-database field for relationship management
  - Enables many-to-many role-action associations

## Database Operations

### Index Configuration
The table includes optimized indexes for performance:

#### Primary Index
- **aclactionid** - Primary key index on 'id' field

#### Composite Indexes
- **idx_aclaction_id_del** - Optimized queries on id and deletion status
- **idx_category_name** - Fast lookups by category and action name

### Query Optimization
- Indexes support efficient ACL permission lookups
- Category-based queries optimized for module permission checks
- Deletion-aware indexes for soft delete functionality

## Integration Points

### Related ACL Components
- **ACLAction.php** - Core class utilizing these field definitions
- **acl_roles_actions table** - Many-to-many relationship table
- **acl_roles_users table** - User-role assignment table
- **securitygroups integration** - Extended security group support

### Permission Framework
- **Module Access Control** - Controls module-level permissions
- **Action-Level Permissions** - Granular operation control
- **Role-Based Security** - Multi-role permission inheritance
- **Security Groups** - Group-based permission extensions

## Security Considerations

### Access Level Management
- **Priority-Based System** - Higher access levels override lower ones
- **Granular Control** - Individual action permissions per module
- **Role Inheritance** - Complex permission resolution through roles
- **Audit Capability** - Full user tracking for permission changes

### Data Integrity
- **Referential Integrity** - Proper foreign key relationships
- **Soft Deletion** - Maintains data integrity during record removal
- **User Tracking** - Complete audit trail for security compliance
- **Category Validation** - Ensures valid module/category associations

## Permission Model

### Access Level Hierarchy
The aclaccess field supports various permission levels:
- **Administrative Access** - Full system control
- **All Access** - Complete module access
- **Owner Access** - Record owner permissions
- **Group Access** - Security group permissions  
- **Limited Access** - Restricted operations
- **No Access** - Complete denial

### Action Types
Standard actions controlled by this system:
- **access** - Basic module access permission
- **view** - Record viewing permissions
- **list** - List view access control
- **edit** - Record modification permissions
- **delete** - Record deletion permissions
- **import/export** - Data transfer permissions
- **massupdate** - Bulk operation permissions

## Performance Considerations

### Index Strategy
- **Composite Indexes** - Optimized for common query patterns
- **Category Lookups** - Fast module permission resolution
- **Deletion Filtering** - Efficient soft delete handling
- **Role Relationships** - Optimized for complex permission queries

### Caching Integration
- Works with session-based ACL caching
- Supports efficient permission resolution
- Minimizes database queries for permission checks
- Enables fast user access validation

## Extension Support

### Custom ACL Types
- **Field-Level ACL** - Granular field permission control
- **Custom Actions** - Application-specific permission types
- **Module Extensions** - Custom module permission integration
- **Third-Party Integration** - External system permission mapping

### Security Group Integration
- Extended support for security group permissions
- Group-based access control mechanisms
- Enhanced permission inheritance models
- Multi-dimensional security frameworks

## Notes

- Foundation of SuiteCRM's permission system
- Supports complex multi-role permission scenarios
- Integrates with security groups for enhanced control
- Critical for enterprise-level access management
- Designed for high-performance permission resolution 