# ArrayMapper.php Documentation

/**
 * @fileoverview Sophisticated array transformation engine using configurable mapping definitions with support for direct mappings, regex patterns, blacklists, and nested structure creation. Core component for data serialization and restructuring operations.
 * @package SuiteCRM\Utility
 * @author SalesAgility Ltd.
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

The `ArrayMapper` class provides a powerful, configurable engine for transforming arrays and objects using mapping definitions. It supports direct field mappings, regex-based pattern matching, blacklisting, and sophisticated nested structure creation. The class is designed for complex data transformations, particularly in serialization scenarios.

## Class Structure

### Namespace
- **Namespace**: `SuiteCRM\Utility`
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility
- **Dependencies**: InvalidArgumentException, Symfony\Component\Yaml\Yaml

### Properties

#### $mappable (private)
**Type**: `array|object`
**Purpose**: The source data to be mapped (array or object)
**Constraints**: Must be array or object, validated in setter

#### $mappings (private)
**Type**: `array`
**Purpose**: Direct field mappings from source field to target path
**Format**: `['source_field' => 'target.path.structure']`

#### $regexMappings (private)
**Type**: `array|null`
**Purpose**: Pattern-based mappings using regex with capture group substitution
**Format**: `['/pattern/' => 'target.path.@1']`

#### $blacklist (private)
**Type**: `array`
**Purpose**: List of field paths to exclude from mapping
**Format**: Array of dot-notation path strings

#### $hideEmptyValues (private)
**Type**: `bool`
**Purpose**: Whether to exclude fields with empty values (null or '')
**Default**: `true`

#### $path (private)
**Type**: `array`
**Purpose**: Current path stack during recursive mapping
**Usage**: Tracks current position in nested structure traversal

#### $cleanArray (private)
**Type**: `array`
**Purpose**: The output array being constructed during mapping
**Reset**: Cleared when new mappable data is set

## Internal API Calls

### Getters and Setters (Fluent Interface)

#### getMappings() / setMappings()
**Purpose**: Access and configure direct field mappings
**Fluent**: `setMappings()` returns `$this` for method chaining

#### getMappable() / setMappable()
**Purpose**: Access and configure source data for mapping
**Validation**: Throws `InvalidArgumentException` for non-array/non-object data
**Side Effect**: Resets `$cleanArray` when new mappable is set

#### getRegexMappings() / setRegexMappings()
**Purpose**: Access and configure regex-based pattern mappings
**Type Safety**: Enforces array type for regex mappings

#### getBlacklist() / setBlacklist()
**Purpose**: Access and configure field exclusion list
**Fluent**: Returns `$this` for method chaining

#### isHideEmptyValues() / setHideEmptyValues()
**Purpose**: Access and configure empty value filtering behavior
**Fluent**: Returns `$this` for method chaining

### Factory Methods

#### make() Method (Static)
**Purpose**: Static factory method for cleaner instantiation syntax
**Return**: `ArrayMapper` - New instance
**Benefit**: Enables fluent method chaining from instantiation

### Core Mapping Methods

#### map() Method
**Purpose**: Main mapping operation that transforms the mappable data
**Parameters**: `$keys` (array|null): Optional array of keys to process (defaults to all)
**Return**: `array` - The transformed array structure

**Process**:
1. Determines if mappable is array or object
2. Calls appropriate mapping method (`mapArray()` or `mapObject()`)
3. Returns the constructed `$cleanArray`

#### mapArray() Method (Private)
**Purpose**: Maps array data with optional key filtering
**Parameters**:
- `$array` (array): Source array to map
- `$keys` (array|null): Optional keys to process

**Process**:
1. Uses provided keys or extracts all array keys
2. Iterates through keys, calling `loop()` for each existing key
3. Handles missing keys gracefully

#### mapObject() Method (Private)
**Purpose**: Maps object properties with optional key filtering
**Parameters**:
- `$obj` (object): Source object to map
- `$keys` (array|null): Optional property names to process

**Process**:
1. Uses provided keys or extracts all object property names
2. Iterates through keys, calling `loop()` for each property
3. Handles undefined properties by passing null values

### Core Processing Loop

#### loop() Method (Private)
**Purpose**: Main processing loop for individual field/value pairs
**Parameters**:
- `$key` (string): Field name or property key
- `$value` (mixed): Value to process

**Processing Pipeline**:
1. **Value Cleaning**: Calls `fixStringValue()` for string processing
2. **Empty Check**: Calls `shouldSkipEmpty()` for empty value filtering
3. **Path Update**: Calls `updatePath()` to build current path
4. **Blacklist Check**: Calls `isBlackListed()` to check exclusions
5. **Structure Handling**: Calls `handleStructure()` for nested objects/arrays
6. **Direct Mapping**: Calls `handleMap()` for exact field matches
7. **Regex Mapping**: Calls `handleRegex()` for pattern matches
8. **Default Handling**: Calls `handleDefault()` for unmapped fields

### Handler Methods

#### handleStructure() Method (Private)
**Purpose**: Processes nested arrays and objects recursively
**Parameters**: `$structure` (mixed): Value to check for array/object type
**Return**: `bool` - True if structure was handled

**Process**:
- Detects arrays and objects
- Recursively calls mapping methods for nested structures
- Manages path stack for proper nesting

#### handleMap() Method (Private)
**Purpose**: Processes direct field mappings from configuration
**Parameters**:
- `$value` (mixed): Value to map
- `$path` (string): Current field path

**Return**: `bool` - True if mapping was found and applied

**Process**:
1. Checks if path exists in `$mappings` array
2. Retrieves mapped path from configuration
3. Calls `handleValue()` to place value at mapped location

#### handleRegex() Method (Private)
**Purpose**: Processes regex-based pattern mappings with capture group substitution
**Parameters**:
- `$value` (mixed): Value to map
- `$path` (string): Current field path

**Return**: `bool` - True if regex pattern matched and was applied

**Process**:
1. Iterates through all regex patterns in `$regexMappings`
2. Tests current path against each regex pattern
3. Captures match groups for substitution
4. Replaces `@n` placeholders with captured groups
5. Calls `handleValue()` to place value at transformed path

**Substitution Example**:
- Pattern: `/^phone_([a-z_]+)$/`
- Path: `'phone_mobile'`
- Target: `'phone.@1'`
- Result: `'phone.mobile'`

#### handleDefault() Method (Private)
**Purpose**: Handles unmapped fields using their original path
**Parameters**:
- `$value` (mixed): Value to place
- `$path` (string): Original field path

**Process**: Places value at original path location as fallback

### Utility Methods

#### updatePath() Method (Private)
**Purpose**: Manages path stack for nested structure tracking
**Parameters**: `$key` (string): Current key to add to path
**Return**: `string` - Dot-notation path string

**Process**:
1. Adds key to path array
2. Joins path components with dots
3. Returns complete path string for use in mapping

#### fixStringValue() Method (Private)
**Purpose**: Cleans and processes string values for proper encoding
**Parameters**: `$value` (mixed): Value to process
**Return**: `mixed` - Cleaned value

**String Processing**:
1. Detects HTML entities using regex `/&#?\w+;/`
2. Converts entities to UTF-8 using `mb_convert_encoding()`
3. Replaces entities in original string
4. Returns cleaned string or original value for non-strings

#### shouldSkipEmpty() Method (Private)
**Purpose**: Determines if empty values should be excluded
**Parameters**: `$value` (mixed): Value to check
**Return**: `bool` - True if value should be skipped

**Empty Criteria**:
- `$hideEmptyValues` must be enabled
- Value must be `null` or empty string `''`

#### putInPath() Method (Private)
**Purpose**: Places a value at a specific path in the output array
**Parameters**:
- `$value` (mixed): Value to place
- `$path` (string): Dot-notation path

**Process**:
1. Gets reference to array location using `getArrayAtPath()`
2. Assigns value to the referenced location

#### appendInPath() Method (Private)
**Purpose**: Appends a value to an array at a specific path
**Parameters**:
- `$value` (mixed): Value to append
- `$path` (string): Dot-notation path

**Process**:
1. Gets reference to array location using `getArrayAtPath()`
2. Appends value to the array at that location

#### getArrayAtPath() Method (Private)
**Purpose**: Gets a reference to an array location for modification
**Parameters**: `$path` (string): Dot-notation path
**Return**: `array&` - Reference to array location

**Process**:
1. Splits path by dots to get path components
2. Navigates through nested array structure
3. Creates missing intermediate arrays as needed
4. Returns reference to final location

#### isBlackListed() Method (Private)
**Purpose**: Checks if a path should be excluded from mapping
**Parameters**: `$path` (string): Path to check
**Return**: `bool` - True if path is blacklisted

**Process**:
1. Checks if path exists in blacklist array
2. Manages path stack when blacklisted item found
3. Returns boolean result

#### handleValue() Method (Private)
**Purpose**: Routes value placement based on target path format
**Parameters**:
- `$value` (mixed): Value to place
- `$mappedPath` (string): Target path (may have special prefixes)

**Special Prefixes**:
- **`+` prefix**: Calls `appendInPath()` for array appending
- **No prefix**: Calls `putInPath()` for direct assignment

## External API Calls

### loadYaml() Method
**Purpose**: Loads mapping configuration from YAML file
**Parameters**: `$file` (string): Path to YAML configuration file
**Return**: `ArrayMapper` - Self for fluent chaining

**Dependencies**: Uses `Symfony\Component\Yaml\Yaml` parser

**Configuration Sections**:
- **mappings**: Direct field mappings
- **regexMappings**: Pattern-based mappings  
- **blacklist**: Field exclusion list

**Process**:
1. Creates new `Yaml` parser instance
2. Parses YAML file using `parseFile()`
3. Extracts configuration sections if present
4. Sets class properties from parsed configuration

## Configuration Format

### YAML Structure
```yaml
mappings:
  source_field: target.nested.path
  another_field: different.location

regexMappings:
  /^pattern_(.+)$/: target.@1
  /^email[0-9]*$/: +emails  # Append to array

blacklist:
  - excluded_field
  - another.excluded.path
```

### Direct Mappings
**Format**: `source_field: target.path`
**Example**: `first_name: name.first`
**Result**: Value from `first_name` placed at `name.first` in output

### Regex Mappings
**Format**: `/pattern/: target.@n`
**Capture Groups**: `@1`, `@2`, etc. replaced with regex captures
**Array Append**: `+` prefix appends to array instead of replacing

**Examples**:
- `/^phone_(.+)$/: phone.@1` → `phone_mobile` becomes `phone.mobile`
- `/^email[0-9]*$/: +email` → All email fields appended to `email` array

### Blacklisting
**Purpose**: Exclude specific fields from output
**Format**: Array of dot-notation paths
**Matching**: Exact path string matching

## Usage Patterns

### Basic Usage
```php
$mapper = ArrayMapper::make()
    ->setMappings(['old_field' => 'new.location'])
    ->setMappable($sourceArray)
    ->setHideEmptyValues(true);

$result = $mapper->map();
```

### YAML Configuration
```php
$mapper = new ArrayMapper();
$mapper->loadYaml('config/mappings.yml');
$mapper->setMappable($data);
$result = $mapper->map();
```

### Selective Key Processing
```php
$mapper = new ArrayMapper();
$mapper->setMappable($data);
$result = $mapper->map(['field1', 'field2', 'field3']); // Only process specific keys
```

### Complex Transformation
```php
$mapper = ArrayMapper::make()
    ->setMappings([
        'user_id' => 'meta.user.id',
        'user_name' => 'meta.user.name'
    ])
    ->setRegexMappings([
        '/^phone_(.+)$/' => 'contact.phone.@1',
        '/^email[0-9]*$/' => '+contact.emails'
    ])
    ->setBlacklist(['internal_field', 'temp_data'])
    ->setMappable($userData);

$structured = $mapper->map();
```

## Performance Considerations

### Optimization Features
- **Selective Processing**: Can process only specified keys
- **Empty Value Filtering**: Reduces output size by excluding empty values
- **Reference Usage**: Uses references for array manipulation to avoid copying
- **Lazy Evaluation**: Only processes paths that aren't blacklisted

### Memory Management
- **Path Stack**: Efficient path tracking using array stack
- **Reference Passing**: Minimizes memory usage through references
- **Configuration Reuse**: Single YAML load can serve multiple mapping operations

## Error Handling

### Exception Types
**InvalidArgumentException**: Thrown when mappable data is not array or object
**YAML Errors**: File parsing errors handled by Symfony YAML component

### Safe Operations
- **Missing Keys**: Gracefully handles missing array keys or object properties
- **Type Safety**: Validates input types before processing
- **Path Safety**: Creates intermediate array structures as needed

## Advanced Features

### Nested Structure Support
- **Recursive Processing**: Automatically handles nested arrays and objects
- **Path Tracking**: Maintains current path context during recursion
- **Structure Creation**: Creates missing intermediate paths automatically

### Flexible Value Placement
- **Direct Assignment**: Standard value placement at specified paths
- **Array Appending**: Special `+` prefix for building arrays
- **Capture Groups**: Regex substitution with captured values

### Configuration-Driven
- **YAML Support**: External configuration files for mapping rules
- **Runtime Configuration**: Programmatic configuration via fluent setters
- **Multi-Format**: Supports both hardcoded and file-based configurations

## Integration Points

### BeanJsonSerializer
**Usage**: Primary consumer for SugarBean serialization
**Configuration**: Uses `BeanJsonSerializer.yml` for field mappings
**Integration**: Provides core transformation engine

### YAML System
**Dependency**: Symfony YAML component for configuration parsing
**Files**: Supports standard YAML configuration files
**Structure**: Predefined configuration sections (mappings, regexMappings, blacklist)

### Data Sources
**Arrays**: Native support for associative and indexed arrays
**Objects**: Support for object property mapping
**Mixed Data**: Handles nested combinations of arrays and objects

## Design Patterns

### Fluent Interface
**Implementation**: Most setters return `$this` for method chaining
**Benefit**: Enables readable, chainable configuration
**Factory Integration**: Combines with factory method for clean syntax

### Strategy Pattern
**Handlers**: Different handling strategies for various mapping types
**Extensibility**: Easy to add new mapping strategies
**Separation**: Clear separation between mapping logic and value placement

### Builder Pattern
**Configuration**: Incremental configuration building through fluent interface
**Flexibility**: Allows partial configuration and runtime modification
**Reusability**: Configured instances can be reused with different data 