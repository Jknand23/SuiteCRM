# SuiteValidator.php Documentation

/**
 * @fileoverview Validation utility class providing ID, key, and field type validation methods for SuiteCRM. Includes configurable validation patterns and support for percentage field detection.
 * @package SuiteCRM\Utility
 * @author SalesAgility Ltd.
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

The `SuiteValidator` class provides essential validation functionality for SuiteCRM, focusing on ID validation, key validation, and field type detection. It supports both strict and flexible validation modes through configurable patterns.

## Class Structure

### Namespace
- **Namespace**: `SuiteCRM\Utility`
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility

### Properties
The class contains no explicit properties and relies on global configuration and utility functions.

## Internal API Calls

### isValidId() Method
**Purpose**: Validates whether a given ID is in a valid format
**Parameters**:
- `$id` (string|null): The ID to validate

**Return**: `bool` - True if ID is valid, false otherwise

**Validation Logic**:
1. Returns `false` if ID is empty or null
2. Uses `getIdValidationPattern()` to obtain validation pattern
3. Accepts numeric IDs as valid
4. For string IDs, validates against the configured pattern using `preg_match()`

**Integration**: Calls `getIdValidationPattern()` for pattern retrieval

### isValidKey() Method
**Purpose**: Validates whether a given key is in a valid format
**Parameters**:
- `$key` (string|null): The key to validate

**Return**: `bool` - True if key is valid, false otherwise

**Validation Logic**:
1. Returns `false` if key is empty or null
2. Uses `getKeyValidationPattern()` to obtain validation pattern
3. Accepts numeric keys as valid
4. For string keys, validates against the configured pattern using `preg_match()`

**Integration**: Calls `getKeyValidationPattern()` for pattern retrieval

### isPercentageField() Method
**Purpose**: Determines if a field name represents a percentage field
**Parameters**:
- `$fieldname` (string): The field name to check

**Return**: `bool` - True if field is percentage type, false otherwise

**Detection Criteria**:
- Exact match: `'aos_products_quotes_vat'`
- Contains (case-insensitive): `'pct'`, `'percent'`, `'percentage'`

**Logic**: Uses `strpos()` with `strtolower()` for case-insensitive substring matching

### getIdValidationPattern() Method
**Purpose**: Retrieves the ID validation pattern based on configuration
**Return**: `string` - Regular expression pattern for ID validation

**Configuration Logic**:
- **Strict Mode**: Uses GUID pattern `/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i`
- **Flexible Mode**: Falls back to `get_id_validation_pattern()` utility function

**Dependency**: Accesses global `$sugar_config['strict_id_validation']`

### getKeyValidationPattern() Method (Protected)
**Purpose**: Retrieves the key validation pattern based on configuration
**Return**: `string` - Regular expression pattern for key validation

**Configuration Logic**:
- **Custom Pattern**: Uses `$sugar_config['key_validation_pattern']` if defined
- **Default Pattern**: `/^[A-Z0-9\-\_\.]*$/i` (alphanumeric, hyphens, underscores, dots)

**Dependency**: Accesses global `$sugar_config['key_validation_pattern']`

## Configuration Dependencies

### Sugar Config Integration
The validator relies on global configuration variables:

#### strict_id_validation
- **Type**: `boolean`
- **Purpose**: Controls ID validation strictness
- **Effect**: 
  - `true`: Enforces strict GUID format validation
  - `false`: Uses flexible pattern from utility function

#### key_validation_pattern
- **Type**: `string` (regex pattern)
- **Purpose**: Custom pattern for key validation
- **Default**: `/^[A-Z0-9\-\_\.]*$/i`
- **Allows**: Alphanumeric characters, hyphens, underscores, periods

## External API Calls

### get_id_validation_pattern()
**Purpose**: Utility function providing flexible ID validation pattern
**Used when**: `strict_id_validation` is disabled or not set
**Integration**: Called from `getIdValidationPattern()` as fallback

## Validation Patterns

### Strict ID Pattern
```regex
/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i
```
- **Format**: Standard GUID/UUID format
- **Optional**: Curly braces around the GUID
- **Case**: Insensitive

### Default Key Pattern
```regex
/^[A-Z0-9\-\_\.]*$/i
```
- **Allowed**: Letters, numbers, hyphens, underscores, periods
- **Case**: Insensitive
- **Length**: No length restrictions

## Usage Patterns

### ID Validation
```php
$validator = new SuiteValidator();

// Valid IDs
$validator->isValidId('123');  // true (numeric)
$validator->isValidId('ABC123-DEF456-GHI789-JKL012-MNO345PQR678');  // true (GUID format)

// Invalid IDs
$validator->isValidId('');     // false (empty)
$validator->isValidId(null);   // false (null)
```

### Key Validation
```php
$validator = new SuiteValidator();

// Valid keys
$validator->isValidKey('module_name');      // true
$validator->isValidKey('CONFIG-VALUE_2');   // true
$validator->isValidKey('123.456');          // true

// Invalid keys
$validator->isValidKey('invalid@key');      // false (contains @)
$validator->isValidKey('');                 // false (empty)
```

### Percentage Field Detection
```php
$validator = new SuiteValidator();

// Percentage fields
$validator->isPercentageField('aos_products_quotes_vat');  // true (exact match)
$validator->isPercentageField('discount_pct');            // true (contains 'pct')
$validator->isPercentageField('tax_percentage');          // true (contains 'percentage')
$validator->isPercentageField('margin_percent');          // true (contains 'percent')

// Non-percentage fields
$validator->isPercentageField('amount');                  // false
$validator->isPercentageField('quantity');               // false
```

## Error Handling

The validator uses safe validation approaches:
- **Null Safety**: Explicitly checks for empty/null values
- **Type Safety**: Handles both string and numeric inputs appropriately
- **Case Insensitivity**: Uses `strtolower()` for percentage field detection
- **Pattern Safety**: Uses `preg_match()` with proper error handling

## Integration Points

### Global Dependencies
- **$sugar_config**: Accesses configuration for validation patterns
- **get_id_validation_pattern()**: Utility function for flexible ID validation

### Module Usage
- **Field Validation**: Used throughout SuiteCRM for validating user input
- **Security**: Ensures IDs and keys conform to expected formats
- **Data Processing**: Percentage field detection for specialized handling

## Security Considerations

### Input Validation
- **ID Format**: Prevents injection of malformed IDs
- **Key Format**: Restricts keys to safe character sets
- **Pattern Matching**: Uses regex for precise format validation

### Configuration Security
- **Pattern Customization**: Allows administrators to define custom validation patterns
- **Strict Mode**: Provides enhanced security through stricter validation patterns 