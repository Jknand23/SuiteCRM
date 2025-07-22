# Activities Popup Picker Display System Documentation

## @fileoverview
Comprehensive activity summary and popup display system that aggregates and presents tasks, meetings, calls, emails, and notes in unified interface views.

## @package Activities
## @copyright SugarCRM Inc & SalesAgility Ltd
## @license GNU Affero General Public License version 3

## Purpose
Provides a unified interface for displaying and organizing all activity types (tasks, meetings, calls, emails, notes) associated with a record, with categorization, filtering, and formatting capabilities.

## Database Operations

### Related Record Retrieval
- **get_linked_fields()**: Discovers all relationship fields on the focus bean
- **load_relationship()**: Establishes relationship connections for activity loading
- **get_linked_beans()**: Retrieves related activity records through established relationships

### Activity Filtering
- **Tasks**: Separates completed vs. open tasks based on status values
  - **Open**: "Not Started", "In Progress", "Pending Input"
  - **Completed**: All other status values
- **Meetings**: Filters based on "Planned" vs completed status
- **Calls**: Filters based on "Planned" vs completed status
- **Emails**: All email records included in summary
- **Notes**: All note records with ACL filtering

### Unlinked Email Processing
- **get_unlinked_email_query()**: Custom query for emails related to record but not explicitly linked
- **process_list_query()**: Processes complex email relationship queries

## Internal API Calls

### BeanFactory Operations
- **getBean()**: Creates bean instances for relationship discovery
- **newBean()**: Creates activity beans for processing and display

### Bean Methods
- **retrieve()**: Loads complete record data for the focus record
- **ACLAccess()**: Validates user permissions for each activity record
- **get_linked_beans()**: Retrieves related records through relationship fields

### Activity-Specific Processing
- **Tasks**: Date due processing, status categorization, description formatting
- **Meetings/Calls**: Contact relationship resolution, status mapping, time zone handling
- **Emails**: Address formatting, content processing, unlinked email discovery
- **Notes**: File attachment handling, description formatting

## UI Functionality

### Display Categories
- **Summary List**: Historical/completed activities sorted by date
- **Open Activity List**: Pending/active activities requiring attention
- **Task List**: Task-specific filtering and display
- **Meeting List**: Meeting-specific formatting and links
- **Calls List**: Call-specific information including direction
- **Emails List**: Email threading and address information
- **Notes List**: Note content with attachment links

### Data Formatting
- **Date Processing**: Timezone-aware date formatting with user preferences
- **Contact Resolution**: Automatic contact linking for meetings and calls
- **Description Formatting**: HTML to text conversion, line break processing
- **File Attachments**: Download URL generation for note attachments
- **Status Mapping**: Translation of internal status codes to display labels

### Template Integration
- **Sugar_Smarty**: Template engine for popup display rendering
- **PopupBody.tpl**: Primary template for activity popup display
- **Theme Integration**: CSS and image asset loading
- **Internationalization**: Language header and charset handling

## Popup_Picker Class

### Public Methods
- **process_page()**: Main processing method that orchestrates entire display workflow
- **getEmailDetails()**: Formats email address information and content
- **getTaskDetails()**: Formats task-specific information including dates
- **formatDescription()**: Converts text descriptions to HTML-safe display format

### Processing Workflow
1. **Record Validation**: Ensures valid focus record exists
2. **Relationship Discovery**: Identifies all activity-related relationships
3. **Activity Retrieval**: Loads all related activity records
4. **ACL Filtering**: Removes activities user cannot access
5. **Status Categorization**: Separates open vs. completed activities
6. **Data Formatting**: Prepares display-ready data structures
7. **Sorting**: Orders activities by relevance and date
8. **Template Assignment**: Populates template variables
9. **Rendering**: Returns formatted HTML for display

## Integration Points

### Related Files
- **config.php**: Uses open status definitions for activity filtering
- **language/en_us.lang.php**: Language strings for activity type labels
- **tpls/PopupBody.tpl**: Primary display template
- **tpls/PopupHeader.tpl**: Header template components
- **tpls/PopupFooter.tpl**: Footer template components

### Module Dependencies
- **Tasks module**: Task record processing and status mapping
- **Meetings module**: Meeting record processing and contact resolution
- **Calls module**: Call record processing and direction handling
- **Emails module**: Email record processing and unlinked email discovery
- **Notes module**: Note record processing and file attachment handling
- **Contacts module**: Contact name resolution for activities

### Theme Integration
- **SugarThemeRegistry**: Icon and image asset loading
- **CSS Integration**: Theme-specific styling
- **Image URLs**: Activity type icons and visual elements

## Data Structures

### Activity Array Format
Each activity record formatted as:
- **name**: Activity subject/title
- **id**: Unique record identifier
- **type**: Human-readable activity type
- **module**: Source module name
- **status**: Formatted status display
- **date_modified**: Formatted date for display
- **description**: Processed description content
- **parent/contact information**: Related record details
- **image**: Theme-appropriate icon URL

### Template Variables
- **summaryList**: All activities sorted chronologically
- **taskslist**: Task-specific activities
- **meetingList**: Meeting-specific activities
- **callsList**: Call-specific activities
- **emailsList**: Email-specific activities
- **notesList**: Note-specific activities

## Security Features
- **ACL Integration**: Activities hidden if user lacks list/view permissions
- **Entry Point Protection**: Standard SuiteCRM security validation
- **XSS Prevention**: HTML encoding and sanitization of user content
- **File Access**: Secure file URL generation for note attachments

## Performance Optimization
- **Relationship Caching**: Efficient loading of related records
- **ACL Batching**: Grouped permission checking
- **Template Caching**: Smarty template compilation caching
- **Image Asset Optimization**: Theme-based asset loading

## Error Handling
- **Record Validation**: Graceful handling of missing focus records
- **Relationship Failures**: Continues processing if individual relationships fail
- **Template Errors**: Fallback to basic display if template issues occur
- **Permission Failures**: Silent filtering of inaccessible records 