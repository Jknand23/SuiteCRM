# Task Timer Feature Documentation

## Overview

The Task Timer feature enables time tracking for tasks in SuiteCRM, providing accurate billing capabilities, productivity analysis, and project estimation tools. This feature integrates seamlessly with the existing Tasks module and provides real-time tracking with manual time entry options.

## Key Features

- **Real-time Timer**: Start/stop timer with automatic time tracking
- **Manual Time Entry**: Add time entries manually with notes
- **Session History**: View all time sessions for a task
- **Total Time Display**: See accumulated time across all sessions
- **Integration**: Available in Detail View, Edit View, and Quick Create

## Database Schema

The following fields are added to the `tasks` table:

| Field | Type | Description |
|-------|------|-------------|
| `timer_is_running` | TINYINT(1) | Boolean flag indicating if timer is currently running |
| `timer_start_time` | DATETIME | Timestamp when current timer session started |
| `timer_total_seconds` | INT(11) | Total accumulated time in seconds |
| `timer_sessions` | TEXT | JSON array of time session records |

## Usage Guide

### Starting the Timer

1. Navigate to a task Detail or Edit view
2. Click the "Start Timer" button in the Time Tracking panel
3. The timer begins tracking time immediately
4. Current session time and total time are displayed

### Stopping the Timer

1. Click the "Stop Timer" button (red button when timer is running)
2. The session is saved and added to the session history
3. Total time is updated with the session duration

### Adding Manual Time

1. Click the "Add Time" button
2. Enter hours and minutes in the modal dialog
3. Optionally add notes describing the work performed
4. Click "Add Time" to save the entry

### Viewing Time Sessions

1. Click on "Time Sessions" header to expand the history
2. View all recorded sessions with:
   - Start time
   - Duration
   - Type (Tracked/Manual)
   - User who recorded the time
   - Notes (if any)

### Quick Create Integration

When creating a new task via Quick Create:
1. Check "Start timer when task is created"
2. The timer will automatically start after saving

## API Endpoints

### Timer Start
```
POST index.php?module=Tasks&action=TimerStart
Parameters: task_id
```

### Timer Stop
```
POST index.php?module=Tasks&action=TimerStop
Parameters: task_id
```

### Timer Status
```
GET index.php?module=Tasks&action=TimerStatus
Parameters: task_id
```

### Add Manual Time
```
POST index.php?module=Tasks&action=TimerAddManual
Parameters: task_id, hours, minutes, notes
```

## Implementation Files

### Backend
- `modules/Tasks/Services/TimerService.php` - Core timer business logic
- `modules/Tasks/controller.php` - API endpoint handlers
- `modules/Tasks/vardefs.php` - Field definitions
- `modules/Tasks/sql/timer_migration.sql` - Database migration script

### Frontend
- `modules/Tasks/js/timer.js` - JavaScript timer functionality
- `modules/Tasks/css/timer.css` - Timer widget styling
- `modules/Tasks/tpls/timer_widget.tpl` - Main timer widget template
- `modules/Tasks/tpls/timer_widget_quick.tpl` - Quick Create widget

### Configuration
- `modules/Tasks/metadata/detailviewdefs.php` - Detail view integration
- `modules/Tasks/metadata/editviewdefs.php` - Edit view integration
- `modules/Tasks/language/en_us.lang.php` - Language strings

## Security Considerations

- Only users with access to a task can manage its timer
- All timer actions are validated server-side
- Timer sessions include user tracking for audit purposes
- Admin users have full access to all task timers

## Performance Notes

- Client-side timer updates every second without server calls
- Timer state is synchronized with server on start/stop actions
- Sessions are stored as JSON for efficient querying
- Indexes on `timer_is_running` and `timer_total_seconds` for reporting

## Troubleshooting

### Timer Not Starting
- Verify user has edit permissions on the task
- Check browser console for JavaScript errors
- Ensure task record exists and is not deleted

### Time Not Saving
- Verify database fields exist (run migration script)
- Check server logs for PHP errors
- Ensure proper write permissions on task record

### Display Issues
- Clear browser cache
- Verify timer.css and timer.js are loading
- Check for JavaScript conflicts with other modules

## Future Enhancements

- Export time reports to CSV/PDF
- Integration with billing/invoicing modules
- Team time tracking and comparisons
- Automated time tracking based on task status changes
- Mobile app integration 