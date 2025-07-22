# CurrentLanguage.php Documentation

/**
 * @fileoverview Simple utility class providing access to the current language setting in SuiteCRM. Acts as a wrapper around the global $current_language variable for object-oriented code.
 * @package SuiteCRM\Utility
 * @author SalesAgility Ltd.
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

The `CurrentLanguage` class provides a simple object-oriented interface to access SuiteCRM's current language setting. It serves as a wrapper around the global `$current_language` variable, enabling dependency injection and better testability in object-oriented code.

## Class Structure

### Namespace
- **Namespace**: `SuiteCRM\Utility`
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility
- **Type**: Simple utility class with minimal functionality

### Properties
The class contains no explicit properties and operates as a stateless utility.

## Internal API Calls

### getCurrentLanguage() Method
**Purpose**: Retrieves the current language code
**Return**: `string` - Current language code (e.g., 'en_us', 'es_es', 'fr_fr')
**Implementation**: Accesses and returns the global `$current_language` variable

**Process**:
1. Accesses global `$current_language` variable
2. Returns the language code directly

## External API Calls

### Global Variable Access
#### $current_language
**Type**: `string`
**Purpose**: Global variable storing the current active language code
**Scope**: Available throughout SuiteCRM application
**Examples**: 
- `'en_us'` - English (United States)
- `'es_es'` - Spanish (Spain)
- `'de_de'` - German (Germany)
- `'fr_fr'` - French (France)

## Usage Patterns

### Basic Usage
```php
$currentLanguage = new CurrentLanguage();
$langCode = $currentLanguage->getCurrentLanguage();
// Returns: 'en_us' (or whatever is currently active)
```

### Dependency Injection
```php
class LanguageService {
    private $currentLanguage;
    
    public function __construct(CurrentLanguage $currentLanguage) {
        $this->currentLanguage = $currentLanguage;
    }
    
    public function getActiveLanguage() {
        return $this->currentLanguage->getCurrentLanguage();
    }
    
    public function isEnglish() {
        return $this->currentLanguage->getCurrentLanguage() === 'en_us';
    }
}
```

### Language-Specific Operations
```php
$currentLanguage = new CurrentLanguage();
$langCode = $currentLanguage->getCurrentLanguage();

switch ($langCode) {
    case 'en_us':
        $dateFormat = 'm/d/Y';
        break;
    case 'de_de':
        $dateFormat = 'd.m.Y';
        break;
    case 'fr_fr':
        $dateFormat = 'd/m/Y';
        break;
    default:
        $dateFormat = 'm/d/Y';
}
```

### Integration with Other Utilities
```php
$currentLanguage = new CurrentLanguage();
$moduleLanguage = new ModuleLanguage();
$applicationLanguage = new ApplicationLanguage();

// Get current language
$langCode = $currentLanguage->getCurrentLanguage();

// Use in other language utilities
$accountStrings = $moduleLanguage->getModuleLanguageStrings($currentLanguage, 'Accounts');
$appStrings = $applicationLanguage->getApplicationLanguageStrings($currentLanguage);
```

## Integration Points

### Language System
- **Global State**: Accesses the global language state managed by SuiteCRM
- **User Preferences**: Reflects the user's language preference setting
- **Session State**: Returns the language active for the current session

### Utility Classes
- **ModuleLanguage**: Used as parameter for module-specific language retrieval
- **ApplicationLanguage**: Used as parameter for application language retrieval
- **Language Services**: Injected into services requiring language information

### Framework Integration
- **Localization**: Integrates with SuiteCRM's localization system
- **User Interface**: Language code determines UI language
- **Data Formatting**: Used for locale-specific data formatting

## Language Code Format

### Standard Format
Language codes follow the format: `{language}_{country}`
- **Language**: Two-letter ISO 639-1 language code (lowercase)
- **Country**: Two-letter ISO 3166-1 country code (lowercase)
- **Separator**: Underscore (`_`) between language and country

### Common Language Codes
- `'en_us'` - English (United States)
- `'en_uk'` - English (United Kingdom)
- `'es_es'` - Spanish (Spain)
- `'de_de'` - German (Germany)
- `'fr_fr'` - French (France)
- `'it_it'` - Italian (Italy)
- `'pt_br'` - Portuguese (Brazil)
- `'zh_cn'` - Chinese (Simplified)
- `'ja_jp'` - Japanese (Japan)

## Global Variable Context

### Setting the Current Language
The `$current_language` variable is typically set by:
- **User Login**: Based on user's language preference
- **System Default**: Falls back to system default language
- **URL Parameters**: May be set via URL language parameters
- **Session Management**: Maintained across user session

### Language Precedence
1. **User Preference**: User's saved language preference
2. **Browser Language**: Browser's accept-language header
3. **System Default**: Configured system default language
4. **Fallback**: Default to 'en_us' if no other option available

## Performance Considerations

### Lightweight Design
- **No State**: Class maintains no internal state
- **Minimal Overhead**: Simple wrapper with negligible performance impact
- **Global Access**: Direct access to global variable for speed

### Memory Usage
- **Stateless**: No memory usage for instance variables
- **Global Reference**: References existing global variable
- **Efficient**: Optimal memory usage pattern

## Error Handling

### Safe Defaults
- **Null Handling**: Returns whatever is in global variable (may be null)
- **Empty Handling**: Returns empty string if global variable is empty
- **Type Safety**: Returns string type consistently

### Defensive Programming
```php
$currentLanguage = new CurrentLanguage();
$langCode = $currentLanguage->getCurrentLanguage() ?: 'en_us';
// Ensures a default language if none is set
```

## Design Patterns

### Wrapper Pattern
- **Purpose**: Wraps global variable access in object-oriented interface
- **Benefit**: Enables dependency injection and testing
- **Simplicity**: Minimal wrapper with clear responsibility

### Accessor Pattern
- **Purpose**: Provides controlled access to global state
- **Encapsulation**: Hides global variable access behind method interface
- **Consistency**: Consistent interface with other utility classes

## Testing Considerations

### Mock Testing
```php
// Create mock for testing
$mockCurrentLanguage = $this->createMock(CurrentLanguage::class);
$mockCurrentLanguage->method('getCurrentLanguage')->willReturn('de_de');

// Test with German language
$service = new LanguageService($mockCurrentLanguage);
$this->assertEquals('de_de', $service->getActiveLanguage());
```

### Integration Testing
- **Global State**: Tests may need to manage global `$current_language` state
- **Language Switching**: Test behavior with different language codes
- **Default Handling**: Test behavior when language is not set

## Future Extensibility

### Potential Enhancements
- **Validation**: Could add validation for language code format
- **Fallback Logic**: Could implement fallback logic for missing languages
- **Language Detection**: Could add browser language detection
- **Caching**: Could cache language code for performance
- **Event Integration**: Could integrate with language change events

### Compatibility
- **Backward Compatibility**: Simple interface ensures compatibility
- **Extension Points**: Can be extended without breaking existing code
- **Interface Evolution**: Can implement interfaces for formal contracts

## Dependencies

### No External Dependencies
- **Self-Contained**: Only depends on global PHP state
- **No Imports**: Uses only built-in PHP functionality
- **Minimal Footprint**: Lightweight with minimal dependencies

### Global State Dependency
- **$current_language**: Only dependency is global variable
- **SuiteCRM Context**: Requires SuiteCRM environment for proper operation
- **Language System**: Indirectly depends on SuiteCRM's language management 