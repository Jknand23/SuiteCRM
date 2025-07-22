# install_utils.php Documentation

## @fileoverview
Comprehensive collection of utility functions supporting all aspects of SuiteCRM installation, configuration, and post-installation setup processes.

## @package SuiteCRM Installation
## @copyright SalesAgility Ltd.
## @license AGPL-3.0

## Overview

This file serves as the central utility library for the SuiteCRM installation system, providing over 50 specialized functions that handle everything from database operations and configuration management to language pack installation and system validation. It acts as the functional backbone supporting the main installation orchestrator.

## Core Functionality Categories

### Installation Hook System
- **installerHook()**: Executes custom installation hooks from `custom/install/install_hooks.php`
- **post_install_modules()**: Automatically installs modules specified in `modules_post_install.php`
- Supports customization and automation of installation processes

### Language and Localization
- **parseAcceptLanguage()**: Detects browser language preferences for installer
- **commitLanguagePack()**: Installs language packs during installation
- **uninstallLangPack()**: Removes language packs from system
- **getInstalledLangPacks()**: Returns installed language pack information
- **langPackFinalMove()**: Handles language pack file operations
- **langPackUnpack()**: Extracts and validates language pack contents

### Database Operations
- **getDbConnection()**: Establishes database connections with proper configuration
- **handleDbCreateDatabase()**: Creates the main SuiteCRM database
- **handleDbCreateSugarUser()**: Creates database user accounts
- **handleDbCharsetCollation()**: Configures database character sets
- **create_table_if_not_exist()**: Creates database tables from bean definitions
- **drop_table_install()**: Removes tables during installation cleanup

### Configuration Management
- **handleSugarConfig()**: Generates main config.php file from session data
- **writeSugarConfig()**: Writes configuration arrays to config.php
- **pullSilentInstallVarsIntoSession()**: Loads silent installation configuration
- **getFtsSettings()**: Configures full-text search settings
- **copyFromArray()**: Copies configuration values between arrays

### File System Operations
- **handleHtaccess()**: Generates and manages Apache .htaccess files
- **handleWebConfig()**: Creates IIS web.config files for Windows
- **make_writable()**: Sets proper file permissions for installation
- **recursive_make_writable()**: Recursively applies write permissions
- **recursive_is_writable()**: Validates write permissions recursively
- **create_writable_dir()**: Creates directories with proper permissions

## Database Operations

### Connection Management
```php
function getDbConnection()
{
    global $setup_db_host_name, $setup_db_admin_user_name, $setup_db_admin_password;
    // Returns configured database connection instance
}
```

### Schema Operations
- **Table Creation**: Uses SugarBean definitions to create database tables
- **Index Management**: Creates database indexes for optimal performance
- **Relationship Tables**: Establishes foreign key relationships
- **Character Set Configuration**: Sets proper UTF-8 encoding

### Data Population
- **create_default_users()**: Creates administrator and default users
- **set_admin_password()**: Sets administrator password securely
- **insert_default_settings()**: Populates system configuration tables
- **addDefaultRoles()**: Creates default ACL roles and permissions

## Configuration File Generation

### Main Configuration (config.php)
The `handleSugarConfig()` function creates comprehensive configuration including:
- Database connection parameters
- Site URLs and directory paths
- Security keys and tokens
- Cache configuration
- Module settings
- Integration parameters

### Web Server Configuration
- **Apache (.htaccess)**: URL rewriting, security restrictions, cache headers
- **IIS (web.config)**: Windows server configuration equivalent
- **Security Rules**: Prevents direct access to sensitive files
- **Performance Optimization**: Sets cache headers and compression

## Module and Package Management

### Package Installation
- **commitModules()**: Installs module packages from upload directory
- **commitPatch()**: Applies system patches and updates
- **validateManifest()**: Validates package manifest files
- **getInstallType()**: Determines package type (module, langpack, patch, theme)

### Upgrade History
- **updateUpgradeHistory()**: Records installation history in database
- **UpgradeHistory Integration**: Tracks installed packages and versions
- **Rollback Support**: Maintains information for package removal

## Language Pack Handling

### Installation Process
1. **Upload Validation**: Validates language pack format and content
2. **Manifest Processing**: Extracts and validates manifest.php files
3. **File Extraction**: Unpacks language files to appropriate directories
4. **Configuration Update**: Updates system language configuration
5. **Cleanup**: Removes temporary files and validates installation

### Language Detection
- **Browser Language**: Automatically detects user's preferred language
- **Fallback Handling**: Provides English fallback for unsupported languages
- **Regional Variants**: Supports country-specific language variants

## Validation Functions

### System Requirements
- **validate_systemOptions()**: Validates system configuration options
- **validate_dbConfig()**: Validates database configuration parameters
- **validate_siteConfig()**: Validates site configuration settings

### Input Validation
- **get_boolean_from_request()**: Safely extracts boolean values from requests
- **stripslashes_checkstrings()**: Sanitizes string inputs
- **getHostPortFromString()**: Parses database host:port combinations

