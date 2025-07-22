# Activities English Language Strings Documentation

## @fileoverview
Comprehensive English language definitions for the Activities module, providing user interface labels, messages, and text strings for all activity-related functionality.

## @package Activities
## @copyright SugarCRM Inc & SalesAgility Ltd
## @license GNU Affero General Public License version 3

## Purpose
Defines all English language strings used throughout the Activities module interface, supporting internationalization and providing consistent terminology across activity management features.

## Language String Categories

### Module Identification
- **LBL_MODULE_NAME**: 'Activities' - Primary module display name
- **LBL_MODULE_TITLE**: 'Activities: Home' - Module home page title
- **LBL_SEARCH_FORM_TITLE**: 'Activities Search' - Search interface title
- **LBL_LIST_FORM_TITLE**: 'Activities List' - List view title

### Navigation Menu Labels (Uppercase)
Template-specific navigation strings for SuiteP theme:
- **LBL_OVERVIEW**: 'OVERVIEW' - Summary view label
- **LBL_TASKS**: 'TASKS' - Tasks section label
- **LBL_MEETINGS**: 'MEETINGS' - Meetings section label
- **LBL_CALLS**: 'CALLS' - Calls section label
- **LBL_EMAILS**: 'EMAILS' - Emails section label
- **LBL_NOTES**: 'NOTES' - Notes section label
- **LBL_PRINT**: 'PRINT' - Print functionality label

### Activity Type Labels
- **LBL_MEETING_TYPE**: 'Meeting' - Meeting activity type
- **LBL_CALL_TYPE**: 'Call' - Call activity type
- **LBL_EMAIL_TYPE**: 'Email' - Email activity type
- **LBL_NOTE_TYPE**: 'Note' - Note activity type

### Data Type Indicators
- **LBL_DATA_TYPE_START**: 'Start:' - Start date/time label
- **LBL_DATA_TYPE_SENT**: 'Sent:' - Email sent date label
- **LBL_DATA_TYPE_MODIFIED**: 'Modified:' - Last modified date label

### List View Column Headers
- **LBL_LIST_SUBJECT**: 'Subject' - Activity subject column
- **LBL_LIST_CONTACT**: 'Contact' - Related contact column
- **LBL_LIST_RELATED_TO**: 'Related to' - Parent record column
- **LBL_LIST_DATE**: 'Date' - Generic date column
- **LBL_LIST_CLOSE**: 'Close' - Close action column
- **LBL_LIST_STATUS**: 'Status' - Activity status column
- **LBL_LIST_DUE_DATE**: 'Due Date' - Due date column
- **LBL_LIST_LAST_MODIFIED**: 'Last Modified' - Modification date column
- **LBL_LIST_DIRECTION**: 'Direction' - Call direction column
- **LBL_LIST_ASSIGNED_TO_NAME**: 'Assigned User' - Assignment column

### Form Field Labels
- **LBL_SUBJECT**: 'Subject:' - Activity subject field
- **LBL_STATUS**: 'Status:' - Activity status field
- **LBL_LOCATION**: 'Location:' - Meeting location field
- **LBL_DATE_TIME**: 'Start Date & Time:' - Combined date/time field
- **LBL_DATE**: 'Start Date:' - Start date field
- **LBL_TIME**: 'Start Time:' - Start time field
- **LBL_DURATION**: 'Duration:' - Activity duration field
- **LBL_HOURS_MINS**: '(hours/minutes)' - Duration format hint
- **LBL_CONTACT_NAME**: 'Contact Name: ' - Contact field label
- **LBL_DESCRIPTION**: 'Description:' - Activity description field
- **LBL_DIRECTION**: 'Direction' - Call direction field

### Navigation Links
#### Creation Links
- **LNK_NEW_CALL**: 'Log Call' - Create new call link
- **LNK_NEW_MEETING**: 'Schedule Meeting' - Create new meeting link
- **LNK_NEW_TASK**: 'Create Task' - Create new task link
- **LNK_NEW_NOTE**: 'Create Note or Add Attachment' - Create new note link
- **LNK_NEW_EMAIL**: 'Create Archived Email' - Create new email link
- **LNK_NEW_APPOINTMENT**: 'New Appointment' - Generic appointment link

