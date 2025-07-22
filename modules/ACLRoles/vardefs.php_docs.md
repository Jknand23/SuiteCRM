# vardefs.php Documentation

## @fileoverview
Field definitions and database schema configuration for the ACLRole module. This file defines the complete data structure, field properties, relationships, and database indices for role-based access control management in SuiteCRM.

## @package
SuiteCRM ACLRoles Module - Database schema and field definitions

## @copyright
Copyright (C) 2004-2013 SugarCRM Inc., 2011-2018 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Database Schema Definition

### Table Configuration
- **Table Name:** `acl_roles`
- **Comment:** ACL Role definition
- **Purpose:** Stores role definitions for access control list management

### Field Definitions

#### Core System Fields

##### `id` Field
- **Type:** `id` (Primary key)
- **VName:** `LBL_ID`
- **Required:** `true`
- **Reportable:** `false`
- **Comment:** Unique identifier for the role record

##### `date_entered` Field
- **Type:** `datetime`
- **VName:** `LBL_DATE_ENTERED` 
- **Required:** `true`
- **Comment:** Date record created
- **Purpose:** Audit trail for record creation

##### `date_modified` Field
- **Type:** `datetime`
- **VName:** `LBL_DATE_MODIFIED`
- **Required:** `true` 
- **Comment:** Date record last modified
- **Purpose:** Audit trail for record modifications

##### `modified_user_id` Field
- **Type:** `assigned_user_name`
- **VName:** `LBL_MODIFIED`
- **RName:** `user_name` (Related field name)
- **ID Name:** `modified_user_id`
- **Table:** `modified_user_id_users` (Join table)
- **DB Type:** `id`
- **Length:** 36 characters
- **Required:** `false`
- **Reportable:** `true`
- **Comment:** User who last modified record

##### `created_by` Field
- **Type:** `assigned_user_name`
- **VName:** `LBL_CREATED`
- **RName:** `user_name`
- **ID Name:** `created_by`
- **Table:** `created_by_users`
- **DB Type:** `id`
- **Length:** 36 characters
- **Comment:** User who created record

##### `deleted` Field
- **Type:** `bool`
- **VName:** `LBL_DELETED`
- **Reportable:** `false`
- **Comment:** Record deletion indicator
- **Purpose:** Soft delete functionality

#### Role-Specific Fields

##### `name` Field
- **Type:** `varchar`
- **VName:** `LBL_NAME`
- **Length:** 150 characters
- **Comment:** The role name
- **Purpose:** Primary identifier for the role displayed to users

##### `description` Field
- **Type:** `text`
- **VName:** `LBL_DESCRIPTION`
- **Comment:** The role description
- **Purpose:** Detailed explanation of role purpose and scope

## Database Operations

### Relationship Definitions

#### `users` Relationship
- **Type:** `link`
- **Relationship:** `acl_roles_users`
- **Source:** `non-db` (Virtual field)
- **VName:** `LBL_USERS`
- **Purpose:** Links roles to users who are assigned those roles

#### `actions` Relationship  
- **Type:** `link`
- **Relationship:** `acl_roles_actions`
- **Source:** `non-db` (Virtual field)
- **VName:** `LBL_USERS`
- **Purpose:** Links roles to specific actions and their permission levels

#### `SecurityGroups` Relationship
- **Type:** `link`
- **Relationship:** `securitygroups_acl_roles`
- **Module:** `SecurityGroups`
- **Bean Name:** `SecurityGroup`
- **Source:** `non-db` (Virtual field)
- **VName:** `LBL_SECURITYGROUPS`
- **Purpose:** Integration with SecurityGroups module for advanced access control

### Database Indices

#### Primary Key Index
- **Name:** `aclrolespk`
- **Type:** `primary`
- **Fields:** `['id']`
- **Purpose:** Primary key constraint and fast lookup by ID

