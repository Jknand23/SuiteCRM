# UpgradeWizard.php Documentation

## @fileoverview
Main upgrade wizard interface for SuiteCRM package management, handling upload, validation, and installation of modules, themes, language packs, and patches through a web-based interface.

## @package Administration
## @copyright SalesAgility Ltd
## @license GNU AGPL v3

## Overview
UpgradeWizard.php provides the primary user interface for managing upgrades and module installations in SuiteCRM. It handles file uploads, manifest validation, security scanning, and coordinates with other upgrade wizard components to facilitate safe package installation.

## Database Operations

### Upload History Management
- Integrates with `UpgradeHistory` class for tracking installation history
- Validates dependencies against previously installed packages using `$uh->checkDependencies()`
- Maintains upgrade tracking through MD5 hash verification with `$uh->findByMd5()`

### Installation Tracking
- Records package installations for future reference and dependency checking
- Supports upgrade path detection through version comparison
- Links to upgrade history for rollback capabilities

## Internal API Calls

### Core Upgrade Components
- **UpgradeWizardCommon.php**: Provides common utility functions for manifest extraction, file type detection, and upgrade operations
- **PackageManagerDisplay**: Renders package listing interface and upload forms
- **ModuleScanner**: Security scanning of uploaded packages for malicious code

### File Upload Processing
- **UploadFile class**: Handles secure file upload validation and storage
- **File validation**: Ensures only .zip files are accepted with proper extension checking
- **Temporary file management**: Creates and manages temporary extraction directories

### Manifest Processing
- **extractManifest()**: Extracts and validates manifest.php from uploaded packages
- **validate_manifest()**: Comprehensive manifest validation through PackageManager
- **MSLoadManifest()**: Loads manifest and installation definitions

### Security Integration
- **ModuleScanner::scanFile()**: Scans manifest files for security issues
- **ModuleScanner::checkConfig()**: Validates configuration safety
- **Security validation**: Prevents execution of malicious code during installation

## External API Calls

### Package Download Integration
- **PackageManager::download()**: Downloads packages from external repositories
- **Remote package handling**: Supports loading packages from external directories
- **Release ID processing**: Manages packages from remote release systems

### File System Operations
- **File extraction**: Uses unzip utilities for package extraction
- **Directory creation**: Creates appropriate upgrade directory structure
- **File movement**: Safely moves uploaded files to permanent storage locations

## UI Functionality

### Upload Interface
- **Multi-mode operation**: Supports both Module Loader and Upgrade Wizard views
- **File upload form**: Secure file upload with JavaScript validation
- **Progress indication**: Upload status and validation feedback

### Package Management Display
- **Package listing**: Shows available packages with metadata
- **Icon display**: Renders package icons and type indicators
- **Action buttons**: Install, delete, and manage package operations

### View-specific Features
- **Module Loader view**: Handles modules, themes, and language packs
- **Upgrade Wizard view**: Manages patches and full system upgrades
- **Common ML directory**: Optional shared module loading directory support

### User Feedback
- **Status messages**: Success/error message display through `$GLOBALS['ML_STATUS_MESSAGE']`
- **Validation feedback**: Real-time validation error reporting
- **Progress tracking**: Visual feedback during upload and validation processes

### Upload Validation
- **File size checking**: Validates against `upload_max_filesize` PHP setting
- **Minimum size enforcement**: Requires 6MB minimum upload limit
- **Type validation**: Ensures proper package type for selected wizard mode

### Security Features
- **Path traversal protection**: Prevents directory traversal attacks in file paths
- **PHAR protocol blocking**: Blocks PHAR protocol usage for security
- **Extension validation**: Strict .zip extension validation
- **Manifest scanning**: Security scan of all package contents

## Associated Tests

### Security Testing
- **Package validation**: Tests for malicious code detection in uploaded packages
- **File path validation**: Ensures secure file handling and path resolution
- **Upload security**: Validates upload file handling and storage security

### Functional Testing
- **Upload process**: Tests complete upload workflow from file selection to storage
- **Manifest processing**: Validates manifest extraction and parsing functionality
- **Type detection**: Tests package type detection and view compatibility

### Integration Testing
- **Module Scanner integration**: Tests security scanning integration
- **Package Manager integration**: Validates package display and management features
- **Upgrade History integration**: Tests installation tracking and dependency checking

## Key Functions

### unlinkTempFiles()
Cleanup function that removes temporary files after upload processing, including both temporary upload files and cached copies.

### File Upload Handling
Comprehensive upload processing that validates file types, checks for security issues, moves files to appropriate directories, and extracts metadata for display.

### Package Type Validation
Enforces compatibility between package types and wizard modes:
- Module Loader: Accepts modules, themes, and language packs
- Upgrade Wizard: Accepts only patches
- Prevents installation of incompatible package types

### Error Handling
- **Upload validation errors**: Comprehensive error checking for file uploads
- **Manifest validation errors**: Detailed error reporting for invalid manifests
- **Security scan failures**: Clear feedback when security issues are detected
- **Permission errors**: Graceful handling of file system permission issues

## Configuration Dependencies
- **upload_max_filesize**: PHP configuration for maximum upload size
- **temporary directories**: Requires writable cache directory for extraction
- **Package directories**: Creates and manages upgrade directory structure
- **Common ML directory**: Optional shared package directory configuration

## Related Files
- **UpgradeWizard_prepare.php**: Next step in upgrade process for package preparation
- **UpgradeWizard_commit.php**: Final step for package installation
- **UpgradeWizardCommon.php**: Shared utilities and common functions
- **PackageManagerDisplay.php**: UI rendering for package management interface 