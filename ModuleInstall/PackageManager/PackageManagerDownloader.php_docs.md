# PackageManagerDownloader.php Documentation

## @fileoverview HTTP download utility for retrieving packages from SugarDepot using cURL with session-based authentication
## @package SuiteCRM\ModuleInstall\PackageManager
## @copyright 2004-2013 SugarCRM Inc., 2011-2018 SalesAgility Ltd.
## @license GNU Affero General Public License version 3

## Overview
The PackageManagerDownloader class provides secure HTTP download functionality for retrieving package files from the SugarDepot repository. It uses cURL for efficient file transfers with session-based authentication and supports configurable download locations.

## Core Download Features

### Secure File Transfer
- HTTPS-based secure downloads
- Session-authenticated transfers
- SSL certificate handling
- Progress monitoring support

### Flexible Configuration
- Configurable download servers
- Custom save directory support
- Session ID-based authentication
- Error handling and retry logic

## Database Operations
No database operations are performed by this class. It focuses purely on HTTP file transfer operations.

## Internal API Calls

### Download Management
- `download()`: Primary download method with full configuration
- Session cookie management for authentication
- File stream handling for efficient transfers
- Error handling and validation

### Configuration Methods
- Configurable download server endpoints
- Customizable save directory paths
- Session ID parameter handling
- SSL verification management

## External API Calls

### SugarDepot Download Service
- Connects to `PACKAGE_MANAGER_DOWNLOAD_SERVER` (https://depot.sugarcrm.com/depot/)
- Uses `PACKAGE_MANAGER_DOWNLOAD_PAGE` (download.php) endpoint
- Authenticated downloads using session cookies
- HTTPS protocol for secure transfers

### File System Integration
- Sugar file stream wrapper usage (`sugar_fopen`)
- Upload directory management
- Temporary file handling
- File permission management

## Security Features

### Secure Transfer Protocol
- HTTPS encryption for all downloads
- SSL verification (configurable)
- Session-based authentication
- Cookie security management

### Authentication Integration
- PHP session ID cookie transmission
- Depot session validation
- Authenticated download verification
- Session timeout handling

### File System Security
- Safe file path handling
- Upload directory restrictions
- File extension validation
- Permission management

## Configuration Management

### Server Configuration
- Default depot server: `https://depot.sugarcrm.com/depot/`
- Default download page: `download.php`
- Configurable server endpoints
- SSL verification options

### Directory Management
- Default save directory: `upload://` (stream wrapper)
- Custom directory support
- Permission validation
- Path sanitization

## Performance Considerations

### Efficient Transfer
- cURL-based downloads for optimal performance
- Stream-based file writing to minimize memory usage
- Direct file transfers without intermediate buffering
- Connection reuse for multiple downloads

### Resource Management
- Automatic file handle cleanup
- Memory-efficient streaming
- Connection timeout management
- Error recovery mechanisms

## Error Handling

### Transfer Errors
- cURL error detection and handling
- Network connectivity validation
- Timeout management
- Retry logic support

### File System Errors
- File creation validation
- Permission error handling
- Disk space validation
- Path resolution errors

### Authentication Errors
- Session validation
- Authentication failure detection
- Token expiration handling
- Re-authentication support

## Usage Patterns

### Standard Download Flow
1. Authenticate with SugarDepot to obtain session ID
2. Call download method with session ID and filename
3. File is transferred using HTTPS with session cookies
4. File is saved to specified directory
5. Full file path is returned for further processing

### Parameters
- `$session_id`: Authentication session identifier
- `$file_name`: Remote filename to download
- `$save_dir`: Local directory for file storage (optional)
- `$download_server`: Alternative server URL (optional)

## Integration Points

### Package Management System
- Provides download services for PackageManager
- Integrates with installation workflow
- Supports package verification processes
- Enables automated package retrieval

### Authentication System
- Uses session-based authentication
- Integrates with SugarDepot login process
- Supports credential validation
- Handles session management

## Associated Tests
No specific test files identified for this class. Testing would cover:
- Download functionality with valid sessions
- Error handling for invalid sessions
- File system permission handling
- Network connectivity error management
- SSL certificate validation
- Large file download performance

## Dependencies
- cURL extension for HTTP transfers
- Sugar file system utilities (`sugar_fopen`)
- Session management system
- SSL/TLS support for secure transfers

## Return Values

### Successful Downloads
Returns the full path to the downloaded file for further processing by the package management system.

### Error Conditions
- Network connectivity failures
- Authentication errors
- File system permission issues
- Disk space limitations

## Security Considerations

### Data Protection
- Encrypted transfer protocol (HTTPS)
- Session-based authentication
- Secure cookie handling
- SSL certificate validation

### File Security
- Safe file naming conventions
- Upload directory restrictions
- Permission validation
- Path traversal prevention

## Configuration Constants

### Server Settings
- `PACKAGE_MANAGER_DOWNLOAD_SERVER`: Base URL for downloads
- `PACKAGE_MANAGER_DOWNLOAD_PAGE`: Download endpoint script

### Default Values
- Save directory defaults to upload stream wrapper
- SSL verification enabled by default
- Session cookie security enabled 