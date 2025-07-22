# en_us.lang.php Documentation

## @fileoverview
**AOD_Index English Language Strings - Localization definitions for Advanced OpenDiscovery search index**
- **Package**: modules/AOD_Index/language
- **Copyright**: SalesAgility Ltd
- **License**: GNU AFFERO GENERAL PUBLIC LICENSE
- **Purpose**: Provides English language labels and messages for AOD_Index module user interface
- **Locale**: en_us (English - United States)

## Language Configuration

### Module Identification
- **Module Context**: Labels specific to AOD_Index search index functionality
- **Scope**: Covers all user-facing text elements in the module
- **Localization**: Base English language for international translation reference
- **Integration**: Used by SuiteCRM's localization framework

## User Interface Labels

### Standard SugarBean Field Labels
```php
'LBL_ASSIGNED_TO_ID' => 'Assigned User Id',
'LBL_ASSIGNED_TO_NAME' => 'Assigned to',
'LBL_DATE_ENTERED' => 'Date Created',
'LBL_DATE_MODIFIED' => 'Date Modified',
'LBL_MODIFIED' => 'Modified By',
'LBL_CREATED' => 'Created By',
'LBL_DESCRIPTION' => 'Description',
'LBL_NAME' => 'Name',
```
- **Purpose**: Standard field labels inherited from SugarBean framework
- **Consistency**: Matches standard SuiteCRM field naming conventions
- **Usage**: Edit forms, detail views, list views throughout module

### Module-Specific Labels
```php
'LBL_MODULE_NAME' => 'Index',
'LBL_MODULE_TITLE' => 'Index',
'LBL_HOMEPAGE_TITLE' => 'My Index',
```
- **Module Identity**: Defines how module appears in navigation and titles
- **Branding**: Consistent "Index" terminology throughout interface
- **Context**: Distinguishes from other search-related modules

### Custom Field Labels
```php
'LBL_LAST_OPTIMISED' => 'Last Optimised',
'LBL_LOCATION' => 'Location',
```
- **Specific Fields**: Labels for AOD_Index custom database fields
- **Terminology**: "Optimised" uses British spelling (SalesAgility Ltd convention)
- **Context**: Location refers to file system path for index storage

## Administrative Interface

### Statistics Display Labels
```php
'LBL_INDEX_STATS' => 'Index stats',
'LBL_TOTAL_RECORDS' => 'Total records',
'LBL_INDEXED_RECORDS' => 'Indexed records',
'LBL_UNINDEXED_RECORDS' => 'Unindexed records',
'LBL_FAILED_RECORDS' => 'Failed records',
'LBL_INDEX_FILES' => 'Index file count',
```
- **Dashboard Elements**: Labels for administrative statistics view
- **Performance Metrics**: User-friendly names for technical measurements
- **Status Indicators**: Clear terminology for index health monitoring

### Action Interface Labels
```php
'LBL_OPTIMISE_NOW' => "Optimise now",
'LBL_SEARCH_BUTTON' => 'Search',
'LBL_EDIT_BUTTON' => 'Edit',
'LBL_REMOVE' => 'Remove',
```
- **User Actions**: Labels for buttons and interactive elements
- **Verb Forms**: Action-oriented language for user operations
- **Accessibility**: Clear, descriptive button text for screen readers

## Search Interface

### Search Functionality Labels
```php
'LBL_SEARCH_QUERY_PLACEHOLDER' => 'Enter search...',
'LBL_USE_AOD_SEARCH' => 'Use Advanced Search',
'LBL_USE_VANILLA_SEARCH' => 'Use Basic Search',
```
- **Search UX**: User guidance for search input and options
- **Feature Toggle**: Labels for switching between search modes
- **Placeholder Text**: Helpful hints for search input fields

