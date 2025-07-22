# BeanJsonSerializer.php Documentation

/**
 * @fileoverview Advanced JSON serialization utility for SugarBean objects with intelligent field mapping, nested structure creation, and configurable output formatting. Uses ArrayMapper for sophisticated field transformations based on YAML configuration.
 * @package SuiteCRM\Utility
 * @author SalesAgility Ltd.
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

The `BeanJsonSerializer` class provides sophisticated JSON serialization for SugarBean objects. It transforms flat bean properties into structured, nested JSON objects using configurable field mappings. The class offers both legacy and modern serialization approaches with support for value cleaning, structure standardization, and performance optimization.

## Class Structure

### Namespace
- **Namespace**: `SuiteCRM\Utility`
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility
- **Dependencies**: ArrayMapper, InvalidArgumentException, Person, SugarBean

### Properties
#### $mapper (private)
**Type**: `ArrayMapper`
**Purpose**: Handles the complex field mapping and transformation logic
**Initialization**: Created in constructor with YAML configuration loaded

## Internal API Calls

### Constructor (__construct)
**Purpose**: Initializes the serializer with ArrayMapper and loads YAML configuration
**Process**:
1. Creates new `ArrayMapper` instance
2. Loads configuration from `BeanJsonSerializer.yml` file
3. Sets up mapping rules, regex patterns, and blacklists

### make() Method (Static Factory)
**Purpose**: Factory method for creating BeanJsonSerializer instances
**Return**: `BeanJsonSerializer` - New instance
**Benefit**: Provides cleaner syntax for one-time usage

### serialize() Method
**Purpose**: Main serialization method that converts SugarBean to JSON string
**Parameters**:
- `$bean` (SugarBean): The bean to serialize
- `$hideEmptyValues` (bool): Whether to exclude empty values (default: true)
- `$pretty` (bool): Whether to format JSON with indentation (default: false)

**Return**: `string` - JSON representation of the bean

**JSON Flags Used**:
- `JSON_UNESCAPED_SLASHES` - Prevents escaping forward slashes
- `JSON_NUMERIC_CHECK` - Converts numeric strings to numbers
- `JSON_UNESCAPED_UNICODE` - Outputs UTF-8 directly
- `JSON_PRETTY_PRINT` - Adds indentation (when $pretty = true)

**Process**:
1. Calls `toArray()` to get structured array representation
2. Applies JSON encoding with specified flags
3. Returns formatted JSON string

### toArray() Method (Modern Implementation)
**Purpose**: Converts SugarBean to structured associative array using ArrayMapper
**Parameters**:
- `$bean` (SugarBean): The bean to serialize  
- `$hideEmptyValues` (bool): Whether to exclude empty values (default: true)
- `$loadRelationships` (bool): Whether to load bean relationships (default: false)

**Return**: `array` - Structured associative array

**Performance Note**: Relationship loading impacts performance (~70% slower)

**Process**:
1. Optionally loads bean relationships using `$bean->load_relationships()`
2. Extracts fields and keys using `getFieldsAndKeys()`
3. Configures ArrayMapper with mappable data and options
4. Uses ArrayMapper to perform sophisticated field mapping
5. Returns structured array with nested organization

### toArrayOld() Method (Legacy Implementation)
**Purpose**: Legacy serialization method with hardcoded field mapping logic
**Status**: @deprecated - Replaced by toArray() method
**Parameters**: Same as toArray() method

**Legacy Field Groupings**:
- **Meta Information**: Created/modified dates and users, assigned users
- **Name Structure**: First name, last name, salutation
- **Account Details**: Account ID, name, title, department
- **Contact Relationships**: Reports to, parent records
- **Communication**: Assistant, messenger, campaign information
- **Address/Phone/Email**: Using regex patterns for field matching

**Manual Mapping Examples**:
- `date_entered` → `meta.created.date`
- `first_name` → `name.first`
- `account_id` → `account.id`
- `phone_*` → `phone.*` (regex mapping)
- `email*` → `email[]` (array collection)

### getFieldsAndKeys() Method (Private)
**Purpose**: Extracts field data and keys from SugarBean in priority order
**Parameters**: `$bean` (SugarBean): Bean to extract data from
**Return**: `array` - [fields, keys] tuple

**Data Source Priority**:
1. **fetched_row + fetched_rel_row**: Database fetch results (preferred)
2. **column_fields**: Defined column fields array
3. **Object properties**: All object variables as fallback

**Logic**:
- Prioritizes database-fetched data for accuracy
- Merges relationship data when available
- Falls back to object introspection for completeness

### fixName() Method (Private)
**Purpose**: Standardizes name structure to prevent conflicts between different bean types
**Parameters**:
- `$bean` (SugarBean): Source bean for type checking
- `$prettyBean` (array, by reference): Target array to modify

**Logic**:
- **Person/Contact beans**: Creates `name.first` and `name.last` structure
- **Other beans**: Creates `name.name` structure with single name field
- **Type detection**: Uses `is_subclass_of(Person::class)` and module name checking

## YAML Configuration System

### Configuration File
**Location**: `lib/Utility/BeanJsonSerializer.yml`
**Purpose**: Defines field mapping rules, regex patterns, and blacklists

### Mapping Sections

#### Direct Mappings
Maps specific field names to nested paths:
```yaml
mappings:
  first_name: name.first
  last_name: name.last  
  date_entered: meta.created.date
  account_id: account.id
```

#### Regex Mappings
Pattern-based mappings with capture group substitution:
```yaml
regexMappings:
  /^email[0-9]*$/: +email              # Appends to email array
  /^phone\_([a-z_]+)$/: phone.@1       # Maps phone_mobile to phone.mobile
  /^address\_([a-z_]+)$/: address.primary.@1  # Maps address_city to address.primary.city
```

#### Blacklist
Fields to exclude from serialization:
```yaml
blacklist:
- deleted
- photo
- password_fields
- system_generated_fields
```

### ArrayMapper Integration
**Dependency**: Uses ArrayMapper for sophisticated field transformation
**Configuration Loading**: ArrayMapper loads YAML via `loadYaml()` method
**Processing**: ArrayMapper handles regex patterns, path creation, and value placement

## Output Structure Examples

### Contact Bean Serialization
```json
{
  "id": "abc123-def456",
  "name": {
    "first": "John",
    "last": "Doe",
    "salutation": "Mr."
  },
  "meta": {
    "created": {
      "date": "2023-01-15 10:30:00",
      "user_id": "user123",
      "user_name": "Admin User"
    },
    "modified": {
      "date": "2023-01-16 14:20:00", 
      "user_id": "user456",
      "user_name": "John Smith"
    },
    "assigned": {
      "user_id": "user789",
      "user_name": "Sales Rep"
    }
  },
  "account": {
    "id": "acc123",
    "name": "Acme Corp",
    "title": "CEO",
    "department": "Executive"
  },
  "phone": {
    "work": "+1-555-123-4567",
    "mobile": "+1-555-987-6543"
  },
  "email": [
    "john.doe@acme.com",
    "jdoe@acme.com"
  ],
  "address": {
    "primary": {
      "street": "123 Main St",
      "city": "New York",
      "state": "NY",
      "postalcode": "10001"
    }
  }
}
```

### Account Bean Serialization
```json
{
  "id": "acc456-def789",
  "name": {
    "name": "Global Industries Inc"
  },
  "meta": {
    "created": {
      "date": "2023-01-10 09:15:00",
      "user_id": "user123"
    }
  },
  "phone": {
    "office": "+1-555-444-7777"
  },
  "address": {
    "billing": {
      "street": "456 Corporate Blvd",
      "city": "San Francisco", 
      "state": "CA"
    }
  }
}
```

## Data Processing Features

### Value Cleaning
**UTF-8 Conversion**: Converts HTML entities to UTF-8 using `mb_convert_encoding()`
**Trimming**: Removes leading/trailing whitespace from string values
**Type Safety**: Prevents objects/arrays/resources from being forcefully cast to strings

### Empty Value Handling
**Optional Filtering**: `$hideEmptyValues` parameter controls inclusion of empty fields
**Empty Definitions**: Treats `null` and empty string `''` as empty
**Performance Benefit**: Reduces JSON size by excluding unnecessary empty fields

### Relationship Loading
**Optional Loading**: `$loadRelationships` parameter controls relationship data inclusion
**Performance Impact**: ~70% performance penalty when enabled
**Use Case**: Only enable when relationship data is specifically needed

## Performance Considerations

### Optimization Strategies
- **Relationship Loading**: Disabled by default for performance
- **ArrayMapper Caching**: YAML configuration loaded once per instance
- **Field Prioritization**: Uses database-fetched data when available
- **Lazy Processing**: Only processes fields that pass filters

### Memory Management
- **Reference Passing**: Uses references where appropriate to minimize copying
- **Selective Loading**: Only loads necessary field data
- **Configuration Reuse**: Single YAML load per serializer instance

## Error Handling

### Exception Types
**InvalidArgumentException**: Thrown for invalid parameter types in legacy methods
**File Access**: YAML loading errors are handled by ArrayMapper
**Type Safety**: Prevents serialization of non-serializable types

### Safe Defaults
- **Missing Fields**: Safely handles missing or undefined bean properties
- **Type Checking**: Validates bean types before processing
- **Fallback Strategies**: Multiple field extraction methods ensure data availability

## Use Cases

### API Responses
**REST APIs**: Clean, structured JSON output for API endpoints
**Data Export**: Standardized format for data export functionality
**Integration**: Consistent format for third-party integrations

### Data Migration
**System Migration**: Structured export for migration to other systems
**Backup/Restore**: JSON serialization for backup purposes
**Data Synchronization**: Standard format for sync operations

### Frontend Integration
**JavaScript Consumption**: Clean JSON structure for frontend applications
**Mobile APIs**: Optimized JSON structure for mobile app consumption
**Reporting**: Structured data for reporting tools

## Integration Points

### ArrayMapper System
**Mapping Engine**: Uses ArrayMapper for sophisticated field transformations
**Configuration**: Leverages YAML-based configuration system
**Extensibility**: Can be extended through YAML configuration changes

### SugarBean Framework
**Bean Integration**: Works with any SugarBean-derived class
**Field Detection**: Automatically detects available bean fields
**Relationship Support**: Optional relationship data inclusion

### JSON Standards
**UTF-8 Support**: Full Unicode support in output
**Standard Compliance**: Produces valid JSON according to RFC 7159
**Format Options**: Supports both compact and pretty-printed output

## Configuration Management

### YAML Customization
**Field Mapping**: Add custom field mappings in YAML
**Regex Patterns**: Define new regex-based transformations
**Blacklist Management**: Control which fields are excluded

### Extension Points
**Custom Mappers**: Can be extended with custom ArrayMapper configurations
**Field Processors**: Additional processing can be added through ArrayMapper
**Output Formats**: Different output structures through configuration changes 