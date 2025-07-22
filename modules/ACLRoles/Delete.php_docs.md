# Delete.php Documentation

## @fileoverview
Role deletion handler for the ACLRoles module. This file processes role deletion requests, performs soft deletion of role records, and handles navigation redirection after deletion completion.

## @package
SuiteCRM ACLRoles Module - Role deletion processing

## @copyright
Copyright (C) 2004-2013 SugarCRM Inc., 2011-2018 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Deletion Process Overview

### Role Deletion Workflow
```php
$role = BeanFactory::newBean('ACLRoles');
if (isset($_REQUEST['record'])) {
    $role->mark_deleted($_REQUEST['record']);
}
```

**Process Steps:**
1. Creates new ACLRole bean instance
2. Checks for record ID in request parameters
3. Performs soft deletion using `mark_deleted()` method
4. Redirects to appropriate page after completion

### Soft Delete Implementation
The deletion process uses SuiteCRM's soft delete mechanism:
- Sets `deleted` field to `1` instead of removing record
- Maintains referential integrity with related records
- Preserves audit trail and historical data
- Allows for potential data recovery

## Database Operations

### Primary Deletion Operation
- **Method:** `$role->mark_deleted($_REQUEST['record'])`
- **Operation Type:** Soft delete (sets deleted flag)
- **Table:** `acl_roles`
- **Impact:** Marks role as deleted without removing data

### Cascade Deletion Effects
Through ACLRole class inheritance, also triggers:
- **Relationship Cleanup:** `mark_relationships_deleted()` method
- **Related Actions:** Updates `acl_roles_actions` relationship table
- **User Assignments:** Handles `acl_roles_users` relationship cleanup

### Database Query Pattern
```sql
UPDATE acl_roles SET deleted=1, date_modified='[current_timestamp]' 
WHERE id='[record_id]'
```

## Internal API Calls

### Bean Factory Integration
- `BeanFactory::newBean('ACLRoles')`: Creates role bean instance for deletion
- Inherits SugarBean deletion methods and validation

### Form Base Integration
- `require_once('include/formbase.php')`: Includes form handling utilities
- `handleRedirect()`: Manages post-deletion navigation

### Request Handling
- `$_REQUEST['record']`: Retrieves role ID for deletion
- Standard SuiteCRM request parameter processing

## UI Functionality

### Deletion Confirmation
The deletion is typically triggered from:
- **DetailView.php**: Delete button with JavaScript confirmation
- **ListView.php**: Mass delete operations
- **Direct URL**: Administrative deletion operations

### Navigation Handling
```php
require_once('include/formbase.php');
handleRedirect();
```

**Redirect Logic:**
- Uses `handleRedirect()` from formbase.php
- Respects `return_module` and `return_action` parameters
- Provides fallback navigation if return parameters missing
- Maintains user workflow context

### User Interface Integration
- Works with JavaScript confirmation dialogs
- Integrates with SuiteCRM's notification system
- Provides feedback through redirect destination

## Security and Access Control

### Entry Point Validation
```php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
```

### Permission Requirements
- Requires DELETE permission on ACLRoles module
- Administrative privileges typically required
- Role-based access control applies to deletion operations

### Data Validation
- Validates record ID presence before deletion
- Prevents deletion of invalid or non-existent roles
- Maintains data integrity through SugarBean validation

### Security Implications
- Soft delete preserves security audit trail
- Related permission assignments automatically handled
- User access rights updated immediately after role deletion

## Error Handling

### Parameter Validation
```php
if (isset($_REQUEST['record'])) {
    // Proceed with deletion
}
```
- Checks for required record parameter
- Gracefully handles missing or invalid record IDs
- Fails silently if no record specified (no action taken)

### Database Error Handling
- Inherits SugarBean error handling mechanisms
- Maintains transaction integrity during deletion
- Proper rollback on deletion failure

### Redirect Error Handling
- `handleRedirect()` provides fallback navigation
- Handles missing return parameters gracefully
- Prevents navigation errors after deletion

## Performance Considerations

### Efficient Deletion Process
- Single database update operation for role record
- Batch processing for related record cleanup
- Minimal overhead through soft delete approach

### Memory Management
- Creates minimal bean instance for deletion
- No unnecessary data loading before deletion
- Efficient cleanup of object references

### Database Optimization
- Uses indexed record ID for fast lookup
- Leverages existing database constraints
- Minimal impact on related table performance

## Integration Points

### Module Integration
- **Users Module**: Removes role assignments from affected users
- **ACLActions Module**: Cleans up permission relationships
- **SecurityGroups Module**: Updates group-based permissions if applicable

### System Integration
- **Audit System**: Maintains deletion audit trail
- **Cache System**: Invalidates cached role permissions
- **Notification System**: May trigger user notifications about permission changes

### Workflow Integration
- Standard SuiteCRM deletion workflow
- Integration with business process automation
- Custom deletion hooks if implemented

## Impact Analysis

### User Permission Impact
When a role is deleted:
- Users assigned to role lose associated permissions
- Permission calculations updated for affected users
- Cache invalidation ensures immediate effect

### System-Wide Effects
- Module access rights recalculated
- List view and search results updated
- Related reports and dashboards affected

### Data Integrity Maintenance
- Soft delete preserves historical assignments
- Audit trail maintained for compliance
- Related data relationships preserved

## Related Operations

### Pre-Deletion Considerations
Before deletion, administrators should consider:
- Impact on users with this role assignment
- Alternative roles for affected users
- Permission dependencies in custom modules

### Post-Deletion Actions
After successful deletion:
- Review affected user permissions
- Update related security group assignments
- Verify custom module access rights

### Recovery Procedures
If role needs to be restored:
- Database-level update to reset deleted flag
- Relationship restoration through admin tools
- Permission cache refresh for all users

## Navigation Flow

### Standard Delete Flow
1. **Trigger**: User clicks delete button with confirmation
2. **Processing**: Delete.php processes deletion request
3. **Database**: Role marked as deleted with relationships cleaned
4. **Redirect**: Navigation to list view or specified return location

### Return Parameter Handling
- `return_module`: Module to return to after deletion
- `return_action`: Action to execute on return module
- `return_id`: Record ID for return context (if applicable)

### Fallback Navigation
If return parameters not specified:
- Defaults to ACLRoles list view
- Maintains module context
- Provides consistent user experience

## Configuration Dependencies

### System Configuration
- Requires proper ACL permissions configuration
- Depends on user role assignment for access
- Integration with SecurityGroups if enabled

### Module Dependencies
- Depends on formbase.php for redirect handling
- Requires ACLRole bean definition and methods
- Integration with SugarBean deletion framework 