#### List View Links
- **LNK_CALL_LIST**: 'View Calls' - Calls list view link
- **LNK_MEETING_LIST**: 'View Meetings' - Meetings list view link
- **LNK_TASK_LIST**: 'View Tasks' - Tasks list view link
- **LNK_NOTE_LIST**: 'View Notes' - Notes list view link
- **LNK_VIEW_CALENDAR**: 'View Calendar' - Calendar view link

#### Import Links
- **LNK_IMPORT_CALLS**: 'Import Calls' - Call import functionality
- **LNK_IMPORT_MEETINGS**: 'Import Meetings' - Meeting import functionality
- **LNK_IMPORT_TASKS**: 'Import Tasks' - Task import functionality
- **LNK_IMPORT_NOTES**: 'Import Notes' - Note import functionality

### Activity Organization
- **LBL_OPEN_ACTIVITIES**: 'Open Activities' - Active activities section
- **LBL_HISTORY**: 'History' - Completed activities section
- **LBL_DEFAULT_SUBPANEL_TITLE**: 'Open Activities' - Default subpanel title

### Button Labels
- **LBL_NEW_TASK_BUTTON_TITLE**: 'Create Task' - Task creation button tooltip
- **LBL_NEW_TASK_BUTTON_LABEL**: 'Create Task' - Task creation button text
- **LBL_SCHEDULE_MEETING_BUTTON_TITLE**: 'Schedule Meeting' - Meeting button tooltip
- **LBL_SCHEDULE_MEETING_BUTTON_LABEL**: 'Schedule Meeting' - Meeting button text
- **LBL_SCHEDULE_CALL_BUTTON_LABEL**: 'Log Call' - Call button text
- **LBL_NEW_NOTE_BUTTON_TITLE**: 'Create Note or Attachment' - Note button tooltip
- **LBL_NEW_NOTE_BUTTON_LABEL**: 'Create Note or Attachment' - Note button text
- **LBL_TRACK_EMAIL_BUTTON_TITLE**: 'Archive Email' - Email button tooltip
- **LBL_TRACK_EMAIL_BUTTON_LABEL**: 'Archive Email' - Email button text

### User Interaction
- **LBL_INVITEE**: 'Invitees' - Meeting/call participants label
- **LBL_ACCEPT_THIS**: 'Accept?' - Invitation acceptance prompt
- **LBL_ACCEPT**: 'Accept' - Acceptance action (508 compliance)

### System Messages
- **LBL_DELETE_ACTIVITY**: 'Are you sure you want to delete this activity?' - Deletion confirmation
- **ERR_DELETE_RECORD**: 'You must specify a record number to delete the account.' - Deletion error

## Integration Points

### Related Files
- **Menu.php**: Uses navigation link labels for menu construction
- **Popup_picker.php**: Uses activity type and data type labels
- **subpaneldefs.php**: References list column headers
- **views/**: Various views use form field labels

### Internationalization
- **Translation Framework**: Serves as base for other language translations
- **Module Language System**: Integrated with SuiteCRM's language management
- **Theme Compatibility**: Supports template-specific label variations

## UI Functionality
- **Consistent Terminology**: Ensures uniform language across all activity interfaces
- **User Experience**: Provides clear, descriptive labels for all functionality
- **Accessibility**: Includes 508 compliance labels where required
- **Template Support**: Special uppercase labels for modern theme templates

## Technical Notes
- **Array Structure**: Standard SuiteCRM module language array format
- **Key Conventions**: Follows established SuiteCRM naming patterns
- **Comment Documentation**: Includes usage notes for special-purpose labels
- **Encoding**: UTF-8 compatible for international character support

## Maintenance Considerations
- **Label Consistency**: Changes here affect all Activities module interfaces
- **Translation Base**: Modifications require updates to all language files
- **Template Dependencies**: Theme-specific labels must be maintained separately
- **Module Integration**: Label changes may affect related modules using Activities strings 