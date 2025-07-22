# ApplicationLanguage.php Documentation

/**
 * @fileoverview Utility class for retrieving application-wide language strings by merging general application strings and application list strings. Provides unified access to SuiteCRM's global language resources.
 * @package SuiteCRM\Utility
 * @author SalesAgility Ltd.
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

The `ApplicationLanguage` class provides access to application-wide language strings in SuiteCRM. It merges both general application language strings and application list strings into a unified array, offering a comprehensive collection of translatable text for the entire application.

## Class Structure

### Namespace
- **Namespace**: `SuiteCRM\Utility`
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility
- **Type**: Service class with dependency injection support

### Properties
The class contains no explicit properties and operates as a stateless service.

## Internal API Calls

### getApplicationLanguageStrings() Method
**Purpose**: Retrieves merged application and application list language strings
**Parameters**:
- `$currentLanguage` (CurrentLanguage): Current language utility object

**Return**: `array` - Merged associative array of application language strings

**Process**:
1. Calls `$currentLanguage->getCurrentLanguage()` to get current language code
2. Calls `return_application_language()` with language code to get general app strings
3. Calls `return_app_list_strings_language()` with language code to get list strings
4. Merges both arrays using `array_merge()` and returns the result

**String Types Included**:
- **Application Strings**: General application messages, labels, and text
- **List Strings**: Dropdown lists, picklist values, and option arrays

## External API Calls

### return_application_language() Function
**Purpose**: SuiteCRM's global function for retrieving general application language strings
**Parameters**: Language code (string)
**Return**: Array of general application translation strings
**Integration**: Core SuiteCRM language system function
**Contains**: Error messages, common labels, system messages, etc.

### return_app_list_strings_language() Function
**Purpose**: SuiteCRM's global function for retrieving application list strings
**Parameters**: Language code (string)  
**Return**: Array of list/dropdown translation strings
**Integration**: Core SuiteCRM language system function
**Contains**: Dropdown options, picklist values, selection lists, etc.

## Dependencies

### CurrentLanguage Class
**Purpose**: Provides current language code
**Method Used**: `getCurrentLanguage()`
**Relationship**: Dependency injection pattern for language code retrieval

### Global Language System
**Integration**: Relies on SuiteCRM's global language loading and caching system
**Files**: Application language files in `include/language/` directory

## Language String Categories

### Application Strings (from return_application_language)
- **System Messages**: Error messages, success messages, warnings
- **Common Labels**: Generic labels used across modules
- **Navigation**: Menu items, breadcrumbs, action buttons
- **Forms**: Generic form labels and validation messages
- **General UI**: Status messages, confirmations, alerts

### Application List Strings (from return_app_list_strings_language)
- **Dropdown Options**: Values for select/dropdown fields
- **Status Lists**: Lead status, opportunity stages, case priorities
- **Type Classifications**: Account types, contact roles, task priorities
- **System Lists**: User types, module permissions, data types
- **Custom Picklists**: Custom dropdown values defined by administrators

## Usage Patterns

### Basic Usage
```php
$currentLanguage = new CurrentLanguage();
$applicationLanguage = new ApplicationLanguage();

$allStrings = $applicationLanguage->getApplicationLanguageStrings($currentLanguage);
// Returns merged array of all application language strings
```

### Dependency Injection
```php
class UIService {
    private $applicationLanguage;
    private $currentLanguage;
    
    public function __construct(
        ApplicationLanguage $applicationLanguage,
        CurrentLanguage $currentLanguage
    ) {
        $this->applicationLanguage = $applicationLanguage;
        $this->currentLanguage = $currentLanguage;
    }
    
    public function getAllLabels() {
        return $this->applicationLanguage->getApplicationLanguageStrings(
            $this->currentLanguage
        );
    }
    
    public function getDropdownOptions($listName) {
        $strings = $this->getAllLabels();
        return $strings[$listName] ?? [];
    }
}
```

### Label Lookup
```php
$currentLanguage = new CurrentLanguage();
$applicationLanguage = new ApplicationLanguage();

$appStrings = $applicationLanguage->getApplicationLanguageStrings($currentLanguage);

// Access general application strings
$saveLabel = $appStrings['LBL_SAVE_BUTTON'] ?? 'Save';
$errorMsg = $appStrings['ERR_INVALID_INPUT'] ?? 'Invalid input';

// Access dropdown/list strings  
$accountTypes = $appStrings['account_type_dom'] ?? [];
$leadSources = $appStrings['lead_source_dom'] ?? [];
```

### Form Building
```php
$currentLanguage = new CurrentLanguage();
$applicationLanguage = new ApplicationLanguage();

$strings = $applicationLanguage->getApplicationLanguageStrings($currentLanguage);

// Build form with translated options
$form = [
    'save_button' => $strings['LBL_SAVE_BUTTON'] ?? 'Save',
    'cancel_button' => $strings['LBL_CANCEL_BUTTON'] ?? 'Cancel',
    'account_type_options' => $strings['account_type_dom'] ?? [],
    'industry_options' => $strings['industry_dom'] ?? [],
];
```

## Return Value Structure

### Merged Array Content
The returned array contains both types of language strings:

#### General Application Strings
```php
[
    'LBL_SAVE_BUTTON' => 'Save',
    'LBL_CANCEL_BUTTON' => 'Cancel',
    'LBL_EDIT_BUTTON' => 'Edit',
    'LBL_DELETE_BUTTON' => 'Delete',
    'ERR_MISSING_REQUIRED_FIELDS' => 'Missing required fields',
    'MSG_RECORD_SAVED' => 'Record saved successfully',
    // ... hundreds of other general strings
]
```

#### Application List Strings
```php
[
    'account_type_dom' => [
        '' => '',
        'Analyst' => 'Analyst',
        'Competitor' => 'Competitor',
        'Customer' => 'Customer',
        'Integrator' => 'Integrator',
        'Investor' => 'Investor',
        'Partner' => 'Partner',
        'Press' => 'Press',
        'Prospect' => 'Prospect',
        'Reseller' => 'Reseller',
        'Other' => 'Other'
    ],
    'lead_source_dom' => [
        '' => '',
        'Cold_Call' => 'Cold Call',
        'Existing_Customer' => 'Existing Customer',
        'Self_Generated' => 'Self Generated',
        'Employee' => 'Employee',
        'Partner' => 'Partner',
        'Public_Relations' => 'Public Relations',
        'Direct_Mail' => 'Direct Mail',
        'Conference' => 'Conference',
        'Trade_Show' => 'Trade Show',
        'Web_Site' => 'Web Site',
        'Word_of_mouth' => 'Word of mouth',
        'Email' => 'Email',
        'Campaign' => 'Campaign',
        'Other' => 'Other'
    ],
    // ... many other dropdown/list arrays
]
```

### Array Merging
- **Precedence**: List strings may override application strings if keys conflict
- **Comprehensive**: Contains all application-wide translatable content
- **Unified Access**: Single array for all application language needs

## Integration Points

### Language File System
- **Application Files**: Loads from `include/language/{language}.lang.php`
- **List Files**: Loads from `include/language/{language}.lang.list.php`
- **Custom Files**: Supports custom language overrides
- **Caching**: Benefits from SuiteCRM's language caching system

### UI Components
- **Form Rendering**: Provides labels and options for form generation
- **Dropdown Population**: Supplies options for select/dropdown fields
- **Message Display**: Provides system messages and notifications
- **Navigation**: Supplies text for menus and navigation elements

### Template System
- **Smarty Integration**: Language strings available in Smarty templates
- **JavaScript**: Can be passed to JavaScript for client-side translation
- **AJAX Responses**: Used in AJAX responses for dynamic content

## Performance Considerations

### Caching Benefits
- **Language Caching**: Benefits from SuiteCRM's built-in language caching
- **Single Load**: Language files loaded once per request
- **Memory Efficiency**: Merged array created once and reused

### Array Merging
- **PHP Native**: Uses native `array_merge()` for optimal performance
- **Single Operation**: Merging happens once per method call
- **Memory Usage**: Creates single merged array instead of multiple references

## Error Handling

### Safe Defaults
- **Missing Strings**: Returns empty array if language strings unavailable
- **File Errors**: Handles missing language files gracefully
- **Merge Safety**: `array_merge()` handles empty arrays safely

### Fallback Behavior
- **Language Fallback**: Falls back to default language if requested language unavailable
- **Empty Values**: Returns empty arrays for missing list strings
- **Default Values**: Calling code can provide defaults using null coalescing

## Use Cases

### Form Generation
- **Dynamic Forms**: Generate forms with translated labels and options
- **Dropdown Fields**: Populate select fields with translated options
- **Validation**: Display translated validation messages

### User Interface
- **Buttons**: Translate action buttons (Save, Cancel, Edit, Delete)
- **Messages**: Display system messages in user's language
- **Navigation**: Translate menu items and navigation elements

### Data Entry
- **Picklists**: Provide translated options for picklist fields
- **Status Values**: Translate status and stage values
- **Type Classifications**: Provide translated type/category options

### Reporting
- **Report Labels**: Translate report headers and labels
- **Filter Options**: Provide translated filter values
- **Export Headers**: Translate export column headers

## Design Patterns

### Facade Pattern
- **Purpose**: Provides unified interface to multiple language functions
- **Simplification**: Hides complexity of multiple language sources
- **Single Point**: One method for all application language needs

### Service Pattern
- **Stateless**: No internal state between method calls
- **Injectable**: Designed for dependency injection
- **Focused**: Single responsibility for application language retrieval

## Future Extensibility

### Potential Enhancements
- **Caching Layer**: Could add application-level caching for merged strings
- **Filtering**: Could add methods to filter strings by prefix or category
- **Lazy Loading**: Could implement lazy loading for better performance
- **Partial Loading**: Could support loading only specific string categories
- **Validation**: Could add validation for language code and string integrity 