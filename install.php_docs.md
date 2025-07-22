/**
 * @fileoverview SuiteCRM installation wizard entry point. This file orchestrates the complete SuiteCRM installation process including system requirements validation, database setup, configuration management, and initial data population. It provides both interactive and silent installation modes with comprehensive error handling and security controls.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# SuiteCRM Installation Wizard

## Overview

The `install.php` file serves as the primary entry point for the SuiteCRM installation process. It manages the complete installation workflow from initial system validation through final configuration, providing both web-based interactive installation and silent installation capabilities for automated deployments.

## Database Operations

### Installation Tracking
- **Session Management**: Uses `$_SESSION` to track installation progress
- **Configuration Storage**: Manages installation parameters across steps
- **Database Setup**: Coordinates database creation and schema installation
- **Initial Data**: Populates initial system data and configurations

### System Requirements Validation
- **PHP Version**: Validates minimum and recommended PHP versions
- **Database Connectivity**: Tests database connection parameters
- **File Permissions**: Validates required file and directory permissions
- **Extension Dependencies**: Checks required PHP extensions

## Internal API Calls

### Core System Initialization
- **Utils Loading**: `require_once 'include/utils.php'` for utility functions
- **Entry Point**: `require_once 'include/entryPoint.php'` for system initialization
- **Version Loading**: Includes both `sugar_version.php` and `suitecrm_version.php`
- **Install Utils**: `require_once 'install/install_utils.php'` for installation functions

### Configuration Management
- **Install Defaults**: `require_once 'install/install_defaults.php'` for default settings
- **Time Handling**: `require_once 'include/TimeDate.php'` for date/time configuration
- **Localization**: `require_once 'include/Localization/Localization.php'` for language support
- **Theme System**: `require_once 'include/SugarTheme/SugarTheme.php'` for interface theming

### Framework Components
- **Logic Hooks**: `require_once 'include/utils/LogicHook.php'` for hook system
- **Data Layer**: `require_once 'data/SugarBean.php'` for ORM foundation
- **Logging**: `require_once 'include/SugarLogger/LoggerManager.php'` for log management

## External API Calls

### Session Management
- **Session Start**: `@session_start()` to initialize installation session
- **Session Cleanup**: Destroys session for clean restart when requested
- **Session Validation**: Checks for existing session state
- **Redirect Control**: Manages page redirects during installation process

### File System Operations
- **Directory Detection**: `getcwd()` for root directory identification
- **File Validation**: Checks existence and permissions of required files
- **Configuration Writing**: Creates and writes configuration files
- **Asset Building**: Triggers JavaScript and CSS asset compilation

### HTTP Operations
- **Header Management**: Controls HTTP headers during installation
- **Redirect Handling**: Manages installation flow redirects
- **Form Processing**: Handles installation form submissions
- **Error Display**: Presents installation errors and warnings

## UI Functionality

### Installation Wizard Interface
- **Multi-Step Process**: Guides users through installation steps
- **Form Validation**: Client and server-side validation of input
- **Progress Tracking**: Visual indication of installation progress
- **Error Presentation**: User-friendly error messages and solutions

### Silent Installation Mode
- **Automated Setup**: `$silentInstall = true` for unattended installation
- **Configuration File**: Uses predefined configuration for automated setup
- **Batch Processing**: Enables scripted installations
- **Error Handling**: Appropriate error handling for automated environments

### Session State Management
- **Progress Persistence**: Maintains installation state across requests
- **Parameter Storage**: Stores configuration parameters in session
- **Error Recovery**: Handles session corruption and recovery
- **Clean Restart**: Provides mechanism for clean installation restart

### SMTP Configuration
- **Tab Selection**: `$_POST['smtp_tab_selected']` for SMTP configuration tabs
- **Setting Merge**: Merges SMTP settings from different configuration tabs
- **Validation**: Validates SMTP server connectivity and authentication
- **Testing**: Provides SMTP connection testing functionality

## Installation Workflow

### Prerequisites Validation
1. **PHP Version Check**: Validates PHP version against requirements
2. **Extension Check**: Verifies required PHP extensions
3. **Permission Check**: Validates file and directory permissions
4. **Database Check**: Tests database connectivity and permissions

### System Configuration
1. **Database Setup**: Creates database schema and tables
2. **Configuration Generation**: Creates config.php and related files
3. **Security Setup**: Configures security settings and keys
4. **Admin User**: Creates initial administrator account

### Data Population
1. **System Data**: Populates initial system configuration data
2. **Default Records**: Creates default records and templates
3. **Locale Setup**: Configures localization and language settings
4. **Theme Installation**: Sets up default theme and styling

### Finalization
1. **Asset Compilation**: Rebuilds JavaScript and CSS assets
2. **Cache Initialization**: Initializes application caches
3. **Validation**: Performs final installation validation
4. **Completion**: Redirects to application or completion page

## Security Features

### Access Control
- **Entry Point Validation**: Requires proper entry point definition
- **Session Security**: Implements secure session management
- **Input Validation**: Validates all user input and configuration
- **File Protection**: Protects configuration files from unauthorized access

### Installation Security
- **Clean Restart**: Provides secure installation restart mechanism
- **Credential Protection**: Secures database and admin credentials
- **Error Containment**: Prevents information disclosure through errors
- **Configuration Security**: Generates secure configuration settings

### Post-Installation Security
- **File Permissions**: Sets appropriate file permissions after installation
- **Configuration Protection**: Protects configuration files
- **Default Removal**: Removes or secures installation files
- **Security Keys**: Generates unique security keys and tokens

## Error Handling and Recovery

### PHP Version Validation
- **Minimum Version**: Enforces minimum PHP version requirements
- **Recommended Version**: Warns about recommended PHP versions
- **EOL Warning**: Alerts about end-of-life PHP versions
- **Migration Guidance**: Provides upgrade guidance and resources

### Installation Error Management
- **Error Capture**: Captures and logs installation errors
- **User Feedback**: Provides clear error messages to users
- **Recovery Options**: Offers recovery and retry mechanisms
- **Debug Information**: Collects debug information for troubleshooting

### Session Recovery
- **State Restoration**: Restores installation state after errors
- **Clean Restart**: Provides clean restart option
- **Progress Recovery**: Resumes installation from last successful step
- **Data Persistence**: Maintains user input across error recovery

## Configuration Management

### Global Variables
- **Installation Flag**: `$GLOBALS['installing'] = true` for installation mode
- **SQL Tracking**: `$GLOBALS['sql_queries'] = 0` for query monitoring
- **Request Handling**: Manages installation-specific request parameters
- **Directory Setup**: `$_REQUEST['root_directory'] = getcwd()` for path configuration

### Asset Management
- **JavaScript Rebuild**: `$_REQUEST['js_rebuild_concat'] = 'rebuild'` for asset compilation
- **CSS Processing**: Handles CSS compilation and optimization
- **Image Processing**: Manages image optimization and processing
- **Cache Management**: Initializes and manages application caches

## Integration Points

### Database Systems
- **MySQL Support**: Full MySQL and MariaDB support
- **PostgreSQL Support**: PostgreSQL database support
- **Connection Testing**: Database connectivity validation
- **Schema Creation**: Automated schema creation and population

### Web Servers
- **Apache Integration**: Apache web server configuration
- **Nginx Support**: Nginx web server compatibility
- **IIS Support**: Internet Information Services support
- **URL Rewriting**: Web server URL rewriting configuration

### PHP Environment
- **Extension Requirements**: Required PHP extension validation
- **Configuration Validation**: PHP configuration requirement checking
- **Performance Optimization**: PHP performance configuration recommendations
- **Security Configuration**: PHP security setting validation 