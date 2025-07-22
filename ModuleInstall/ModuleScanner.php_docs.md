# ModuleScanner.php Documentation

## @fileoverview Security scanner for module installation packages that validates files against blacklisted functions and dangerous operations
## @package SuiteCRM\ModuleInstall
## @copyright 2004-2013 SugarCRM Inc., 2011-2018 SalesAgility Ltd.
## @license GNU Affero General Public License version 3

## Overview
The ModuleScanner class provides comprehensive security scanning for SuiteCRM module installation packages. It validates files against configurable blacklists of functions, classes, and methods to prevent malicious code execution during module installation.

## Core Security Features

### Multi-Level Validation System
1. **File Extension Validation**: Ensures only approved file types are processed
2. **PHP Code Detection**: Identifies PHP content in files regardless of extension
3. **Token-Based Analysis**: Uses PHP tokenizer for deep code analysis
4. **Function Blacklisting**: Blocks dangerous PHP functions
5. **Class and Method Restrictions**: Prevents instantiation of restricted classes

### Configuration Management
The scanner supports flexible configuration through multiple sources:
- Global `$GLOBALS['sugar_config']['moduleInstaller']` settings
- PHP constants for specific blacklist overrides
- Runtime configuration options
- Exemption lists for legitimate use cases

## Database Operations
No direct database operations are performed by this class.

## Internal API Calls

### Core Scanning Methods
- `scanPackage()`: Main entry point for package validation
- `scanDir()`: Recursively scans directory structures
- `scanFile()`: Analyzes individual files for security issues
- `scanManifest()`: Validates installation manifest files
- `isPHPFile()`: Detects PHP content in files
- `isValidExtension()`: Validates file extensions against whitelist

### Security Validation
- `isConfigFile()`: Prevents overriding core configuration files
- `sugarFileExists()`: Checks if files are part of core SuiteCRM installation
- `normalizePath()`: Validates and normalizes file paths
- `lockConfig()`: Temporarily locks configuration during scanning

### Blacklist Management
The scanner maintains several blacklists:

#### Function Blacklist
Dangerous PHP functions including:
- File system operations (`unlink`, `rmdir`, `chmod`)
- Command execution (`exec`, `system`, `shell_exec`, `passthru`)
- Dynamic code execution (`eval`, `create_function`)
- Process control (`proc_open`, `popen`)
- Callback functions (`call_user_func`, `array_map`)

#### Class Blacklist
Restricted classes including:
- Reflection classes (`ReflectionClass`, `ReflectionMethod`)
- Archive manipulation (`ZipArchive`, `PclZip`)
- File system objects (`SplFileInfo`, `SplFileObject`)

#### Method Blacklist
Specific method restrictions with class context awareness.

### Token Analysis Process
1. **Tokenization**: Converts PHP code to tokens using `token_get_all()`
2. **Context Analysis**: Tracks token relationships and context
3. **Function Detection**: Identifies function calls and method invocations
4. **Security Validation**: Checks against blacklists and security rules
5. **Issue Reporting**: Collects and reports security violations

## External API Calls
None - this class operates entirely within the SuiteCRM environment.

## UI Functionality

### Issue Reporting
- `getIssues()`: Returns array of discovered security issues
- `hasIssues()`: Boolean check for any security violations
- `displayIssues()`: Outputs formatted security issue reports
- `printToWiki()`: Debug method for displaying configuration

### Security Issue Types
- Invalid file extensions
- Blacklisted function usage
- Dangerous class instantiation
- Core file override attempts
- Malformed PHP code
- Path traversal attempts

### Progress Integration
Integrates with SuiteCRM's installation progress system for user feedback during package validation.

## Configuration Options

### Configurable Security Settings
- `MODULE_INSTALLER_PACKAGE_SCAN_BLACK_LIST_EXEMPT`: Function exemptions
- `MODULE_INSTALLER_PACKAGE_SCAN_BLACK_LIST`: Additional blacklisted functions
- `MODULE_INSTALLER_PACKAGE_SCAN_CLASS_BLACK_LIST_EXEMPT`: Class exemptions
- `MODULE_INSTALLER_PACKAGE_SCAN_CLASS_BLACK_LIST`: Additional blacklisted classes
- `MODULE_INSTALLER_PACKAGE_SCAN_VALID_EXT`: Additional allowed extensions
- `MODULE_INSTALLER_PACKAGE_SCAN_METHOD_LIST`: Method-specific blacklists

### Override Controls
- `MODULE_INSTALLER_DISABLE_CONFIG_OVERRIDE`: Disables config-based overrides
- `MODULE_INSTALLER_DISABLE_DEFINE_OVERRIDE`: Disables constant-based overrides
- `MODULE_INSTALLER_PACKAGE_SCAN`: Enables/disables scanning globally

## Associated Tests
No specific test files identified for this class. Testing would cover:
- Function blacklist validation
- Class restriction enforcement
- File extension filtering
- Path normalization security
- Configuration override handling
- Token analysis accuracy

## Dependencies
- PHP Tokenizer extension for code analysis
- SuiteCRM logging system
- Translation system for error messages
- File system utilities for scanning operations

## Security Considerations

### Bypass Prevention
- Multiple validation layers prevent common bypass techniques
- Token-based analysis catches obfuscated code
- Path normalization prevents directory traversal
- Configuration locking prevents runtime manipulation

### Performance Optimization
- Static caching of MD5 hashes for core file verification
- Efficient tokenizer usage for large files
- Recursive scanning with depth management
- Configurable scanning scope

## Integration Points
This scanner integrates with:
- Module installation workflow
- Package management system
- Security audit logging
- Installation progress tracking
- Error reporting system 