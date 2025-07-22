# DiagnosticRun.php Documentation

## @fileoverview
Comprehensive system diagnostic tool that collects, analyzes, and exports detailed system information including configuration files, database schemas, PHP environment, file integrity checks, and custom code analysis for troubleshooting and support purposes.

## @package Administration
## @copyright SalesAgility Ltd
## @license GNU AGPL v3

## Overview
DiagnosticRun.php is a powerful diagnostic utility that gathers extensive system information for analysis and troubleshooting. It creates a comprehensive diagnostic package containing configuration data, database information, file integrity checks, and system environment details, packaged into a downloadable ZIP file for support teams.

## Database Operations

### Configuration Table Analysis
- **Config table dump**: Exports complete `config` table with password masking for security
- **Fields metadata extraction**: Captures `fields_meta_data` table for custom field analysis
- **Upgrade history tracking**: Exports `upgrade_history` table for installation timeline
- **Version information**: Captures `versions` table for system version tracking

### Database Schema Documentation
- **Complete schema export**: Generates full database schema with table definitions and indexes
- **Column information**: Detailed column definitions including types, lengths, and constraints
- **Index documentation**: Comprehensive index structure for performance analysis
- **Relationship mapping**: Database relationship documentation for data integrity

### Table Dumps with Security
- **Selective table dumping**: Configurable table export with sensitive data protection
- **Password masking**: Automatic masking of sensitive fields (smtppass, admin_password, proxy passwords)
- **Data sanitization**: Replaces sensitive values with asterisks while preserving structure
- **Row counting**: Provides row count information for capacity analysis

### Database Information Collection
- **Database version**: Captures database server version and configuration
- **Connection details**: Documents database type and connection parameters
- **Performance metrics**: Database-specific performance and configuration data

## Internal API Calls

### Core System Integration
- **DBManagerFactory**: Database connection management and query execution
- **SugarLogger**: Log file access and copying for diagnostic inclusion
- **BeanFactory**: Bean file validation and module integrity checking
- **TimeDate**: Date/time handling for diagnostic timestamps

### File System Operations
- **sugar_cached()**: Cache directory management for diagnostic storage
- **sugar_fopen/sugar_fclose**: Secure file operations for output generation
- **create_cache_directory()**: Directory creation for organized diagnostic output
- **zip_dir()**: Custom directory compression for custom code inclusion

### Configuration Management
- **$sugar_config**: System configuration export with password sanitization
- **write_array_to_file()**: Configuration serialization for diagnostic export
- **loadCleanConfig()**: Clean configuration loading without sensitive data

### Bean Validation System
- **$beanList/$beanFiles**: Module file existence validation
- **file_exists()**: Physical file validation for module integrity
- **Bean dependency checking**: Validates module file dependencies and availability

## External API Calls

### File Integrity Verification
- **generateMD5array()**: Calculates MD5 checksums for all system files
- **files.md5 comparison**: Compares current file hashes against original installation
- **File modification detection**: Identifies changed, added, or missing files
- **Integrity reporting**: Generates comprehensive file integrity reports

### System Environment Analysis
- **phpinfo()**: Complete PHP environment documentation
- **PHP version detection**: Runtime environment analysis
- **Extension availability**: PHP extension detection and configuration
- **Memory and resource limits**: System resource documentation

### Custom Code Analysis
- **Custom directory scanning**: Complete custom code directory compression
- **Modification tracking**: Identifies customizations and modifications
- **Extension analysis**: Documents installed extensions and customizations

## UI Functionality

### Progress Tracking System
- **Real-time progress bar**: Visual progress indication during diagnostic execution
- **Weighted progress calculation**: Accurate progress reporting based on operation complexity
- **Status updates**: Real-time status messages for user feedback
- **Operation timing**: Performance tracking for each diagnostic component

### Interactive Diagnostic Selection
- **Checkbox-based selection**: User control over diagnostic components to include
- **Configurable options**: Granular control over diagnostic scope and detail level
- **Default recommendations**: Intelligent defaults for comprehensive diagnostics

### User Interface Components
- **Progress visualization**: JavaScript-based progress bar with percentage completion
- **Status messaging**: Clear status messages for each diagnostic operation
- **Error reporting**: User-friendly error messages with actionable guidance
- **Completion feedback**: Success confirmation with download links

### Security and Access Control
- **Administrator validation**: Requires admin privileges for diagnostic execution
- **Configuration-based access**: Respects `hide_admin_diagnostics` setting
- **Secure execution**: Validates user permissions before diagnostic operations

## Associated Tests

### File Integrity Testing
- **MD5 hash validation**: Tests file integrity checking against known good hashes
- **Modification detection**: Validates ability to detect file modifications
- **Custom code identification**: Tests custom code discovery and documentation

