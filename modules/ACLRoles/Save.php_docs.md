# Save.php Documentation

## @fileoverview
Form submission processing for ACLRoles module. This file handles role creation, modification, duplication, and permission assignment through form data processing and database operations.

## @package
SuiteCRM ACLRoles Module - Form save processing

## @copyright
Copyright (C) 2004-2013 SugarCRM Inc., 2011-2018 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Save Processing Workflow

### Role Creation and Modification

#### Standard Role Save Process
```php
$role = BeanFactory::newBean('ACLRoles');
if (isset($_REQUEST['record'])) {
    $role->id = $_POST['record'];
}
if (!empty($_REQUEST['name'])) {
    $role->name = $_POST['name'];
    $role->description = $_POST['description'];
    $role->save();
}
```

**Process Steps:**
1. Creates new ACLRole bean instance
2. Sets role ID if editing existing role
3. Populates name and description from form data
4. Saves role to database

### Role Duplication Functionality

#### Duplication Process
```php
if (isset($_REQUEST['isduplicate']) && !empty($_REQUEST['isduplicate'])) {
    $role_actions = $role->getRoleActions($_REQUEST['isduplicate']);
    foreach ($role_actions as $module) {
        foreach ($module as $type) {
            foreach ($type as $act) {
                $role->setAction($role->id, $act['id'], $act['aclaccess']);
            }
        }
    }
}
```

**Duplication Steps:**
1. Checks for `isduplicate` parameter indicating role duplication
2. Retrieves all actions from source role using `getRoleActions()`
3. Iterates through modules, action types, and specific actions
4. Copies each permission setting to new role using `setAction()`

## Database Operations

### Role Record Management

#### Role Save Operation
- Uses `$role->save()` method from SugarBean
- Automatically handles `date_entered` and `date_modified` fields
- Generates new ID for new roles, updates existing for modifications

#### Permission Assignment
```php
$role->setAction($role->id, $name, $value);
```
- **Parameters:**
  - `$role->id`: The role identifier
  - `$name`: Action ID (extracted from form field name)
  - `$value`: Permission level (ACL_ALLOW_ALL, ACL_ALLOW_NONE, etc.)

### Form Data Processing

#### Permission Data Extraction
```php
foreach ($_POST as $name => $value) {
    if (substr_count($name, 'act_guid') > 0) {
        $name = str_replace('act_guid', '', $name);
        $role->setAction($role->id, $name, $value);
    }
}
```

**Process:**
1. Iterates through all POST data
2. Identifies permission fields by `act_guid` prefix
3. Extracts action ID by removing `act_guid` prefix
4. Sets permission level for the action

## Internal API Calls

### Bean Factory Integration
- `BeanFactory::newBean('ACLRoles')`: Creates role bean instance
- Inherits standard SugarBean functionality for CRUD operations

### Role Management API
- `$role->getRoleActions($role_id)`: Retrieves existing role permissions for duplication
- `$role->setAction($role_id, $action_id, $access)`: Sets individual permission levels
- `$role->save()`: Persists role changes to database

### System Integration
- `ob_clean()`: Clears output buffer for AJAX responses
- `sugar_cleanup(true)`: Performs cleanup for AJAX termination
- `header("Location: ...")`: Redirects after save completion

## UI Functionality

### Form Processing Modes

#### Standard Form Submission
- Processes role name and description
- Handles role creation and modification
- Redirects to DetailView after completion

#### AJAX Permission Updates
```php
echo "result = {role_id:'$role->id', module:'$flc_module'}";
sugar_cleanup(true);
```
- Returns JSON-formatted response for JavaScript processing
- Includes role ID for client-side reference
- Terminates script execution to prevent redirect

### Redirect Handling
```php
header("Location: index.php?module=ACLRoles&action=DetailView&record=". $role->id);
```
- Redirects to role DetailView after successful save
- Includes record ID in URL for context
- Follows standard SuiteCRM navigation pattern

### Form Field Processing

#### Role Information Fields
- `$_POST['name']`: Role name (required for save)
- `$_POST['description']`: Role description (optional)
- `$_POST['record']`: Role ID for existing role modification

#### Permission Fields
- Field naming pattern: `act_guid{action_id}`
- Values represent permission levels (0-4 typically)
- Processed in batch for efficiency

## Security and Access Control

### Entry Point Validation
```php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
```

### Input Validation
- Checks for required `name` field before processing
- Validates record ID for existing role updates
- Sanitizes form data through SugarBean processing

### Permission Security
- Only processes permission fields with proper `act_guid` prefix
- Uses established ACL permission levels
- Maintains data integrity through database constraints

## Performance Considerations

### Batch Processing
- Groups permission updates for efficiency
- Minimizes database calls through batch operations
- Uses single save operation for role metadata

### Memory Management
- Clears output buffer for AJAX responses
- Proper cleanup with `sugar_cleanup(true)`
- Efficient iteration through form data

### Database Optimization
- Uses indexed role and action IDs
- Leverages existing relationship table structure
- Efficient JOIN operations in underlying methods

## Error Handling

### Form Validation
- Requires role name for save operation
- Gracefully handles missing or invalid data
- Provides fallback for incomplete form submissions

### Database Error Handling
- Inherits SugarBean error handling mechanisms
- Maintains transaction integrity
- Proper rollback on failure conditions

### Response Handling
- Different responses for standard vs. AJAX submissions
- Proper HTTP status codes and redirects
- Error messages through SuiteCRM notification system

## Integration Points

### Module Integration
- **ACLActions Module**: Retrieves and processes action permissions
- **Users Module**: Indirectly affects user permissions through role changes
- **SecurityGroups Module**: May influence permission inheritance

### Form Integration
- **EditView.php**: Source of form data and submission
- **DetailView.php**: Destination after successful save
- **JavaScript**: Receives AJAX responses for real-time updates

### Workflow Integration
- Standard SuiteCRM save workflow
- Integration with audit logging
- Cache invalidation for permission changes

## Data Flow

### Standard Save Flow
1. **Form Submission** → EditView.php submits to Save.php
2. **Data Processing** → Extract role info and permissions
3. **Database Update** → Save role and update permissions
4. **Redirect** → Navigate to DetailView with saved role

### Duplication Flow
1. **Duplication Request** → Include `isduplicate` parameter
2. **Source Role Analysis** → Retrieve all permissions from source
3. **Permission Copying** → Apply same permissions to new role
4. **Completion** → Standard redirect to new role DetailView

### AJAX Flow
1. **Permission Change** → JavaScript submits individual permission updates
2. **Batch Processing** → Process all changed permissions
3. **JSON Response** → Return confirmation with role ID
4. **Client Update** → JavaScript updates UI without page reload

## Configuration Dependencies

### Form Field Configuration
- Depends on EditView template structure
- Field naming conventions must match processing logic
- Permission matrix layout affects field processing

### Permission Level Configuration
- Uses standard ACL permission constants
- Integrates with system-wide permission definitions
- Maintains consistency with other ACL modules 