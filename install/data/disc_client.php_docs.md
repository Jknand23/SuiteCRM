# disc_client.php Documentation

## @fileoverview
Configuration file that defines file and directory patterns to be ignored or excluded during disc client synchronization operations in SuiteCRM.

## @package SuiteCRM Installation Data
## @copyright SalesAgility Ltd.
## @license AGPL-3.0

## Overview

This file provides configuration arrays used by the disc client system to determine which files and directories should be excluded from synchronization operations. It defines regular expression patterns that help filter out temporary files, configuration files, and other system files that should not be synchronized between environments.

## Core Functionality

### File Exclusion Configuration
The file defines two main configuration arrays:
- **$disc_client_ignore**: Array of regexp patterns for files/directories to ignore during sync
- **$disc_client_no_sync**: Array for additional sync exclusion rules (currently empty)

### Pattern Definitions
The ignore patterns are organized into logical categories:

#### Directory Exclusions
- `\\./cache/.*` - Excludes all cache directory contents
- `\\./examples/.*` - Excludes example files directory

#### File Type Exclusions
- `\\.*config\\.php\$` - Excludes all config.php files
- `\\.*sugarcrm\\.log\\.*` - Excludes SugarCRM log files
- `\\.*sync\\.log\\.*` - Excludes synchronization log files
- `\\.htaccess\$` - Excludes Apache configuration files
- `\\.*\\.tmp\$` - Excludes temporary files
- `\\.*\\.bak\$` - Excludes backup files
- `\\.*\\.zip\$` - Excludes ZIP archive files

## Integration Points

### Disc Client System
This configuration integrates with:
- **Synchronization Engine**: Provides filter rules for file sync operations
- **Backup Systems**: Helps exclude non-essential files from backups
- **Development Tools**: Excludes temporary and cache files from version control-like operations

### Pattern Matching System
- Uses regular expression syntax with escaped special characters
- Patterns must not contain '#' characters (used as delimiter)
- Supports both file and directory matching patterns

## Security Considerations

### Sensitive File Protection
The configuration helps protect:
- Configuration files containing database credentials
- Log files that may contain sensitive information
- Temporary files that might contain user data

### System Integrity
- Prevents accidental synchronization of cache files
- Excludes system-generated files that could cause conflicts
- Protects against overwriting environment-specific configurations

## Usage Context

### Installation Process
During installation, this configuration:
- Helps identify which files to include in system packages
- Prevents inclusion of environment-specific files
- Ensures clean deployment to target systems

### Maintenance Operations
For ongoing maintenance:
- Supports backup operations by excluding unnecessary files
- Helps with system migration by filtering relevant files
- Reduces synchronization overhead by skipping cache files

## Development Notes

### Pattern Syntax
- Uses PCRE regular expression syntax
- Special characters must be escaped with double backslashes
- Patterns are applied to full file paths including directory structure

### Configuration Management
- Patterns should be thoroughly tested before deployment
- New exclusion patterns should consider impact on system functionality
- Regular review of patterns helps maintain synchronization efficiency

### Extension Points
- Additional ignore patterns can be added to $disc_client_ignore array
- $disc_client_no_sync array available for future exclusion rules
- Configuration can be extended for specific deployment scenarios 