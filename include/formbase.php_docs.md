# formbase.php Documentation

/**
 * @fileoverview Form handling utilities and helper functions for SuiteCRM
 * @package SuiteCRM
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `formbase.php` file provides essential form handling utilities for SuiteCRM applications. It contains functions for form validation, bean population from POST data, HTML form generation, redirect handling, and relationship management. This file is fundamental to the MVC architecture and form processing throughout the application.

## Core Form Functions

### Validation Functions

#### checkRequired($prefix, $required)
Validates that required fields are present and not empty in $_POST data.
- **Parameters:**
  - `$prefix` (string) - Field name prefix to prepend
  - `$required` (array) - Array of field names to validate
- **Returns:** boolean - true if all required fields are present and valid
- **Usage:** Form validation before processing submissions

### Bean Population Functions

#### populateFromPost($prefix, &$focus, $skipRetrieve = false, $checkACL = false)
Primary function for populating SugarBean objects from $_POST data.
- **Parameters:**
  - `$prefix` (string) - Field name prefix in form data
  - `$focus` (SugarBean) - Bean object to populate (passed by reference)
  - `$skipRetrieve` (boolean) - Skip database retrieval of existing record
  - `$checkACL` (boolean) - Check ACL permissions before updating fields
- **Returns:** SugarBean - The populated bean object
- **Features:**
  - Handles field type-specific processing via SugarField handlers
  - Manages related fields and ID mappings
  - Supports duplicate checking with new_with_id flag
  - Integrates with Security Groups module
  - Processes additional column fields
  - Handles assignment notification flags

## HTML Form Generation

### Hidden Field Functions

#### add_hidden_elements($key, $value)
Generates HTML hidden input elements from key-value pairs.
- **Parameters:**
  - `$key` (string) - Field name
  - `$value` (mixed) - Field value (supports arrays)
- **Returns:** string - HTML hidden input elements
- **Features:**
  - Handles both scalar values and arrays
  - Generates proper array notation for complex data

#### getPostToForm($ignore='', $isRegularExpression=false)
Converts $_POST data to hidden form fields.
- **Parameters:**
  - `$ignore` (string) - Field name to exclude or regex pattern
  - `$isRegularExpression` (boolean) - Treat ignore parameter as regex
- **Returns:** string - HTML hidden fields representing POST data
- **Usage:** Preserving form state during redirects

#### getGetToForm($ignore='', $usePostAsAuthority = false)
Converts $_GET data to hidden form fields.
- **Parameters:**
  - `$ignore` (string) - Field name to exclude
  - `$usePostAsAuthority` (boolean) - Skip fields already in POST
- **Returns:** string - HTML hidden fields representing GET data
- **Features:**
  - Input validation with logging for invalid data types
  - POST data takes precedence when usePostAsAuthority is true

#### getAnyToForm($ignore='', $usePostAsAuthority = false)
Combines both POST and GET data into hidden form fields.
- **Parameters:**
  - `$ignore` (string) - Field name to exclude
  - `$usePostAsAuthority` (boolean) - POST data precedence flag
- **Returns:** string - HTML hidden fields from both POST and GET

## Navigation and Redirect Handling

### Redirect Functions

#### handleRedirect($return_id='', $return_module='', $additionalFlags = false)
Handles HTTP redirects after form processing.
- **Parameters:**
  - `$return_id` (string) - Record ID for redirect target
  - `$return_module` (string) - Module name for redirect
  - `$additionalFlags` (array) - Additional URL parameters
- **Features:**
  - Processes return_url parameter for custom redirects
  - Generates appropriate redirect headers and exits
  - Delegates URL building to buildRedirectURL()

#### buildRedirectURL($return_id='', $return_module='')
Constructs redirect URLs based on form action and context.
- **Parameters:**
  - `$return_id` (string) - Record ID for redirect
  - `$return_module` (string) - Module name for redirect
- **Returns:** string - Complete Location header string
- **Logic:**
  - **Close and Create New:** Redirects to EditView with duplicate flag
  - **Save Action:** Redirects to DetailView for most modules
  - **Cancel Action:** Returns to previous list view
  - **Special Modules:** Handles Activities, Calendar, Home, Forecasts
- **Features:**
  - Meeting integration support
  - AJAX response handling
  - Duplicate record creation support
  - Offset preservation for pagination

### Navigation Utilities

#### isCloseAndCreateNewPressed()
Detects "Close and Create New" button press in forms.
- **Returns:** boolean - true if Save and New action was triggered
- **Usage:** Determining redirect behavior after form submission

## Search and Query Functions

### Search Utilities

#### getLikeForEachWord($fieldname, $value, $minsize=4)
Generates SQL LIKE clauses for multi-word search terms.
- **Parameters:**
  - `$fieldname` (string) - Database field name
  - `$value` (string) - Search value with multiple words
  - `$minsize` (integer) - Minimum word length to include
- **Returns:** string - SQL WHERE clause with OR conditions
- **Usage:** Full-text search functionality across multiple terms

## Relationship Management

### Prospect List Functions

#### add_prospects_to_prospect_list($parent_id, $child_id)
Adds prospects to prospect lists through relationship management.
- **Parameters:**
  - `$parent_id` (string) - Prospect list ID
  - `$child_id` (string|array) - Prospect ID(s) to add
- **Features:**
  - Supports single or multiple prospect IDs
  - Dynamic relationship field discovery
  - Uses BeanFactory for proper object instantiation

#### add_to_prospect_list($query_panel, $parent_module, $parent_type, $parent_id, $child_id, $link_attribute, $link_type, $parent)
Advanced prospect list management with subpanel integration.
- **Parameters:**
  - `$query_panel` (string) - Subpanel definition name
  - `$parent_module` (string) - Parent module name
  - `$parent_type` (string) - Parent bean class name
  - `$parent_id` (string) - Parent record ID
  - `$child_id` (string) - Child record field name
  - `$link_attribute` (string) - Relationship field name
  - `$link_type` (string) - Relationship type configuration
  - `$parent` (SugarBean) - Parent bean object
- **Features:**
  - ACL permission checking
  - Module access validation
  - Dynamic class loading
  - Subpanel query execution
  - Marketing ID filtering support
  - Bulk relationship creation

### Report Integration

#### save_from_report($report_id, $parent_id, $module_name, $relationship_attr_name)
Links report results to parent records through relationships.
- **Parameters:**
  - `$report_id` (string) - Saved report ID
  - `$parent_id` (string) - Parent record ID
  - `$module_name` (string) - Target module name
  - `$relationship_attr_name` (string) - Relationship field name
- **Features:**
  - Report definition retrieval and execution
  - Dynamic relationship loading
  - Bulk record linking from report results
  - SubpanelFromReports integration

## Integration Points

### SugarField Integration
- Uses SugarFieldHandler for type-specific field processing
- Supports custom field types and processing logic
- Handles field definition metadata

### Security Integration
- ACL permission checking for module access
- User ownership validation
- Security Groups support
- Admin privilege checking

### Bean Factory Integration
- Uses BeanFactory for proper bean instantiation
- Supports dynamic module loading
- Maintains object lifecycle consistency

### Subpanel Integration
- SubPanelTiles integration for complex queries
- Dynamic subpanel definition loading
- Union query support for related lists

## Common Usage Patterns

### Standard Form Processing
```php
// Validate required fields
if (!checkRequired('', ['name', 'email'])) {
    // Handle validation error
}

// Populate bean from form
$bean = BeanFactory::getBean('Contacts');
populateFromPost('', $bean);
$bean->save();

// Redirect to detail view
handleRedirect($bean->id, 'Contacts');
```

### Form State Preservation
```php
// Preserve form data during multi-step process
$hiddenFields = getPostToForm('action');
echo "<form method='post'>$hiddenFields</form>";
```

### Relationship Management
```php
// Add prospects to campaign
add_prospects_to_prospect_list($campaignId, [$prospect1, $prospect2]);

// Link report results to account
save_from_report($reportId, $accountId, 'Accounts', 'contacts');
```

## Security Considerations

- Input validation for all form data
- ACL permission checking before operations
- XSS prevention in HTML generation
- SQL injection prevention in query building
- Module access validation

## Performance Notes

- SugarField handlers optimize field processing
- Batch relationship operations for efficiency
- Query optimization in report integration
- Proper resource cleanup after operations

## Error Handling

- Comprehensive logging for debugging
- Graceful fallback for missing classes
- Input validation with appropriate error responses
- Database error handling in relationship operations

## Dependencies

- SugarFields framework for field processing
- BeanFactory for bean instantiation
- ACL system for permission checking
- SubPanel framework for complex queries
- Reports module for report integration 