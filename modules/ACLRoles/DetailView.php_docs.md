# DetailView.php Documentation

## @fileoverview
Role detail view interface for the ACLRoles module. This file displays role information with permission matrix, provides action buttons for role management, and includes subpanel integration for related records display.

## @package
SuiteCRM ACLRoles Module - Role detail view interface

## @copyright
Copyright (C) 2004-2013 SugarCRM Inc., 2011-2019 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## DetailView Workflow

### Role Data Loading
```php
$role = BeanFactory::newBean('ACLRoles');
$categories = $role->getRoleActions($_REQUEST['record']);
$role->retrieve($_REQUEST['record']);
```

**Process Steps:**
1. Creates new ACLRole bean instance
2. Retrieves permission matrix for the role
3. Loads complete role record data
4. Prepares data for display rendering

### Permission Matrix Display
```php
$names = ACLAction::setupCategoriesMatrix($categories);
if (!empty($names)) {
    $tdWidth = 100 / (is_countable($names) ? count($names) : 0);
}
```

**Display Calculation:**
- Organizes permissions by category and action type
- Calculates dynamic column widths for responsive display
- Handles empty permission sets gracefully

## Database Operations

### Role Data Retrieval
- `$role->retrieve($_REQUEST['record'])`: Loads complete role record
- `$role->getRoleActions($_REQUEST['record'])`: Retrieves associated permissions
- `ACLAction::setupCategoriesMatrix($categories)`: Organizes permission data

### Subpanel Data Loading
```php
$focus =& $role;
$_REQUEST['module'] = 'ACLRoles';
require_once __DIR__ . '/../../include/SubPanel/SubPanelTiles.php';
$subPanel = new SubPanelTiles($role, 'ACLRoles');
```

**Subpanel Integration:**
- Users subpanel showing role assignments
- Related records through relationship definitions
- Dynamic subpanel generation based on role relationships

## Internal API Calls

### Smarty Template Engine
- `new Sugar_Smarty()`: Creates template engine instance
- Template variable assignments for role display
- Integration with DetailView.tpl template

### Language and Localization
- `$mod_strings`: Module-specific language strings
- `$app_strings`: Application-wide language strings
- `$app_list_strings`: Dropdown and list options

### Permission Matrix API
- `ACLAction::setupCategoriesMatrix()`: Organizes permissions for display
- Permission level calculations and formatting
- Dynamic action name generation

### SubPanel Integration
- `SubPanelTiles($role, 'ACLRoles')`: Creates subpanel manager
- `$subPanel->display()`: Renders related record subpanels
- Integration with relationship definitions

## UI Functionality

### Detail Display Components

#### Template Variable Assignment
```php
$sugar_smarty->assign('ROLE', $role->toArray());
$sugar_smarty->assign('CATEGORIES', $categories);
$sugar_smarty->assign('TDWIDTH', $tdWidth);
$sugar_smarty->assign('ACTION_NAMES', $names);
```

#### Role Information Display
- Role name and description
- Creation and modification audit information
- Role ID and system metadata

#### Permission Matrix Display
- Categorized permission display by module
- Action-level permission visualization
- Color-coded permission levels
- Responsive matrix layout

### Action Button Configuration
```php
$buttons[] = "<input title=\"{$app_strings['LBL_EDIT_BUTTON_TITLE']}\" 
    class=\"btn btn-danger\" 
    onclick=\"var _form = $('#form')[0]; _form.action.value='EditView'; _form.submit();\" 
    type=\"submit\" name=\"button\" value=\"{$app_strings['LBL_EDIT_BUTTON']}\" />";
```

**Available Actions:**
- **Edit Button**: Navigate to EditView for role modification
- **Duplicate Button**: Create copy of role with same permissions
- **Delete Button**: Remove role with confirmation dialog

### Navigation Breadcrumbs
```php
$params[] = "<a href='index.php?module=ACLRoles&action=index'>{$mod_strings['LBL_MODULE_NAME']}</a>";
$params[] = $role->get_summary_text();
echo getClassicModuleTitle("ACLRoles", $params, true);
```

