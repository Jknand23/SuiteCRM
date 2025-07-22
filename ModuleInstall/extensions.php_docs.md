# extensions.php Documentation

## @fileoverview Extension type definitions and configuration mapping for SuiteCRM module installation system
## @package SuiteCRM\ModuleInstall
## @copyright 2004-2013 SugarCRM Inc., 2011-2021 SalesAgility Ltd.
## @license GNU Affero General Public License version 3

## Overview
This file defines the master array of extension types supported by the SuiteCRM module installation system. Each extension type maps to specific directories, target files, and compilation settings used by the ExtensionManager and ModuleInstaller classes.

## Core Data Structure

### Extension Configuration Array
The `$extensions` array contains comprehensive mapping for all supported extension types:

```php
$extensions = array(
    "extension_key" => array(
        "section" => "manifest_section_name",
        "extdir" => "source_directory_name", 
        "file" => "target_filename",
        "module" => "specific_module_or_application"
    )
);
```

## Database Operations
No direct database operations are performed by this configuration file.

## Internal API Calls

### Extension Type Definitions

#### Action and View Mappings
- **actionviewmap**: Maps action-view combinations to specific handlers
- **actionfilemap**: Maps actions to file locations  
- **actionremap**: Remaps action names to different implementations

#### Core System Extensions
- **administration**: Administration panel extensions (Administration module)
- **entrypoints**: Custom entry point registrations (application-level)
- **exts**: General extension registry (application-level)
- **modules**: Module bean definitions and registration (application-level)

#### Security and Access Control
- **file_access**: File access control mapping
- **externaloauthproviders**: External OAuth provider configurations (application-level)

#### UI and Display Extensions
- **languages**: Language file extensions (custom rebuild process)
- **layoutdefs**: Layout definition extensions for forms and views
- **menus**: Menu structure extensions
- **userpage**: User page customizations (Users module)

#### System Integration
- **links**: Global link definitions (application-level)
- **logichooks**: Logic hook implementations
- **schedulers**: Scheduled task definitions (Schedulers module)
- **utils**: Utility function extensions (application-level)
- **vardefs**: Variable definition extensions for data models

#### Advanced Features
- **jsgroupings**: JavaScript file grouping configurations
- **aow**: Advanced OpenWorkflow action definitions (AOW_Actions module)
- **pdf**: PDF generation and template extensions (application-level)

### Module-Specific vs Application-Level
Extensions are categorized as either:
- **Module-specific**: Apply to individual modules (most extensions)
- **Application-level**: Apply globally across the system (marked with `"module" => "application"`)

## External API Calls
None - this is a pure configuration file.

## UI Functionality
No direct UI functionality - provides configuration data for extension compilation system.

### Extension Processing Flow
1. ModuleInstaller reads extension definitions from this array
2. ExtensionManager uses mappings to determine source and target locations
3. Files are compiled from `custom/Extension/` to `custom/` locations
4. Target files are included by SuiteCRM core during runtime

### Custom Extension Support
The file includes dynamic loading of additional extensions:
```php
if (file_exists("custom/application/Ext/Extensions/extensions.ext.php")) {
    include("custom/application/Ext/Extensions/extensions.ext.php");
}
```

This allows custom packages to define new extension types beyond the default set.

## Associated Tests
No specific test files identified for this configuration. Testing would verify:
- Proper extension type registration
- Correct mapping of source to target locations
- Custom extension loading functionality
- Integration with ExtensionManager compilation process

## Dependencies
- Used by ModuleInstaller class for installation processing
- Used by ExtensionManager class for compilation operations
- May be extended by custom extension definitions

## Security Considerations
- Validates entry point before loading
- Extension definitions control file access patterns
- Proper validation required when adding custom extension types

## Integration Points
This configuration file is central to the SuiteCRM extension system and integrates with:
- Module installation and upgrade processes
- Extension compilation system
- Runtime extension loading
- Package management system 