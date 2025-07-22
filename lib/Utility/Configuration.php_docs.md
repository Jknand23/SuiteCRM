# Configuration.php Documentation

/**
 * @fileoverview ArrayAccess-compliant configuration wrapper class providing object-oriented access to SuiteCRM's Configurator settings. Implements array-like access patterns with validation and error handling for configuration management.
 * @package SuiteCRM\Utility
 * @author SalesAgility Ltd.
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

The `Configuration` class provides an object-oriented, array-like interface to SuiteCRM's configuration system. It implements the `ArrayAccess` interface to enable intuitive configuration access while adding validation and error handling on top of the underlying Configurator system.

## Class Structure

### Namespace
- **Namespace**: `SuiteCRM\Utility`
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility
- **Implements**: `ArrayAccess` interface for array-like access

### Properties
#### $container (private)
**Type**: `array`
**Purpose**: Stores the configuration data from Configurator
**Source**: Initialized from `(new \Configurator())->config`

## Internal API Calls

### Constructor (__construct)
**Purpose**: Initializes the configuration container with Configurator data
**Process**:
1. Accesses global `$sugar_config` variable
2. Requires `modules/Configurator/Configurator.php`
3. Creates new `Configurator()` instance
4. Extracts `->config` property to `$this->container`

**Dependencies**: 
- Global `$sugar_config` array
- `\Configurator` class from modules/Configurator/

## ArrayAccess Implementation

### offsetSet() Method
**Purpose**: Sets configuration values with validation
**Parameters**:
- `$offset` (mixed): Configuration key to set
- `$value` (mixed): Value to assign

**Validation Rules**:
1. **Null Offset Check**: Throws `Exception` if offset is null
2. **Existence Check**: Throws `Exception` if key doesn't exist in container
3. **Assignment**: Sets value if validation passes

**Exceptions**:
- `Exception('[Configuration][missing offset]')` - When offset is null
- `Exception('[Configuration][not found]')` - When key doesn't exist

### offsetExists() Method
**Purpose**: Checks if a configuration key exists
**Parameters**:
- `$offset` (mixed): Configuration key to check

**Return**: `bool` - True if key exists, false otherwise
**Implementation**: Uses `isset($this->container[$offset])`

### offsetUnset() Method
**Purpose**: Removes a configuration key from the container
**Parameters**:
- `$offset` (mixed): Configuration key to remove

**Implementation**: Uses `unset($this->container[$offset])`
**Note**: No validation - allows removal of any key

### offsetGet() Method
**Purpose**: Retrieves configuration values with null coalescing
**Parameters**:
- `$offset` (mixed): Configuration key to retrieve

**Return**: `mixed|null` - Configuration value or null if not found
**Implementation**: Uses null coalescing operator `??` for safe access

## Dependencies

### Configurator Class
**Location**: `modules/Configurator/Configurator.php`
**Purpose**: SuiteCRM's main configuration management class
**Property Used**: `->config` - Contains the configuration array
**Integration**: Configuration class wraps Configurator's config property

### Global Variables
#### $sugar_config
**Type**: `array`
**Purpose**: Global configuration array
**Usage**: Accessed in constructor, though not directly used
**Scope**: Available throughout SuiteCRM application

### Exception Handling
**Class**: `SuiteCRM\Exception\Exception`
**Usage**: Thrown for validation failures in `offsetSet()`
**Integration**: Provides consistent error handling across SuiteCRM

## Usage Patterns

### Array-Like Access
```php
$config = new Configuration();

// Reading configuration values
$dbHost = $config['dbconfig']['db_host_name'];
$siteName = $config['site_name'];

// Setting configuration values (only if key exists)
$config['log_level'] = 'debug';
$config['cache_expire_timeout'] = 3600;

// Checking if configuration exists
if (isset($config['custom_setting'])) {
    $customValue = $config['custom_setting'];
}

// Removing configuration
unset($config['temporary_setting']);
```

### Safe Value Retrieval
```php
$config = new Configuration();

// With null coalescing for defaults
$timeout = $config['session_timeout'] ?? 300;
$theme = $config['default_theme'] ?? 'SuiteP';

// Checking existence before access
if ($config->offsetExists('database_backup_enabled')) {
    $backupEnabled = $config['database_backup_enabled'];
}
```

### Configuration Validation
```php
$config = new Configuration();

try {
    // This will succeed if 'log_level' key exists
    $config['log_level'] = 'info';
    
    // This will throw exception if key doesn't exist
    $config['non_existent_key'] = 'value';
} catch (Exception $e) {
    // Handle configuration error
    if (strpos($e->getMessage(), '[Configuration][not found]') !== false) {
        // Key doesn't exist in configuration
    }
}
```

### Bulk Configuration Access
```php
$config = new Configuration();

// Access nested configuration
$dbConfig = [
    'host' => $config['dbconfig']['db_host_name'] ?? 'localhost',
    'name' => $config['dbconfig']['db_name'] ?? 'suitecrm',
    'user' => $config['dbconfig']['db_user_name'] ?? 'root',
    'password' => $config['dbconfig']['db_password'] ?? '',
];
```

## Configuration Categories

### Database Configuration
- `dbconfig['db_host_name']` - Database host
- `dbconfig['db_name']` - Database name  
- `dbconfig['db_user_name']` - Database username
- `dbconfig['db_password']` - Database password
- `dbconfig['db_type']` - Database type (mysql, etc.)

### System Configuration
- `site_url` - Base URL of SuiteCRM installation
- `log_level` - Application logging level
- `cache_expire_timeout` - Cache expiration time
- `session_timeout` - User session timeout
- `default_theme` - Default UI theme

### Feature Flags
- `enable_line_editing` - Enable inline editing
- `disable_export` - Disable data export functionality
- `require_accounts` - Require account association
- `portal_on` - Enable customer portal

## Error Handling

### Exception Types
#### Missing Offset Exception
**Trigger**: Attempting to set value with null offset
**Message**: `'[Configuration][missing offset]'`
**Purpose**: Prevents setting values without proper keys

#### Not Found Exception  
**Trigger**: Attempting to set value for non-existent key
**Message**: `'[Configuration][not found]'`
**Purpose**: Prevents creation of new configuration keys

### Safe Operations
- **Reading**: `offsetGet()` returns null for missing keys (no exception)
- **Checking**: `offsetExists()` safely checks key existence
- **Unsetting**: `offsetUnset()` works on any key (no validation)

## Security Considerations

### Validation Benefits
- **Key Validation**: Prevents setting of arbitrary configuration keys
- **Existence Check**: Ensures only existing configuration can be modified
- **Null Safety**: Prevents null key assignments

### Access Control
- **Read Access**: Full read access to all configuration values
- **Write Access**: Limited to existing configuration keys only
- **Key Creation**: Prevented - cannot create new configuration keys

## Performance Considerations

### Initialization
- **Single Load**: Configuration loaded once during object construction
- **Configurator Caching**: Benefits from Configurator's internal caching
- **Memory Usage**: Stores full configuration in memory for fast access

### Array Access
- **Native PHP**: Uses native PHP array access for optimal performance
- **Null Coalescing**: Efficient null checking with `??` operator
- **Isset Check**: Fast existence checking with `isset()`

## Integration Points

### Configurator System
- **Data Source**: Gets configuration from `Configurator->config`
- **Validation**: Relies on Configurator for configuration structure
- **Updates**: Changes affect the same configuration container

### Global Configuration
- **$sugar_config**: Accesses global configuration state
- **Session State**: Configuration changes affect current session
- **Application Behavior**: Modified configuration affects SuiteCRM behavior

### Exception System
- **Error Handling**: Integrates with SuiteCRM's exception system
- **Error Messages**: Provides standardized error message format
- **Exception Propagation**: Allows calling code to handle configuration errors

## Design Patterns

### Adapter Pattern
- **Purpose**: Adapts Configurator interface to ArrayAccess interface
- **Benefit**: Provides familiar array-like syntax for configuration access
- **Abstraction**: Hides Configurator complexity behind simple interface

### Validation Pattern
- **Write Validation**: Validates configuration changes before applying
- **Existence Checking**: Ensures configuration integrity
- **Error Feedback**: Provides clear error messages for validation failures

## Future Extensibility

### Potential Enhancements
- **Type Validation**: Could add type checking for configuration values
- **Change Tracking**: Could track configuration changes for audit purposes
- **Nested Access**: Could support dot notation for nested configuration access
- **Backup/Restore**: Could add configuration backup and restore functionality
- **Validation Rules**: Could add custom validation rules per configuration key 