# JSON.php Documentation

/**
 * @fileoverview JSON encoding and decoding utility class providing secure JSON operations
 * @package SuiteCRM
 * @copyright SugarCRM Inc. (2004-2013), SalesAgility Ltd. (2011-2018)
 * @license AGPL-3.0
 */

## Overview

The `JSON` class provides a simplified interface for JSON encoding and decoding operations in SuiteCRM. While it originally provided custom JSON functionality, it now serves as a wrapper around PHP's built-in JSON functions with additional security features and backward compatibility.

## Class Definition

### JSON
- **Type**: Utility class with static methods
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility
- **API Status**: Public API (`@api` annotation)

## Methods

### encode()
```php
public static function encode($array, $addSecurityEnvelope = false, $encodeSpecial = false)
```

**Purpose**: Encodes PHP arrays/objects into JSON strings with optional security features

**Parameters**:
- `$array` (array): Data to encode as JSON
- `$addSecurityEnvelope` (bool): Legacy parameter for security envelope (default: false, no longer used)
- `$encodeSpecial` (bool): Whether to encode special HTML characters (default: false)

**Returns**: `string` - JSON-encoded string

**Security Features**:
- When `$encodeSpecial` is true, encodes dangerous HTML characters:
  - `<` → `\u003C`
  - `>` → `\u003E`
  - `'` → `\u0027`
  - `&` → `\u0026`

### decode()
```php
public static function decode($string, $examineEnvelope = false, $assoc = true)
```

**Purpose**: Decodes JSON strings into PHP arrays/objects

**Parameters**:
- `$string` (string): JSON string to decode
- `$examineEnvelope` (bool): Legacy parameter for security envelope examination (default: false, no longer used)
- `$assoc` (bool): Return associative arrays instead of objects (default: true)

**Returns**: `mixed` - Decoded PHP data structure

**Behavior**: Direct wrapper around PHP's `json_decode()` function

### encodeReal() (Deprecated)
```php
public static function encodeReal($string)
```

**Purpose**: Legacy method that calls `encode()`
**Status**: `@deprecated` - Use `JSON::encode()` instead
**Returns**: Result of `JSON::encode($string)`

### decodeReal() (Deprecated)
```php
public static function decodeReal($string)
```

**Purpose**: Legacy method that calls `decode()`
**Status**: `@deprecated` - Use `JSON::decode()` instead  
**Returns**: Result of `JSON::decode($string)`

## External API Calls

### PHP JSON Extension
- **Function**: `json_encode()` - Core encoding functionality
- **Function**: `json_decode()` - Core decoding functionality
- **Integration**: Direct wrapper with additional security options

## Internal API Calls

### String Processing
- **Function**: `str_replace()` - Character encoding for security
- **Usage**: Converts dangerous HTML characters to Unicode escape sequences

## UI Functionality

### AJAX Response Processing
- Used extensively in SuiteCRM's AJAX operations
- Provides secure data serialization for client-server communication
- Handles form data encoding and response formatting

### JavaScript Integration
- Ensures safe data transfer to JavaScript contexts
- Prevents XSS attacks through character encoding
- Maintains data integrity across client-server boundaries

## Security Considerations

### XSS Prevention
- Special character encoding prevents script injection
- Unicode escape sequences neutralize HTML entities
- Security envelope functionality removed (no longer evaluates JSON)

### Data Integrity
- Consistent encoding/decoding preserves data structure
- Associative array preference maintains key-value relationships
- Error handling through PHP's native JSON functions

## Usage Examples

### Basic Encoding
```php
$data = ['name' => 'John', 'age' => 30];
$json = JSON::encode($data);
// Result: {"name":"John","age":30}
```

### Secure Encoding with Special Characters
```php
$data = ['html' => '<script>alert("test")</script>'];
$json = JSON::encode($data, false, true);
// Result: {"html":"\u003Cscript\u003Ealert(\"test\")\u003C/script\u003E"}
```

### Decoding to Array
```php
$json = '{"name":"John","age":30}';
$data = JSON::decode($json);
// Result: ['name' => 'John', 'age' => 30]
```

### Decoding to Object
```php
$json = '{"name":"John","age":30}';
$data = JSON::decode($json, false, false);
// Result: stdClass object
```

## Migration Notes

- Security envelope functionality has been removed for security reasons
- `encodeReal()` and `decodeReal()` methods are deprecated
- Modern code should use `encode()` and `decode()` methods directly
- Special character encoding should be used for user-generated content 