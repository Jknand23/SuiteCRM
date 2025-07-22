# Activities Module Menu Configuration Documentation

## @fileoverview
Constructs the navigation menu for the Activities module with ACL-protected links to create, list, and import various activity types.

## @package Activities
## @copyright SugarCRM Inc & SalesAgility Ltd
## @license GNU Affero General Public License version 3

## Purpose
Dynamically builds the Activities module menu array with appropriate permissions checking, providing links to create, view, and import calls, meetings, tasks, notes, and calendar views.

## Internal API Calls

### ACL Controller Operations
- **ACLController::checkAccess()**: Validates user permissions for each menu item
  - **Parameters**: 
    - Module name (Calls, Meetings, Tasks, Notes, Calendar)
    - Action type (edit, list, import)
    - Boolean flag for user-specific check

### Menu Structure
Each menu item follows the pattern:
```php
[URL, Label, Icon, Category]
```

## UI Functionality

### Create/Edit Actions
- **Schedule Call**: Links to Calls EditView with proper return navigation
- **Schedule Meeting**: Links to Meetings EditView with proper return navigation  
- **Create Task**: Links to Tasks EditView with proper return navigation
- **Create Note**: Links to Notes EditView with proper return navigation

### List/View Actions
- **Call List**: Access to Calls module list view
- **Meeting List**: Access to Meetings module list view
- **Task List**: Access to Tasks module list view
- **Note List**: Access to Notes module list view
- **View Calendar**: Links to Calendar day view

### Import Actions
- **Import Calls**: Access to Import module for call records
- **Import Meetings**: Access to Import module for meeting records
- **Import Tasks**: Access to Import module for task records
- **Import Notes**: Access to Import module for note records

## Global Dependencies
- **$mod_strings**: Module-specific language strings for menu labels
- **$app_strings**: Application-wide language strings
- **$module_menu**: Global array populated with menu items

## Integration Points

### Related Modules
- **Calls**: Target module for call-related menu items
- **Meetings**: Target module for meeting-related menu items
- **Tasks**: Target module for task-related menu items
- **Notes**: Target module for note-related menu items
- **Calendar**: Target module for calendar view
- **Import**: Target module for import functionality

### Language Integration
- Uses language/en_us.lang.php strings for menu labels
- Supports internationalization through module language files

## Security Features
- **ACL Integration**: Each menu item checked against user permissions
- **Permission Types**: edit, list, import permissions validated separately
- **Graceful Degradation**: Menu items hidden if user lacks permissions

## Technical Notes
- Menu array structure follows SuiteCRM standard format
- Return navigation properly configured for each action
- Icon assignments support theme customization
- Import actions include proper module targeting and return paths

## Menu Categories
- **Creation Actions**: New record creation with icons (Create, Schedule_Call, Schedule_Meetings)
- **List Actions**: Record browsing (Calls, List, Calendar)  
- **Import Actions**: Data import functionality (Import) 