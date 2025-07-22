# dir_inc.php Documentation

/**
 * @fileoverview Directory manipulation and file system utilities for SuiteCRM
 * @package SuiteCRM
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `dir_inc.php` file provides essential directory manipulation and file system utilities for SuiteCRM. It contains functions for recursive directory operations, file discovery, and path management that are used throughout the application for file handling operations.

## Dependencies

- `include/SugarCache/SugarCache.php` - For PHP file cache management
- Global `$GLOBALS['log']` - For error logging

## Core Functions

### Directory Operations

#### copy_recursive($source, $dest)
Recursively copies files and directories from source to destination.
- **Parameters:**
  - `$source` (string) - Source file or directory path
  - `$dest` (string) - Destination path
- **Returns:** boolean - Success status
- **Features:**
  - Handles both files and directories
  - Automatically cleans PHP file cache for copied .php files
  - Creates destination directories as needed

#### mkdir_recursive($path, $check_is_parent_dir = false)
Creates directory structure recursively, handling cross-platform path differences.
- **Parameters:**
  - `$path` (string) - Directory path to create
  - `$check_is_parent_dir` (boolean) - Optional parent directory validation
- **Returns:** boolean - Success status
- **Features:**
  - Cross-platform Windows/Unix path handling
  - UNC path support for Windows
  - Absolute and relative path support
  - Current working directory awareness

#### rmdir_recursive($path)
Recursively removes files and directories.
- **Parameters:**
  - `$path` (string) - Path to remove
- **Returns:** boolean - Success status
- **Features:**
  - Safely handles files and directories
  - Depth-first removal for proper cleanup
  - Error logging for failed removals

### File Discovery Functions

#### findTextFiles($the_dir, $the_array)
Discovers text files in a directory tree based on MIME type detection.
- **Parameters:**
  - `$the_dir` (string) - Directory to search
  - `$the_array` (array) - Array to accumulate results
- **Returns:** array - Array of text file paths
- **Supported MIME Types:**
  - `text/html` - HTML files
  - `text/plain` - Plain text files
- **Skipped Types:** PDF, ZIP, images, RTF

#### findAllFiles($the_dir, $the_array, $include_dirs=false, $ext='', $exclude_dir='')
Comprehensive file discovery with filtering options.
- **Parameters:**
  - `$the_dir` (string) - Directory to search
  - `$the_array` (array) - Array to accumulate results
  - `$include_dirs` (boolean) - Include directory paths in results
  - `$ext` (string) - File extension filter (regex pattern)
  - `$exclude_dir` (string|array) - Directories to exclude
- **Returns:** array - Sorted array of file paths
- **Features:**
  - Recursive directory traversal
  - Extension-based filtering
  - Directory exclusion support
  - Results sorted in reverse order

#### findAllFilesRelative($the_dir, $the_array)
Finds files relative to a specific directory by changing working directory.
- **Parameters:**
  - `$the_dir` (string) - Target directory
  - `$the_array` (array) - Array to accumulate results
- **Returns:** array - Array of relative file paths
- **Features:**
  - Preserves original working directory
  - Uses relative path notation

#### findAllTouchedFiles($the_dir, $the_array, $date_modified, $filter='')
Discovers files modified after a specific date.
- **Parameters:**
  - `$the_dir` (string) - Directory to search
  - `$the_array` (array) - Array to accumulate results
  - `$date_modified` (string) - Date threshold for modification check
  - `$filter` (string) - Optional regex filter for filenames
- **Returns:** array - Array of modified file paths
- **Features:**
  - Timezone-aware date comparison
  - Optional filename filtering
  - Recursive directory traversal

### Debugging Utilities

#### getBacktraceString()
Captures PHP debug backtrace as a string for error reporting.
- **Returns:** string - Formatted backtrace output
- **Usage:** Error logging and debugging file operations

## Integration Points

### SugarCache Integration
- Automatically cleans PHP file cache when copying .php files
- Ensures cache consistency after file operations

### Logging Integration
- Uses global `$GLOBALS['log']` for error reporting
- Provides detailed error messages for debugging
- Includes backtrace information for complex errors

### Cross-Platform Compatibility
- Windows UNC path support
- Case-insensitive path comparison on Windows
- Proper directory separator handling

## Common Usage Patterns

### Module Installation
```php
// Copy module files during installation
copy_recursive('temp/module', 'modules/CustomModule');

// Create required directory structure
mkdir_recursive('custom/modules/CustomModule/views');
```

### Backup Operations
```php
// Find all PHP files for backup
$phpFiles = findAllFiles('modules', array(), false, '\.php$');

// Find recently modified files
$recentFiles = findAllTouchedFiles('custom', array(), '2023-01-01 00:00:00');
```

### Cleanup Operations
```php
// Remove temporary directories
rmdir_recursive('temp/upgrade');

// Find text files for processing
$textFiles = findTextFiles('custom/templates', array());
```

## Error Handling

- All functions include error checking and logging
- Returns boolean false on failure
- Provides detailed error messages through global logger
- Includes backtrace information for debugging

## Security Considerations

- Validates directory existence before operations
- Proper path cleaning and validation
- Safe handling of symbolic links and special files
- Prevents operations outside intended directories

## Performance Notes

- Uses PHP's native directory functions for optimal performance
- Implements depth-first traversal for memory efficiency
- Sorts results for consistent output
- Includes early termination for error conditions 