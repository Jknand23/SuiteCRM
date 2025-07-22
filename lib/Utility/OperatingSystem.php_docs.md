# OperatingSystem.php Documentation

/**
 * @fileoverview Operating system detection and path manipulation utility class providing cross-platform compatibility methods for SuiteCRM. Handles OS detection and path format conversion for Unix, Windows, macOS, and other systems.
 * @package SuiteCRM\Utility
 * @author SalesAgility Ltd.
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

The `OperatingSystem` class provides essential operating system detection and path manipulation functionality for SuiteCRM. It enables cross-platform compatibility by detecting the current OS and converting paths to appropriate formats for different operating systems.

## Class Structure

### Namespace
- **Namespace**: `SuiteCRM\Utility`
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility
- **Type**: Utility class with instance methods for OS operations

### Properties
The class contains no explicit properties and operates as a stateless utility.

## Internal API Calls

### Operating System Detection Methods

#### isOsBSD() Method
**Purpose**: Detects if the current operating system is BSD-based
**Return**: `bool` - True if OS is BSD, false otherwise
**Detection**: Uses `stristr(php_uname('s'), 'BSD')` for case-insensitive matching

#### isOsLinux() Method
**Purpose**: Detects if the current operating system is Linux
**Return**: `bool` - True if OS is Linux, false otherwise
**Detection**: Uses `stristr(php_uname('s'), 'Linux')` for case-insensitive matching

#### isOsMacOSX() Method
**Purpose**: Detects if the current operating system is macOS/Mac OS X
**Return**: `bool` - True if OS is macOS, false otherwise
**Detection**: Uses `stristr(php_uname('s'), 'Darwin')` to detect Darwin kernel (macOS foundation)

#### isOsSolaris() Method
**Purpose**: Detects if the current operating system is Solaris
**Return**: `bool` - True if OS is Solaris, false otherwise
**Detection**: Uses `stristr(php_uname('s'), 'Solaris')` for case-insensitive matching

#### isOsUnknown() Method
**Purpose**: Detects if the operating system is unknown/unidentified
**Return**: `bool` - True if OS is unknown, false otherwise
**Detection**: Checks if `php_uname('s')` returns exactly 'Unknown'

#### isOsWindows() Method
**Purpose**: Detects if the current operating system is Windows
**Return**: `bool` - True if OS is Windows, false otherwise
**Detection**: Uses `stristr(php_uname('s'), 'Windows')` for case-insensitive matching

### Path Manipulation Methods

#### toOsPath() Method
**Purpose**: Converts file paths to the format appropriate for the current operating system
**Parameters**:
- `$path` (string): The path to convert
- `$ds` (string): Directory separator to use (default: `DIRECTORY_SEPARATOR`)

**Return**: `string` - Path converted for the current OS

**Conversion Process**:
1. **Quote Removal**: Strips surrounding double quotes from path
2. **Unescape Spaces**: Converts `'\ '` back to regular spaces
3. **Unescape Tabs**: Converts `'\t'` back to regular tabs
4. **Separator Replacement**: Converts all `\` and `/` to the target separator
5. **Platform-Specific Escaping**:
   - **Unix/Linux/macOS** (`$ds === '/'`): Escapes spaces and tabs for shell compatibility
   - **Windows** (`$ds !== '/'`): Leaves spaces and tabs unescaped

**Examples**:
- Unix: `'/path/with spaces/file.txt'` → `'/path/with\ spaces/file.txt'`
- Windows: `'C:\path\with spaces\file.txt'` → `'C:\path\with spaces\file.txt'`

## External API Calls

### php_uname() Function
**Purpose**: PHP's built-in function to get system information
**Parameter**: `'s'` - Returns the operating system name
**Used in**: All OS detection methods
**Return Examples**:
- `'Linux'` - Linux systems
- `'Windows NT'` - Windows systems
- `'Darwin'` - macOS systems
- `'FreeBSD'` - BSD systems
- `'SunOS'` - Solaris systems

### stristr() Function
**Purpose**: Case-insensitive string search
**Used in**: OS detection methods for flexible string matching
**Benefit**: Handles variations in OS name formatting

## Utility Functions

### String Processing Functions
- `trim()` - Removes quotes and whitespace from paths
- `str_replace()` - Handles escape sequence conversion
- `preg_replace()` - Performs pattern-based separator replacement

### Constants Used
- `DIRECTORY_SEPARATOR` - PHP constant for OS-appropriate directory separator

## Usage Patterns

### Operating System Detection
```php
$os = new OperatingSystem();

if ($os->isOsWindows()) {
    // Windows-specific code
    $configPath = 'C:\Program Files\SuiteCRM\config.php';
} elseif ($os->isOsLinux() || $os->isOsMacOSX()) {
    // Unix-like systems
    $configPath = '/etc/suitecrm/config.php';
} elseif ($os->isOsBSD()) {
    // BSD-specific code
    $configPath = '/usr/local/etc/suitecrm/config.php';
}
```

### Path Conversion
```php
$os = new OperatingSystem();

// Convert to current OS format
$path = 'C:/Program Files/SuiteCRM/cache';
$osPath = $os->toOsPath($path);

// On Windows: 'C:\Program Files\SuiteCRM\cache'
// On Unix: 'C:/Program Files/SuiteCRM/cache' (with escaped spaces)
```

### Cross-Platform File Operations
```php
$os = new OperatingSystem();

$inputPath = '"C:/Program Files/SuiteCRM/upload/file with spaces.pdf"';
$cleanPath = $os->toOsPath($inputPath);

// Windows result: 'C:\Program Files\SuiteCRM\upload\file with spaces.pdf'
// Unix result: 'C:/Program Files/SuiteCRM/upload/file\ with\ spaces.pdf'
```

### Conditional Command Execution
```php
$os = new OperatingSystem();

if ($os->isOsWindows()) {
    $command = 'dir ' . $os->toOsPath($directory);
} else {
    $command = 'ls -la ' . $os->toOsPath($directory);
}
```

## Platform-Specific Behaviors

### Windows Handling
- **Separator**: Uses backslash (`\`) as directory separator
- **Escaping**: Does not escape spaces or special characters
- **Quotes**: Handles paths with double quotes
- **Drive Letters**: Preserves Windows drive letter notation

### Unix/Linux/macOS Handling
- **Separator**: Uses forward slash (`/`) as directory separator
- **Escaping**: Escapes spaces and tabs for shell safety
- **Case Sensitivity**: Respects case-sensitive file systems
- **Special Characters**: Proper escaping for shell commands

### BSD Systems
- **Detection**: Identifies FreeBSD, OpenBSD, NetBSD, etc.
- **Behavior**: Follows Unix-like path conventions
- **Compatibility**: Same escaping rules as Linux

## Security Considerations

### Path Safety
- **Quote Stripping**: Safely removes surrounding quotes
- **Escape Handling**: Proper handling of escaped characters
- **Injection Prevention**: Path sanitization helps prevent command injection

### Shell Command Safety
- **Space Escaping**: Automatically escapes spaces in Unix paths
- **Tab Escaping**: Handles tab characters in file names
- **Quote Removal**: Prevents double-quoting issues

## Performance Considerations

### Efficient Detection
- **Cached Results**: `php_uname('s')` result is cached by PHP
- **Single Call**: Each detection method makes only one system call
- **String Operations**: Uses efficient string functions for detection

### Path Processing
- **Single Pass**: Path conversion happens in a single method call
- **Regex Efficiency**: Uses optimized regex for separator replacement
- **Minimal Allocation**: Reuses variables where possible

## Error Handling

### Robust Detection
- **Unknown OS**: Gracefully handles unrecognized operating systems
- **Empty Results**: Safe handling of empty or null results from `php_uname()`
- **Case Variations**: Case-insensitive matching handles OS name variations

### Path Conversion Safety
- **Empty Paths**: Handles empty or null path inputs
- **Invalid Characters**: Processes paths with unusual characters safely
- **Encoding Issues**: Works with paths containing various character encodings

## Integration Points

### File System Operations
- **Upload Handling**: Used for processing uploaded file paths
- **Cache Paths**: Converts cache directory paths for OS compatibility
- **Log Files**: Ensures log file paths are correctly formatted

### Command Line Integration
- **Shell Commands**: Prepares paths for safe shell command execution
- **Backup Scripts**: Used in backup and maintenance scripts
- **Installation**: Path conversion during SuiteCRM installation

### Configuration Management
- **Config Paths**: Converts configuration file paths
- **Module Paths**: Handles module installation and upgrade paths
- **Template Paths**: Ensures template file paths work across platforms

## Cross-Platform Compatibility

### Supported Platforms
- ✅ **Windows** (Windows NT, Windows 10, Windows Server)
- ✅ **Linux** (All major distributions)
- ✅ **macOS** (Mac OS X, macOS)
- ✅ **BSD** (FreeBSD, OpenBSD, NetBSD)
- ✅ **Solaris** (Oracle Solaris, OpenSolaris)
- ⚠️ **Unknown Systems** (Basic path conversion only)

### Testing Considerations
- **Mock Testing**: `$ds` parameter allows testing path conversion with different separators
- **Platform Simulation**: Can simulate different OS environments for testing
- **Edge Cases**: Handles unusual path formats and character combinations 