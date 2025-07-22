# SuiteCRM System Requirements Check Documentation

**File:** `install/installSystemCheck.php`

## @fileoverview
Comprehensive system requirements validation module for SuiteCRM installation. Performs extensive checks of server environment, PHP configuration, database connectivity, and other prerequisites to ensure successful installation.

## @package
Installation

## @copyright
2004-2018 SugarCRM Inc. and SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

---

## Purpose

This file provides comprehensive system validation during SuiteCRM installation, ensuring that the server environment meets all technical requirements for successful deployment. It performs detailed checks of:
- Server software compatibility (Apache, IIS, Nginx)
- PHP version and configuration requirements
- Database connectivity and permissions
- File system permissions and directory accessibility
- Memory and resource availability
- Required PHP extensions and modules

## Key Functionality

### Primary Validation Function

#### `runCheck($install_script, $mod_strings)`
**Purpose**: Executes comprehensive system environment validation
**Parameters**:
- `$install_script` (bool): Installation script context flag
- `$mod_strings` (array): Localized message strings for errors and warnings

**Returns**: String containing HTML formatted system check results

### Web Server Validation

#### IIS Server Checks
- **Version Validation**: Ensures compatible IIS version (7.0+)
- **FastCGI Detection**: Validates FastCGI installation and configuration
- **Logging Configuration**: Checks FastCGI logging settings for performance
- **PHP Integration**: Verifies proper PHP-IIS integration

#### Apache Server Checks
- **Module Detection**: Validates required Apache modules
- **Configuration Validation**: Checks .htaccess support and mod_rewrite
- **Performance Settings**: Validates memory and execution limits

### PHP Environment Validation

#### Version Compatibility
- Validates minimum PHP version requirements (7.0+)
- Checks for deprecated PHP features that may cause issues
- Validates PHP configuration for SuiteCRM compatibility

#### Memory and Resource Checks
- **Memory Limit**: Validates minimum memory allocation (40MB+)
- **Execution Time**: Checks maximum execution time settings
- **Upload Limits**: Validates file upload size and timeout limits

#### Required Extensions
- **Database Extensions**: MySQLi, PDO support validation
- **File Handling**: ZIP, GD library validation
- **Security Extensions**: OpenSSL, cURL validation
- **Performance Extensions**: OPcache, APCu validation

### Database Connectivity Validation

#### Connection Testing
- Tests database server connectivity
- Validates database credentials and permissions
- Checks database version compatibility
- Validates character set and collation support

#### Permission Validation
- Tests CREATE, DROP, ALTER table permissions
- Validates INSERT, UPDATE, DELETE data permissions
- Checks INDEX creation and management permissions

### File System Validation

#### Directory Permissions
- **Write Permissions**: Validates writable directories for uploads, cache, logs
- **Read Permissions**: Ensures proper access to configuration and module files
- **Execute Permissions**: Validates script execution capabilities

#### Critical Path Validation
- **Configuration Directory**: Validates config/ directory accessibility
- **Upload Directory**: Ensures upload/ directory is writable
- **Cache Directory**: Validates cache/ directory permissions
- **Log Directory**: Ensures custom/ and logs/ directories are accessible

## Error Detection and Reporting

### Error Classification System
- **Critical Errors**: Issues that prevent installation
- **Warnings**: Recommendations for optimal performance
- **Information**: System status and configuration details

### Error Message Generation
```php
$error_txt .= '
    <p><b>'.$mod_strings['LBL_CHECKSYS_CATEGORY'].'</b></p>
    <p><span class="error">'.$errorMessage.'</span></p>
';
```

### Progress Tracking
- Maintains `$error_found` flag for installation flow control
- Generates detailed error reports for troubleshooting
- Provides actionable recommendations for issue resolution

## Integration Points

### Database Operations
- Tests database connectivity and permissions
- Validates database server version and configuration
- Checks for existing SuiteCRM installations

### Internal API Calls
- `installLog()`: Logs system check progress and errors
- `check_iis_version()`: Validates IIS server version compatibility
- Database factory methods for connection testing

### External API Calls
None - Focuses on local system validation

### UI Functionality
- **Progress Display**: Shows system check progress to users
- **Error Reporting**: Displays formatted error messages and warnings
- **Recommendation Engine**: Provides specific guidance for issue resolution
- **Status Indicators**: Visual indicators for pass/fail status of each check

## System Check Categories

### 1. Web Server Compatibility
- Server software detection and validation
- Configuration requirement verification
- Performance optimization recommendations

### 2. PHP Configuration
- Version compatibility validation
- Extension requirement checking
- Configuration directive verification

### 3. Database Connectivity
- Connection establishment testing
- Permission validation
- Version compatibility checking

### 4. File System Access
- Directory permission validation
- Path accessibility verification
- Security configuration checking

### 5. Resource Availability
- Memory allocation validation
- Execution time limit checking
- Upload capacity verification

## Security Validation

### Entry Point Protection
- Validates installation script context
- Prevents direct script execution
- Maintains secure installation environment

### Configuration Security
- Validates secure file permissions
- Checks for potentially insecure configurations
- Recommends security best practices

## Error Recovery Mechanisms

### Automatic Retry Logic
- Retries failed connection attempts
- Validates configuration changes
- Provides real-time status updates

### User Guidance
- Specific error resolution instructions
- Configuration file examples
- Administrator contact recommendations

## Performance Optimization

### Resource Monitoring
- Memory usage tracking during checks
- Execution time monitoring
- Database query performance validation

### Efficiency Measures
- Optimized check sequencing
- Minimal resource consumption
- Fast failure detection

## Dependencies

### Required Components
- PHP session management
- Database connectivity libraries
- File system access functions
- System information gathering functions

### Optional Enhancements
- Extended logging capabilities
- Performance monitoring tools
- Advanced security validation modules

## Related Files

- `install/install_utils.php`: Installation utility functions
- `install/performSetup.php`: Post-validation setup processing
- `install/installConfig.php`: Configuration management
- Database management classes for connectivity testing

## Output Format

The function returns HTML-formatted results including:
- Visual progress indicators
- Detailed error descriptions
- Recommendation summaries
- Next step guidance

## Usage in Installation Flow

1. **Pre-Installation**: Validates environment before user configuration
2. **Progress Tracking**: Shows real-time validation status
3. **Error Handling**: Provides detailed troubleshooting guidance
4. **Continuation Logic**: Controls installation flow based on results

## Notes

- Critical component of SuiteCRM installation process
- Prevents installation failures due to environment issues
- Provides comprehensive system compatibility validation
- Generates detailed logs for troubleshooting support
- Supports multiple server environments and configurations 