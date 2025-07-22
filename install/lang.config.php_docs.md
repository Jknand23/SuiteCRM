# lang.config.php Documentation

## @fileoverview
Simple configuration file that defines additional language packs available for SuiteCRM installation.

## @package SuiteCRM Installation Configuration
## @copyright SalesAgility Ltd.
## @license AGPL-3.0

## Overview

This file provides a minimal configuration structure for defining additional language packs that can be installed with SuiteCRM. It serves as a placeholder for language pack extensions and custom localization configurations.

## Core Functionality

### Language Configuration Array
The file defines a single configuration array:
```php
$config['languages'] = array();
```

This array is intended to hold additional language pack definitions beyond the default English language pack.

## Configuration Structure

### Language Pack Definition
When populated, the array would contain language pack definitions in the format:
- **Language Code**: ISO language/locale codes (e.g., 'es_ES', 'fr_FR')
- **Language Name**: Human-readable language names
- **Pack Location**: Paths to language pack files

## Integration Points

### Installation System
This configuration integrates with:
- **install_utils.php**: Language pack installation functions
- **welcome.php**: Language selection interface
- **Installation Wizard**: Multi-language installation support

### Language Pack System
- Works with SuiteCRM's language management framework
- Supports dynamic language pack installation
- Enables custom localization configurations

## Usage Context

### Installation Process
During installation:
- System checks for additional language configurations
- Provides language options beyond default English
- Supports custom language pack installation

### Customization
- Custom language packs can be defined in this array
- Third-party language extensions reference this configuration
- Enables regional localization support

## Development Notes

### Extension Support
- Additional language packs can be registered by populating the array
- Configuration supports standard SuiteCRM language pack structure
- Custom localizations should follow established naming conventions

### Maintenance
- File serves as extension point for language pack management
- Configuration changes require proper validation
- Language pack installation should update this configuration accordingly 