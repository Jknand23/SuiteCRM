# ModuleLanguage.php Documentation

/**
 * @fileoverview Simple wrapper class for retrieving module-specific language strings using SuiteCRM's language system. Provides object-oriented interface to the global return_module_language() function.
 * @package SuiteCRM\Utility
 * @author SalesAgility Ltd.
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

The `ModuleLanguage` class provides a simple object-oriented wrapper around SuiteCRM's module language retrieval functionality. It serves as a bridge between object-oriented code and the global language functions.

## Class Structure

### Namespace
- **Namespace**: `SuiteCRM\Utility`
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility
- **Type**: Service class with dependency injection support

### Properties
The class contains no explicit properties and operates as a stateless service.

## Internal API Calls

### getModuleLanguageStrings() Method
**Purpose**: Retrieves language strings for a specific module
**Parameters**:
- `$currentLanguage` (CurrentLanguage): Current language utility object
- `$moduleName` (string): Name of the module to get language strings for

**Return**: `array` - Associative array of language strings for the module

**Process**:
1. Calls `$currentLanguage->getCurrentLanguage()` to get current language code
2. Passes language code and module name to `return_module_language()`
3. Returns the complete array of module-specific language strings

## External API Calls

### return_module_language() Function
**Purpose**: SuiteCRM's global function for retrieving module language strings
**Parameters**: 
- Language code (string)
- Module name (string)
**Return**: Array of translated strings specific to the module
**Integration**: Core SuiteCRM language system function

## Dependencies

### CurrentLanguage Class
**Purpose**: Provides current language code
**Method Used**: `getCurrentLanguage()`
**Relationship**: Dependency injection pattern for language code retrieval

### Global Language System
**Integration**: Relies on SuiteCRM's global language loading and caching system
**Files**: Module-specific language files in `modules/{ModuleName}/language/`

## Usage Patterns

### Basic Usage
```php
$currentLanguage = new CurrentLanguage();
$moduleLanguage = new ModuleLanguage();

$strings = $moduleLanguage->getModuleLanguageStrings($currentLanguage, 'Accounts');
// Returns array like: ['LBL_NAME' => 'Name', 'LBL_PHONE' => 'Phone', ...]
```

### Dependency Injection
```php
class SomeService {
    private $moduleLanguage;
    private $currentLanguage;
    
    public function __construct(ModuleLanguage $moduleLanguage, CurrentLanguage $currentLanguage) {
        $this->moduleLanguage = $moduleLanguage;
        $this->currentLanguage = $currentLanguage;
    }
    
    public function getAccountLabels() {
        return $this->moduleLanguage->getModuleLanguageStrings(
            $this->currentLanguage, 
            'Accounts'
        );
    }
}
```

### Translation Lookup
```php
$moduleLanguage = new ModuleLanguage();
$currentLanguage = new CurrentLanguage();

$contactStrings = $moduleLanguage->getModuleLanguageStrings($currentLanguage, 'Contacts');
$nameLabel = $contactStrings['LBL_NAME'] ?? 'Name'; // Fallback to default
```

## Integration Points

### Language System
- **Module Files**: Loads from `modules/{ModuleName}/language/{language}.lang.php`
- **Custom Files**: Supports custom language overrides in `custom/modules/{ModuleName}/language/`
- **Caching**: Benefits from SuiteCRM's language caching system

### CurrentLanguage Utility
- **Language Detection**: Uses CurrentLanguage class to determine active language
- **User Preferences**: Respects user language preferences via CurrentLanguage
- **Session State**: Accesses current session language settings

### Module System
- **Module Names**: Works with all SuiteCRM module names (Accounts, Contacts, etc.)
- **Custom Modules**: Supports custom module language strings
- **Dynamic Loading**: Language strings are loaded on-demand per module

## Language File Structure

### Standard Module Language Files
```php
// modules/Accounts/language/en_us.lang.php
$mod_strings = [
    'LBL_NAME' => 'Account Name',
    'LBL_PHONE' => 'Phone',
    'LBL_EMAIL' => 'Email',
    // ... more strings
];
```

### Custom Override Files
```php
// custom/modules/Accounts/language/en_us.lang.php
$mod_strings = [
    'LBL_NAME' => 'Company Name', // Override standard label
    'LBL_CUSTOM_FIELD' => 'Custom Field', // Add new label
];
```

## Return Value Structure

### Module Language Array
The returned array contains module-specific translation strings:
- **Keys**: Label constants (e.g., 'LBL_NAME', 'LBL_PHONE')
- **Values**: Translated text in the current language
- **Scope**: Limited to the specified module's language strings
- **Merging**: Includes both standard and custom language strings

### Example Return Value
```php
[
    'LBL_NAME' => 'Account Name',
    'LBL_WEBSITE' => 'Website',
    'LBL_PHONE' => 'Phone',
    'LBL_EMAIL' => 'Email Address',
    'LBL_BILLING_ADDRESS' => 'Billing Address',
    'LBL_SHIPPING_ADDRESS' => 'Shipping Address',
    // ... hundreds of other module-specific strings
]
```

## Performance Considerations

### Caching
- **Language Caching**: Benefits from SuiteCRM's built-in language caching
- **One-Time Loading**: Module language files are loaded once per request
- **Memory Efficiency**: Only loads language strings for requested modules

### Stateless Design
- **No State**: Class maintains no internal state between calls
- **Thread Safe**: Safe for concurrent use across multiple requests
- **Lightweight**: Minimal overhead for object creation

## Error Handling

### Safe Defaults
- **Missing Modules**: Returns empty array if module doesn't exist
- **Missing Language**: Falls back to default language if requested language unavailable
- **File Errors**: Handles missing language files gracefully

### Dependency Safety
- **CurrentLanguage**: Safe handling if CurrentLanguage object is not properly initialized
- **Global Functions**: Relies on SuiteCRM's robust global function error handling

## Design Patterns

### Wrapper Pattern
- **Purpose**: Provides object-oriented interface to procedural language functions
- **Benefits**: Enables dependency injection and better testability
- **Consistency**: Maintains consistent interface with other utility classes

### Service Pattern
- **Stateless**: No internal state, purely functional service
- **Injectable**: Designed for dependency injection frameworks
- **Single Responsibility**: Focused solely on module language retrieval

## Future Extensibility

### Potential Enhancements
- **Caching Layer**: Could add application-level caching for frequently accessed strings
- **Batch Loading**: Could support loading multiple modules' strings at once
- **Filtering**: Could add methods to filter strings by prefix or pattern
- **Validation**: Could add validation for module names and language codes 