# Activities Email Reminder System Documentation

## @fileoverview
Comprehensive email reminder system for meetings and calls, handling automated notification delivery to invitees based on configured reminder times.

## @package Activities
## @copyright SugarCRM Inc & SalesAgility Ltd
## @license GNU Affero General Public License version 3

## Purpose
Manages the automated sending of email reminders for upcoming meetings and calls to all invited participants, ensuring timely notification of scheduled activities.

## Database Operations

### Meeting Queries
- **getMeetingsForRemind()**: Retrieves meetings requiring reminder emails
  - **Query Criteria**: email_reminder_time != -1, deleted = 0, email_reminder_sent = 0, status != 'Held'
  - **Date Range**: Between current time and maximum reminder time
  - **Time Calculation**: Compares current timestamp against (date_start - email_reminder_time)

### Call Queries  
- **getCallsForRemind()**: Retrieves calls requiring reminder emails
  - **Query Criteria**: email_reminder_time != -1, deleted = 0, email_reminder_sent = 0, status != 'Held'
  - **Date Range**: Between current time and maximum reminder time
  - **Time Calculation**: Compares current timestamp against (date_start - email_reminder_time)

### Recipient Queries
- **getRecipients()**: Fetches all participants for a specific activity
  - **Users**: From meetings_users/calls_users tables where accept_status != 'decline'
  - **Contacts**: From meetings_contacts/calls_contacts tables where accept_status != 'decline'
  - **Leads**: From meetings_leads/calls_leads tables where accept_status != 'decline'

### Bean Operations
- **retrieve()**: Loads complete meeting/call records for email processing
- **save()**: Updates email_reminder_sent flag after successful delivery

## Internal API Calls

### BeanFactory Operations
- **newBean('Administration')**: Retrieves system email configuration
- **newBean('Meetings')**: Creates meeting beans for reminder processing
- **newBean('Calls')**: Creates call beans for reminder processing
- **newBean('Users')**: Creates user beans for recipient processing
- **newBean('Contacts')**: Creates contact beans for recipient processing
- **newBean('Leads')**: Creates lead beans for recipient processing

### Reminder Integration
- **Reminder::sendEmailReminders()**: Delegates to SuiteCRM reminder system for additional processing

### Email System Integration
- **SugarPHPMailer**: Email delivery engine with system mailer configuration
- **OutboundEmail**: System SMTP configuration retrieval
- **XTemplate**: Email template processing and customization

## External API Calls

### Email Delivery
- **SMTP Server**: Configured system outbound email server
- **SugarEmailAddress**: Email address validation and formatting
- **Email Templates**: System-configured email notification templates

## Class Structure

### EmailReminder Class
- **Constructor**: Initializes time ranges based on system reminder time options
- **Properties**:
  - `$now`: Current database timestamp
  - `$max`: Maximum future timestamp for reminder processing

### Public Methods
- **process()**: Main processing method that coordinates entire reminder workflow
- **sendReminders()**: Handles email composition and delivery for specific activities
- **getMeetingsForRemind()**: Database query for eligible meetings
- **getCallsForRemind()**: Database query for eligible calls

### Protected Methods
- **setReminderBody()**: Populates email template with activity-specific data
- **getRecipients()**: Retrieves all participants for activity reminder delivery

## Email Template Processing

### Template Variables
- **{OBJECT}_SUBJECT**: Activity name/subject
- **{OBJECT}_STARTDATE**: Formatted start date with timezone
- **{OBJECT}_LOCATION**: Meeting/call location (if applicable)
- **{OBJECT}_CREATED_BY**: Activity creator's full name
- **{OBJECT}_DESCRIPTION**: Activity description/notes

### Template Types
- **MeetingReminder**: Template for meeting reminder emails
- **CallReminder**: Template for call reminder emails

## Integration Points

### Related Files
- **config.php**: Uses open status definitions for activity filtering
- **Meetings module**: Source of meeting data and relationships
- **Calls module**: Source of call data and relationships
- **Administration module**: Email configuration and system settings
- **language/en_us.lang.php**: May use language strings for template processing

### System Dependencies
- **Cron/Scheduler**: Typically executed via scheduled job
- **Email System**: Requires configured SMTP settings
- **Template System**: Uses notification template infrastructure
- **Timezone System**: Handles user-specific time zone conversions

## UI Functionality
- No direct UI components - operates as background service
- Results visible in sent reminder flags on activity records
- Email delivery status logged to system log files

## Error Handling
- **SMTP Failures**: Logged to system log with detailed error information
- **Missing Configuration**: Returns false if SMTP server not configured
- **Template Errors**: Graceful handling of missing or malformed templates
- **Database Errors**: Standard SuiteCRM database error handling

## Security Features
- **User Authorization**: Only sends to authenticated, enabled users
- **Email Validation**: Validates email addresses before sending
- **Accept Status**: Respects declined invitations (no reminders sent)
- **System Configuration**: Uses admin-configured sender information

## Performance Considerations
- **Batch Processing**: Processes all eligible activities in single execution
- **Time Range Limits**: Constrains database queries to reasonable time windows
- **Recipient Filtering**: Only processes participants who haven't declined
- **Status Tracking**: Prevents duplicate reminder sending via email_reminder_sent flag

## Configuration Dependencies
- **$GLOBALS['app_list_strings']['reminder_time_options']**: System reminder time configurations
- **Administration settings**: SMTP configuration and sender information
- **Template files**: Email notification template availability 