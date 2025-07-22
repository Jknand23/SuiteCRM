# installType.php Documentation

## @fileoverview
Installation type selection interface that allows users to choose between typical and custom installation modes during the SuiteCRM setup process.

## @package SuiteCRM Installation
## @copyright SalesAgility Ltd.
## @license AGPL-3.0

## Overview

This file provides users with the choice between different installation types, typically "Typical" (standard) and "Custom" (advanced) installation modes. It manages the configuration of installation options and sets appropriate defaults based on the selected installation approach.

## Core Functionality

### Installation Type Selection
- **Typical Installation**: Standard installation with recommended defaults
- **Custom Installation**: Advanced installation with detailed configuration options
- **Option Management**: Manages installation type selection and persistence
- **Default Configuration**: Sets appropriate defaults for each installation type

### Configuration Management
- **Installation Defaults**: Applies appropriate defaults for selected type
- **Option Persistence**: Maintains selected type across installation steps
- **Validation Logic**: Validates installation type selection
- **Flow Control**: Directs installation flow based on selected type

### User Interface
- **Selection Interface**: Clean, intuitive installation type selection
- **Option Descriptions**: Clear descriptions of each installation type
- **Progress Tracking**: Shows current step in installation process
- **Navigation Controls**: Appropriate next/back navigation

## UI Functionality

### Installation Type Interface
Professional installation type selection interface:

#### HTML Structure
```php
$langHeader = get_language_header();
$out = <<<EOQ
<!doctype html>
<html {$langHeader}>
<head>
    <title>{$mod_strings['LBL_WIZARD_TITLE']} {$mod_strings['LBL_INSTALL_TYPE_TITLE']}</title>
EOQ;
```

#### Visual Elements
- **Header Section**: Installation branding and step progression
- **Type Selection**: Radio buttons or selection interface for installation types
- **Descriptions**: Detailed descriptions of each installation option
- **Progress Indicators**: Visual step progression tracking
- **Navigation Footer**: Installation step controls

### Interactive Components
- **Type Selection Controls**: Radio buttons or dropdown for type selection
- **Description Display**: Dynamic descriptions based on selection
- **Validation Feedback**: Real-time validation of selection
- **Navigation Integration**: Smart navigation based on type choice

## Session Management

### Installation Type Storage
Manages installation type through session variables:
```php
if (!isset($_SESSION['licenseKey_submitted']) || !$_SESSION['licenseKey_submitted']) {
    $_SESSION['setup_license_key_users']        = 0;
    $_SESSION['setup_license_key_expire_date']  = "";
    $_SESSION['setup_license_key']              = "";
    $_SESSION['setup_num_lic_oc']              = 0;
}
```

### Configuration Persistence
- **Type Selection**: Stores selected installation type
- **Associated Settings**: Maintains type-specific configuration
- **Validation State**: Preserves validation results
- **Progress Tracking**: Records completion of type selection

### Default Management
- **Typical Defaults**: Sets standard defaults for typical installation
- **Custom Defaults**: Provides base settings for custom installation
- **License Configuration**: Manages license-related settings
- **System Preferences**: Establishes system preference defaults

## Installation Type Options

### Typical Installation
Standard installation with recommended settings:
- **Simplified Process**: Streamlined installation with minimal user input
- **Recommended Defaults**: Uses best-practice default configurations
- **Essential Features**: Enables core functionality out-of-the-box
- **Quick Setup**: Optimized for fast, reliable installation

### Custom Installation
Advanced installation with detailed configuration:
- **Full Control**: Provides access to all configuration options
- **Advanced Settings**: Exposes advanced system configuration
- **Flexible Options**: Allows customization of all installation aspects
- **Expert Mode**: Designed for experienced administrators

### Configuration Impact
Each installation type affects:
- **Database Configuration**: Different database setup approaches
- **Feature Selection**: Varying feature enablement defaults
- **Security Settings**: Different security configuration defaults
- **Performance Options**: Varied performance optimization settings

## PHP Version Handling

### Version Compatibility
Includes PHP version suggestions and warnings:
```php
$php_suggested_ver = '';
if (check_php_version() === -1) {
    $php_suggested_ver = $mod_strings['LBL_YOUR_PHP_VERSION'].phpversion().$mod_strings['LBL_RECOMMENDED_PHP_VERSION'];
}
```

### Version Management
- **Version Detection**: Detects current PHP version
- **Compatibility Checking**: Validates PHP version compatibility
- **Recommendation Display**: Shows recommended PHP version
- **Warning Generation**: Provides warnings for incompatible versions

## Integration Points

### Installation Workflow
Integrates with overall installation process:
- **Type Selection**: Influences subsequent installation steps
- **Configuration Flow**: Directs configuration based on type choice
- **Feature Enablement**: Controls which features are configured
- **Validation Requirements**: Adjusts validation based on type

