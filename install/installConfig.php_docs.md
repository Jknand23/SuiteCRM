# installConfig.php Documentation

## @fileoverview
Installation configuration interface generator that creates dynamic HTML forms and layouts for the SuiteCRM installation wizard, managing user interface rendering and configuration data collection.

## @package SuiteCRM Installation
## @copyright SalesAgility Ltd.
## @license AGPL-3.0

## Overview

This file serves as the presentation layer for the SuiteCRM installation process, responsible for generating dynamic HTML forms, managing user interface layouts, and processing configuration data during the installation wizard. It provides a comprehensive framework for creating professional, interactive installation interfaces.

## Core Classes

### NonDBLocalization Class
Extends the standard Localization class to provide installation-specific localization without requiring database access.

#### Key Features
- **Database-Independent**: Operates without database connectivity during installation
- **Name Format Handling**: Manages locale-specific name format examples
- **Installation Context**: Provides localization services before system is fully configured

#### Methods
- **`__construct()`**: Initializes default locale name formatting
- **`getUsableLocaleNameOptions()`**: Creates localized dropdown options with examples

### InstallLayout Class
Central UI rendering engine that generates complete installation pages with professional styling and functionality.

#### Key Capabilities
- **Form Generation**: Creates dynamic installation forms based on configuration
- **HTML Template Management**: Renders complete HTML pages with headers and styling
- **Interactive Elements**: Manages form controls, validation, and user interaction
- **Progress Tracking**: Displays installation step progress indicators

#### Core Methods
- **`getSelect()`**: Generates HTML select dropdown elements
- **`getFormControlls()`**: Creates navigation and action buttons
- **`getForm()`**: Builds complete form structure with upload capabilities
- **`getOutput()`**: Renders final HTML page output
- **`show()`**: Main display method that orchestrates page rendering

### DisplayErrors Class
Utility class for managing error reporting and debugging during installation.

#### Functionality
- **Error State Management**: Saves and restores error reporting settings
- **Debug Support**: Enables enhanced error reporting for troubleshooting
- **Settings Stack**: Maintains multiple error reporting configurations

## Internal Operations

### Configuration Processing
The file processes multiple configuration areas:

#### Database Configuration
```php
// Database driver selection
$setup_db_type = $_SESSION['setup_db_type'];
$drivers = DBManagerFactory::getDbDrivers();

// Database connection parameters
$_SESSION['setup_db_host_name'] = $sugar_config['db_host_name'] ?? $_SERVER['SERVER_NAME'];
$_SESSION['setup_db_collation'] = 'utf8mb4_general_ci';
$_SESSION['setup_db_charset'] = 'utf8mb4';
```

#### Site Configuration
- **Admin User Setup**: Administrator credentials and email configuration
- **URL Configuration**: Site URL and path settings
- **Security Settings**: GUID generation and security preferences
- **Theme and Localization**: Default theme, language, and locale settings

#### Session Management
- **Configuration Persistence**: Maintains configuration across installation steps
- **Validation State**: Preserves validation errors and user input
- **Installation Scenarios**: Loads predefined installation configuration scenarios

## Database Operations

### Driver Management
- **Driver Detection**: Identifies available database drivers
- **Type Selection**: Manages database type selection interface
- **Connection Validation**: Provides database connection testing capabilities

### Configuration Storage
- **Session Persistence**: Stores configuration in session variables
- **Database Parameters**: Manages host, credentials, and connection settings
- **Character Set Configuration**: Handles UTF-8 encoding setup

## UI Functionality

### Form Generation
The InstallLayout class creates sophisticated installation forms:

#### Form Elements
- **Input Fields**: Text, password, email, and hidden inputs
- **Select Dropdowns**: Database drivers, languages, themes
- **Checkboxes**: Feature toggles and configuration options
- **File Uploads**: Logo and customization file handling

#### Form Structure
```php
public function getForm($name, $id, $items, $controlls, $scripts, $next_step)
{
    // Creates iframe for upload handling
    // Generates form with proper enctype for file uploads
    // Includes hidden fields for step management
    // Adds JavaScript for client-side interaction
}
```

### Page Layout
Complete HTML page generation including:
- **DOCTYPE and HTML5 Structure**: Modern web standards compliance
- **CSS Styling**: Professional installation wizard appearance
- **JavaScript Integration**: Client-side validation and interaction
- **Progress Indicators**: Visual step progression tracking

### Error Handling and Validation
- **Validation Error Display**: Shows configuration errors with specific messages
- **Input Validation**: Client and server-side validation integration
- **User Feedback**: Clear error messages and guidance
- **Required Field Indicators**: Visual marking of mandatory fields

## Configuration Management

### Session Variable Processing
Manages complex session data including:
- Database connection parameters
- Site configuration settings
- Language and localization preferences
- Feature enablement flags
- Custom configuration overrides

### Default Value Management
```php
$sugarConfigDefaults = array_merge(get_sugar_config_defaults(), $_SESSION);
```

### Configuration Validation
- **Input Sanitization**: Validates configuration parameters
- **Dependency Checking**: Ensures configuration consistency
- **Error Collection**: Aggregates validation errors for display

## Installation Scenarios