## Utility Functions

### Data Generation
- **createEmailAddress()**: Generates realistic demo email addresses
- **createWebAddress()**: Creates demo website URLs
- **create_phone_number()**: Generates formatted phone numbers
- **create_date()**: Creates random dates for demo data
- **create_time()**: Generates time values for demo data
- **create_db_user_creds()**: Creates secure random passwords

### File Operations
- **getLicenseContents()**: Reads license file contents for display
- **installLog()**: Writes installation progress to log files
- **extractFile()**: Extracts specific files from ZIP archives

## External API Integration

### Search Engine Setup
- **Elasticsearch Configuration**: Sets up search engine connections
- **Index Creation**: Initializes search indexes
- **Search Module Integration**: Configures search across modules

### Third-Party Connectors
- **enableInsideViewConnector()**: Configures InsideView business intelligence
- **enableSugarFeeds()**: Activates social feed functionality
- **Social Integration**: Sets up social media connectors

## Error Handling

### Installation Logging
- **installLog()**: Centralized logging with timestamps
- **Error Tracking**: Records installation errors and warnings
- **Debug Output**: Provides detailed debugging information
- **Progress Monitoring**: Tracks installation step completion

### Validation and Recovery
- **Configuration Validation**: Validates all configuration parameters
- **File Permission Checking**: Ensures proper file system access
- **Database Connectivity**: Validates database connections
- **Rollback Preparation**: Maintains state for installation recovery

## Security Features

### Input Sanitization
- **SQL Injection Prevention**: Uses parameterized queries and escaping
- **File Path Validation**: Prevents directory traversal attacks
- **Configuration Sanitization**: Validates configuration values
- **Upload Validation**: Secures file upload processes

### Access Control
- **Entry Point Validation**: Enforces proper script access
- **Permission Management**: Sets secure file permissions
- **User Credential Handling**: Securely processes passwords
- **Configuration Protection**: Protects sensitive configuration data

## Performance Optimization

### Database Optimization
- **Connection Pooling**: Efficient database connection management
- **Index Creation**: Optimizes database query performance
- **Batch Operations**: Groups database operations for efficiency
- **Character Set Optimization**: Uses efficient UTF-8 encoding

### File System Optimization
- **Permission Caching**: Caches file permission checks
- **Directory Creation**: Efficiently creates directory structures
- **Temporary File Management**: Cleans up temporary files promptly
- **Archive Handling**: Optimizes ZIP file operations

## Development Support

### Debugging Functions
- **print_debug_array()**: Formats arrays for debugging output
- **print_debug_comment()**: Adds debug comments to output
- **Installation Tracking**: Monitors installation progress
- **Error Reporting**: Provides detailed error information

### Customization Hooks
- **Custom Install Hooks**: Supports custom installation logic
- **Configuration Override**: Allows custom configuration injection
- **Module Extensions**: Supports custom module installation
- **Post-Install Automation**: Enables automated post-installation tasks

## Dependencies

### System Requirements
- **PHP Extensions**: ZIP, database drivers, file system functions
- **Database Support**: MySQL, SQL Server, or compatible database
- **File System Access**: Read/write permissions for installation directories
- **Web Server**: Apache or IIS with appropriate modules

### SuiteCRM Components
- **BeanFactory**: Object creation and relationship management
- **DBManagerFactory**: Database abstraction layer
- **ModuleInstaller**: Module installation framework
- **PackageManager**: Package management system
- **SugarBean**: Base object model for database operations

## Installation Integration

### Installation Workflow
1. **Pre-Installation**: System validation and requirement checking
2. **Configuration**: Database and site configuration processing
3. **Database Setup**: Schema creation and data population
4. **Module Installation**: Core and custom module deployment
5. **Post-Installation**: Final configuration and cleanup

### Silent Installation Support
- **Configuration File Support**: Reads config_si.php for automated installation
- **Batch Processing**: Supports unattended installation modes
- **Error Handling**: Provides appropriate error handling for automation
- **Validation**: Ensures configuration completeness

## Maintenance and Administration

### Upgrade Support
- **Version Tracking**: Maintains version information in database
- **Package History**: Records all installed packages and modifications
- **Rollback Information**: Maintains data for package removal
- **Compatibility Checking**: Validates package compatibility

### System Administration
- **Permission Management**: Tools for managing file permissions
- **Configuration Updates**: Utilities for configuration modifications
- **Database Maintenance**: Functions for database optimization
- **Log Management**: Installation and error log handling

## Development Notes

### Code Organization
- Functions grouped by functional area for maintainability
- Consistent parameter handling and return value patterns
- Comprehensive error handling and logging throughout
- Modular design supports testing and modification

### Customization Guidelines
- Use installer hooks for custom installation logic
- Extend validation functions for custom requirements
- Add custom configuration handlers as needed
- Maintain backward compatibility with existing installations 