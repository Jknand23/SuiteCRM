# Activities SetAcceptStatus AJAX Handler Documentation

## @fileoverview
AJAX endpoint for updating acceptance status of meetings and calls, allowing users to accept or decline invitations without page refresh.

## @package Activities
## @copyright SugarCRM Inc & SalesAgility Ltd
## @license GNU Affero General Public License version 3

## Purpose
Provides a lightweight AJAX interface for updating the acceptance status of meeting and call invitations, typically called from calendar or activity list interfaces.

## Internal API Calls

### BeanFactory Operations
- **newBean('Meetings')**: Creates Meeting bean instance for status updates
- **newBean('Calls')**: Creates Call bean instance for status updates

### Bean Methods
- **set_accept_status()**: Updates invitation acceptance status for the current user
  - **Parameters**: 
    - `$current_user`: User object whose status is being updated
    - `$_REQUEST['accept_status']`: New status value (accept/decline/tentative)

## Request Parameters

### Required Parameters
- **object_type**: String - Either "Meeting" or "Call"
- **object_id**: String - ID of the meeting or call record
- **accept_status**: String - New acceptance status value

### Global Dependencies
- **$current_user**: Current authenticated user object
- **$json**: JSON response handler (declared but not used)

## Response Format
- Returns simple "1" response to indicate successful processing
- Uses `exit` to terminate script after processing

## Integration Points

### Related Components
- **Calendar module**: Primary caller for status updates from calendar views
- **Activities Popup_picker.php**: May trigger status updates from activity summaries
- **Meetings module**: Target of status updates for meeting records
- **Calls module**: Target of status updates for call records

### Security Considerations
- Relies on global `$current_user` for authorization
- Updates only affect current user's acceptance status
- No explicit ACL checks beyond standard entry point protection

## UI Functionality
- Typically called via AJAX from calendar interfaces
- Provides immediate feedback for invitation responses
- Enables real-time status updates without page reload

## Error Handling
- No explicit error handling for invalid object types
- Silent failure if record ID doesn't exist
- Returns success indicator regardless of actual operation result

## Technical Notes
- Minimal overhead design for responsive user interaction
- Direct bean manipulation without additional validation
- Exit-based response pattern common in SuiteCRM AJAX handlers 