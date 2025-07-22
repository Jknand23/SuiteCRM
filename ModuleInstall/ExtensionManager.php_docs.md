# ExtensionManager.php Documentation

## @fileoverview Extension compilation manager that merges custom extension files into compiled application and module extensions
## @package SuiteCRM\ModuleInstall
## @copyright 2004-2013 SugarCRM Inc., 2011-2021 SalesAgility Ltd.
## @license GNU Affero General Public License version 3

## Overview
The ExtensionManager class is responsible for compiling extension files from the `custom/Extension/` directory structure into their final target locations. It processes both application-level and module-specific extensions, merging multiple source files into single compiled extension files.

## Core Functionality

### Extension Compilation Process
The class implements a two-phase compilation system:

1. **Module Extensions**: Compiles files from `custom/Extension/modules/<module>/<extension>/` into `custom/modules/<module>/Ext/<extension>/<targetFile>`
2. **Application Extensions**: Compiles files from `custom/Extension/application/Ext/<extension>/` into `custom/application/Ext/<extension>/<targetFile>`

### Security and Authentication
- Validates entry point through `handleAuth()` method
- Ensures proper Sugar authentication before processing
- Dies with error message if invalid entry point detected

## Database Operations
No direct database operations are performed by this class.

## Internal API Calls

### Module System Integration
- `get_module_dir_list()`: Retrieves list of available modules for compilation
- `LoggerManager::getLogger()`: Obtains logger instance for debugging output
- `mkdir_recursive()`: Creates directory structure for compiled files
- `sugar_fopen()`: Uses Sugar's file opening wrapper for cross-platform compatibility

### File Processing Methods
- `compileExtensionFiles()`: Main entry point for extension compilation
- `compileModuleExtensions()`: Processes module-specific extension files
- `compileApplicationExtensions()`: Processes application-level extension files
- `shouldSkipFile()`: Determines if a file should be excluded from compilation
- `removePhpTagsFromString()`: Strips PHP opening/closing tags from included content
- `saveFile()`: Writes compiled content to target location
- `unlinkFile()`: Removes target files when no source files exist

### Language Extension Handling
Special handling for language extensions where target filename is prefixed with filter parameter if not already present.

### Override File Processing
Module extensions support override files (prefixed with `_override`) which are processed last to ensure they take precedence over regular extension files.

## External API Calls
None - this class operates entirely within the SuiteCRM file system.

## UI Functionality
No direct UI functionality - operates as a backend service during extension compilation.

### Compilation Output
Generates compiled files with header warning:
```php
<?php
// WARNING: The contents of this file are auto-generated
```

### Directory Structure Management
- Automatically creates target directory structure if missing
- Removes empty compiled files when no source extensions exist
- Maintains separation between application and module extensions

## Associated Tests
No specific test files identified for this class. Testing would typically involve:
- Verifying proper file compilation from multiple sources
- Testing override file precedence
- Validating directory creation and cleanup
- Confirming proper PHP tag removal

## Dependencies
- LoggerManager (for debugging output)
- Sugar file system utilities (mkdir_recursive, sugar_fopen)
- Global module directory functions

## Installation Integration
This extension manager integrates with:
- **install/performSetup.php**: Called during installation for system extension compilation
- **ModuleInstaller.php**: Works with module installation process for extension processing
- **PackageManager.php**: Coordinates with package management for extension deployment

## Configuration Support
- Supports filtering by file prefix (commonly used for language extensions)
- Option to compile only application extensions via `$applicationOnly` parameter
- Maintains compatibility with various extension types defined in extensions.php 