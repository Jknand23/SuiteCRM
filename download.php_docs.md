/**
 * @fileoverview Secure file download handler for SuiteCRM. This file manages controlled access to system files, documents, and attachments with comprehensive security validation, access control, and audit logging. It handles both regular file downloads and temporary file access while enforcing proper authentication and authorization.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# SuiteCRM File Download Handler

## Overview

The `download.php` file provides secure, controlled access to files within the SuiteCRM system. It implements comprehensive security measures including authentication verification, access control validation, and audit logging to ensure only authorized users can access appropriate files through proper channels.

## Database Operations

### File Record Retrieval
- **Factory**: `BeanFactory::newBean($module)` to create appropriate bean instance
- **Record Loading**: `$focus->retrieve($_REQUEST['id'])` to load file metadata
- **Document Revisions**: Special handling for Document module file revisions
- **Relationship**: `$focus->document_revision_id` for document-revision relationships

### Access Control Validation
- **Permission Check**: `$focus->ACLAccess('view')` for record-level permissions
- **Module Validation**: Validates module exists in `$beanList` array
- **Bean File Check**: Verifies bean file exists in `$beanFiles` array
- **Security**: Database-level access control integration

### User Authentication
- **Session Validation**: `$_SESSION['authenticated_user_id']` for user authentication
- **Current User**: `$GLOBALS['current_user']->retrieve()` for user context
- **Language**: `$_SESSION['authenticated_user_language']` for localization
- **Preferences**: User-specific download preferences and settings

## Internal API Calls

### Security Entry Point
- **Validation**: `if (!defined('sugarEntry') || !sugarEntry)` prevents direct access
- **Die Statement**: `die('Not A Valid Entry Point')` for unauthorized access
- **Parameter Validation**: Validates required parameters for file access
- **Session Check**: Ensures authenticated user session exists

### Module System Integration
- **Module Loading**: `require('include/modules.php')` for module definitions
- **Bean Factory**: Uses BeanFactory for proper object instantiation
- **Module Validation**: Cross-references module names with system modules
- **Case Handling**: Special handling for 'aCase' module naming

### Language and Localization
- **App Strings**: `return_application_language($GLOBALS['current_language'])`
- **Module Strings**: `return_module_language($GLOBALS['current_language'], 'ACL')`
- **Error Messages**: Localized error messages for user feedback
- **Character Encoding**: User's preferred export charset handling

### Document Revision Handling
- **Document Detection**: `$focus->object_name == 'Document'` for document identification
- **Revision Retrieval**: `BeanFactory::newBean('DocumentRevisions')` for revision access
- **ID Resolution**: Resolves document ID to revision ID when necessary
- **File Location**: Determines actual file location through revision system

## External API Calls

### HTTP Header Management
- **Content-Type**: Sets appropriate MIME type for file downloads
- **Content-Disposition**: Controls download vs inline display behavior
- **Content-Length**: Sets file size for download progress indication
- **Cache Control**: Manages browser caching behavior for sensitive files

### File System Operations
- **File Access**: Reads files from secure storage locations
- **Path Validation**: Validates file paths to prevent directory traversal
- **Existence Check**: Verifies file exists before attempting download
- **Permission Check**: Validates file system permissions

### Compression Control
- **Zlib Compression**: `ini_set('zlib.output_compression', 'Off')` to disable compression
- **Content-Length**: Ensures accurate Content-Length headers
- **Buffer Management**: Controls output buffering for large files
- **Performance**: Optimizes download performance for large files

## UI Functionality

### Download Parameters
- **ID Parameter**: `$_REQUEST['id']` for record or file identifier
- **Type Parameter**: `$_REQUEST['type']` for module type specification
- **Profile Flag**: `$_REQUEST['isProfile']` for profile image handling
- **Temp File Flag**: `$_REQUEST['isTempFile']` for temporary file access

### File Type Handling
- **Module Resolution**: Resolves file type to appropriate SuiteCRM module
- **Case Sensitivity**: Handles case variations in module names
- **Type Validation**: Validates file type against allowed modules
- **Error Handling**: Provides appropriate error messages for invalid types

### Access Control Interface
- **Authentication**: Requires valid user session for file access
- **Authorization**: Validates user permissions for specific files
- **Error Messages**: User-friendly error messages for access denials
- **Logging**: Comprehensive access logging for audit purposes

### Document-Specific Handling
- **Document Module**: Special processing for Document module files
- **Revision System**: Handles document revision relationships
- **File Resolution**: Resolves document references to actual files
- **Version Control**: Manages access to different document versions

## Security Architecture

### Access Control
- **Authentication**: Session-based user authentication
- **Authorization**: Record-level access control validation
- **Module Security**: Module-level permission checking
- **File Security**: File-level access validation

### Parameter Validation
- **Required Parameters**: Validates presence of required parameters
- **Type Safety**: Ensures parameter types match expectations
- **Injection Prevention**: Prevents SQL injection and path traversal
- **Error Handling**: Secure error handling without information disclosure

### Session Security
- **Session Validation**: Verifies active user session
- **User Context**: Maintains proper user context throughout process
- **Timeout Handling**: Handles session timeout scenarios
- **Concurrent Access**: Manages concurrent download requests

### File System Security
- **Path Validation**: Prevents directory traversal attacks
- **File Location**: Restricts access to authorized file locations
- **Permission Checks**: Validates file system permissions
- **Temporary Files**: Secure handling of temporary file access

## Error Handling

### Validation Errors
- **Missing Parameters**: Handles missing required parameters
- **Invalid Types**: Processes invalid module types
- **Authentication Failures**: Manages authentication error scenarios
- **Authorization Denials**: Handles permission denial cases

### System Errors
- **File Not Found**: Handles missing file scenarios
- **Database Errors**: Manages database connectivity issues
- **Module Errors**: Handles module loading failures
- **Configuration Errors**: Processes configuration-related errors

### User Experience
- **Error Messages**: User-friendly error message presentation
- **Localization**: Localized error messages based on user language
- **Graceful Degradation**: Handles errors without system compromise
- **Logging**: Comprehensive error logging for debugging

## File Type Support

### Document Files
- **Office Documents**: Microsoft Office file support
- **PDF Files**: Adobe PDF document support
- **Image Files**: Various image format support
- **Text Files**: Plain text and rich text support

### System Files
- **Configuration**: Controlled access to configuration files
- **Logs**: Secure access to system log files
- **Exports**: User-generated export file access
- **Reports**: Generated report file access

### Temporary Files
- **Upload Processing**: Temporary file handling during uploads
- **Export Generation**: Temporary files for export processing
- **Cache Files**: Controlled access to cached content
- **Session Files**: Temporary session-related files

## Integration Points

### Module System
- **Bean Factory**: Integration with SuiteCRM bean factory
- **Module Registry**: Uses system module registry
- **ACL System**: Integration with access control system
- **Bean Files**: Leverages bean file registry

### Document Management
- **Document Module**: Deep integration with document management
- **Revision Control**: Document revision system integration
- **File Storage**: Integration with file storage system
- **Metadata**: Document metadata management

### User Management
- **Authentication**: User authentication system integration
- **Preferences**: User preference system integration
- **Localization**: User language preference integration
- **Session Management**: Session management system integration 