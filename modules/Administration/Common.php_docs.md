# Common.php

## Overview
**@fileoverview** Utility functions for managing language files, dropdown lists, application strings, and custom field labels within SuiteCRM's administration system.

**@package** Administration  
**@copyright** SugarCRM Inc. / SalesAgility Ltd.  
**@license** AGPL v3  

This file provides a comprehensive set of utility functions for managing SuiteCRM's localization and customization features. It handles creation, modification, and maintenance of custom language files, dropdown lists, and application strings while ensuring proper file structure and avoiding duplicates.

## Database Operations

### Language File Management
The file manages custom language files stored in the filesystem:
- **Custom Module Languages**: `custom/modules/{module}/language/{language}.lang.php`
- **Custom Application Languages**: `custom/include/language/{language}.lang.php`
- **Dropdown Customizations**: Stored within language files as `$app_list_strings` arrays

### Cache Management
Language modifications trigger cache invalidation:
- **Cache Keys**: `app_list_strings.{language}` format for dropdown caches
- **Cache Clearing**: `sugar_cache_clear($cache_key)` after successful saves
- **Performance**: Ensures language changes are immediately visible

## Internal API Calls

### Directory Creation Functions

#### create_include_lang_dir()
Creates the custom include language directory structure:
- **Path Creation**: `custom/include/language` directory
- **Recursive Creation**: Uses `sugar_mkdir()` with recursive flag
- **Return Value**: Boolean indicating success/failure

#### create_module_lang_dir($module)
Creates module-specific language directory structure:
- **Module Path**: `custom/modules/{module}/language` directory
- **Parameter Validation**: Accepts module name as string parameter
- **Directory Safety**: Checks for existing directory before creation

### Field Label Management Functions

#### create_field_label_all_lang($module, $key, $value, $overwrite)
Creates field labels across all available languages:
- **Language Iteration**: Processes all languages returned by `get_languages()`
- **Batch Processing**: Calls `create_field_label()` for each language
- **Error Handling**: Stops on first failure and returns false
- **Overwrite Control**: Optional parameter to force overwrites

#### create_field_label($module, $language, $key, $value, $overwrite)
Creates individual field label in specified language:
- **Duplicate Check**: Prevents accidental overwrites unless explicitly allowed
- **File Merging**: Merges new labels with existing module strings
- **Content Generation**: Uses `create_field_lang_pak_contents()` for proper formatting
- **Logging**: Records successful writes and error conditions

### Dropdown Management Functions

#### create_dropdown_type_all_lang($dropdown_name)
Creates new dropdown type across all languages:
- **Multi-Language**: Ensures consistency across all available languages
- **Atomic Operation**: All languages must succeed or operation fails
- **Error Propagation**: Returns false if any language creation fails

#### create_dropdown_type($dropdown_name, $language)
Creates new dropdown type for specific language:
- **Duplicate Prevention**: Checks for existing dropdown names
- **File Integration**: Merges with existing custom app list strings
- **Empty Initialization**: Creates dropdown with single empty option

#### dropdown_item_delete($dropdown_type, $language, $index)
Removes item from dropdown list:
- **Array Manipulation**: Uses `helper_dropdown_item_delete()` for safe removal
- **File Persistence**: Updates custom language file with modified array
- **Index Validation**: Handles array reordering after deletion

#### dropdown_item_move_up($dropdown_type, $language, $index)
Moves dropdown item one position up:
- **Boundary Checking**: Validates index is not at top position
- **Key Preservation**: Maintains original key-value pairs during move
- **Array Reconstruction**: Uses delete and insert helpers for safe operation

#### dropdown_item_move_down($dropdown_type, $language, $index)
Moves dropdown item one position down:
- **Boundary Checking**: Validates index is not at bottom position
- **Position Logic**: Calculates new position and validates array bounds
- **Safe Manipulation**: Uses helper functions to prevent data corruption

#### dropdown_item_insert($dropdown_type, $language, $index, $key, $value)
Inserts new item into dropdown at specified position:
- **Position Flexibility**: Handles insertion at any valid array position
- **Key-Value Integrity**: Maintains proper associative array structure
- **Helper Integration**: Uses `helper_dropdown_item_insert()` for array manipulation

#### dropdown_item_edit($dropdown_type, $language, $key, $value)
Modifies existing dropdown item value:
- **Direct Assignment**: Updates value for existing key
- **File Persistence**: Saves changes to custom language file
- **Content Management**: Uses `replace_or_add_dropdown_type()` for proper formatting

### Content Generation Functions

