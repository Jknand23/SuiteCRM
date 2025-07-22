# ModuleInstaller.php Documentation

## @fileoverview Core module installation engine that manages the complete lifecycle of SuiteCRM module packages
## @package SuiteCRM\ModuleInstall
## @copyright 2004-2013 SugarCRM Inc., 2011-2018 SalesAgility Ltd.
## @license GNU Affero General Public License version 3

## Overview
The ModuleInstaller class is the central engine for installing, upgrading, and uninstalling SuiteCRM modules. It processes installation manifests, manages file operations, handles database changes, and integrates new functionality into the existing system architecture.

## Core Installation Process

### Installation Workflow
The installation follows a systematic multi-phase approach:

1. **Validation Phase**: Security scanning and manifest validation
2. **Pre-execution**: Custom pre-installation scripts
3. **File Operations**: Copying files and creating directory structures
4. **Extension Installation**: Processing various extension types
5. **Database Operations**: Custom fields, relationships, and schema changes
6. **System Integration**: Rebuilding caches and updating configurations
7. **Post-execution**: Custom post-installation scripts

### Manifest Processing
Supports both standard installation and upgrade manifests:
- Standard: `$installdefs` array with installation instructions
- Upgrade: `$upgrade_manifest` with version-specific upgrade paths
- Validation of manifest structure and required components

## Database Operations

### Custom Field Management
- `install_custom_fields()`: Processes custom field definitions
- Integration with SuiteCRM's custom field system
- Automatic database schema updates for new fields

### Relationship Management
- `install_relationships()`: Creates database relationships between modules
- `rebuild_relationships()`: Reconstructs relationship metadata
- `createTableParams()`: Builds relationship table structures
- `createRelationshipMeta()`: Updates relationship registry

### System Database Updates
- Audit table rebuilding for new modules
- Index repair and optimization
- Database integrity verification

## Internal API Calls

### Core Installation Methods
- `install()`: Main installation orchestrator
- `uninstall()`: Complete module removal process
- `upgrade()`: Module version upgrade handling
- `pre_execute()`: Executes custom pre-installation scripts
- `post_execute()`: Executes custom post-installation scripts

### File Management Operations
- `install_copy()`: Processes file copying operations with backup support
- `copy_path()`: Handles individual file and directory copying
- `uninstall_copy()`: Removes installed files and restores backups
- `uninstall_new_files()`: Removes files added during installation

### Extension Processing
- `installExt()`: Installs extension files to appropriate directories
- `uninstallExt()`: Removes extension files during uninstallation
- `rebuildExt()`: Rebuilds compiled extension files
- `disableExt()`: Temporarily disables extensions
- `enableExt()`: Re-enables disabled extensions

### System Integration
- `install_beans()`: Registers new modules in the system
- `install_user_prefs()`: Configures user preferences for new modules
- `merge_files()`: Compiles extension files into system files
- `rebuild_all()`: Comprehensive system rebuild after installation

### Module Management
- `install_images()`: Processes module images and icons
- `install_dashlets()`: Installs dashboard components
- `install_connectors()`: Handles external system connectors
- `install_layoutfields()`: Processes custom layout field definitions

## Installation System Integration
The ModuleInstaller coordinates with the broader installation framework:
- **ExtensionManager.php**: Handles extension compilation after module installation
- **install/install_utils.php**: Utilizes installation utilities for system operations
- **PackageManager/**: Coordinates with package management for module distribution
- **Database Installation**: Integrates with SuiteCRM's database setup and schema management

## External API Calls

### Progress Bar Integration
- Integration with SuiteCRM's installation progress tracking
- Real-time feedback during long-running operations
- Error reporting through progress system

### Cache Management
- Clear various cache systems after installation
- Rebuild JavaScript and language file caches
- Reset Sugar cache and VarDef cache

## UI Functionality

### Installation Interface Integration
- Silent installation mode support
- Progress tracking and user feedback
- Error display and logging integration
- Installation log display functionality

### User Preference Management
- Automatic tab configuration for new modules
- User-specific module visibility settings
- Preference inheritance and defaults

### Admin Panel Integration
- Module listing and management interface
- Installation status tracking
- Uninstallation capability through admin interface

## Extension Type Support

### Supported Extension Categories
Based on extensions.php configuration:
- **vardefs**: Variable definitions for data models
- **layoutdefs**: Form and view layout definitions
- **languages**: Internationalization and language support
- **logichooks**: Event-driven functionality hooks
- **menus**: Navigation and menu structure
- **administration**: Admin panel extensions
- **entrypoints**: Custom application entry points

### Module vs Application Extensions
- Module-specific extensions apply to individual modules
- Application-level extensions affect the entire system
- Proper segregation and compilation of extension types

## Security Features

### Package Validation
- Integration with ModuleScanner for security validation
- Manifest validation and sanitization
- File operation safety checks

### Backup and Restore
- Automatic backup creation before file operations
- Restore capability for failed installations
- File integrity verification using MD5 checksums

### Permission Management
- Directory permission handling
- File ownership considerations
- Security context preservation

## Associated Tests
No specific test files identified for this class. Testing would cover:
- Installation workflow validation
- Extension processing accuracy
- Database operation integrity
- Backup and restore functionality
- Security validation integration
- Multi-module installation handling

## Dependencies
- ModuleScanner (for security validation)
- ExtensionManager (for extension compilation)
- DBManagerFactory (for database operations)
- VardefManager (for metadata management)
- BeanFactory (for module registration)
- RepairAndClear (for system maintenance)
- SugarCache (for cache management)

## Performance Considerations

### Batch Processing
- Efficient handling of multiple file operations
- Optimized database query execution
- Minimal system rebuilds during installation

### Memory Management
- Stream-based file operations for large packages
- Progressive processing to avoid memory exhaustion
- Cleanup of temporary resources

## Error Handling

### Installation Failure Recovery
- Automatic rollback on critical errors
- Partial installation state recovery
- Detailed error logging and reporting

### Validation and Safety Checks
- Pre-installation validation of all components
- Dependency verification before installation
- Conflict detection and resolution

## Integration Points
This installer integrates with:
- Package management system
- Module loading and registration
- Extension compilation system
- Database schema management
- User interface customization
- Security and audit systems 