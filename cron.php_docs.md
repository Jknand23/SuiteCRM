/**
 * @fileoverview Scheduled task management system for SuiteCRM. This CLI-only script manages automated background jobs including email processing, data cleanup, indexing, and other maintenance tasks. It implements security controls, user validation, and comprehensive logging for reliable task execution.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# SuiteCRM Cron Job Manager

## Overview

The `cron.php` file serves as the central entry point for SuiteCRM's scheduled task system. It manages automated background processes including email delivery, data maintenance, search indexing, and system cleanup tasks. The script enforces CLI-only execution and implements comprehensive security controls for safe automated operation.

## Database Operations

### System User Authentication
- **Bean**: `BeanFactory::newBean('Users')`
- **Method**: `$current_user->getSystemUser()`
- **Purpose**: Establishes system-level user context for cron operations
- **Permissions**: Provides elevated privileges for maintenance tasks
- **Security**: Ensures consistent user context across all scheduled jobs

### Job Queue Management
- **Driver**: Configurable cron driver class (default: `SugarCronJobs`)
- **Tables**: Manages scheduled jobs in job queue tables
- **Status Tracking**: Updates job execution status and results
- **Error Handling**: Records job failures and retry information

### Session Management
- **Cleanup**: Performs database connection cleanup after job execution
- **Disconnection**: Explicit database disconnection to prevent resource leaks
- **Session Destruction**: Removes any residual session data
- **Resource Management**: Ensures proper cleanup of database resources

## Internal API Calls

### Environment Initialization
- **Directory Change**: `chdir(__DIR__)` to ensure proper working directory
- **Entry Point**: `require_once('include/entryPoint.php')` for system initialization
- **Configuration**: Loads `$sugar_config` and system settings
- **Logging**: Initializes comprehensive logging system

### Language and Localization
- **Default Language**: Uses `$sugar_config['default_language']`
- **App Strings**: `return_application_language($current_language)`
- **List Strings**: `return_app_list_strings_language($current_language)`
- **Globalization**: Ensures proper localization for scheduled tasks

### Job Queue Driver
- **Custom Path**: `custom/include/SugarQueue/$cron_driver.php`
- **Default Path**: `include/SugarQueue/$cron_driver.php`
- **Dynamic Loading**: Configurable driver class through `$sugar_config['cron_class']`
- **Execution**: `$jobq->runCycle()` to process scheduled jobs

### System Cleanup
- **Function**: `sugar_cleanup(false)` for application-level cleanup
- **Database**: `DBManagerFactory::getInstance()->disconnect()` for connection cleanup
- **Sessions**: `session_destroy()` if session exists
- **Exit Code**: Returns 0 for success, 1 for failure based on `$jobq->runOk()`

## External API Calls

### CLI Environment Validation
- **SAPI Check**: `php_sapi_name()` to ensure CLI execution
- **Access Control**: Dies with error if not running in CLI mode
- **Security**: Prevents web-based execution of scheduled tasks

### User Validation (Non-Windows)
- **User Detection**: `getRunningUser()` to identify executing user
- **Allow List**: Validates against `$sugar_config['cron']['allowed_cron_users']`
- **Root Protection**: Special handling to prevent root execution
- **Security Logging**: Logs user validation failures and warnings

### Platform Detection
- **Windows Check**: `is_windows()` for platform-specific handling
- **User Validation**: Skips user validation on Windows systems
- **Path Handling**: Adjusts file path handling for platform differences

## UI Functionality

### CLI Interface
- **Execution Mode**: Command-line interface only
- **Error Messages**: Outputs appropriate error messages for invalid access
- **Logging Output**: Comprehensive debug and error logging
- **Exit Codes**: Standard Unix exit codes for success/failure

### Security Controls
- **CLI Enforcement**: Prevents web browser access
- **User Validation**: Validates executing user against allow list
- **Directory Security**: Changes to script directory for secure execution
- **Root Prevention**: Actively prevents root user execution

### Error Handling
- **User Warnings**: Logs warnings for missing configuration
- **Fatal Errors**: Logs fatal errors for security violations
- **Graceful Failure**: Handles configuration and permission errors gracefully
- **Debug Information**: Comprehensive debug logging for troubleshooting

## Scheduled Job Architecture

### Job Queue System
- **Driver Pattern**: Pluggable job queue drivers
- **Configuration**: `$sugar_config['cron_class']` for driver selection
- **Execution Cycle**: Single cycle execution per cron run
- **Status Reporting**: Returns success/failure status

### Job Types
- **Email Processing**: Scheduled email delivery and processing
- **Data Maintenance**: Database cleanup and optimization
- **Search Indexing**: Search index updates and maintenance
- **Report Generation**: Automated report generation and delivery
- **System Cleanup**: Temporary file cleanup and system maintenance

### Execution Flow
1. **Environment Setup**: Initialize directory, entry point, and configuration
2. **Security Validation**: Verify CLI mode and user permissions
3. **System Initialization**: Load language, create system user context
4. **Driver Loading**: Load and instantiate configured cron driver
5. **Job Execution**: Run job cycle through driver
6. **Cleanup**: Perform comprehensive system cleanup
7. **Exit**: Return appropriate exit code

## Security Features

### Access Control
- **CLI Only**: Enforces command-line execution only
- **User Validation**: Validates executing user against configured allow list
- **Root Prevention**: Prevents execution as root user with specific guidance
- **Entry Point Security**: Requires proper application initialization

### Configuration Security
- **Allow List**: `$sugar_config['cron']['allowed_cron_users']` for user control
- **Missing Config**: Graceful handling of missing configuration
- **Logging**: Comprehensive security event logging
- **Error Messages**: Security-focused error messaging

### Resource Protection
- **Database Cleanup**: Ensures database connections are properly closed
- **Session Management**: Destroys any residual sessions
- **Memory Management**: Performs cleanup to prevent memory leaks
- **File System**: Operates in controlled directory context

## Configuration Options

### Cron Driver Selection
- **Default**: `SugarCronJobs` class
- **Custom**: Configurable via `$sugar_config['cron_class']`
- **Location**: Custom drivers in `custom/include/SugarQueue/`
- **Fallback**: Default drivers in `include/SugarQueue/`

### User Security
- **Allow List**: `$sugar_config['cron']['allowed_cron_users']` array
- **Platform Specific**: Different handling for Windows vs Unix-like systems
- **Logging**: Configurable logging levels for cron operations
- **Error Handling**: Configurable error reporting and handling

## Monitoring and Logging

### Execution Logging
- **Debug Markers**: Clear start/end markers for cron execution
- **Driver Logging**: Logs selected cron driver
- **User Validation**: Logs user validation results
- **Error Tracking**: Comprehensive error and warning logging

### Performance Monitoring
- **Execution Time**: Tracks job execution duration
- **Success Rate**: Monitors job success/failure rates
- **Resource Usage**: Monitors memory and database usage
- **Queue Status**: Tracks job queue health and backlog 