#### create_field_lang_pak_contents($old_contents, $key, $value, $language, $module)
Generates properly formatted language file content:
- **Content Merging**: Integrates new content with existing file data
- **Duplicate Removal**: Uses regex to prevent duplicate key definitions
- **Header Generation**: Adds creation date and metadata comments
- **PHP Formatting**: Ensures valid PHP syntax with proper opening/closing tags

#### create_dropdown_lang_pak_contents(&$the_array, $language)
Creates formatted content for dropdown language files:
- **Array Export**: Uses `var_export()` for proper array representation
- **Metadata Headers**: Includes creation timestamp and language information
- **PHP Structure**: Generates valid PHP file with proper variable assignment

## External API Calls

### File System Operations
- **File Reading**: `file_get_contents()` for existing language files
- **File Writing**: `sugar_file_put_contents()` for safe file operations
- **Directory Creation**: `sugar_mkdir()` with proper permissions

### SuiteCRM Integration
- **Language Access**: `return_module_language()` for current language strings
- **App Strings**: `return_app_list_strings_language()` for dropdown data
- **Logging**: Uses `$GLOBALS['log']` and `LoggerManager::getLogger()`

### Array Utilities
- **Array Utils**: Includes `include/utils/array_utils.php` for enhanced operations
- **Safe Manipulation**: Uses built-in PHP array functions with proper validation

## UI Functionality

### HTML Generation

#### create_dropdown_html($identifier, &$pairs, $first_entry, $selected_key)
Generates HTML select element for dropdowns:
- **Select Element**: Creates properly formatted `<select>` with name attribute
- **Option Generation**: Iterates through key-value pairs for option elements
- **Selection Handling**: Marks appropriate option as selected
- **First Entry**: Optional default/empty option at top of list

### Form Processing Support
The utility functions support form-based administration interfaces:
- **POST Processing**: Handles form submissions for language modifications
- **Validation**: Checks for existing keys and proper data formats
- **Error Feedback**: Provides user-friendly error messages and logging

## Content Replacement and Parsing

### Pattern Matching Functions

#### replace_or_add_dropdown_type($dropdown_type, &$dropdown_array, &$file_contents)
Intelligently replaces or adds dropdown definitions:
- **Replace Logic**: Attempts to replace existing dropdown definitions
- **Append Fallback**: Adds new definition if replacement fails
- **Duplicate Prevention**: Uses `dropdown_duplicate_check()` for cleanup
- **Content Safety**: Maintains valid PHP file structure

#### replace_dropdown_type($dropdown_type, &$dropdown_array, &$file_contents)
Replaces existing dropdown definition using regex:
- **Pattern Matching**: Uses complex regex to identify dropdown definitions
- **Single Replacement**: Limits replacement to first occurrence only
- **Array Formatting**: Uses `override_value_to_string()` for proper syntax

#### dropdown_duplicate_check($dropdown_type, &$file_contents)
Prevents and removes duplicate dropdown definitions:
- **Pattern Detection**: Uses regex to find multiple definitions
- **Cleanup Logic**: Removes duplicates and preserves last definition
- **File Integrity**: Maintains proper PHP file structure after cleanup

### Application String Management

#### replace_or_add_app_string($name, $value, &$file_contents)
Manages application string definitions:
- **String Replacement**: Updates existing app string values
- **Addition Logic**: Adds new strings if replacement fails
- **Duplicate Handling**: Uses `app_string_duplicate_check()` for cleanup

#### replace_app_string($name, $value, &$file_contents)
Replaces individual application string value:
- **Targeted Replacement**: Uses specific regex for app strings
- **Value Safety**: Handles string escaping and quote management
- **Single Update**: Limits replacement to first occurrence

## Helper Functions

### Array Manipulation Helpers

#### helper_dropdown_item_delete(&$dropdown_array, $index)
Safely removes item from array by index:
- **Splice Operation**: Uses `array_splice()` for clean removal
- **Array Reconstruction**: Merges remaining elements properly
- **Index Safety**: Handles edge cases and array bounds

#### helper_dropdown_item_insert(&$dropdown_array, $index, $key, $value)
Inserts key-value pair at specified array position:
- **Position Logic**: Handles insertion at beginning, middle, or end
- **Array Merging**: Uses multiple `array_merge()` calls for proper insertion
- **Boundary Handling**: Validates insertion index and handles edge cases

The Common.php file serves as the backbone for SuiteCRM's language customization system, providing robust, safe, and comprehensive tools for managing multilingual content and dropdown configurations. 