# StringValidator.php Documentation

/**
 * @fileoverview String validation utility class for common string operations and checks
 * @package SuiteCRM\Utility
 * @namespace SuiteCRM\Utility
 * @copyright SugarCRM Inc. (2004-2013), SalesAgility Ltd. (2011-2018)
 * @license AGPL-3.0
 */

## Overview

The `StringValidator` class provides static utility methods for performing common string validation operations. It offers type-safe string comparison methods with proper error handling and input validation.

## Class Definition

### StringValidator
- **Namespace**: `SuiteCRM\Utility`
- **Type**: Static utility class
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility

## Methods

### startsWith()
```php
public static function startsWith($haystack, $needle)
```

**Purpose**: Determines if a string starts with a specific substring

**Parameters**:
- `$haystack` (string): The string to search within
- `$needle` (string): The substring to search for at the beginning

**Returns**: `bool` - True if `$haystack` starts with `$needle`, false otherwise

**Error Handling**: Throws `\InvalidArgumentException` if either parameter is not a string

**Algorithm**: Uses `substr()` comparison for efficient prefix checking

### endsWith()
```php
public static function endsWith($haystack, $needle)
```

**Purpose**: Determines if a string ends with a specific substring

**Parameters**:
- `$haystack` (string): The string to search within
- `$needle` (string): The substring to search for at the end

**Returns**: `bool` - True if `$haystack` ends with `$needle`, false otherwise

**Error Handling**: Throws `\InvalidArgumentException` if either parameter is not a string

**Special Cases**: Returns true for empty needle (any string ends with empty string)

**Algorithm**: Uses negative `substr()` with needle length for efficient suffix checking

## Internal API Calls

### PHP String Functions
- **Function**: `is_string()` - Type validation for input parameters
- **Function**: `strlen()` - Gets length of needle for comparison
- **Function**: `substr()` - Extracts string portions for comparison

### Exception Handling
- **Exception**: `\InvalidArgumentException` - Thrown for invalid input types
- **Messages**: Descriptive error messages indicating method and parameter names

## Integration Points

### Framework Integration
- Used throughout SuiteCRM for string validation operations
- Provides consistent string checking across different modules
- Part of the utility framework for common operations

### Type Safety
- Enforces strict type checking for string parameters
- Prevents common type-related bugs in string operations
- Provides clear error messages for debugging

## Usage Examples

### Basic String Prefix Checking
```php
use SuiteCRM\Utility\StringValidator;

// Check if string starts with prefix
$result = StringValidator::startsWith('Hello World', 'Hello');
// Result: true

$result = StringValidator::startsWith('Hello World', 'World');
// Result: false
```

### Basic String Suffix Checking
```php
use SuiteCRM\Utility\StringValidator;

// Check if string ends with suffix
$result = StringValidator::endsWith('Hello World', 'World');
// Result: true

$result = StringValidator::endsWith('Hello World', 'Hello');
// Result: false
```

### Empty String Handling
```php
use SuiteCRM\Utility\StringValidator;

// Empty needle handling
$result = StringValidator::endsWith('Any String', '');
// Result: true (any string ends with empty string)

$result = StringValidator::startsWith('Any String', '');
// Result: true (any string starts with empty string)
```

### Error Handling
```php
use SuiteCRM\Utility\StringValidator;

try {
    // This will throw InvalidArgumentException
    StringValidator::startsWith(123, 'test');
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
    // "StringValidator::startsWith $haystack must be a string"
}

try {
    // This will also throw InvalidArgumentException
    StringValidator::endsWith('test', null);
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
    // "StringValidator::endsWith $needle must be a string"
}
```

### Case Sensitivity
```php
use SuiteCRM\Utility\StringValidator;

// Methods are case-sensitive
$result = StringValidator::startsWith('Hello World', 'hello');
// Result: false

$result = StringValidator::endsWith('Hello World', 'WORLD');
// Result: false
```

## Performance Considerations

### Efficiency
- Uses direct `substr()` comparison instead of regular expressions
- Minimal overhead for type checking
- Efficient for both short and long strings
- No unnecessary string copying or manipulation

### Memory Usage
- Static methods reduce memory overhead
- No object instantiation required
- Minimal temporary variable usage

## Security Considerations

### Input Validation
- Strict type checking prevents type confusion attacks
- Clear error messages aid in debugging without exposing sensitive information
- No string modification or data leakage

### Safe Operations
- Read-only operations on input strings
- No side effects or state modification
- Exception-safe error handling

## Use Cases

### Module Path Validation
```php
// Check if module path starts with expected prefix
if (StringValidator::startsWith($modulePath, 'modules/')) {
    // Process module path
}
```

### File Extension Checking
```php
// Check if filename has specific extension
if (StringValidator::endsWith($filename, '.php')) {
    // Process PHP file
}
```

### URL Validation
```php
// Check if URL has HTTPS protocol
if (StringValidator::startsWith($url, 'https://')) {
    // Process secure URL
}
```

## Testing Considerations

- Methods are pure functions (no side effects)
- Easy to unit test with various input combinations
- Clear exception paths for invalid inputs
- Predictable behavior for edge cases

## Best Practices

- Always handle potential `InvalidArgumentException` when input types are uncertain
- Use for simple prefix/suffix checking without complex pattern matching
- Consider case sensitivity requirements before using
- Prefer these methods over regular expressions for simple string checks 