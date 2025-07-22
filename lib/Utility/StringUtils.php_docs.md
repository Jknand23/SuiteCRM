# StringUtils.php Documentation

/**
 * @fileoverview Static utility class providing string manipulation methods for camelCase conversion, translation lookup, and string exploding functionality. Essential for handling class names, labels, and UI text formatting across SuiteCRM.
 * @package SuiteCRM\Utility
 * @author SalesAgility Ltd.
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

The `StringUtils` class provides essential string manipulation utilities for SuiteCRM, focusing on camelCase transformations, translation support, and string parsing. All methods are static, making this a utility class for string operations throughout the application.

## Class Structure

### Namespace
- **Namespace**: `SuiteCRM\Utility`
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility
- **Type**: Static utility class (all methods are static)

### Entry Point Protection
Contains SuiteCRM entry point validation to prevent direct access.

## Internal API Calls

### camelToUnderscoreCase() Method
**Purpose**: Converts camelCase strings to snake_case format
**Parameters**:
- `$input` (string): The camelCase string to convert
- `$uppercase` (bool): Whether to uppercase the result (default: true)

**Return**: `string` - Converted snake_case string

**Process**:
1. Calls `explodeCamelCase()` to break the string into components
2. Joins components with underscores using `implode('_', $shards)`
3. Optionally converts to uppercase with `strtoupper()`

**Examples**:
- `camelToUnderscoreCase('ElasticSearchEngine')` → `'ELASTIC_SEARCH_ENGINE'`
- `camelToUnderscoreCase('userName', false)` → `'user_name'`

### camelToTranslation() Method
**Purpose**: Attempts to find a translation for camelCase strings (typically class names)
**Parameters**:
- `$input` (string): The camelCase string to translate

**Return**: `string` - Translated string or human-readable fallback

**Process**:
1. Generates label key: `'LBL_' . camelToUnderscoreCase($input)`
2. Calls `translate($label)` to attempt translation lookup
3. If translation found (label !== translation), returns translated text
4. Falls back to human-readable format using `ucwords(implode(' ', explodeCamelCase($input)))`

**Examples**:
- `camelToTranslation('ElasticSearchEngine')` → Looks for `'LBL_ELASTIC_SEARCH_ENGINE'`
- If translation exists: Returns translated value
- If no translation: Returns `'Elastic Search Engine'`

### explodeCamelCase() Method
**Purpose**: Breaks camelCase strings into individual word components
**Parameters**:
- `$input` (string): The camelCase string to explode

**Return**: `string[]` - Array of word components in lowercase

**Process**:
1. Uses regex pattern: `'!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!'`
2. Captures word boundaries in camelCase strings
3. Normalizes each component:
   - All uppercase words → lowercase (`strtolower()`)
   - Mixed case words → lowercase first letter (`lcfirst()`)

**Regex Breakdown**:
- `[A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])` - Captures consecutive uppercase letters
- `[A-Za-z][a-z0-9]+` - Captures normal words starting with letter

**Examples**:
- `explodeCamelCase('ElasticSearchEngine')` → `['elastic', 'search', 'engine']`
- `explodeCamelCase('userID')` → `['user', 'id']`
- `explodeCamelCase('HTTPSConnection')` → `['https', 'connection']`

## External API Calls

### translate() Function
**Purpose**: SuiteCRM's global translation function
**Used in**: `camelToTranslation()` method
**Integration**: Looks up language labels using generated label keys

### ucwords() Function
**Purpose**: PHP's built-in function to capitalize first letter of each word
**Used in**: `camelToTranslation()` method for fallback formatting

## Utility Functions

### String Processing Functions
- `implode()` - Joins array elements with separator
- `strtoupper()` - Converts string to uppercase
- `strtolower()` - Converts string to lowercase
- `lcfirst()` - Lowercases first character
- `preg_match_all()` - Performs regex matching with capture groups

## Usage Patterns

### Label Generation
```php
// Converting class names to label keys
$className = 'ElasticSearchEngine';
$labelKey = 'LBL_' . StringUtils::camelToUnderscoreCase($className);
// Result: 'LBL_ELASTIC_SEARCH_ENGINE'
```

### Translation Lookup
```php
// Automatic translation with fallback
$translation = StringUtils::camelToTranslation('SearchConfiguration');
// Looks for: LBL_SEARCH_CONFIGURATION
// Fallback: 'Search Configuration'
```

### Database Field Naming
```php
// Converting camelCase to database field format
$fieldName = StringUtils::camelToUnderscoreCase('createdDateTime', false);
// Result: 'created_date_time'
```

### Component Extraction
```php
// Breaking down complex names
$components = StringUtils::explodeCamelCase('XMLHttpRequest');
// Result: ['xml', 'http', 'request']
```

## Integration Points

### Language System
- **Label Generation**: Creates standardized label keys for translation system
- **Translation Fallbacks**: Provides human-readable fallbacks when translations are missing
- **Automatic Formatting**: Converts technical names to user-friendly display text

### Database Integration
- **Field Naming**: Converts camelCase to standard database field naming conventions
- **Case Conversion**: Provides flexible case conversion for different contexts

### UI Components
- **Display Text**: Generates user-friendly text from class/component names
- **Form Labels**: Creates consistent labeling for form fields and UI elements

## Advanced Features

### Regex Pattern Analysis
The camelCase exploding uses sophisticated regex to handle edge cases:
- **Consecutive Capitals**: `'XMLHttpRequest'` correctly splits to `['xml', 'http', 'request']`
- **Acronyms**: Handles acronyms followed by regular words properly
- **Numbers**: Supports alphanumeric components in camelCase strings

### Case Normalization
The class provides intelligent case handling:
- **All Caps**: Converts acronyms to lowercase for consistency
- **Mixed Case**: Preserves word boundaries while normalizing case
- **Optional Uppercase**: Allows control over final case formatting

## Performance Considerations

### Static Methods
- **No Instantiation**: All methods are static, reducing object creation overhead
- **Stateless Operations**: No instance state, safe for concurrent use

### Regex Efficiency
- **Single Pass**: Uses `preg_match_all()` for single-pass string parsing
- **Optimized Pattern**: Regex pattern designed for efficient camelCase detection

## Error Handling

### Safe Operations
- **Null Safety**: Handles empty strings gracefully
- **Type Safety**: Works with string inputs, implicitly converts other types
- **Fallback Strategy**: Always provides usable output even when translations are missing 