# controller.php

## Overview
**@fileoverview** Administration module controller that handles administrative actions including tab configuration, language settings, search settings, AJAX UI configuration, and sprite rebuilding.

**@package** Administration  
**@copyright** SugarCRM Inc. / SalesAgility Ltd.  
**@license** AGPL v3  

The AdministrationController extends SugarController and provides action methods for managing various administrative functions within SuiteCRM. It serves as the entry point for administrative configuration changes initiated from the user interface.

## Database Operations

### Configuration Storage
The controller interacts with configuration storage through several mechanisms:
- **TabController**: Manages system tab configurations in database
- **Configurator**: Handles config.php overrides for persistent settings
- **SubPanelDefinitions**: Manages subpanel visibility settings

### Language Configuration
- **Database Storage**: Language preferences stored via Configurator class
- **Config Override**: Uses `$cfg->handleOverride()` to persist language settings
- **Validation**: Prevents disabling the currently active language

## Internal API Calls

### action_savetabs()
Processes tab configuration changes from the administration interface:
- **Permission Check**: Validates admin privileges using `is_admin($current_user)`
- **JSON Decoding**: Processes `enabled_tabs` and `disabled_tabs` from POST data
- **Tab Management**: Uses TabController to set system tabs and user editing permissions
- **SubPanel Handling**: Manages subpanel visibility through SubPanelDefinitions
- **Redirect**: Returns user to ConfigureTabs action after processing

### action_savelanguages()
Handles language configuration updates:
- **Input Processing**: Decodes JSON data for enabled and disabled languages
- **Current Language Protection**: Prevents disabling the currently active language
- **Error Handling**: Uses `displayAdminError()` for validation failures
- **Configuration Storage**: Uses Configurator to save disabled languages list
- **Logging**: Fatal log entry for invalid language disable attempts

### action_saveglobalsearchsettings()
Manages global search module configuration:
- **Admin Authorization**: Ensures only administrators can modify search settings
- **UnifiedSearchAdvanced Integration**: Uses UnifiedSearchAdvanced class for persistence
- **AJAX Response**: Returns 'true' or 'false' as plain text response
- **Exception Handling**: Catches and handles configuration save failures

### action_UpdateAjaxUI()
Updates AJAX-enabled module configuration:
- **Module Filtering**: Processes disabled modules list from JSON input
- **Configurator Integration**: Uses `addAjaxBannedModules` configuration key
- **Key Management**: Adds configuration to override ignore list
- **View Assignment**: Sets view to 'configureajaxui' for response rendering

### action_callRebuildSprites()
Initiates CSS sprite rebuilding process:
- **AJAX View**: Sets view to 'ajax' for direct response output
- **Image Library Check**: Validates `imagecreatetruecolor()` function availability
- **Admin Verification**: Restricts sprite rebuilding to administrators
- **Sprite Rebuilding**: Calls `rebuildSprites(false)` from upgrade utilities
- **Error Feedback**: Displays error message if image processing unavailable

## External API Calls

### UpgradeWizard Integration
- **File Include**: Requires 'modules/UpgradeWizard/uw_utils.php'
- **Sprite Function**: Calls `rebuildSprites(false)` for CSS sprite generation

### UnifiedSearchAdvanced Integration
- **Class Instantiation**: Creates new UnifiedSearchAdvanced instance
- **Settings Persistence**: Calls `saveGlobalSearchSettings()` method

### TabController Integration
- **System Tabs**: Uses `set_system_tabs($enabled_tabs)` for tab configuration
- **User Permissions**: Manages user editing permissions via `set_users_can_edit()`
- **Key Array Processing**: Converts disabled tabs to key array format

### SubPanelDefinitions Integration
- **Hidden SubPanels**: Uses `set_hidden_subpanels()` for visibility management
- **Bean Association**: Associates subpanel definitions with controller bean

## UI Functionality

### Form Processing
The controller handles various administrative forms:
- **JSON Data**: Processes JSON-encoded form data from administration interfaces
- **HTML Entity Decoding**: Properly decodes HTML entities before JSON parsing
- **Checkbox Processing**: Handles boolean checkbox values for user permissions

### Redirection Logic
Implements consistent redirection patterns:
- **Success Redirects**: Returns users to appropriate configuration pages
- **Error Handling**: Uses SugarApplication::redirect() for error conditions
- **Header Location**: Uses standard HTTP header redirects for success cases

### AJAX Response Handling
- **Plain Text Responses**: Returns simple 'true'/'false' for AJAX operations
- **View Assignment**: Sets appropriate view types for different response formats
- **Exception Management**: Graceful error handling for AJAX requests

## Security Features

### Administrative Access Control
All actions implement administrative privilege checking:
- **Admin Verification**: Uses `is_admin($current_user)` for access control
- **Sugar Die**: Calls `sugar_die()` with appropriate error messages
- **Global App Strings**: Uses `$app_strings['ERR_NOT_ADMIN']` for error messages

### Input Validation
- **HTML Entity Decoding**: Safely processes HTML-encoded input data
- **JSON Validation**: Handles JSON decoding with error checking
- **Current Language Protection**: Prevents system from becoming unusable

### Error Logging
- **Fatal Logging**: Uses `$GLOBALS['log']->fatal()` for critical errors
- **Error Display**: Uses `displayAdminError()` for user-visible errors
- **Exception Catching**: Implements try-catch blocks for robust error handling

## Configuration Integration

### Configurator Class Usage
The controller extensively uses the Configurator class:
- **Config Array**: Manages `$cfg->config` array for setting storage
- **Override Handling**: Uses `handleOverride()` for persistent configuration
- **Key Management**: Uses `addKeyToIgnoreOverride()` for special handling

### Sugar Config Integration
- **Config Access**: Reads current configuration via SugarConfig::getInstance()
- **Merge Operations**: Implements `mergeFtsConfig()` for full-text search settings
- **Language Settings**: Manages disabled languages configuration

The AdministrationController serves as the central hub for processing administrative configuration changes, ensuring proper validation, persistence, and user feedback for all administrative operations. 