### Subpanel Display
```php
echo $subPanel->display();
```
- Related users assigned to role
- Role assignment history
- Integration with SecurityGroups if enabled

## Security and Access Control

### Entry Point Validation
```php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
```

### Permission Requirements
- Read access to ACLRoles module
- Specific role viewing permissions
- Administrative access for action buttons

### Data Protection
- Role information visibility based on user permissions
- Action button availability based on user privileges
- Secure handling of role permission display

## Performance Considerations

### Template Processing
- Efficient Smarty template compilation
- Minimal data loading for display
- Optimized permission matrix rendering

### Permission Matrix Optimization
- Calculated column widths for performance
- Efficient organization of permission data
- Minimal database queries for display

### Subpanel Performance
- Lazy loading of related record data
- Efficient relationship query processing
- Pagination for large user assignments

## Integration Points

### Template Integration
- **DetailView.tpl**: Main template for role display
- **Permission matrix templates**: Specialized permission display
- **Subpanel templates**: Related record display

### Module Integration
- **EditView.php**: Edit action destination
- **Delete.php**: Deletion action handler
- **Users Module**: User assignment subpanel
- **SecurityGroups Module**: Group-based permission integration

### JavaScript Integration
- Form submission handling for action buttons
- Confirmation dialogs for destructive actions
- Dynamic UI interactions
- AJAX-based subpanel updates

## Button Action Implementation

### Edit Button
```php
onclick="var _form = $('#form')[0]; _form.action.value='EditView'; _form.submit();"
```
- Sets form action to EditView
- Submits form with current role context
- Maintains return navigation parameters

### Duplicate Button
```php
onclick="this.form.isDuplicate.value='1'; this.form.action.value='EditView'"
```
- Sets duplication flag
- Navigates to EditView with role data
- Preserves permission settings for copying

### Delete Button
```php
onclick="this.form.return_module.value='ACLRoles'; 
    this.form.return_action.value='index'; 
    this.form.action.value='Delete'; 
    return confirm('{$app_strings['NTC_DELETE_CONFIRMATION']}')"
```
- Sets return navigation parameters
- Shows confirmation dialog
- Proceeds to Delete.php on confirmation

## Error Handling

### Data Loading Errors
- Graceful handling of missing role records
- Default display values for incomplete data
- Error messages for invalid role access

### Permission Matrix Errors
- Fallback display for missing permissions
- Handling of invalid action configurations
- Error logging for troubleshooting

### Subpanel Errors
- Graceful degradation if subpanels fail to load
- Error handling for relationship issues
- Fallback display options

## Customization Points

### Template Customization
- Custom DetailView.tpl for organization layouts
- Permission matrix styling and organization
- Additional information display sections

### Permission Matrix Customization
- Custom permission level displays
- Module-specific permission visualizations
- Integration with custom modules

### Subpanel Customization
- Additional relationship subpanels
- Custom user assignment displays
- Integration with custom related modules

## Related Files and Dependencies

### Template Files
- `modules/ACLRoles/DetailView.tpl`: Main detail display template
- `modules/ACLRoles/DetailUserRole.tpl`: User role detail template
- Permission matrix display templates

### JavaScript Dependencies
- Form submission scripts
- Confirmation dialog handlers
- Subpanel interaction scripts

### CSS Dependencies
- Module-specific styling for permission matrix
- Button styling and layout
- Responsive design for mobile access

## Navigation Flow

### Standard View Flow
1. **Access**: User navigates to role detail view
2. **Loading**: Role data and permissions retrieved
3. **Display**: Template renders role information and matrix
4. **Subpanels**: Related records loaded and displayed
5. **Actions**: User can edit, duplicate, or delete role

### Return Navigation
```php
$return = ['module' => 'ACLRoles', 'action' => 'DetailView', 'record' => $role->id];
$sugar_smarty->assign('RETURN', $return);
```
- Maintains context for form submissions
- Provides consistent navigation experience
- Handles return from edit operations

### Subpanel Navigation
- Click-through to related user records
- Integration with user management workflows
- Contextual navigation maintaining role context 