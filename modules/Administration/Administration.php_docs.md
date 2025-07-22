# Administration.php

## Overview
**@fileoverview** Core administration module class that manages system configuration settings, SMTP configurations, and administrative preferences for SuiteCRM.

**@package** Administration  
**@copyright** SugarCRM Inc. / SalesAgility Ltd.  
**@license** AGPL v3  

The Administration class extends SugarBean and serves as the primary interface for managing system-wide configuration settings stored in the `config` database table. It handles settings retrieval, caching, encryption of sensitive data, and integration with external email services.

## Database Operations

### Primary Table
- **Table Name**: `config`
- **Purpose**: Stores system configuration settings categorized by type
- **Schema**: `category`, `name`, `value` columns

### Configuration Categories
The class manages several configuration categories:
- `disclosure`: Email disclosure settings appended to outbound emails
- `notify`: System notification preferences  
- `system`: Core system settings
- `portal`: Portal configuration
- `proxy`: Proxy server settings
- `massemailer`: Mass email configuration
- `ldap`: LDAP authentication settings
- `captcha`: CAPTCHA verification settings
- `sugarpdf`: PDF generation settings

### retrieveSettings($category, $clean)
Retrieves configuration settings from database with intelligent caching:
- **Caching**: Uses `sugar_cache_retrieve('admin_settings_cache')` for performance
- **Security**: Automatically decrypts sensitive fields (LDAP/proxy passwords)
- **Integration**: Merges OutboundEmail settings when mail configuration is requested
- **Flexibility**: Can retrieve all settings or filter by specific category

### saveSetting($category, $key, $value)
Persists individual configuration settings:
- **Upsert Logic**: Checks for existing records and performs INSERT or UPDATE accordingly
- **Security**: Encrypts sensitive password fields before storage
- **Cache Management**: Clears admin settings cache after updates
- **SQL Safety**: Uses proper database quoting for all parameters

## Internal API Calls

### saveConfig()
Primary method for processing and saving configuration form submissions:
- **POST Processing**: Iterates through `$_POST` data to identify configuration fields
- **Category Validation**: Only processes settings from defined config_categories
- **Array Handling**: Converts array values to comma-separated strings
- **Email Integration**: Saves OutboundEmail settings when SMTP server is configured
- **Cache Refresh**: Forces settings cache refresh after save operations

### checkSmtpError($displayWarning)
Validates SMTP configuration and displays warnings:
- **Configuration Check**: Verifies SMTP server settings are present
- **Notification Integration**: Checks if notifications are enabled
- **Sendmail Fallback**: Considers alternative sendmail configuration
- **Warning Display**: Shows admin error messages when SMTP is misconfigured

### get_config_prefix($str)
Utility method for parsing configuration field names:
- **String Parsing**: Splits configuration keys on underscore delimiter
- **Category Extraction**: Returns array with category and setting name
- **Error Handling**: Returns false values for invalid input

## External API Calls

### OutboundEmail Integration
The class integrates with OutboundEmail for mail settings:
- **System Mailer**: Calls `$oe->getSystemMailerSettings()` to retrieve mail configuration
- **Field Mapping**: Maps mail/SMTP fields from OutboundEmail to Administration settings
- **Save Coordination**: Calls `$oe->saveSystem()` when SMTP server is configured

### Sugar Cache Integration
Utilizes SuiteCRM's caching system for performance:
- **Cache Storage**: `sugar_cache_put('admin_settings_cache', $this->settings)`
- **Cache Retrieval**: `sugar_cache_retrieve('admin_settings_cache')`
- **Cache Invalidation**: `sugar_cache_clear('admin_settings_cache')`

## UI Functionality

### Class Properties
- **settings**: Array storing retrieved configuration values
- **checkbox_fields**: Defines fields that should be treated as boolean checkboxes
- **config_categories**: Whitelist of valid configuration categories
- **disable_custom_fields**: Prevents custom field creation on this module

### Form Processing
The class is designed to work with administration forms:
- **Field Validation**: Only processes fields from valid configuration categories
- **Checkbox Handling**: Special handling for boolean checkbox fields
- **Array Support**: Converts multi-select arrays to comma-separated values

### Error Display
Integrates with SuiteCRM's admin error display system:
- **SMTP Warnings**: Uses `displayAdminError()` for SMTP configuration issues
- **Localization**: Supports translated error messages via `translate()` function

## Security Features

### Password Encryption
Sensitive configuration values are encrypted:
- **LDAP Passwords**: `ldap_admin_password` encrypted before storage
- **Proxy Passwords**: `proxy_password` encrypted before storage
- **Decryption**: Automatic decryption on retrieval using `decrypt_after_retrieve()`
- **Encryption**: Uses `encrpyt_before_save()` method for storage

### SQL Injection Protection
All database operations use proper quoting:
- **Parameter Quoting**: `$this->db->quote()` for all user inputs
- **Query Safety**: Prepared statements prevent SQL injection attacks

## Constructor and Initialization

### __construct()
- **Parent Initialization**: Calls `parent::__construct()` for SugarBean setup
- **Custom Fields**: Calls `setupCustomFields('Administration')` for field configuration

The Administration class serves as the central hub for all system configuration management in SuiteCRM, providing a secure, cached, and integrated approach to storing and retrieving administrative settings. 