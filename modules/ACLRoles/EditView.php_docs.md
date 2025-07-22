# EditView.php Documentation

## @fileoverview
Role editing interface for the ACLRoles module. This file renders the role creation and modification form with permission matrix, handles duplication scenarios, and manages the complete role editing workflow with Smarty template integration.

## @package
SuiteCRM ACLRoles Module - Role editing interface

## @copyright
Copyright (C) 2004-2013 SugarCRM Inc., 2011-2018 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## EditView Workflow

### Role Loading and Initialization
```php
$role = BeanFactory::newBean('ACLRoles');
if (!empty($_REQUEST['record'])) {
    $role->retrieve($_REQUEST['record']);
    $categories = (new ACLRole())->getRoleActions($_REQUEST['record']);
    $role_name = $role->name;
}
```

**Process Steps:**
1. Creates new ACLRole bean instance
2. Loads existing role if record ID provided
3. Retrieves role actions and permissions for display
4. Prepares role data for form population

### Duplication Handling
```php
if (!empty($_REQUEST['isDuplicate'])) {
    $role->id = '';
    $sugar_smarty->assign('ISDUPLICATE', $_REQUEST['record']);
    $duplicateString = translate('LBL_DUPLICATE_OF', 'ACLRoles');
}
```

**Duplication Process:**
- Clears role ID to create new record
- Preserves source role permissions
- Sets duplicate flag for form processing
- Updates form title with duplication indicator

## Database Operations

### Role Data Retrieval
- `$role->retrieve($_REQUEST['record'])`: Loads existing role data
- `getRoleActions($record_id)`: Retrieves permission matrix for role
- `ACLAction::setupCategoriesMatrix($categories)`: Organizes permissions for display

### Permission Matrix Generation
```php
$categories = (new ACLRole())->getRoleActions($_REQUEST['record']);
$names = ACLAction::setupCategoriesMatrix($categories);
```

**Data Structure:**
- Categories organized by module
- Actions grouped by type (module vs. record level)
- Permission levels for each action displayed in matrix format

## Internal API Calls

### Smarty Template Engine
- `new Sugar_Smarty()`: Creates template engine instance
- Template variable assignments for form rendering
- Integration with SuiteCRM's templating system

### Language and Localization
- `$mod_strings`: Module-specific language strings
- `$app_strings`: Application-wide language strings
- `$app_list_strings`: Dropdown and list options
- `translate()`: Dynamic translation of duplication label

### ACL Integration
- `ACLAction::setupCategoriesMatrix()`: Organizes permissions for display
- `(new ACLRole())->getRoleActions()`: Retrieves role permissions
- Permission matrix calculation and organization

### Navigation Handling
```php
$return = array('module'=>'ACLRoles', 'action'=>'index', 'record'=>'');
if (isset($_REQUEST['return_module'])) {
    $return['module'] = $_REQUEST['return_module'];
    // Additional return parameter processing
}
```

## UI Functionality

### Form Generation and Display

#### Template Variable Assignment
```php
$sugar_smarty->assign('ROLE', $role->toArray());
$sugar_smarty->assign('CATEGORIES', $categories);
$sugar_smarty->assign('ACTION_NAMES', $names);
$sugar_smarty->assign('RETURN', $return);
```

#### Permission Matrix Display
- `$categories`: Organized permission data by module
- `$names`: Action names for matrix headers
- `$tdwidth`: Column width calculation for responsive display
- Dynamic matrix generation based on available actions

### Button Configuration
```php
$buttons = array(
    "Save Button HTML",
    "Cancel Button HTML"
);
```

**Button Functionality:**
- **Save Button**: Validates form and submits to Save.php
- **Cancel Button**: Returns to specified return location
- JavaScript integration for form validation
- Proper accessibility attributes and styling

### Form Validation
```php
onclick="this.form.action.value='Save';return check_form('EditView');"
```
- Client-side validation before submission
- Form completeness checking
- Required field validation
- Integration with SuiteCRM's validation framework

### Navigation Breadcrumbs
```php
$params = array();
$params[] = "<a href='index.php?module=ACLRoles&action=index'>{$mod_strings['LBL_MODULE_NAME']}</a>";
if (empty($role->id)) {
    $params[] = $GLOBALS['app_strings']['LBL_CREATE_BUTTON_LABEL'];
} else {
    $params[] = $role->get_summary_text();
}
echo getClassicModuleTitle("ACLRoles", $params, true);
```

## Security and Access Control

### Entry Point Validation
```php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
```

### Permission Requirements
- Administrative privileges typically required for role editing
- ACL permissions on ACLRoles module
- Proper authentication and session validation

### Data Validation
- Form field validation through JavaScript
- Server-side validation in Save.php
- Protection against unauthorized role modification

## Performance Considerations

### Template Processing
- Efficient Smarty template compilation
- Minimal data loading for form display
- Optimized permission matrix generation

### Permission Matrix Optimization
- Calculated column widths for responsive display
- Efficient organization of large permission datasets
- Minimal database queries for permission retrieval

### Memory Management
- Proper object cleanup after template rendering
- Efficient array processing for permission data
- Minimal memory footprint for large role sets

## Integration Points

### Template Integration
- **EditView.tpl**: Main template file for form rendering
- **EditRole.tpl**: Alternative template for role-specific editing
- Template inheritance and customization support

### Module Integration
- **Save.php**: Form submission destination
- **DetailView.php**: Post-save navigation target
- **Index.php**: List view return location

### JavaScript Integration
- Form validation functions
- AJAX permission updates
- Dynamic UI interactions
- Client-side permission matrix management

## Form Field Structure

### Role Information Fields
- **Name**: Text input for role name (required)
- **Description**: Textarea for role description
- **Record ID**: Hidden field for existing role updates

### Permission Matrix Fields
- Field naming pattern: `act_guid{action_id}`
- Dropdown selectors for permission levels
- Matrix organization by module and action type
- Real-time permission preview

### Navigation Fields
- **Return Module**: Hidden field for post-save navigation
- **Return Action**: Specific action for return navigation
- **Return Record**: Context record for return navigation
- **IsDuplicate**: Flag for duplication workflow

## Error Handling

### Form Validation Errors
- Client-side validation with user feedback
- Required field highlighting
- Error message display through SuiteCRM notifications

### Data Loading Errors
- Graceful handling of missing role records
- Default permission matrix for new roles
- Fallback values for incomplete data

### Template Rendering Errors
- Proper error handling in Smarty template processing
- Fallback display options for rendering failures
- Error logging for troubleshooting

## Customization Points

### Template Customization
- Custom EditView.tpl for organization-specific layouts
- Permission matrix styling and organization
- Additional form fields through vardefs extension

### Permission Matrix Customization
- Custom action organization
- Module-specific permission displays
- Integration with custom modules

### Workflow Integration
- Custom validation rules
- Approval workflows for role changes
- Integration with external systems

## Related Files and Dependencies

### Template Files
- `modules/ACLRoles/EditView.tpl`: Main edit form template
- `modules/ACLRoles/EditRole.tpl`: Alternative role editing template
- Theme-specific template overrides

### JavaScript Dependencies
- Form validation scripts
- Permission matrix interaction scripts
- AJAX communication for real-time updates

### Style Dependencies
- Module-specific CSS for permission matrix
- Theme integration for consistent styling
- Responsive design considerations for mobile access 