# ACLRole.php Documentation

## @fileoverview
Core bean class for SuiteCRM's Access Control List (ACL) role management system. This class handles role creation, modification, deletion, and permission management for users and modules within the CRM system.

## @package
SuiteCRM ACLRoles Module - Core access control management functionality

## @copyright
Copyright (C) 2004-2013 SugarCRM Inc., 2011-2018 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Class Overview

The `ACLRole` class extends `SugarBean` and provides comprehensive role-based access control functionality. It manages the relationship between users, roles, and permissions across all modules in the SuiteCRM system.

### Key Properties
- `$module_dir`: 'ACLRoles' - Module directory identifier
- `$object_name`: 'ACLRole' - Object name for the bean
- `$table_name`: 'acl_roles' - Database table name
- `$disable_row_level_security`: true - Disables row-level security for this module
- `$disable_custom_fields`: true - Disables custom field functionality
- `$relationship_fields`: Array mapping user relationships

## Database Operations

### Role Retrieval and Management

#### `getUserRoles($user_id, $getAsNameArray = true)`
Retrieves all roles assigned to a specific user from the database.

**Parameters:**
- `$user_id` (GUID): The user's unique identifier
- `$getAsNameArray` (boolean): If true, returns role names; if false, returns ACLRole objects

**Database Query:**
```sql
SELECT acl_roles.* 
FROM acl_roles 
INNER JOIN acl_roles_users ON acl_roles_users.user_id = '$user_id' 
    AND acl_roles_users.role_id = acl_roles.id 
    AND acl_roles_users.deleted = 0 
WHERE acl_roles.deleted=0
```

**Returns:** Array of role names or ACLRole objects

#### `getUserRoleNames($user_id)` (Static)
Cached version of role retrieval that returns only role names for performance optimization.

**Caching:** Uses `sugar_cache_retrieve("RoleMembershipNames_".$user_id)` for performance

#### `getAllRoles($returnAsArray = false)`
Retrieves all active roles in the system, ordered by name.

**Database Query:**
```sql
SELECT acl_roles.* FROM acl_roles 
WHERE acl_roles.deleted=0 ORDER BY name
```

#### `getRoleActions($role_id, $type='module')`
Retrieves all actions and permissions associated with a specific role.

**Database Query:**
```sql
SELECT acl_actions.*, acl_roles_actions.access_override 
FROM acl_actions 
LEFT JOIN acl_roles_actions ON acl_roles_actions.role_id = '$role_id' 
    AND acl_roles_actions.action_id = acl_actions.id 
    AND acl_roles_actions.deleted = 0
WHERE acl_actions.deleted=0 
ORDER BY acl_actions.category, acl_actions.name
```

### Relationship Management

#### `setAction($role_id, $action_id, $access)`
Creates or updates the relationship between a role and an action with specific access permissions.

**Parameters:**
- `$role_id` (GUID): The role identifier
- `$action_id` (GUID): The ACL action identifier  
- `$access` (int): Access level (ACL_ALLOW_ALL, ACL_ALLOW_NONE, ACL_ALLOW_OWNER, etc.)

**Database Operation:** Updates `acl_roles_actions` relationship table

#### `mark_relationships_deleted($id)`
Performs cascade deletion of role relationships when a role is deleted.

**Database Query:**
```sql
UPDATE acl_roles_actions SET deleted=1, date_modified='$datetime' 
WHERE role_id = '$id' AND deleted=0
```

## Internal API Calls

### Bean Factory Integration
- Uses `BeanFactory::newBean('ACLRoles')` for object instantiation
- Uses `BeanFactory::newBean('ACLActions')` for action object creation

### Database Manager Integration  
- `DBManagerFactory::getInstance()` for database connections
- `$this->db->query()` and `$result = $db->fetchByAssoc()` for data retrieval

### Cache Integration
- `sugar_cache_retrieve()` and `sugar_cache_put()` for role name caching
- Cache key pattern: `"RoleMembershipNames_".$user_id`

### Relationship API
- `$this->set_relationship()` for managing role-action relationships
- `parent::mark_relationships_deleted()` for cascade deletions

## UI Functionality

### Data Presentation

#### `get_summary_text()`
Returns the role name as display text for UI components.

**Returns:** Role name as string for display purposes

#### `toArray($dbOnly = false, $stringOnly = false, $upperKeys=false)`
Converts role object to array format for UI rendering.

**Fields Included:**
- `id`: Role unique identifier
- `name`: Role display name  
- `description`: Role description text

#### `fromArray($arr)`
Populates role object from array data (typically from form submissions).

### Permission Matrix Display
- `langCompare()`: Private static method for sorting role categories by translated module names
- Integrates with `$app_list_strings['moduleList']` for localized display
- Filters out modules not present in `$beanList` to prevent display of inactive modules

## Security and Access Control

### Entry Point Validation
```php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
```

### Row-Level Security
- `$disable_row_level_security = true` - ACL roles themselves are not subject to row-level restrictions
- `$disable_custom_fields = true` - Prevents custom field additions to maintain system integrity

### Permission Levels
The class works with standard SuiteCRM permission constants:
- `ACL_ALLOW_ALL` - Full access
- `ACL_ALLOW_NONE` - No access
- `ACL_ALLOW_OWNER` - Owner-only access
- `ACL_ALLOW_DEFAULT` - Default system permissions

## Integration Points

### Module Integration
- Validates modules against `$beanList` to ensure only active modules are displayed
- Uses `$app_list_strings['moduleList']` for module name translations

### User Management Integration
- Connects with Users module through `acl_roles_users` relationship table
- Supports SecurityGroups integration through `securitygroups_acl_roles` relationship

### Action Management Integration  
- Integrates with ACLActions module for permission granularity
- Manages action categories (module-level and record-level permissions)

## Performance Considerations

### Caching Strategy
- Role names are cached per user to reduce database queries
- Cache invalidation occurs when roles are modified
- Uses SuiteCRM's built-in sugar_cache system

### Query Optimization
- Uses proper JOIN clauses for efficient data retrieval
- Includes deleted flag filtering in all queries
- Orders results for consistent UI presentation

## Error Handling

### Validation
- Validates module existence before displaying in permission matrix
- Handles empty role IDs gracefully in `getRoleActions()`
- Provides fallback to default permissions when role-specific permissions are not set

### Data Integrity
- Cascade deletion ensures orphaned relationships are cleaned up
- Relationship validation through proper foreign key handling
- Transaction safety through SugarBean's built-in mechanisms 