/**
 * @fileoverview Data export utility for SuiteCRM that provides secure CSV and sample data export functionality. This file implements comprehensive access controls, user authentication, and module-specific export capabilities while preventing unauthorized data access and maintaining audit compliance.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# SuiteCRM Data Export Handler

## Overview

The `export.php` file provides secure data export functionality for SuiteCRM modules. It supports both full data exports and sample data generation with comprehensive security controls including user authentication, module permissions, and administrative restrictions to ensure data protection and compliance.

## Database Operations

### User Authentication Validation
- **Current User**: Validates `$current_user` existence and authentication
- **User ID Check**: Ensures valid user ID for all export operations
- **Session Validation**: Verifies active user session before processing
- **Security**: Prevents unauthorized access to export functionality

### Module Data Retrieval
- **Bean Factory**: Uses `$beanList[$_REQUEST['module']]` for module validation
- **Data Extraction**: Retrieves module-specific data through export utilities
- **Record Selection**: Supports specific record exports via UID parameter
- **Relationship Data**: Includes related record data when requested

### Access Control Integration
- **ACL Controller**: `ACLController::moduleSupportsACL($the_module)` for ACL validation
- **User Access**: `ACLAction::getUserAccessLevel()` for permission checking
- **Admin Rights**: Validates administrative access levels for restricted exports
- **Module Permissions**: Enforces module-specific export permissions

## Internal API Calls

### Security Entry Point
- **Validation**: `if (!defined('sugarEntry') || !sugarEntry)` prevents direct access
- **Die Statement**: `die('Not A Valid Entry Point')` for unauthorized access
- **Input Sanitization**: `clean_string($_REQUEST['module'])` for secure parameter handling
- **Error Prevention**: Blocks execution for invalid entry points

### Export Utility Integration
- **Export Utils**: `require_once('include/export_utils.php')` for core export functions
- **Sample Export**: `exportSample(clean_string($_REQUEST['module']))` for sample data
- **Full Export**: `export()` function for complete data exports
- **Utility Functions**: Leverages export utility functions for data processing

### Global System Integration
- **Sugar Config**: Uses `$sugar_config` for export configuration settings
- **App Strings**: `$GLOBALS['app_strings']` for localized error messages
- **Bean List**: `$beanList` for module validation and instantiation
- **Logging**: `$log` for security logging and audit trails

### Output Buffer Management
- **Buffer Control**: `ob_start()` for output buffering control
- **Compression**: `ini_set('zlib.output_compression', 'Off')` to prevent compression issues
- **Header Management**: Ensures proper HTTP headers for file downloads
- **Performance**: Optimizes large data export handling

## External API Calls

### HTTP Header Configuration
- **Content-Type**: Sets appropriate MIME types for CSV exports
- **Content-Disposition**: Controls file download behavior
- **Cache Control**: Manages browser caching for exported files
- **Content-Length**: Sets file size for download progress

### File System Operations
- **Temporary Files**: Creates temporary export files when needed
- **File Cleanup**: Manages cleanup of temporary export files
- **Path Security**: Validates file paths for security
- **Permission Handling**: Manages file system permissions

## UI Functionality

### Export Parameters
- **Module Parameter**: `$_REQUEST['module']` for target module specification
- **UID Parameter**: `$_REQUEST['uid']` for specific record export
- **Sample Flag**: `$_REQUEST['sample']` for sample data generation
- **Members Flag**: `$_REQUEST['members']` for relationship data inclusion

### Security Controls
- **Admin Only**: `$sugar_config['admin_export_only']` for administrative restriction
- **Export Disable**: `$sugar_config['disable_export']` for system-wide export control
- **User Validation**: Comprehensive user authentication and authorization
- **Module Security**: Module-specific access control enforcement

### Error Handling
- **Disabled Export**: `$GLOBALS['app_strings']['ERR_EXPORT_DISABLED']` for disabled exports
- **Invalid Module**: Security logging for invalid module access attempts
- **Runtime Exceptions**: Throws exceptions for unexpected errors
- **User Feedback**: Provides appropriate error messages to users

### Export Types
- **Sample Export**: Generates sample data with help messages for module understanding
- **Full Export**: Exports complete module data based on user permissions
- **Selective Export**: Exports specific records based on UID parameters
- **Related Data**: Includes related record data when members flag is set

## Security Architecture

### Access Control Hierarchy
1. **System Level**: Global export disable configuration
2. **Administrative**: Admin-only export restrictions
3. **User Level**: Individual user authentication validation
4. **Module Level**: Module-specific ACL permission checking
5. **Record Level**: Individual record access validation

### Permission Validation
- **Admin Users**: `is_admin($current_user)` for administrative privileges
- **ACL Support**: Module ACL support validation
- **Access Levels**: Multiple access level validation (access, admin, admin_dev)
- **Permission Hierarchy**: Enforces proper permission hierarchy

### Input Security
- **Parameter Cleaning**: `clean_string()` for all user input parameters
- **Module Validation**: Validates module existence in system bean list
- **Type Safety**: Ensures parameter types match expectations
- **Injection Prevention**: Prevents injection attacks through input sanitization

### Audit and Logging
- **Security Logging**: `$log->security()` for security event logging
- **Invalid Access**: Logs attempts to access invalid modules
- **User Tracking**: Tracks user export activities
- **Error Logging**: Comprehensive error logging for debugging

## Export Configuration

### System Configuration
- **Disable Export**: `$sugar_config['disable_export']` for system-wide control
- **Admin Only**: `$sugar_config['admin_export_only']` for administrative restriction
- **Module Settings**: Module-specific export configuration
- **User Preferences**: User-specific export preferences

### Output Formatting
- **CSV Format**: Standard comma-separated value format
- **Character Encoding**: User-preferred character encoding support
- **Field Selection**: Configurable field inclusion/exclusion
- **Data Formatting**: Proper data type formatting for export

## Performance Considerations

### Large Data Handling
- **Output Buffering**: Efficient memory management for large exports
- **Compression Control**: Disables problematic compression for large files
- **Streaming**: Supports streaming for very large datasets
- **Memory Management**: Optimizes memory usage during export operations

### Caching and Optimization
- **Query Optimization**: Efficient database queries for data retrieval
- **Index Usage**: Leverages database indexes for performance
- **Batch Processing**: Processes large datasets in manageable batches
- **Resource Cleanup**: Proper cleanup of resources after export

## Integration Points

### Module System
- **Bean List**: Integration with SuiteCRM bean list for module validation
- **Bean Files**: Uses bean file registry for module instantiation
- **Module Registry**: Leverages module registry for validation
- **Custom Modules**: Supports custom module export functionality

### Export Utilities
- **Export Utils**: Deep integration with export utility functions
- **Sample Generation**: Uses utility functions for sample data creation
- **Data Formatting**: Leverages utilities for proper data formatting
- **File Generation**: Uses utilities for export file creation

### Security System
- **ACL Integration**: Deep integration with access control system
- **User Management**: Integration with user authentication system
- **Permission System**: Leverages SuiteCRM permission framework
- **Audit System**: Integration with system audit and logging

## Error Recovery

### Graceful Error Handling
- **User Feedback**: Provides clear error messages for users
- **System Protection**: Prevents system compromise through errors
- **Logging**: Comprehensive error logging for debugging
- **Recovery Options**: Provides recovery mechanisms where possible

### Security Error Management
- **Access Denials**: Graceful handling of permission denials
- **Invalid Requests**: Secure handling of malformed requests
- **Authentication Failures**: Proper handling of authentication errors
- **System Errors**: Secure error handling without information disclosure 