# Activities Subpanel Definitions Documentation

## @fileoverview
Defines comprehensive subpanel layout configurations for Activities module, specifying display columns, widgets, and filtering for tasks, meetings, and calls in subpanel views.

## @package Activities
## @copyright SugarCRM Inc & SalesAgility Ltd
## @license GNU Affero General Public License version 3

## Purpose
Configures the subpanel display structure for the Activities module, defining how tasks, meetings, and calls appear when displayed as related records in other modules' detail views.

## UI Functionality

### Subpanel Structure
The layout defines a comprehensive subpanel configuration that displays three activity types with specific filtering and column arrangements.

### Top Button Configuration
- **SubPanelTopCreateTaskButton**: Quick task creation widget
- **SubPanelTopScheduleMeetingButton**: Quick meeting scheduling widget  
- **SubPanelTopScheduleCallButton**: Quick call scheduling widget
- **SubPanelTopComposeEmailButton**: Quick email composition widget

### Column Definitions

#### Meetings Subpanel
- **Icon Column**: SubPanelIcon widget (2% width)
- **Subject**: name field with SubPanelDetailViewLink widget (30% width)
- **Status**: status field with SubPanelActivitiesStatusField widget (15% width)
- **Contact**: contact_name with module linking to Contacts (11% width)
- **Related To**: parent_name field (22% width)
- **Start Date**: date_start field (10% width)
- **Close Button**: SubPanelCloseButton widget (6% width)
- **Edit Button**: SubPanelEditButton widget (2% width)
- **Remove Button**: SubPanelRemoveButton widget (2% width)

#### Tasks Subpanel
- **Icon Column**: SubPanelIcon widget (2% width)
- **Close Button**: SubPanelCloseButton widget (6% width)
- **Subject**: name field with SubPanelDetailViewLink widget (30% width)
- **Status**: status field with SubPanelActivitiesStatusField widget (15% width)
- **Contact**: contact_name with module linking to Contacts (11% width)
- **Related To**: parent_name field (22% width)
- **Start Date**: date_start field (10% width)
- **Edit Button**: SubPanelEditButton widget (2% width)
- **Remove Button**: SubPanelRemoveButton widget (2% width)

#### Calls Subpanel
- **Icon Column**: SubPanelIcon widget (2% width)
- **Close Button**: SubPanelCloseButton widget (6% width)
- **Subject**: name field with SubPanelDetailViewLink widget (30% width)
- **Status**: status field with SubPanelActivitiesStatusField widget (15% width)
- **Contact**: contact_name with module linking to Contacts (11% width)
- **Related To**: parent_name field (20% width)
- **Start Date**: date_start field (22% width)
- **Edit Button**: SubPanelEditButton widget (2% width)
- **Remove Button**: SubPanelRemoveButton widget (2% width)

## Database Operations

### Filtering Criteria
Each activity type uses specific WHERE clauses to filter displayed records:

#### Meetings Filter
```sql
WHERE meetings.status='Planned'
ORDER BY meetings.date_start
```

#### Tasks Filter  
```sql
WHERE (tasks.status='Not Started' OR tasks.status='In Progress' OR tasks.status='Pending Input')
ORDER BY tasks.date_start
```

#### Calls Filter
```sql
WHERE calls.status='Planned'
ORDER BY calls.date_start
```

### Relationship Configuration
- **linked_field**: Specifies relationship field for remove operations
- **target_record_key**: Defines key field for contact linking
- **target_module**: Specifies target module for detail view links

## Widget Integration

### Specialized Widgets
- **SubPanelIcon**: Displays module-specific icons
- **SubPanelDetailViewLink**: Creates clickable links to record detail views
- **SubPanelActivitiesStatusField**: Specialized status display for activities
- **SubPanelCloseButton**: Provides quick status closure functionality
- **SubPanelEditButton**: Quick edit access for records
- **SubPanelRemoveButton**: Relationship removal functionality

### Top Action Widgets
- **SubPanelTopCreateTaskButton**: Task creation from parent record context
- **SubPanelTopScheduleMeetingButton**: Meeting creation with parent relationship
- **SubPanelTopScheduleCallButton**: Call creation with parent relationship
- **SubPanelTopComposeEmailButton**: Email composition with parent context

## Integration Points

### Related Files
- **config.php**: Status filtering uses open status definitions
- **language/en_us.lang.php**: Language strings for subpanel labels
- **Subpanel widget classes**: Specialized display and action widgets

### Module Dependencies
- **Tasks module**: Task record display and management
- **Meetings module**: Meeting record display and management  
- **Calls module**: Call record display and management
- **Contacts module**: Contact linking and detail views

### Layout System
- **Layout Editor**: Configuration is compatible with layout customization
- **Subpanel Framework**: Uses standard SuiteCRM subpanel architecture
- **Widget System**: Leverages pluggable widget architecture

## Activity Status Integration

### Status-Based Filtering
- **Open Activities**: Only shows activities in "open" statuses (Planned, Not Started, In Progress, Pending Input)
- **Status Consistency**: Aligns with config.php open status definitions
- **Activity Lifecycle**: Reflects current activity states for user workflow

### Ordering
- **Date-Based**: All activity types ordered by start date/due date
- **Chronological Display**: Shows activities in time-sequence order
- **User Planning**: Supports time-based activity management

## Customization Features
- **Column Widths**: Percentage-based responsive layout
- **Widget Flexibility**: Pluggable widget system for custom functionality
- **Filter Customization**: Configurable WHERE clauses for business rules
- **Field Selection**: Configurable column display for different requirements

## Security Considerations
- **Widget Security**: Each widget handles its own ACL checks
- **Module Access**: Leverages standard SuiteCRM module security
- **Action Buttons**: Buttons only appear if user has appropriate permissions

## Performance Optimization
- **Filtered Queries**: WHERE clauses limit data retrieval to relevant records
- **Indexed Ordering**: ORDER BY clauses use indexed date fields
- **Widget Efficiency**: Specialized widgets optimize display rendering 