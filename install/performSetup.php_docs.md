# performSetup.php Documentation

## @fileoverview
Main installation orchestration engine that coordinates database setup, configuration file creation, and SuiteCRM system initialization during the installation process.

## @package SuiteCRM Installation
## @copyright SalesAgility Ltd.
## @license AGPL-3.0

## Overview

This file serves as the core engine of the SuiteCRM installation process, responsible for translating user-provided configuration data from session variables into a fully functional SuiteCRM instance. It handles database creation, table schema deployment, initial configuration, and system bootstrapping.

## Core Functionality

### Installation Status Tracking
- **Status Reporting**: Implements `installStatus()` function for real-time progress feedback
- **JSON Status File**: Maintains `install/status.json` for monitoring installation progress
- **User Feedback**: Provides visual progress indicators during lengthy operations

### Database Operations
- **Database Creation**: Creates the main SuiteCRM database if requested
- **Table Schema**: Deploys all required database tables using SugarBean definitions
- **Index Creation**: Establishes database indexes for optimal performance
- **Foreign Key Setup**: Configures referential integrity constraints

### Configuration Management
- **Session Variable Processing**: Extracts configuration from $_SESSION variables
- **Config File Generation**: Creates `config.php` with all system settings
- **Security Settings**: Applies security configurations and restrictions
- **Cache Configuration**: Sets up caching mechanisms and directories

## Internal Operations

### Installation Flow Control
```php
// Security validation
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Installation script verification
if (!isset($install_script) || !$install_script) {
    die($mod_strings['ERR_NO_DIRECT_SCRIPT']);
}
```

### Environment Setup
- **Output Buffering**: Configures real-time output for user feedback
- **Time Limits**: Sets extended execution time for complex operations
- **Memory Management**: Prepares system for resource-intensive operations
- **Tracker Pause**: Suspends user activity tracking during installation

### Session Variable Processing
The file extracts critical configuration from session:
- Database connection parameters (host, credentials, name)
- Site configuration (URL, admin user, passwords)
- Installation options (demo data, database creation flags)
- Localization settings (language, time zone, currency)

## Database Operations

### Connection Management
- **Database Factory**: Uses DBManagerFactory for database abstraction
- **Connection Validation**: Verifies database connectivity before proceeding
- **Credential Handling**: Securely processes database authentication
- **Type-Specific Logic**: Handles different database engine requirements

### Schema Deployment
- **Table Dictionary**: Leverages modules/TableDictionary.php for schema definitions
- **SugarBean Integration**: Uses bean definitions for table creation
- **Relationship Tables**: Creates relationship mapping tables
- **Metadata Tables**: Establishes system metadata storage

### Data Population
- **System Configuration**: Inserts essential system settings
- **Admin User**: Creates initial administrator account
- **Default Data**: Populates necessary reference data
- **Demo Content**: Optionally loads demonstration data

## Configuration File Generation

### Config.php Creation
Generates comprehensive configuration including:
- Database connection parameters
- Site URLs and paths
- Security settings and keys
- Caching configuration
- Module settings
- Integration parameters

### Security Configuration
- **Installer Locking**: Prevents re-execution of installation
- **Authentication Keys**: Generates security tokens
- **File Permissions**: Sets appropriate access controls
- **CSRF Protection**: Establishes anti-forgery measures

## Integration Points

### Installation Utilities
Integrates with `install_utils.php` for:
- Custom installation hooks
- Language pack handling
- File system operations
- Validation routines

### Module System
Coordinates with module framework:
- **Module Registration**: Registers all standard modules
- **Relationship Setup**: Configures inter-module relationships
- **Permissions**: Establishes module-level access controls
- **Custom Modules**: Handles any pre-installed custom modules

### Suite-Specific Features
Initializes SuiteCRM-specific functionality:
- Advanced OpenSales integration
- Advanced OpenPortal setup
- Search engine configuration
- Workflow engine initialization

## External API Integration

### Third-Party Services
Prepares integration endpoints for:
- Email services (SMTP configuration)
- Calendar synchronization
- External authentication providers
- API access credentials

### Search Integration
- **Elasticsearch Setup**: Configures search engine if enabled
- **Index Creation**: Establishes search indexes
- **Connector Configuration**: Sets up search service connections

## UI Functionality

### Progress Feedback
- **Real-Time Updates**: Streams installation progress to browser
- **Status Messages**: Provides detailed step-by-step feedback
- **Error Reporting**: Displays installation errors and warnings
- **Completion Notification**: Signals successful installation completion

### Error Handling
- **Graceful Degradation**: Handles installation failures appropriately
- **Rollback Capability**: Provides mechanisms for installation recovery
- **Logging**: Records installation progress and errors
- **User Guidance**: Offers troubleshooting information

## Error Handling

### Installation Failures
- **Database Errors**: Handles database connection and query failures
- **File System Errors**: Manages file permission and access issues
- **Configuration Errors**: Validates configuration parameters
- **Memory/Timeout**: Handles resource limitation issues

### Recovery Mechanisms
- **Partial Completion**: Supports resumption of interrupted installations
- **Cleanup Procedures**: Removes incomplete installation artifacts
- **State Validation**: Verifies installation state consistency
- **Manual Intervention**: Provides fallback options for manual completion

## Security Considerations

### Input Validation
- **Session Data**: Validates all session-stored configuration
- **SQL Injection**: Uses prepared statements and parameterized queries
- **File Path**: Validates file system paths and operations
- **Configuration**: Sanitizes configuration values

### Access Control
- **Entry Point**: Enforces proper entry point validation
- **Installation Lock**: Prevents unauthorized re-installation
- **Admin Privileges**: Secures administrative account creation
- **System Files**: Protects critical system files

## Dependencies

### System Requirements
- **PHP Extensions**: Database drivers, output buffering, file system access
- **Database**: Supported database engine (MySQL, SQL Server, etc.)
- **File System**: Write access to configuration and cache directories
- **Memory**: Sufficient memory for schema deployment

### SuiteCRM Components
- **install_utils.php**: Installation utility functions
- **TableDictionary.php**: Database schema definitions
- **BeanFactory**: Object relationship management
- **TrackerManager**: User activity tracking system

## Performance Considerations

### Optimization Strategies
- **Batch Operations**: Groups database operations for efficiency
- **Memory Management**: Manages memory usage during large operations
- **Index Creation**: Optimizes database index creation order
- **Cache Preparation**: Pre-configures caching for better performance

### Resource Management
- **Time Limits**: Sets appropriate execution time limits
- **Memory Allocation**: Configures memory limits for installation
- **Database Connections**: Optimizes database connection usage
- **File Operations**: Minimizes file system I/O operations

## Development Notes

### Customization Points
- **Custom Hooks**: Supports custom installation hooks via `installerHook()`
- **Module Extensions**: Handles custom module installation
- **Configuration Override**: Allows configuration customization
- **Post-Install Scripts**: Supports post-installation automation

### Testing Considerations
- **Silent Installation**: Supports automated installation testing
- **Configuration Validation**: Includes comprehensive parameter validation
- **Rollback Testing**: Supports installation failure testing
- **Performance Testing**: Includes timing and resource monitoring 