### Database Operations Testing
- **Schema export validation**: Tests complete schema export functionality
- **Sensitive data masking**: Validates password and sensitive data protection
- **Table dump accuracy**: Tests selective table dumping with data integrity

### System Environment Testing
- **PHP configuration capture**: Tests PHP environment documentation accuracy
- **Extension detection**: Validates PHP extension detection and reporting
- **Resource limit documentation**: Tests system resource limit capture

### Progress and UI Testing
- **Progress calculation**: Tests weighted progress calculation accuracy
- **Status messaging**: Validates status message accuracy and timing
- **Error handling**: Tests error condition handling and user feedback

## Key Functions

### prepareDiag()
Initializes diagnostic environment, creates directory structure, determines MD5 checking availability, configures table dump selection, and sets up progress tracking system.

### executesugarlog()
Copies the SuiteCRM log file to the diagnostic directory for inclusion in the diagnostic package, providing essential troubleshooting information.

### executephpinfo()
Captures complete PHP environment information using phpinfo() and saves it as an HTML file for comprehensive environment documentation.

### executeconfigphp()
Exports system configuration with sensitive password data masked, preserving configuration structure while protecting security credentials.

### execute_sql()
Comprehensive database analysis including schema export, table dumps, and database information collection with configurable options for detail level.

### executebeanlistbeanfiles()
Validates module file integrity by checking BeanList/BeanFiles consistency, identifying missing or misconfigured module files.

### executecustom_dir()
Compresses the entire custom directory for inclusion in diagnostic package, preserving custom code and modifications for analysis.

### executemd5()
Performs comprehensive file integrity analysis by calculating MD5 hashes and comparing against original installation files.

### executevardefs()
Generates comprehensive schema documentation based on SuiteCRM vardefs, providing detailed table and field documentation.

### finishDiag()
Completes diagnostic process by creating final ZIP package, providing download links, and cleaning up temporary files.

## Progress Bar System

### Weighted Progress Calculation
- **Operation weights**: Each diagnostic operation has assigned weight based on complexity
- **Real-time updates**: Progress bar updates as each operation completes
- **Accurate estimation**: Weighted system provides realistic completion estimates

### Progress Constants
- **CONFIG_WEIGHT**: Configuration export complexity weight
- **CUSTOM_DIR_WEIGHT**: Custom directory compression weight
- **PHPINFO_WEIGHT**: PHP information capture weight
- **SQL_DUMPS_WEIGHT**: Database dump operation weight
- **MD5_WEIGHT**: File integrity checking weight
- **VARDEFS_WEIGHT**: Schema documentation weight

## Security Features

### Data Protection
- **Password masking**: Automatic detection and masking of sensitive password fields
- **LDAP password protection**: Specific protection for LDAP admin passwords
- **Proxy password security**: Masks proxy authentication credentials
- **SMTP credential protection**: Protects email server credentials

### Access Control
- **Admin privilege requirement**: Ensures only administrators can run diagnostics
- **Configuration-based disabling**: Respects system configuration for diagnostic access
- **Secure file handling**: Prevents unauthorized access to sensitive diagnostic data

### File Security
- **Temporary file cleanup**: Automatic cleanup of temporary diagnostic files
- **Secure directory creation**: Uses secure directory creation with appropriate permissions
- **Download link security**: Generates secure, time-limited download links

## Output Structure

### Diagnostic Package Contents
- **config.php**: Sanitized system configuration
- **phpinfo.html**: Complete PHP environment information
- **custom_directory.zip**: Compressed custom code directory
- **Database directory**: Schema, dumps, and database information
- **MD5 directory**: File integrity analysis results
- **suitecrm.log**: System log file
- **vardefschema.html**: Comprehensive schema documentation
- **beanFiles.html**: Module file validation results

### File Organization
- **Hierarchical structure**: Organized directory structure for easy navigation
- **Timestamped naming**: Unique timestamps prevent conflicts
- **GUID identification**: Unique identifiers for diagnostic packages
- **Categorized content**: Logical grouping of diagnostic information

## Configuration Dependencies
- **Max execution time**: Requires extended execution time for comprehensive diagnostics
- **Memory limits**: May require increased memory limits for large installations
- **File permissions**: Requires write access to cache directory
- **Database access**: Requires database connection for schema and data analysis
- **PHP extensions**: Optimal functionality requires GD library and zip extensions

## Related Files
- **Diagnostic.php**: Diagnostic interface and form handling
- **DiagnosticDownload.php**: Secure diagnostic package download handler
- **DiagnosticDelete.php**: Diagnostic package cleanup and deletion
- **Diagnostic.tpl**: User interface template for diagnostic options 