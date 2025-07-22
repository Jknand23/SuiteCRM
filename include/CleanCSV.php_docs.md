# CleanCSV.php Documentation

/**
 * @fileoverview CSV security utility for preventing formula injection in exported data
 * @package SuiteCRM
 * @namespace SuiteCRM
 * @copyright SugarCRM Inc. (2004-2013), SalesAgility Ltd. (2011-2021)
 * @license AGPL-3.0
 */

## Overview

The `CleanCSV` class provides security functionality to prevent CSV injection attacks, also known as formula injection. It sanitizes CSV fields by escaping potentially dangerous characters that could be interpreted as formulas by spreadsheet applications like Excel, LibreOffice Calc, or Google Sheets.

## Class Definition

### CleanCSV
- **Namespace**: `SuiteCRM`
- **Type**: Utility class for CSV security
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility

## Properties

### $escapeChar
- **Type**: `string`
- **Visibility**: `protected`
- **Purpose**: Character used to escape dangerous CSV fields
- **Default**: `'` (single quote)

### $startingChars
- **Type**: `array|string[]`
- **Visibility**: `protected` 
- **Purpose**: Array of characters that trigger escaping when found at field start
- **Default**: `['=', '-', '+', '@']`

## Methods

### __construct()
```php
public function __construct($escapeChar = "'", array $startingChars = ['=', '-', '+', '@'])
```

**Purpose**: Initializes CSV cleaner with escape character and dangerous character list

**Parameters**:
- `$escapeChar` (string): Character to prefix dangerous fields (default: `'`)
- `$startingChars` (array): Characters that trigger escaping (default: `['=', '-', '+', '@']`)

### getStartingChars()
```php
public function getStartingChars()
```

**Purpose**: Returns array of characters that trigger field escaping

**Returns**: `array|string[]` - Array of dangerous starting characters

### getEscapeChar()
```php
public function getEscapeChar()
```

**Purpose**: Returns the escape character used for field sanitization

**Returns**: `string` - The escape character

### escapeField()
```php
public function escapeField($cell)
```

**Purpose**: Sanitizes a CSV field by escaping dangerous starting characters

**Parameters**:
- `$cell` (string): CSV field value to sanitize

**Returns**: `string` - Sanitized field value

**Logic**: If field starts with dangerous character, prepends escape character

## Security Considerations

### CSV Injection Prevention
- **Formula Injection**: Prevents `=SUM()`, `=CMD()` and similar attacks
- **Command Execution**: Blocks `-` commands in applications like Excel
- **Macro Execution**: Prevents `+` macro triggers
- **DDE Attacks**: Blocks `@` Dynamic Data Exchange attacks

### Attack Vectors Mitigated
1. **Excel Formula Injection**: `=cmd|'/c calc'!A0` type attacks
2. **Command Line Injection**: `-2+3+cmd|'/c calc'!A0` patterns  
3. **Macro Execution**: `+cmd|'/c calc'!A0` style attacks
4. **DDE Exploitation**: `@SUM(1+1)*cmd|'/c calc'!A0` variants

## Internal API Calls

### String Processing
- **Function**: `is_string()` - Validates input type
- **Function**: `empty()` - Checks for empty values
- **Character Detection**: Checks first character against dangerous patterns

## UI Functionality

### Export Security
- Integrates with SuiteCRM's export functionality
- Sanitizes data before CSV generation
- Maintains data integrity while preventing exploitation
- Transparent to end users

### Data Preservation
- Only modifies fields that start with dangerous characters
- Preserves original data content
- Maintains CSV format compatibility
- Reversible through escape character removal

## Usage Examples

### Basic Field Escaping
```php
$cleaner = new SuiteCRM\CleanCSV();

// Dangerous formula
$field = "=SUM(A1:A10)";
$safe = $cleaner->escapeField($field);
// Result: "'=SUM(A1:A10)"

// Safe field
$field = "Normal text";
$safe = $cleaner->escapeField($field);
// Result: "Normal text" (unchanged)
```

### Custom Configuration
```php
// Custom escape character and dangerous chars
$cleaner = new SuiteCRM\CleanCSV(
    "\t",                    // Tab as escape
    ['=', '-', '+', '@', '|'] // Additional pipe character
);

$field = "|dangerous";
$safe = $cleaner->escapeField($field);
// Result: "\t|dangerous"
```

### Batch Processing
```php
$cleaner = new SuiteCRM\CleanCSV();
$csvData = [
    ['Name', 'Formula', 'Notes'],
    ['John', '=1+1', 'Normal note'],
    ['Jane', '+MACRO()', 'Another note'],
    ['Bob', 'Safe text', '@mention']
];

foreach ($csvData as &$row) {
    foreach ($row as &$cell) {
        $cell = $cleaner->escapeField($cell);
    }
}
```

## Integration Points

### Export Functionality
- Used by SuiteCRM's CSV export features
- Integrates with report generation
- Part of data download security pipeline

### Data Processing
- Applied during data serialization
- Transparent to normal operations
- Maintains compatibility with CSV standards

## Configuration Options

### Escape Character Selection
- Single quote (`'`) is recommended default
- Tab character (`\t`) alternative for some applications
- Backslash (`\`) option for specific requirements

### Dangerous Character Lists
- Default covers most common injection vectors
- Extensible for specific security requirements
- Configurable based on target application

## Performance Considerations

- Minimal overhead for safe fields
- Only processes fields starting with dangerous characters
- Efficient string operations
- No regular expressions for better performance

## Standards Compliance

- Maintains RFC 4180 CSV format compatibility
- Works with Excel, LibreOffice, Google Sheets
- Preserves data integrity
- Follows security best practices for data export 