#### Composite Index
- **Name:** `idx_aclrole_id_del`
- **Type:** `index`
- **Fields:** `['id', 'deleted']`
- **Purpose:** Optimized queries filtering by ID and deletion status

## Internal API Calls

### SugarBean Integration
- Extends base SugarBean functionality for CRUD operations
- Inherits standard field validation and data handling
- Automatic handling of audit fields (date_entered, date_modified, etc.)

### Relationship Management
- `acl_roles_users` - Many-to-many relationship with Users module
- `acl_roles_actions` - Many-to-many relationship with ACLActions module  
- `securitygroups_acl_roles` - Integration with SecurityGroups for hierarchical permissions

### Field Type Integration
- `assigned_user_name` fields automatically create user lookup functionality
- `datetime` fields integrate with SuiteCRM's TimeDate handling
- `bool` fields provide checkbox UI components
- `text` fields support rich text editing where enabled

## UI Functionality

### Form Field Generation
Field definitions automatically generate appropriate UI components:

#### Input Fields
- `name`: Text input with 150 character limit
- `description`: Textarea for multi-line text entry

#### User Assignment Fields  
- `modified_user_id`: User picker with autocomplete
- `created_by`: Read-only user display

#### System Fields
- `date_entered`: Formatted datetime display (read-only)
- `date_modified`: Formatted datetime display (read-only)
- `deleted`: Checkbox for soft delete (admin only)

### List View Integration
Reportable fields (`modified_user_id`, `name`, `description`) are available for:
- List view column selection
- Search form fields
- Report generation
- Export functionality

### Subpanel Integration
Relationship fields enable automatic subpanel generation:
- Users subpanel showing assigned users
- Actions subpanel showing role permissions
- SecurityGroups subpanel for group-based access

## Security and Data Integrity

### Field Validation
- Required fields enforce data integrity (`id`, `date_entered`, `date_modified`)
- Character limits prevent data overflow (`name` limited to 150 chars)
- Foreign key relationships maintain referential integrity

### Access Control Integration
- Role permissions apply to the role records themselves
- Administrative privileges required for role management
- Audit trail through created_by and modified_user_id fields

### Data Protection
- Soft delete functionality preserves historical data
- Relationship integrity maintained through proper foreign keys
- Index optimization for performance without compromising security

## Performance Considerations

### Query Optimization
- Primary key index ensures fast record retrieval
- Composite index on `id` and `deleted` optimizes common queries
- Relationship definitions use proper join tables for scalability

### Memory Efficiency
- `non-db` source for relationship fields prevents unnecessary data loading
- Appropriate field lengths balance functionality with storage efficiency
- Text fields use efficient storage for variable-length content

## Integration Points

### Module Integration
- Links with Users module for role assignment
- Connects to ACLActions for granular permission control
- Integrates with SecurityGroups for advanced access hierarchies

### System Integration
- Audit fields integrate with user management system
- Datetime fields use system timezone handling
- Boolean fields integrate with system-wide true/false handling

### Reporting Integration
- Reportable fields available in report builder
- Relationship fields enable cross-module reporting
- Field labels support internationalization through language files

## Data Model Relationships

### Direct Relationships
1. **ACLRole → Users**: Many-to-many through `acl_roles_users`
2. **ACLRole → ACLActions**: Many-to-many through `acl_roles_actions`  
3. **ACLRole → SecurityGroups**: Many-to-many through `securitygroups_acl_roles`

### Indirect Relationships
- Through Users: Access to all user-related modules
- Through ACLActions: Control over module and record-level permissions
- Through SecurityGroups: Hierarchical access control structures

## Field Dependency Matrix

### Required Fields
- `id`: Always required (system-generated)
- `date_entered`: Auto-populated on creation
- `date_modified`: Auto-updated on modification

### Optional Fields
- `name`: User-provided (recommended for clarity)
- `description`: User-provided (optional documentation)
- `created_by`: Auto-populated from current user
- `modified_user_id`: Auto-populated from current user 