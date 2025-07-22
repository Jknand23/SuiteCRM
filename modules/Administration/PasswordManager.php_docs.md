# PasswordManager.php Documentation

/**
 * @fileoverview Password Management configuration interface for SuiteCRM administration
 * @package Administration
 * @copyright SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

The PasswordManager.php file provides the administrative interface for configuring password policies, authentication settings, and security features in SuiteCRM. It handles LDAP authentication, CAPTCHA settings, password complexity requirements, and email template configurations for password-related communications.

## Database Operations

### Configuration Storage
```php
$configurator = new Configurator();
$configurator->saveConfig();
```
- **Purpose**: Persists password and authentication settings
- **Storage**: Updates `config.php` and `config_override.php`
- **Scope**: System-wide configuration affecting all users

### Administration Settings
```php
$focus = BeanFactory::newBean('Administration');
$focus->saveConfig();
$focus->retrieveSettings();
```
- **Purpose**: Manages Administration module settings
- **Database**: Uses `config` table for persistent storage
- **Access Pattern**: Key-value pairs for configuration data

### Email Template Integration
```php
$email_templates_arr = get_bean_select_array(true, 'EmailTemplate', 'name', '', 'name', true);
```
- **Purpose**: Retrieves available email templates for password operations
- **Query**: Fetches EmailTemplate names for dropdown selections
- **Integration**: Links password management with email notification system

## Internal API Calls

### Configuration Management
```php
require_once('modules/Configurator/Configurator.php');
$configurator = new Configurator();
$configurator->parseLoggerSettings();
```
- **Purpose**: Manages system configuration through Configurator class
- **Functionality**: Parses and validates configuration settings
- **Integration**: Central configuration management system

### Form Processing
```php
require_once('modules/Administration/Forms.php');
echo getClassicModuleTitle("Administration", [...], false);
```
- **Purpose**: Provides standardized form rendering and module titles
- **UI Integration**: Consistent interface styling and navigation
- **Breadcrumbs**: Hierarchical navigation structure

### Authentication Validation
```php
if (!is_admin($current_user)) {
    sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);
}
```
- **Purpose**: Ensures only administrators can access password settings
- **Security**: Prevents unauthorized configuration changes
- **Error Handling**: Graceful failure with appropriate error message

## External API Calls

### CAPTCHA Validation
```php
$handle = @fopen("http://www.google.com/recaptcha/api/challenge?k=" . $_POST['captcha_public_key'], 'rb');
```
- **Purpose**: Validates Google reCAPTCHA public key
- **External Service**: Google reCAPTCHA API
- **Network Call**: HTTP request to verify key validity
- **Error Handling**: Graceful fallback if validation fails

### IMAP Capability Check
```php
include_once __DIR__ . '/../../include/Imap/ImapHandlerFactory.php';
$imapFactory = new ImapHandlerFactory();
if (!$imapFactory->areAllHandlersAvailable()) {
    $sugar_smarty->assign('IE_DISABLED', 'DISABLED');
}
```
- **Purpose**: Checks IMAP library availability for email functionality
- **Dependencies**: PHP IMAP extension and related libraries
- **UI Feedback**: Disables IMAP-related settings if unavailable

### SMTP Configuration Check
```php
require_once('include/SugarPHPMailer.php');
$mail = new SugarPHPMailer();
$mail->setMailerForSystem();
```
- **Purpose**: Validates SMTP server configuration
- **Integration**: SugarPHPMailer for email functionality
- **Feedback**: Warns if SMTP server not configured

## UI Functionality

### Password Policy Configuration
```php
$configurator->config['passwordsetting']['oneupper'] = $_POST['passwordsetting_oneupper'];
$configurator->config['passwordsetting']['onelower'] = $_POST['passwordsetting_onelower'];
$configurator->config['passwordsetting']['onenumber'] = $_POST['passwordsetting_onenumber'];
$configurator->config['passwordsetting']['onespecial'] = $_POST['passwordsetting_onespecial'];
$configurator->config['passwordsetting']['minpwdlength'] = $_POST['passwordsetting_minpwdlength'];
```

#### Password Complexity Settings
- **oneupper**: Require at least one uppercase letter
- **onelower**: Require at least one lowercase letter  
- **onenumber**: Require at least one numeric digit
- **onespecial**: Require at least one special character
- **minpwdlength**: Minimum password length requirement

### LDAP Authentication Configuration
```php
if (isset($_REQUEST['system_ldap_enabled']) && $_REQUEST['system_ldap_enabled'] == 'on') {
    $_POST['system_ldap_enabled'] = 1;
    clearPasswordSettings();
}
```
- **Purpose**: Configures LDAP authentication integration
- **Side Effect**: Clears password settings when LDAP enabled
- **Logic**: LDAP authentication supersedes local password policies

#### LDAP Settings
- **system_ldap_enabled**: Master LDAP enable/disable flag
- **ldap_authentication**: LDAP authentication specific toggle
- **ldap_group**: LDAP group-based authorization
- **ldap_group_attr_req_dn**: Group attribute requires DN format

### Template Selection Interface
```php
$TMPL_DRPDWN_LOST = get_select_options_with_id($email_templates_arr, $res['lostpasswordtmpl']);
$TMPL_DRPDWN_GENERATE = get_select_options_with_id($email_templates_arr, $res['generatepasswordtmpl']);
$TMPL_DRPDWN_FACTOR = get_select_options_with_id($email_templates_arr, $res['factoremailtmpl']);
```

#### Email Template Dropdowns
- **Lost Password Template**: Template for password reset emails
- **Generate Password Template**: Template for new password emails
- **Factor Email Template**: Template for two-factor authentication emails

### CAPTCHA Configuration
```php
if (!empty($focus->settings['captcha_on'])) {
    $sugar_smarty->assign("CAPTCHA_CONFIG_DISPLAY", 'inline');
} else {
    $sugar_smarty->assign("CAPTCHA_CONFIG_DISPLAY", 'none');
}
```
- **Purpose**: Shows/hides CAPTCHA configuration based on enable status
- **UI Behavior**: Dynamic form sections based on settings
- **Validation**: Integrates with Google reCAPTCHA validation

## Security Features

### Access Control
```php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
```
- **Entry Point Validation**: Prevents direct file access
- **Security Layer**: Standard SuiteCRM security pattern
- **Attack Prevention**: Mitigates direct URL manipulation

### Password Policy Enforcement
- **Complexity Requirements**: Configurable character class requirements
- **Length Requirements**: Minimum password length enforcement
- **System Integration**: Policies applied system-wide through configuration

### Authentication Security
- **Admin-Only Access**: Restricted to administrator users
- **LDAP Integration**: Secure external authentication support
- **Session Management**: Standard SuiteCRM session handling

### Configuration Protection
```php
if (!function_exists('openssl_encrypt')) {
    $sugar_smarty->assign("LDAP_ENC_KEY_READONLY", 'readonly');
}
```
- **Encryption Validation**: Checks for OpenSSL availability
- **Key Protection**: LDAP encryption key handling
- **Graceful Degradation**: Read-only mode when encryption unavailable

## Performance Considerations

### Template Caching
```php
$sugar_smarty = new Sugar_Smarty();
$sugar_smarty->display('modules/Administration/PasswordManager.tpl');
```
- **Template Engine**: Uses Smarty for efficient template rendering
- **Caching**: Smarty provides template compilation and caching
- **Memory Management**: Efficient template variable assignment

### Configuration Optimization
- **Lazy Loading**: Configuration loaded only when needed
- **Batch Updates**: Multiple settings saved in single operation
- **Minimal Database Hits**: Efficient configuration retrieval and storage

### Network Optimization
- **CAPTCHA Validation**: Single HTTP request for key validation
- **Timeout Handling**: Network calls have appropriate timeouts
- **Fallback Logic**: Graceful handling of network failures

## Error Handling

### Input Validation
```php
if (isset($_REQUEST['passwordsetting_lockoutexpirationtime']) && is_numeric($_REQUEST['passwordsetting_lockoutexpirationtime'])) {
    $_POST['passwordsetting_lockoutexpiration'] = 2;
}
```
- **Type Checking**: Validates numeric inputs
- **Boundary Validation**: Ensures appropriate value ranges
- **Default Values**: Provides safe defaults for missing inputs

### Configuration Validation
```php
$valid_public_key = substr($buffer, 1, 4) == 'var ' ? true : false;
if ($valid_public_key) {
    // Process configuration
}
```
- **External Validation**: Validates CAPTCHA keys with Google
- **Conditional Processing**: Only processes valid configurations
- **Error Recovery**: Maintains existing settings on validation failure

### System Checks
- **IMAP Availability**: Checks system capabilities before enabling features
- **SMTP Configuration**: Validates email server settings
- **OpenSSL Support**: Verifies encryption capabilities

## Integration Points

### Email System Integration
- **Template Management**: Links with EmailTemplate module
- **SMTP Configuration**: Integrates with mail server settings
- **Notification System**: Supports password-related email notifications

### Authentication System
- **LDAP Integration**: External authentication provider support
- **Local Authentication**: Native SuiteCRM password handling
- **Two-Factor Authentication**: Multi-factor authentication support

### Configuration System
- **Configurator Integration**: Central configuration management
- **Administration Module**: Integrated with admin panel
- **System Settings**: Global configuration impact

## Administration Features

### Password Reset Workflow
- **Email Templates**: Configurable templates for reset emails
- **Link Expiration**: Configurable timeout for reset links
- **Security Options**: Various reset security configurations

### Account Lockout Settings
- **Lockout Policies**: Configurable account lockout rules
- **Expiration Settings**: Time-based lockout expiration
- **Administrative Override**: Admin unlock capabilities

### System Integration
- **Module Navigation**: Integrated breadcrumb navigation
- **Help System**: Context-sensitive help integration
- **Language Support**: Internationalization through language files 