# Customer Interaction Summary Generator Documentation

## Overview

The Customer Interaction Summary Generator is a powerful feature that aggregates all customer interactions (tasks, meetings, calls, emails, and notes) from related records into a unified, chronological timeline view. This feature helps marketing agencies get a complete picture of all customer touchpoints in one place.

## Key Features

- **Comprehensive Timeline View**: Displays all customer interactions in chronological order
- **Advanced Filtering**: Filter by date range, activity type, and assigned user
- **Export Capabilities**: Export timeline data to CSV format for external analysis
- **ACL-Aware**: Respects SuiteCRM's security model and only shows activities the user has permission to view
- **Timezone Support**: Properly handles timezone conversions for accurate date/time display
- **Module Integration**: Can be accessed from any module that has activity relationships

## Technical Architecture

### Component Structure

```
custom/modules/InteractionSummary/
├── controller/
│   └── controller.php          # Handles timeline and export actions
├── service/
│   └── InteractionSummaryService.php    # Core aggregation logic
├── language/
│   └── en_us.lang.php         # Language strings
├── tpls/
│   └── TimelineView.tpl       # Smarty template for timeline display
├── views/
│   └── view.timeline.php      # Full page view handler
└── InteractionTimelineAction.php    # Integration helper for other modules
```

### Key Classes

1. **InteractionSummaryService**: Core service class that handles data aggregation
   - Extends SugarBean for database access
   - Uses relationship-based queries for performance
   - Implements comprehensive ACL checks
   - Handles timezone conversions

2. **InteractionSummaryController**: HTTP request handler
   - Processes timeline display requests
   - Handles export functionality
   - Manages AJAX requests for filtering

3. **InteractionTimelineAction**: Integration helper
   - Provides methods to add timeline buttons to other modules
   - Handles menu integration

## Implementation Details

### Data Aggregation

The service uses SuiteCRM's relationship framework to efficiently load related activities:

```php
// For each activity type (tasks, meetings, calls, emails, notes)
$linkedBeans = $focus->get_linked_beans(
    $config['relationship'], 
    $config['beanClass'],
    array(), // sort_array
    0,       // begin_index
    -1,      // end_index (-1 = all)
    0,       // deleted
    $whereClause // Custom filtering
);
```

### Date Field Mapping

Different activity types use different date fields for chronological sorting:
- **Emails**: `date_sent_received`
- **Calls/Meetings**: `date_start`
- **Tasks**: `date_due`
- **Notes**: `date_modified`

### Security Implementation

Multiple layers of security are implemented:
1. Parent record ACL check before any data retrieval
2. Individual activity ACL checks for each record
3. SQL injection prevention through proper quoting
4. XSS protection through HTML encoding

## Integration Guide

### Adding Timeline to a Module

To add the Interaction Timeline button to any module's detail view:

1. Create a custom detail view for the module:

```php
// custom/modules/[ModuleName]/views/view.detail.php
require_once('modules/[ModuleName]/views/view.detail.php');
require_once('custom/modules/InteractionSummary/InteractionTimelineAction.php');

class Custom[ModuleName]ViewDetail extends [ModuleName]ViewDetail
{
    public function display()
    {
        parent::display();
        
        if (!empty($this->bean->id)) {
            echo InteractionTimelineAction::getTimelineButtonScript('[ModuleName]', $this->bean->id);
        }
    }
    
    public function preDisplay()
    {
        parent::preDisplay();
        
        if (!empty($this->bean->id)) {
            global $module_menu;
            InteractionTimelineAction::addTimelineMenuLink($module_menu, '[ModuleName]', $this->bean->id);
        }
    }
}
```

### Direct URL Access

The timeline can be accessed directly via URL:
```
index.php?module=InteractionSummary&action=GetTimeline&parent_type=[ModuleName]&parent_id=[RecordID]
```

### Export URL Format

To export interactions directly:
```
index.php?module=InteractionSummary&action=Export&format=csv&parent_type=[ModuleName]&parent_id=[RecordID]
```

## User Guide

### Viewing the Timeline

1. Navigate to any record detail view (e.g., Account, Contact, Lead)
2. Click the "View Interaction Timeline" button
3. The timeline will open in a new window showing all related activities

### Using Filters

The timeline provides several filtering options:

- **Date Range**: Select start and end dates to filter activities within a specific period
- **Activity Type**: Filter by specific activity types (Notes, Emails, Calls, Meetings, Tasks)
- **Assigned User**: Filter by the user assigned to the activities

### Exporting Data

1. Apply any desired filters
2. Click "Export to CSV" button
3. The filtered data will download as a CSV file

### Understanding the Timeline

Each row in the timeline shows:
- **Date**: When the activity occurred (timezone-adjusted)
- **Type**: The type of activity with a color-coded label
- **Subject**: The activity title (clickable to view details)
- **Description**: A preview of the activity content
- **Status**: Current status of the activity
- **Assigned To**: User responsible for the activity

## Performance Considerations

### Optimization Strategies

1. **Relationship-Based Queries**: Uses SuiteCRM's optimized relationship loading
2. **Single Query Per Type**: Loads all activities of each type in one query
3. **Lazy Loading**: Only loads data when requested
4. **Efficient Sorting**: Sorts in memory after aggregation

### Recommended Limits

- For best performance, use date filters to limit the timeline to relevant periods
- Large accounts with thousands of activities may take longer to load
- Export is limited to prevent memory issues with very large datasets

## Troubleshooting

### Common Issues

1. **No Timeline Button Appears**
   - Ensure the custom view file is properly created
   - Clear SuiteCRM cache (Admin → Repair → Quick Repair and Rebuild)
   - Check file permissions

2. **No Activities Show in Timeline**
   - Verify user has permission to view activities
   - Check that activities are properly related to the parent record
   - Ensure activities are not deleted

3. **Export Fails**
   - Check PHP memory limits for large exports
   - Verify user has export permissions
   - Check browser popup blocker settings

## Future Enhancements

Potential improvements for future versions:

1. **PDF Export**: Add PDF export capability for professional reports
2. **Activity Grouping**: Group activities by day/week/month
3. **Caching**: Implement caching for frequently accessed timelines
4. **Real-time Updates**: Add WebSocket support for live updates
5. **Custom Activity Types**: Support for custom modules as activities
6. **Advanced Analytics**: Add summary statistics and charts

## Technical Notes

### Module Structure

The InteractionSummary is implemented as a service module without a database table. It leverages existing SuiteCRM relationships and provides a unified view of dispersed data.

### Compatibility

- Tested with SuiteCRM 7.x
- PHP 7.4+ compatible
- Works with all standard SuiteCRM themes
- Mobile-responsive design

### Security Best Practices

1. Always use ACL checks before displaying data
2. Sanitize all user inputs
3. Use parameterized queries
4. Escape output for XSS prevention
5. Validate parent record access 