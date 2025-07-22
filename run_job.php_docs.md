/**
 * @fileoverview Individual job execution script for SuiteCRM scheduled tasks. This CLI-only utility executes specific scheduled jobs with proper authentication, error handling, and logging. It works in conjunction with the main cron system to provide isolated job execution with comprehensive security controls.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# SuiteCRM Individual Job Runner

## Overview

The `run_job.php` file provides isolated execution of individual scheduled jobs within the SuiteCRM system. It serves as a dedicated runner for specific jobs, offering controlled execution environment with proper error handling, authentication, and resource management for reliable task processing.

## Database Operations

### System User Authentication
- **Bean Factory**: `BeanFactory::newBean('Users')` for user context creation
- **System User**: `$current_user->getSystemUser()` for elevated privileges
- **User Context**: Establishes proper user context for job execution
- **Authentication**: Ensures authenticated context for database operations

### Job Execution Management
- **Job Loading**: `SchedulersJob::runJobId($argv[1], $argv[2])` for specific job execution
- **Job Status**: Tracks job execution status and results
- **Error Recording**: Records job execution errors and failures
- **Result Storage**: Stores job execution results in database

### Resource Management
- **Database Cleanup**: Manages database connections during job execution
- **Session Cleanup**: Handles session cleanup after job completion
- **Memory Management**: Optimizes memory usage during job execution
- **Connection Pooling**: Manages database connection pooling

## Internal API Calls

### Environment Setup
- **Directory Change**: `chdir(__DIR__)` to ensure proper working directory
- **Entry Point**: `require_once('include/entryPoint.php')` for system initialization
- **Configuration**: Loads system configuration and settings
- **Logging**: Initializes logging system for job execution

### Language and Localization
- **Default Language**: Uses `$sugar_config['default_language']` for localization
- **App Strings**: `return_application_language($current_language)` for message localization
- **List Strings**: `return_app_list_strings_language($current_language)` for dropdown values
- **Global Context**: Establishes proper language context for job execution

### Job System Integration
- **Scheduler Jobs**: `require_once 'modules/SchedulersJobs/SchedulersJob.php'` for job framework
- **Job Execution**: Direct integration with SchedulersJob class
- **Job Management**: Leverages job management framework
- **Error Handling**: Uses framework error handling mechanisms

### System Cleanup
- **Sugar Cleanup**: `sugar_cleanup(false)` for application-level cleanup
- **Resource Cleanup**: Ensures proper cleanup of system resources
- **Memory Management**: Manages memory cleanup after job execution
- **Exit Handling**: Proper exit code management for job results

## External API Calls

### CLI Environment Validation
- **SAPI Check**: `php_sapi_name()` to ensure CLI execution
- **CLI Enforcement**: `substr($sapi_type, 0, 3) != 'cli'` for CLI-only execution
- **Access Control**: Dies with error if not running in CLI mode
- **Security**: Prevents web-based execution of job runner

### Command Line Arguments
- **Argument Count**: `$argc < 3` for parameter validation
- **Job ID**: `$argv[1]` for job identifier specification
- **Client ID**: `$argv[2]` for client context specification
- **Parameter Validation**: Ensures required parameters are provided

### Process Management
- **Exit Codes**: Standard Unix exit codes for success/failure
- **Output Management**: Controls output for CLI execution
- **Error Output**: Manages error output to appropriate streams
- **Process Isolation**: Ensures isolated process execution

## UI Functionality

### Command Line Interface
- **Parameter Validation**: Validates command line parameters
- **Error Messages**: Provides appropriate CLI error messages
- **Output Control**: Manages console output for job execution
- **Exit Codes**: Returns appropriate exit codes for process control

### Job Execution Interface
- **Job Identification**: Accepts job ID as command line parameter
- **Client Context**: Accepts client ID for execution context
- **Execution Logging**: Logs job execution start and completion
- **Result Reporting**: Reports job execution results

### Error Handling Interface
- **Parameter Errors**: Handles missing or invalid parameters
- **Execution Errors**: Manages job execution errors
- **System Errors**: Handles system-level error conditions
- **Recovery**: Provides appropriate error recovery mechanisms

## Security Controls

### Access Control
- **CLI Only**: Enforces command-line execution only
- **Parameter Validation**: Validates all input parameters
- **System User**: Uses system user context for secure execution
- **Resource Control**: Controls system resource access

### Execution Security
- **Process Isolation**: Runs in isolated process context
- **Resource Limits**: Applies appropriate resource limits
- **Error Containment**: Contains errors within job execution
- **Audit Logging**: Logs job execution for audit purposes

### Data Security
- **User Context**: Maintains proper user context for data access
- **Permission Enforcement**: Enforces data access permissions
- **Secure Cleanup**: Ensures secure cleanup of sensitive data
- **Error Protection**: Prevents data disclosure through errors

## Job Execution Architecture

### Execution Flow
1. **Environment Validation**: Validate CLI environment and parameters
2. **System Initialization**: Initialize SuiteCRM environment and configuration
3. **User Context**: Establish system user context for execution
4. **Job Loading**: Load and validate specified job
5. **Job Execution**: Execute job with proper error handling
6. **Result Processing**: Process and store job execution results
7. **Cleanup**: Perform comprehensive system cleanup
8. **Exit**: Return appropriate exit code

### Error Management
- **Parameter Errors**: Handle missing or invalid command line parameters
- **System Errors**: Manage system initialization and configuration errors
- **Job Errors**: Handle job-specific execution errors
- **Cleanup Errors**: Manage cleanup operation errors

### Resource Management
- **Memory Management**: Optimizes memory usage during job execution
- **Database Connections**: Manages database connection lifecycle
- **File Handles**: Proper management of file system resources
- **Network Resources**: Manages network resource cleanup

## Integration Points

### Scheduler System
- **Job Queue**: Integration with main job queue system
- **Job Management**: Leverages scheduler job management framework
- **Status Reporting**: Reports job status to scheduler system
- **Error Reporting**: Reports job errors to central system

### Cron System
- **Cron Integration**: Works with main cron.php system
- **Job Delegation**: Receives job execution delegation from cron system
- **Process Management**: Manages individual job processes
- **Resource Coordination**: Coordinates resources with main cron system

### Logging System
- **Execution Logging**: Comprehensive logging of job execution
- **Error Logging**: Detailed error logging for debugging
- **Performance Logging**: Logs job execution performance metrics
- **Audit Logging**: Maintains audit trail of job executions

## Performance Considerations

### Execution Optimization
- **Process Isolation**: Isolates job execution for optimal performance
- **Memory Management**: Optimizes memory usage for job execution
- **Resource Cleanup**: Ensures proper cleanup for resource optimization
- **Exit Efficiency**: Efficient exit handling for process management

### Scalability
- **Parallel Execution**: Supports parallel job execution
- **Resource Sharing**: Efficient resource sharing between job instances
- **Load Management**: Manages system load during job execution
- **Capacity Planning**: Supports capacity planning for job execution

## Monitoring and Diagnostics

### Execution Monitoring
- **Job Tracking**: Tracks individual job execution
- **Performance Metrics**: Collects job execution performance data
- **Resource Usage**: Monitors resource usage during execution
- **Error Tracking**: Tracks job execution errors and failures

### Diagnostic Information
- **Debug Logging**: Comprehensive debug information for troubleshooting
- **Execution Context**: Captures execution context for analysis
- **Environment Information**: Collects environment information for diagnostics
- **Error Analysis**: Provides detailed error analysis information

## Configuration Management

### Job Configuration
- **Job Parameters**: Manages job-specific configuration parameters
- **Execution Settings**: Handles job execution settings
- **Resource Limits**: Configures resource limits for job execution
- **Error Handling**: Configures error handling behavior

### System Configuration
- **Language Settings**: Uses system language configuration
- **Logging Configuration**: Applies system logging configuration
- **Security Settings**: Enforces system security configuration
- **Performance Settings**: Applies performance optimization settings 