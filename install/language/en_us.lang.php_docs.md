# en_us.lang.php Documentation

## @fileoverview
Comprehensive English language string definitions for the SuiteCRM installation process, providing user interface text and error messages.

## @package SuiteCRM Installation Language
## @copyright SalesAgility Ltd.
## @license AGPL-3.0

## Overview

This file contains the complete English (US) language pack for the SuiteCRM installation wizard. It defines all user-facing text, error messages, labels, and instructions that guide users through the installation process. The language strings are organized into logical categories covering system requirements, database configuration, administrative setup, and error handling.

## Core Functionality

### Language String Organization
The file provides localized strings for:
- **System Compatibility Checks**: Error messages and warnings for system requirements
- **Database Configuration**: Labels and errors for database setup screens
- **Administrative Setup**: Text for admin user creation and configuration
- **Installation Progress**: Status messages and progress indicators
- **Error Handling**: Comprehensive error messages with solutions

### User Interface Text Categories

#### System Requirements ($mod_strings)
- Search functionality labels (`LBL_BASIC_SEARCH`, `LBL_ADVANCED_SEARCH`)
- Database type selection (`LBL_SYSOPTS_DB`, `LBL_SYSOPTS_DB_TITLE`)
- System check error messages (`ERR_CHECKSYS_*` series)

#### Database Configuration
- Connection validation errors (`ERR_DB_LOGIN_FAILURE_*`)
- Database existence checks (`ERR_DB_EXISTS_*`)
- Version compatibility warnings (`ERR_DB_MYSQL_VERSION`)
- Database naming validation (`ERR_DB_*_DB_NAME_INVALID`)

#### Administrative User Setup
- User validation (`ERR_ADMIN_USER_NAME_BLANK`, `ERR_ADMIN_PASS_BLANK`)
- Account configuration messages
- Permission and security warnings

## Installation Integration

### Error Message System
The language file integrates with:
- **System Check Module**: Provides text for compatibility validation
- **Database Setup**: Supplies error messages for connection issues
- **Configuration Validator**: Offers detailed error descriptions
- **Installation Progress**: Displays status and completion messages

### Multi-language Support Framework
- Follows SuiteCRM's standard language pack structure
- Uses consistent naming conventions for easy translation
- Supports fallback to English for missing translations
- Integrates with `install_defaults.php` for default language configuration
- Works with `UploadLangFileCheck.php` for language pack validation

## UI Functionality

### Installation Wizard Interface
Provides text for all installation screens:
- Welcome and license acceptance
- System requirements validation
- Database configuration and testing
- Administrative user creation
- Installation progress and completion

### Error Display System
- Detailed error messages with actionable solutions
- System requirement warnings with specific remediation steps
- Database connection troubleshooting guidance
- File permission error descriptions

### Progress Feedback
- Installation step descriptions
- Progress indicator text
- Completion confirmation messages
- Next step guidance

## Localization Features

### String Structure
Language strings follow consistent patterns:
- `LBL_*` prefix for labels and UI text
- `ERR_*` prefix for error messages
- `DEFAULT_*` prefix for default values
- Descriptive suffixes for context clarity

### Error Message Categories
Organized error types:
- `ERR_CHECKSYS_*` - System compatibility checks
- `ERR_DB_*` - Database-related errors
- `ERR_ADMIN_*` - Administrative setup errors
- `ERR_CONFIG_*` - Configuration file errors

### Technical Requirements Messages
Specific error messages for:
- PHP version compatibility (`ERR_CHECKSYS_PHP_INVALID_VER`)
- Required PHP extensions (`ERR_CHECKSYS_MBSTRING`, `ERR_CHECKSYS_CURL`)
- File permission issues (`ERR_CHECKSYS_*_NOT_WRITABLE`)
- Database support verification (`LBL_CHECKSYS_DB_SUPPORT_NOT_AVAILABLE`)

## Integration Points

### Installation System Components
This language file supports:
- **installSystemCheck.php**: System compatibility validation text
- **performSetup.php**: Installation progress messages
- **dbConfig_*.php**: Database configuration screens
- **installConfig.php**: General configuration interface
- **welcome.php**: Language selection and initial wizard interface
- **install_utils.php**: Language pack installation and detection functions
- **populateSeedData.php**: Localized content for demo data generation
- **Advanced_Password_SeedData.php**: Email template content localization

### Error Handling Integration
- Provides user-friendly error descriptions for technical issues
- Offers specific remediation steps for common problems
- Supports diagnostic information display
- Enables internationalization of error reporting

## Development Notes

### Translation Guidelines
- Maintain consistent terminology across all strings
- Provide clear, actionable error messages
- Include technical details where helpful for troubleshooting
- Follow established UI text conventions

### String Maintenance
- New installation features require corresponding language strings
- Error messages should be tested for clarity and accuracy
- Regular review ensures consistency with current installation flow
- Version-specific messages should be updated with system changes

### Customization Support
- Custom installation screens can add new string categories
- Error handling can be extended with additional message types
- Language pack structure supports easy modification and extension
- String organization facilitates maintenance and updates 