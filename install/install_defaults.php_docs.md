# SuiteCRM Installation Defaults Configuration Documentation

**File:** `install/install_defaults.php`

## @fileoverview
Default configuration values and settings for the SuiteCRM installation process. Defines initial values for installation options, system preferences, and configuration parameters used throughout the installation wizard.

## @package
Installation

## @copyright
2004-2019 SugarCRM Inc. and SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

---

## Purpose

This file establishes default configuration values for the SuiteCRM installation process, providing:
- Initial installation option settings
- Default system configuration parameters
- Base values for installation wizard forms
- Standard configuration templates for common deployment scenarios

## Key Configuration Arrays

### `$installer_defaults` Array
The primary configuration array containing default values for all installation options:

#### Language and Localization
```php
'language' => 'en_us'
```
- Sets default installation language to English (US)
- Determines language files used during installation
- Affects localized text and error messages

#### Installation Type Configuration
```php
'oc_install' => ''
```
- Controls installation type selection
- Empty value indicates standard installation
- May be set to specific values for custom installation modes

#### License and Legal Settings
```php
'setup_license_accept' => false
'license_submitted' => false
'setup_license_key_users' => '0'
'setup_license_key_expire_date' => ''
'setup_license_key' => ''
```
- **License Acceptance**: Default to false, requiring explicit user acceptance
- **License Submission**: Tracks license form submission status
- **User Limits**: Default user count for licensed installations
- **Expiration**: License expiration date configuration
- **License Key**: Placeholder for license key entry

#### Session and System Testing
```php
'test_session' => 'sessions are available'
```
- Default session availability message
- Used for session functionality validation
- Confirms PHP session support is working

## Integration Points

### Database Operations
None - This is a configuration file that defines default values

### Internal API Calls
- Used by installation wizard forms to populate default values
- Referenced by validation functions for baseline configuration
- Integrated with session management for state persistence

### External API Calls
None - Provides local configuration defaults only

### UI Functionality
- **Form Initialization**: Populates installation wizard forms with default values
- **Progress Tracking**: Provides baseline configuration for installation steps
- **Validation Reference**: Used as reference for configuration validation
- **Reset Functionality**: Allows restoration of default installation settings

## Configuration Categories

### 1. Language and Localization
- Default system language selection
- Regional configuration preferences
- Character encoding and display settings

### 2. License Management
- License acceptance tracking
- User count and limitation settings
- License key validation parameters
- Expiration date management

### 3. Installation Options
- Installation type selection defaults
- Custom installation mode settings
- Upgrade vs. fresh installation preferences

### 4. System Validation
- Session testing configuration
- System requirement checking defaults
- Compatibility validation settings

## Usage Patterns

### Installation Wizard Integration
```php
// Form initialization
$default_language = $installer_defaults['language'];
$license_accepted = $installer_defaults['setup_license_accept'];
```

### Session State Management
- Defaults are merged with session variables
- User selections override default values
- Fallback values when session data is unavailable

### Validation and Error Handling
- Baseline values for configuration validation
- Default error states and messaging
- Recovery values for failed configuration

## Security Considerations

### Default Security Settings
- License acceptance defaults to false for security
- No pre-filled sensitive information
- Secure baseline configuration values

### Validation Integration
- Defaults validated against security requirements
- No hardcoded credentials or sensitive data
- Safe fallback values for all configurations

## Customization Support

### Environment-Specific Defaults
The configuration array can be modified for specific deployment environments:
- Development environment defaults
- Production deployment settings
- Custom organizational requirements

### Integration Hooks
- Allows override by custom installation scripts
- Supports environment variable integration
- Compatible with automated deployment tools

## Dependencies

### Required Components
None - This is a standalone configuration file

### Integration Requirements
- PHP array processing capabilities
- Session management for state persistence
- Installation wizard framework for value consumption

## Related Files

- `install/installConfig.php`: Main installation configuration processor
- `install/install_utils.php`: Installation utility functions
- Installation wizard form files that consume these defaults
- Language files that work with default language setting

## Modification Guidelines

### Safe Customization
- Modify default values to match organizational requirements
- Ensure all required keys remain in the array
- Validate changes against installation wizard requirements

### Version Compatibility
- Maintain compatibility with SuiteCRM installation process
- Test changes with complete installation workflow
- Document any custom modifications for maintenance

## Environmental Considerations

### Development Environments
- May include development-specific defaults
- Testing and debugging configuration values
- Accelerated installation settings for development

### Production Deployments
- Security-focused default settings
- Performance-optimized configuration values
- Enterprise deployment considerations

## Future Extensions

### Planned Enhancements
- Support for additional configuration categories
- Integration with external configuration management
- Enhanced customization and override mechanisms

### Compatibility Maintenance
- Backward compatibility with existing installations
- Forward compatibility with future SuiteCRM versions
- Migration support for configuration changes

## Notes

- Central configuration point for installation defaults
- Provides consistent baseline across all installations
- Supports customization while maintaining stability
- Integrates seamlessly with SuiteCRM installation framework
- Essential for automated and manual installation processes 