### Scenario Loading
Supports predefined installation configurations:
```php
require_once('install/suite_install/scenarios.php');
if (isset($installation_scenarios)) {
    $_SESSION['installation_scenarios'] = $installation_scenarios;
}
```

### Scenario Types
- **Typical Installation**: Standard configuration for most users
- **Custom Installation**: Advanced configuration options
- **Silent Installation**: Automated installation scenarios

## External Integration

### Database Factory Integration
- **Driver Detection**: Uses DBManagerFactory for database driver enumeration
- **Instance Creation**: Creates database instances for configuration testing
- **Type Management**: Handles different database engine requirements

### Localization Integration
- **Language Pack Support**: Integrates with language pack installation
- **Locale Management**: Handles regional settings and formatting
- **Character Set Handling**: Manages UTF-8 encoding throughout

## JavaScript and Client-Side Functionality

### Client-Side Validation
- **Form Validation**: Real-time validation of configuration inputs
- **Password Matching**: Validates password confirmation fields
- **URL Validation**: Checks site URL format and accessibility

### User Interface Enhancement
- **Progress Tracking**: Updates progress indicators dynamically
- **Conditional Display**: Shows/hides configuration sections based on user selections
- **Upload Management**: Handles file uploads with progress feedback

## Security Features

### Input Validation
- **Session Data Validation**: Validates all session-stored configuration
- **File Upload Security**: Secures file upload processes
- **CSRF Protection**: Includes CSRF token management
- **SQL Injection Prevention**: Sanitizes database configuration inputs

### Access Control
- **Entry Point Validation**: Enforces proper script access
- **Installation Script Check**: Validates installation context
- **Session Security**: Secures session data handling

## Error Handling

### Configuration Errors
- **Database Connection Errors**: Handles database connectivity issues
- **File Permission Errors**: Manages file system access problems
- **Validation Errors**: Processes configuration validation failures
- **Display Errors**: Formats and displays user-friendly error messages

### Recovery Mechanisms
- **Error State Preservation**: Maintains error context across requests
- **Configuration Recovery**: Allows correction of invalid configurations
- **Graceful Degradation**: Provides fallback options for failed operations

## Performance Considerations

### Rendering Optimization
- **Template Caching**: Optimizes HTML template generation
- **Asset Management**: Efficiently loads CSS and JavaScript resources
- **Form State Management**: Minimizes session data overhead
- **Database Query Optimization**: Reduces database calls during configuration

### Memory Management
- **Variable Scope Management**: Proper variable scoping and cleanup
- **Session Data Optimization**: Efficient session variable handling
- **Large Form Handling**: Manages complex configuration forms efficiently

## Integration Points

### Installation Workflow
Integrates with other installation components:
- **install_utils.php**: Uses utility functions for configuration processing
- **performSetup.php**: Provides configuration data for setup execution
- **Language Packs**: Supports multilingual installation interfaces
- **Database Configuration**: Interfaces with database setup processes

### Module Integration
- **Suite-Specific Features**: Handles SuiteCRM-specific configuration options
- **Extension Support**: Supports custom module configuration
- **Theme Integration**: Manages theme selection and customization

## Development Support

### Debugging Features
- **DisplayErrors Class**: Enhanced error reporting for development
- **Configuration Dumping**: Debug output for configuration analysis
- **Session State Inspection**: Tools for debugging session data
- **Validation Error Tracking**: Detailed validation error reporting

### Customization Hooks
- **Template Customization**: Supports custom installation templates
- **Configuration Extension**: Allows custom configuration fields
- **Validation Extension**: Supports custom validation rules
- **UI Customization**: Enables custom styling and branding

## Dependencies

### System Requirements
- **PHP Sessions**: Session handling for configuration persistence
- **File System Access**: File upload and configuration file handling
- **Web Server**: HTTP request/response handling
- **CSS/JavaScript Support**: Modern web browser capabilities

### SuiteCRM Components
- **DBManagerFactory**: Database abstraction layer
- **Localization**: Internationalization and localization framework
- **Session Management**: SuiteCRM session handling
- **Configuration Defaults**: System default configuration values

## Usage Context

### Installation Wizard
This file provides the user interface for:
1. **System Options**: Database driver selection and system preferences
2. **Database Configuration**: Database connection and setup parameters
3. **Site Configuration**: Site settings, admin user, and preferences
4. **Advanced Options**: Custom paths, security settings, and features

### Configuration Flow
1. **Page Request**: User navigates to installation step
2. **Configuration Loading**: Loads existing configuration from session
3. **Form Generation**: Creates appropriate configuration form
4. **User Interaction**: User enters configuration data
5. **Validation**: Validates configuration parameters
6. **Session Storage**: Stores valid configuration in session
7. **Navigation**: Proceeds to next installation step

## Development Notes

### Code Organization
- Clear separation between data processing and presentation
- Modular class design for maintainability
- Consistent naming conventions and documentation
- Proper error handling and validation throughout

### Maintenance Guidelines
- Update HTML templates for modern web standards
- Maintain cross-browser compatibility
- Keep validation rules synchronized with system requirements
- Ensure security best practices in form handling
- Test configuration scenarios thoroughly 