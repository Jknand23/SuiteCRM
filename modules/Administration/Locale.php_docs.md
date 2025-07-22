# Locale.php Documentation

/**
 * @fileoverview Locale and internationalization configuration interface for SuiteCRM
 * @package Administration
 * @copyright SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

The Locale.php file provides the administrative interface for configuring localization settings in SuiteCRM. It manages language settings, database collation, character encoding, name formatting, and other locale-specific configurations that affect how the system displays and processes international data.

## Database Operations

### Database Collation Management
```php
$collationOptions = DBManagerFactory::getInstance()->getCollationList();
if (!empty($collationOptions)) {
    if (!isset($sugar_config['dbconfigoption']['collation'])) {
        $sugar_config['dbconfigoption']['collation'] = DBManagerFactory::getInstance()->getDefaultCollation();
    }
}
```
- **Purpose**: Manages database character collation settings
- **Scope**: System-wide database character handling
- **Impact**: Affects sorting, searching, and data comparison operations

### Database Reconnection
```php
if (array_key_exists('collation', $sugar_config['dbconfigoption']) && $_REQUEST['collation'] != $sugar_config['dbconfigoption']['collation']) {
    DBManagerFactory::getInstance()->disconnect();
    DBManagerFactory::getInstance()->connect();
}
```
- **Purpose**: Reconnects database when collation changes
- **Requirement**: Ensures new collation takes effect immediately
- **Safety**: Graceful disconnection and reconnection process

### Configuration Persistence
```php
$cfg = new Configurator();
$cfg->populateFromPost();
$cfg->handleOverride();
```
- **Purpose**: Saves locale configuration changes
- **Storage**: Updates system configuration files
- **Scope**: Global configuration affecting all users

## Internal API Calls

### Authentication and Authorization
```php
global $current_user, $sugar_config;
if (!is_admin($current_user)) {
    sugar_die("Unauthorized access to administration.");
}
```
- **Purpose**: Ensures only administrators can modify locale settings
- **Security**: Prevents unauthorized configuration changes
- **Error Handling**: Graceful termination for unauthorized access

### Configuration Management
```php
require_once('modules/Configurator/Configurator.php');
$cfg = new Configurator();
```
- **Purpose**: Manages system configuration through Configurator class
- **Integration**: Central configuration management system
- **Functionality**: Handles configuration validation and persistence

### Form Processing
```php
echo getClassicModuleTitle(
    "Administration",
    array(
        "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME', 'Administration')."</a>",
        $mod_strings['LBL_MANAGE_LOCALE'],
    ),
    false
);
```
- **Purpose**: Renders standardized module title with breadcrumbs
- **Navigation**: Provides hierarchical navigation structure
- **Internationalization**: Uses language files for titles

## External API Calls

### Database Manager Integration
```php
DBManagerFactory::getInstance()->getCollationList();
DBManagerFactory::getInstance()->getDefaultCollation();
```
- **Purpose**: Retrieves available database collation options
- **Database Vendor**: Works across different database systems
- **Compatibility**: Adapts to specific database capabilities

### Language Processing
```php
$sugar_smarty->assign('LANGUAGES', get_languages());
```
- **Purpose**: Retrieves available system languages
- **Integration**: System language management functions
- **UI Components**: Populates language selection dropdowns

### Locale Processing
```php
$sugar_smarty->assign('NAMEFORMATS', $locale->getUsableLocaleNameOptions($sugar_config['name_formats']));
$sugar_smarty->assign('getNameJs', $locale->getNameJs());
```
- **Purpose**: Manages name formatting options and JavaScript
- **Functionality**: Locale-specific name formatting rules
- **Client Integration**: JavaScript locale functions for UI

## UI Functionality

### Character Encoding Configuration
```php
$sugar_smarty->assign("exportCharsets", get_select_options_with_id($locale->getCharsetSelect(), $sugar_config['default_export_charset']));
```
- **Purpose**: Configures character encoding for data export
- **Options**: Multiple character set options for different regions
- **Default**: System default export character set selection

### Database Collation Selection
```php
$sugar_smarty->assign('collationOptions', get_select_options_with_id(array_combine($collationOptions, $collationOptions), $sugar_config['dbconfigoption']['collation']));
```
- **Purpose**: Provides dropdown for database collation selection
- **Display**: Available collations from database system
- **Current Selection**: Shows currently configured collation

### Name Format Configuration
```php
$sugar_smarty->assign('NAMEFORMATS', $locale->getUsableLocaleNameOptions($sugar_config['name_formats']));
```
- **Purpose**: Configures how names are displayed throughout the system
- **Options**: Various name formatting patterns (First Last, Last, First, etc.)
- **Localization**: Culture-appropriate name display formats

### Language Selection Interface
- **Available Languages**: Dropdown of installed system languages
- **Default Language**: System default language configuration
- **User Override**: Individual user language preferences

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

### Administrative Authentication
- **Admin-Only Access**: Restricted to administrator users
- **Session Validation**: Standard SuiteCRM authentication
- **Permission Checking**: Validates administrative privileges

### Configuration Protection
- **Validation**: Input validation for configuration changes
- **Atomicity**: Configuration changes applied as complete units
- **Rollback**: Maintains system stability if changes fail

## Performance Considerations

### Template Rendering
```php
$sugar_smarty = new Sugar_Smarty();
$sugar_smarty->display('modules/Administration/Locale.tpl');
```
- **Template Engine**: Efficient Smarty template rendering
- **Caching**: Template compilation and caching benefits
- **Memory**: Efficient variable assignment and cleanup

### Database Optimization
- **Connection Management**: Efficient database connection handling
- **Collation Caching**: Collation options cached for performance
- **Minimal Queries**: Limited database queries for configuration retrieval

### Configuration Loading
- **Lazy Loading**: Configuration loaded only when needed
- **Caching**: Configuration values cached in memory
- **Efficient Updates**: Minimal overhead for configuration changes

## Error Handling

### Input Validation
```php
if (isset($_REQUEST['process']) && $_REQUEST['process'] == 'true') {
    if (isset($_REQUEST['collation']) && !empty($_REQUEST['collation'])) {
        // Process collation change
    }
}
```
- **Parameter Checking**: Validates required parameters
- **Type Safety**: Ensures appropriate data types
- **Boundary Validation**: Checks for valid configuration values

### Database Error Handling
- **Connection Errors**: Graceful handling of database connection issues
- **Collation Validation**: Ensures valid collation selection
- **Fallback Options**: Default collation if specific option unavailable

### Configuration Validation
- **Consistency Checks**: Validates configuration consistency
- **Format Validation**: Ensures proper configuration format
- **Error Recovery**: Maintains system stability on invalid input

## Integration Points

### Locale Name Format Upgrade
```php
if ($locale->invalidLocaleNameFormatUpgrade()) {
    $locale->removeInvalidLocaleNameFormatUpgradeNotice();
}
```
- **Purpose**: Handles locale format upgrades between versions
- **Validation**: Checks for invalid upgrade states
- **Cleanup**: Removes upgrade notices after resolution

### Character Set Management
- **Export Functions**: Integrates with data export functionality
- **Import Functions**: Supports various character sets for data import
- **Database Compatibility**: Ensures character set compatibility

### JavaScript Integration
```php
$sugar_smarty->assign('getNameJs', $locale->getNameJs());
$sugar_smarty->assign("JAVASCRIPT", get_set_focus_js());
```
- **Client-Side Locale**: JavaScript locale functions for UI
- **Focus Management**: Standard JavaScript focus handling
- **Dynamic Updates**: Real-time locale format updates

## Configuration Options

### Regional Settings
- **Date Formats**: Locale-specific date formatting
- **Number Formats**: Regional number and currency formatting
- **Time Zones**: Time zone configuration and display

### Language Configuration
- **System Language**: Default system language
- **User Languages**: Available languages for user selection
- **Language Packs**: Integration with language pack system

### Database Localization
- **Collation**: Character sorting and comparison rules
- **Character Sets**: Database character encoding settings
- **Unicode Support**: UTF-8 and other Unicode encoding options

## Administration Features

### System-Wide Impact
- **Global Configuration**: Settings affect entire system
- **User Experience**: Impacts all user interface elements
- **Data Processing**: Affects how data is stored and retrieved

### Upgrade Compatibility
- **Version Migration**: Handles locale setting migrations
- **Backward Compatibility**: Maintains compatibility with existing data
- **Format Updates**: Updates locale formats during system upgrades

### Integration Support
- **Module Integration**: Locale settings affect all modules
- **Third-Party Compatibility**: Supports external integrations
- **API Consistency**: Maintains consistent locale handling across APIs 