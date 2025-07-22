# SuiteLogger.php Documentation

/**
 * @fileoverview PSR-3 compliant logger implementation that extends AbstractLogger to provide standardized logging functionality across SuiteCRM. Acts as a bridge between PSR-3 logging standards and SuiteCRM's native LoggerManager system.
 * @package SuiteCRM\Utility
 * @author SalesAgility Ltd.
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

The `SuiteLogger` class provides a PSR-3 compliant logging interface that seamlessly integrates with SuiteCRM's existing LoggerManager system. It translates PSR-3 log levels into appropriate SuiteCRM logger calls and supports message interpolation with context variables.

## Class Structure

### Namespace and Inheritance
- **Namespace**: `SuiteCRM\Utility`
- **Extends**: `Psr\Log\AbstractLogger`
- **Implements**: PSR-3 LoggerInterface (via AbstractLogger)

### Properties
The class uses the `#[\AllowDynamicProperties]` attribute for PHP 8.2+ compatibility but contains no explicit properties.

## Internal API Calls

### log() Method
**Purpose**: Core logging method that handles all PSR-3 log levels
**Parameters**:
- `$level` (LogLevel|string): PSR-3 log level constant
- `$message` (string): Message template with placeholder support (e.g., 'hello {user}')
- `$context` (array): Context data for message interpolation (e.g., `['user' => 'joe']`)

**Log Level Mapping**:
- `LogLevel::EMERGENCY` → `$log->fatal('[EMERGENCY] ' . $message)`
- `LogLevel::ALERT` → `$log->fatal('[ALERT] ' . $message)`
- `LogLevel::CRITICAL` → `$log->fatal('[CRITICAL] ' . $message)`
- `LogLevel::ERROR` → `$log->fatal('[ERROR] ' . $message)`
- `LogLevel::WARNING` → `$log->warn('[WARNING] ' . $message)`
- `LogLevel::NOTICE` → `$log->warn('[NOTICE] ' . $message)`
- `LogLevel::INFO` → `$log->info('[INFO] ' . $message)`
- `LogLevel::DEBUG` → `$log->debug('[DEBUG] ' . $message)`

**Integration**: Delegates to `LoggerManager::getLogger()` for actual log writing

### interpolate() Method
**Purpose**: Private helper for PSR-3 message interpolation
**Parameters**:
- `$message` (string): Message template with {placeholder} syntax
- `$context` (array): Replacement values

**Process**:
1. Returns original message if context is empty
2. Builds replacement array with braces around context keys
3. Validates that values can be cast to string (excludes arrays and non-stringable objects)
4. Uses `strtr()` for efficient string replacement

## Error Handling

### InvalidArgumentException
**Thrown when**: Invalid log level is provided to `log()` method
**Message**: "Invalid log level type: {level}"
**Purpose**: Ensures only valid PSR-3 log levels are processed

## Dependencies

### Required Classes
- `Psr\Log\AbstractLogger` - Base PSR-3 logger implementation
- `Psr\Log\InvalidArgumentException` - PSR-3 exception for invalid arguments  
- `Psr\Log\LogLevel` - PSR-3 log level constants
- `LoggerManager` - SuiteCRM's global logger manager (used via static call)

### Integration Points
- **LoggerManager**: Uses `LoggerManager::getLogger()` to obtain SuiteCRM logger instance
- **Global Logger**: All log messages are routed through SuiteCRM's global logging system
- **PSR-3 Standards**: Fully compliant with PSR-3 logging interface specifications

## Usage Patterns

### Basic Logging
```php
$logger = new SuiteLogger();
$logger->info('User logged in', ['user_id' => '123']);
$logger->error('Database connection failed', ['host' => 'localhost']);
```

### Message Interpolation
```php
$logger->warning('User {username} failed login attempt {attempts} times', [
    'username' => 'john_doe',
    'attempts' => 3
]);
// Results in: "User john_doe failed login attempt 3 times"
```

### Exception Logging
```php
try {
    // risky operation
} catch (Exception $e) {
    $logger->critical('System failure: {error}', ['error' => $e->getMessage()]);
}
```

## PSR-3 Compliance

### Implemented Features
- ✅ All 8 PSR-3 log levels supported
- ✅ Message interpolation with {placeholder} syntax
- ✅ Context array support
- ✅ InvalidArgumentException for invalid levels
- ✅ String-only value interpolation (safe handling of objects/arrays)

### Integration Benefits
- **Standardization**: Provides consistent logging interface across SuiteCRM
- **Interoperability**: Compatible with PSR-3 aware libraries and frameworks
- **Message Templates**: Supports structured logging with context separation
- **Type Safety**: Validates log levels and context values

## Configuration Dependencies

The logger relies on SuiteCRM's existing logger configuration:
- Log levels are handled by the underlying LoggerManager configuration
- File paths, rotation, and formatting are managed by SuiteCRM's logging system
- No additional configuration required for PSR-3 compliance layer 