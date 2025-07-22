# Paths.php Documentation

/**
 * @fileoverview Path utility class providing standardized access to SuiteCRM directory locations
 * @package SuiteCRM\Utility
 * @namespace SuiteCRM\Utility
 * @copyright SugarCRM Inc. (2004-2013), SalesAgility Ltd. (2011-2018)
 * @license AGPL-3.0
 */

## Overview

The `Paths` class provides a centralized utility for accessing standard SuiteCRM directory paths. It ensures consistent path resolution across the application by maintaining all standard locations in one place, reducing hardcoded paths throughout the codebase.

## Class Definition

### Paths
- **Namespace**: `SuiteCRM\Utility`
- **Type**: Path utility class
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility
- **Purpose**: Centralizes path management for SuiteCRM directories

## Methods

### getContainersFilePath()
```php
public function getContainersFilePath()
```

**Purpose**: Returns the full path to the API containers configuration file

**Returns**: `string` - Path to `lib/API/core/containers.php`

**Usage**: Used by the API framework for dependency injection container configuration

### getLibraryPath()
```php
public function getLibraryPath()
```

**Purpose**: Returns the absolute path to the lib directory

**Returns**: `string` - Resolved absolute path to the lib directory

**Implementation**: Uses `realpath(dirname(__DIR__))` to resolve from current class location

### getCustomLibraryPath()
```php
public function getCustomLibraryPath()
```

**Purpose**: Returns the path to the custom lib directory for customizations

**Returns**: `string` - Resolved path to custom/lib directory

**Logic**: 
1. Gets project path and library path
2. Replaces project path with custom path structure
3. Returns resolved custom library path

**Customization**: Follows SuiteCRM's custom directory structure pattern

### getProjectPath()
```php
public function getProjectPath()
```

**Purpose**: Returns the absolute path to the SuiteCRM project root

**Returns**: `string` - Resolved absolute path to project root directory

**Implementation**: Uses `realpath(dirname($this->getLibraryPath()))` to get parent of lib directory

## Internal API Calls

### PHP Path Functions
- **Function**: `realpath()` - Resolves absolute paths and follows symlinks
- **Function**: `dirname()` - Gets parent directory of given path
- **Function**: `str_replace()` - String manipulation for custom path construction
- **Constant**: `DIRECTORY_SEPARATOR` - Platform-specific directory separator

## Integration Points

### API Framework
- **Containers**: Provides path to dependency injection container configuration
- **Core API**: Used by API library for file location resolution

### Customization Framework
- **Custom Directory**: Supports SuiteCRM's custom directory structure
- **Override Paths**: Enables custom library implementations

### Development Environment
- **Path Resolution**: Handles different deployment scenarios
- **Cross-Platform**: Works with different operating systems

## Usage Examples

### Basic Path Access
```php
use SuiteCRM\Utility\Paths;

$paths = new Paths();

// Get project root
$projectRoot = $paths->getProjectPath();
// Result: /var/www/suitecrm

// Get library directory
$libPath = $paths->getLibraryPath();
// Result: /var/www/suitecrm/lib

// Get custom library directory
$customLibPath = $paths->getCustomLibraryPath();
// Result: /var/www/suitecrm/custom/lib
```

### API Container Configuration
```php
use SuiteCRM\Utility\Paths;

$paths = new Paths();
$containerFile = $paths->getContainersFilePath();

// Load API dependency injection configuration
require_once $containerFile;
```

### Custom Implementation Loading
```php
use SuiteCRM\Utility\Paths;

$paths = new Paths();
$customLibPath = $paths->getCustomLibraryPath();

// Check for custom implementation
$customFile = $customLibPath . '/MyCustomClass.php';
if (file_exists($customFile)) {
    require_once $customFile;
}
```

### File Path Construction
```php
use SuiteCRM\Utility\Paths;

$paths = new Paths();

// Construct specific file paths
$configFile = $paths->getProjectPath() . '/config.php';
$apiCore = $paths->getLibraryPath() . '/API/Core/SomeClass.php';
$customExtension = $paths->getCustomLibraryPath() . '/Extensions/MyExtension.php';
```

## Path Structure

### Standard Directory Layout
```
/project-root/              # getProjectPath()
├── lib/                    # getLibraryPath()
│   ├── API/
│   │   └── core/
│   │       └── containers.php  # getContainersFilePath()
│   ├── Utility/
│   └── Search/
└── custom/
    └── lib/                # getCustomLibraryPath()
        └── Extensions/
```

### Customization Support
- Custom library directory mirrors main lib structure
- Allows for complete library class overrides
- Supports modular customization approach

## Cross-Platform Compatibility

### Directory Separators
- Uses `DIRECTORY_SEPARATOR` constant for platform compatibility
- Handles Windows and Unix-style paths correctly
- Resolves symlinks and relative paths

### Path Resolution
- All paths use `realpath()` for absolute resolution
- Handles various deployment scenarios (symlinks, moved directories)
- Works consistently across different server configurations

## Performance Considerations

### Path Caching
- Methods can be called multiple times efficiently
- `realpath()` results are computed on each call
- Consider caching results in performance-critical applications

### Lazy Resolution
- Paths are resolved only when requested
- No upfront directory scanning or validation
- Minimal memory footprint

## Error Handling

### Invalid Paths
- `realpath()` returns false for non-existent paths
- Methods may return false if directories don't exist
- Calling code should validate returned paths before use

### Permission Issues
- Path resolution depends on file system permissions
- May fail silently if directories are not readable
- Consider error checking in production environments

## Testing Integration

### Unit Testing
- Class is designed for easy mocking in tests
- Methods are pure functions for predictable testing
- Path resolution can be tested across different environments

### Test Environment Setup
```php
// Example test usage
class PathsTest extends PHPUnit\Framework\TestCase
{
    public function testGetProjectPath()
    {
        $paths = new Paths();
        $projectPath = $paths->getProjectPath();
        
        $this->assertNotEmpty($projectPath);
        $this->assertTrue(is_dir($projectPath));
    }
}
```

## Security Considerations

### Path Traversal Prevention
- Uses `realpath()` to resolve and validate paths
- Prevents directory traversal attacks
- Returns absolute paths only

### File System Access
- Does not perform file system writes
- Read-only path resolution operations
- Safe for use in security-sensitive contexts

## Best Practices

- Use this class instead of hardcoded paths throughout the application
- Cache path results in long-running processes if performance is critical
- Always validate returned paths before file operations
- Consider using dependency injection to provide Paths instance to classes that need it 