### Language System
- **Multi-Language Support**: Supports installation type selection in multiple languages
- **Language Dropdown**: Provides language selection capability
- **Localized Content**: Displays type descriptions in selected language
- **RTL Support**: Supports right-to-left languages

## External Integration

### License Integration
- **License Key Management**: Handles license key configuration
- **User Limit Configuration**: Manages user license limits
- **Expiration Handling**: Processes license expiration settings
- **Commercial Features**: Manages commercial feature enablement

### System Integration
- **Environment Detection**: Detects system environment characteristics
- **Capability Assessment**: Evaluates system capabilities
- **Resource Allocation**: Adjusts resource allocation based on type
- **Performance Optimization**: Optimizes based on installation type

## Security Considerations

### Input Validation
- **Type Selection Validation**: Validates installation type selection
- **Session Security**: Secure session variable handling
- **Entry Point Validation**: Ensures proper script access
- **Installation Context**: Validates installation script context

### Configuration Security
- **Default Security**: Applies appropriate security defaults
- **Type-Specific Security**: Adjusts security based on installation type
- **Access Control**: Manages access control configuration
- **Security Settings**: Configures security-related options

## User Interface Features

### Professional Design
- **Clean Layout**: Professional, intuitive type selection interface
- **Visual Hierarchy**: Clear organization of selection options
- **Brand Consistency**: Maintains SuiteCRM visual identity
- **Responsive Design**: Works effectively across devices

### User Guidance
- **Clear Descriptions**: Detailed descriptions of each installation type
- **Recommendation Guidance**: Helps users choose appropriate type
- **Context Help**: Provides relevant help information
- **Progress Indication**: Shows installation progress clearly

### Accessibility Features
- **Keyboard Navigation**: Full keyboard accessibility
- **Screen Reader Support**: Proper semantic markup
- **High Contrast**: Compatible with high contrast modes
- **Alternative Text**: Appropriate alt text for visual elements

## Error Handling

### Selection Validation
- **Required Selection**: Ensures installation type is selected
- **Valid Option Validation**: Validates selected option is valid
- **Session Error Handling**: Handles session initialization errors
- **Type-Specific Validation**: Performs type-specific validation

### User Feedback
- **Clear Error Messages**: Provides specific error messages
- **Visual Indicators**: Uses visual cues for validation state
- **Help Integration**: Links to relevant help information
- **Recovery Options**: Allows correction of selection errors

## Performance Optimization

### Resource Loading
- **CSS Optimization**: Efficient stylesheet loading
- **JavaScript Optimization**: Optimized script loading
- **Image Optimization**: Optimized images for faster loading
- **Font Loading**: Efficient font resource loading

### Page Generation
- **Template Efficiency**: Efficient HTML template generation
- **Session Optimization**: Minimal session data usage
- **Memory Management**: Efficient variable usage
- **Rendering Optimization**: Optimized page rendering

## Development Support

### Customization Features
- **Type Extension**: Ability to add custom installation types
- **Configuration Customization**: Custom configuration options
- **Template Modification**: Customizable page templates
- **Branding Customization**: Custom branding and styling

### Testing Support
- **Type Testing**: Comprehensive testing of installation types
- **Cross-Browser Testing**: Testing across different browsers
- **Mobile Testing**: Mobile device compatibility testing
- **Integration Testing**: Testing with overall installation flow

## Dependencies

### System Requirements
- **PHP Environment**: Compatible PHP version and configuration
- **Session Support**: PHP session handling capability
- **File System**: Access to installation files and resources
- **Web Browser**: Modern browser with CSS and JavaScript support

### SuiteCRM Components
- **Installation Utilities**: Integration with installation helper functions
- **Language System**: Internationalization framework support
- **Session Management**: SuiteCRM session handling integration
- **Configuration System**: System configuration management

## Installation Flow Impact

### Typical Installation Flow
- **Simplified Steps**: Fewer configuration steps required
- **Automatic Configuration**: Many settings configured automatically
- **Quick Setup**: Faster installation completion
- **Standard Features**: Standard feature set enabled

### Custom Installation Flow
- **Detailed Configuration**: More configuration steps available
- **Manual Settings**: User controls most configuration options
- **Advanced Features**: Access to advanced configuration options
- **Flexible Setup**: Highly customizable installation process

## Development Notes

### Code Organization
- **Clear Structure**: Well-organized code for easy maintenance
- **Type Separation**: Clear separation of type-specific logic
- **Modular Design**: Modular structure for easy extension
- **Documentation**: Comprehensive inline documentation

### Future Enhancements
- **Additional Types**: Capability to add new installation types
- **Enhanced Options**: More detailed configuration options
- **Wizard Integration**: Enhanced wizard-style interface
- **Automation Features**: Additional automation capabilities 