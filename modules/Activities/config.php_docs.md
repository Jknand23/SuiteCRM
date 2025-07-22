# Activities Configuration File Documentation

## @fileoverview
Defines configuration settings for the Activities module, specifically status definitions for calls and meetings.

## @package Activities
## @copyright SugarCRM Inc & SalesAgility Ltd
## @license GNU Affero General Public License version 3

## Purpose
This configuration file establishes the "open status" definitions used throughout the Activities module to determine which activities should be considered active or pending.

## Configuration Variables

### `$open_status` Array
- **Type**: Array
- **Purpose**: Defines status values that represent open/active activities
- **Contents**: 
  - "Planned" - Status indicating a meeting or call is scheduled but not yet completed
- **Usage**: Referenced by other Activities module components to filter active activities

## Integration Points

### Related Files
- **SetAcceptStatus.php**: Uses status values when updating meeting/call acceptance
- **Popup_picker.php**: Filters activities based on open status definitions
- **EmailReminder.php**: Determines which activities need reminder emails
- **subpaneldefs.php**: Uses status filtering in subpanel where clauses

### Module Dependencies
- **Meetings module**: Inherits status definitions for meeting records
- **Calls module**: Inherits status definitions for call records
- **Calendar module**: Uses status information for calendar displays

## Technical Notes
- Values are keys, not translated strings - actual display text comes from language files
- "Planned" status is the primary open status for both meetings and calls
- Configuration is loaded globally and available to all Activities components

## Security
- File uses standard SuiteCRM entry point protection
- No direct user input or database operations 