### Search Results Labels
```php
'LBL_SEARCH_RESULT_SCORE' => 'Search Score',
'LBL_SEARCH_RESULT_MODULE' => 'Module',
'LBL_SEARCH_RESULT_NAME' => 'Name',
'LBL_SEARCH_RESULT_DATE_CREATED' => 'Date Created',
'LBL_SEARCH_RESULT_DATE_MODIFIED' => 'Date Modified',
'LBL_SEARCH_RESULT_EMPTY' => 'No results',
'LBL_SEARCH_RESULT_SUMMARY' => 'Summary',
```
- **Results Display**: Column headers and metadata labels for search results
- **Relevance**: Search score helps users understand result ranking
- **Empty State**: User-friendly message when no results found

## Navigation and Form Labels

### List View Labels
```php
'LBL_LIST_FORM_TITLE' => 'Index List',
'LBL_LIST_NAME' => 'Name',
'LBL_SEARCH_FORM_TITLE' => 'Search Index',
```
- **Navigation Context**: Titles for different views within module
- **Form Headers**: Descriptive titles for various interface screens
- **Consistency**: Follows SuiteCRM naming patterns

### Record Management Labels
```php
'LNK_NEW_RECORD' => 'Create Index',
'LNK_LIST' => 'View Index',
'LBL_NEW_FORM_TITLE' => 'New Index',
```
- **CRUD Operations**: Labels for create, read, update operations
- **Menu Items**: Text for navigation menu entries
- **Form Titles**: Headers for data entry and editing screens

## Status and Feedback Messages

### System Status Labels
```php
'LBL_NEVER_OPTIMISED' => 'Never',
'LBL_DELETED' => 'Deleted',
```
- **Status Indicators**: Clear status messages for system states
- **Temporal Context**: "Never" indicates optimization has not occurred
- **Record State**: Standard SuiteCRM deletion status terminology

### Subpanel Integration
```php
'LBL_HISTORY_SUBPANEL_TITLE' => 'View History',
'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Activities',
```
- **Related Records**: Labels for subpanel sections in detail view
- **Framework Integration**: Standard SuiteCRM subpanel terminology
- **Audit Trail**: History tracking for administrative records

## Localization Features

### Translation Framework
- **String Keys**: Prefixed with `LBL_` following SuiteCRM conventions
- **No Hardcoded Text**: All user-visible text defined in language files
- **Override Support**: Custom language files can override these definitions
- **Encoding**: UTF-8 compatible for international character support

### Customization Support
- **Extension Pattern**: Custom modules can extend or override these labels
- **Site-Specific**: Organizations can customize labels for their terminology
- **Multi-Language**: Base for translation into other languages
- **Fallback**: Provides default English text if translations missing

## Technical Integration

### Framework Usage
```php
$mod_strings = array(
    // All label definitions
);
```
- **Array Structure**: Standard PHP associative array format
- **Global Scope**: Available throughout module via `$mod_strings` global
- **Dynamic Loading**: Loaded by SuiteCRM's language framework
- **Caching**: Labels cached for performance optimization

### Security and Validation
```php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
```
- **Entry Point Protection**: Standard SuiteCRM security validation
- **Direct Access Prevention**: Prevents execution outside framework context
- **Framework Requirement**: Ensures proper SuiteCRM initialization

## Usage Patterns

### Template Integration
- **Smarty Templates**: Labels used in `.tpl` template files
- **Variable Access**: `{$MOD.LBL_LABEL_NAME}` syntax in templates
- **Dynamic Content**: Labels rendered at page generation time
- **Internationalization**: Automatic language switching based on user preferences

### JavaScript Integration
- **Client-Side Access**: Labels can be passed to JavaScript for dynamic interfaces
- **AJAX Responses**: Localized messages in AJAX response handling
- **Form Validation**: Error messages and user feedback in local language
- **Dynamic UI**: Labels for dynamically generated interface elements

## Administrative Context

### System Configuration
- **Module Setup**: Labels used during module installation and configuration
- **User Training**: Consistent terminology aids user documentation
- **Support**: Clear labels reduce confusion in support scenarios
- **Maintenance**: Descriptive labels help with system administration

### Error Handling
- **User Messages**: Friendly error messages instead of technical codes
- **Status Communication**: Clear status updates during operations
- **Help Text**: Contextual assistance through descriptive labels
- **Accessibility**: Screen reader compatible label text 