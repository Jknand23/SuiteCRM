# Case_Updates.php Documentation

## Overview
**File:** `modules/AOP_Case_Updates/Case_Updates.php`  
**Purpose:** UI display and interaction functions for AOP case updates  
**Dependencies:** Sugar_Smarty templating, SugarThemeRegistry, ACLRoles  

## Function Definitions

### display_updates($focus)
Main function that renders the complete case updates interface including JavaScript controls and update stream.

**Parameters:**
- `$focus`: Case bean object

**Returns:** HTML string with embedded JavaScript

**UI Components:**
- Expand/collapse controls for all updates
- Individual update toggle functionality  
- AJAX-powered update submission form
- Collapsible update display stream

## Internal API Calls

### Case Update Retrieval
- **Function:** `$focus->get_linked_beans('aop_case_updates', 'AOP_Case_Updates')`
- **Purpose:** Fetches all related case updates
- **Processing:** Updates sorted by date_entered in ascending order
- **Integration:** Uses SuiteCRM's relationship loading system

### Theme Asset Management
- **Function:** `SugarThemeRegistry::current()->getImageURL()`
- **Purpose:** Retrieves theme-specific image URLs
- **Assets Used:**
  - `basic_search.gif`: Collapse indicator
  - `advanced_search.gif`: Expand indicator

### User Permission Checking
- **Function:** `ACLRole::getUserRoles($user_id)`
- **Purpose:** Validates user edit permissions for cases
- **Logic:** Blocks case update form if user has 'no edit cases' role

### Note Attachment Retrieval
- **Function:** `$update->get_linked_beans('notes', 'Notes')`
- **Purpose:** Fetches file attachments for each update
- **Display:** Creates download links for attached files

## External API Calls

### AJAX Update Submission
- **Endpoint:** `index.php`
- **Method:** POST
- **Parameters:**
  - `record`: Case ID
  - `module`: 'Cases'
  - `action`: 'Save'
  - `update_text`: Encoded update content
  - `internal`: Internal flag (0 or 1)
- **Response Handling:** Reloads history panel and update stream

### Template Rendering
- **Function:** `Sugar_Smarty::fetch()`
- **Template:** `modules/AOP_Case_Updates/tpl/caseUpdateForm.tpl`
- **Variables Assigned:**
  - `MOD`: Module strings
  - `APP`: Application strings

## UI Functionality

### JavaScript Interface Controls

#### Update Stream Management
- **Function:** `collapseAllUpdates()`
- **Purpose:** Collapses all case updates in the stream
- **Visual Effect:** Slides up all `.caseUpdate` elements

- **Function:** `expandAllUpdates()`
- **Purpose:** Expands all case updates in the stream
- **Visual Effect:** Slides down all `.caseUpdate` elements

- **Function:** `toggleCaseUpdate(updateId)`
- **Purpose:** Toggles visibility of individual case update
- **Animation:** Smooth slide toggle with image state change

#### AJAX Update Processing
- **Function:** `caseUpdates(record)`
- **Purpose:** Submits new case update via AJAX
- **User Experience:**
  - Shows loading dialog during processing
  - Refreshes update stream after submission
  - Auto-expands newest update
  - Clears form after successful submission

### Update Display Functions

#### Single Update Rendering
- **Function:** `display_single_update($update)`
- **Purpose:** Renders individual case update with appropriate styling
- **Styling Logic:**
  - Internal updates: `caseStyleInternal` styling
  - User updates: `caseStyleUser` styling with less margin
  - Contact updates: `caseStyleContact` styling with extra margin
- **Content Processing:** Uses `purify_html()` for XSS protection

#### Update Header Generation
- **Function:** `getUpdateDisplayHead($update)`
- **Purpose:** Creates update header with author and timestamp information
- **Author Logic:**
  - Contact updates: Shows contact name
  - User updates: Shows assigned user name
  - Unknown: Shows 'Unknown Contact' fallback
- **Features:**
  - Toggle control integration
  - Internal update indicator
  - Attachment file links

#### Attachment Display
- **Function:** `display_case_attachments($case)`
- **Purpose:** Shows file attachments linked to case
- **Format:** Links to Notes DetailView for each attachment

### Quick Edit Form
- **Function:** `quick_edit_case_updates($case)`
- **Purpose:** Renders inline case update form
- **Restrictions:**
  - Only available on DetailView action
  - Blocked for users without case edit permissions
- **Form Elements:**
  - Multi-line text area for update content
  - Internal flag checkbox
  - AJAX submit button
- **JavaScript Integration:** Calls `caseUpdates()` function on submit

### Update Form Template
- **Function:** `display_update_form()`
- **Purpose:** Renders case update form using Smarty template
- **Template Variables:**
  - `MOD`: Module-specific language strings
  - `APP`: Application language strings

## Content Security

### HTML Sanitization
- **Function:** `purify_html()`
- **Configuration:** `['HTML.ForbiddenElements' => ['iframe' => true]]`
- **Purpose:** Prevents XSS attacks while preserving formatting
- **Application:** Applied to all update descriptions before display

### Content Processing
- **Function:** `html_entity_decode()`
- **Purpose:** Converts HTML entities to readable characters
- **Integration:** Used before HTML purification

- **Function:** `nl2br()`
- **Purpose:** Converts line breaks to HTML `<br>` tags
- **Application:** Applied to user and internal updates

## Internationalization

### Language String Management
- **Global Variables:**
  - `$mod_strings`: Module-specific translations
  - `$app_strings`: Application-wide translations
- **Dynamic Loading:** `return_module_language($current_language, 'Cases')`

### Localized Labels Used
- `LBL_CASE_UPDATES_COLLAPSE_ALL`: Collapse control text
- `LBL_CASE_UPDATES_EXPAND_ALL`: Expand control text
- `LBL_INTERNAL`: Internal update indicator
- `LBL_UNKNOWN_CONTACT`: Fallback for missing author
- `LBL_AOP_CASE_ATTACHMENTS`: Attachment section header
- `LBL_UPDATE_TEXT`: Update form field label
- `LBL_SAVE_BUTTON_LABEL`: Save button text
- `LBL_SAVE_BUTTON_TITLE`: Save button tooltip

## Performance Optimization

### Client-Side Optimization
- **jQuery Integration:** Uses efficient selectors and animations
- **AJAX Loading:** Updates only specific page sections
- **Progressive Enhancement:** Form works with JavaScript disabled

### Server-Side Optimization
- **Conditional Rendering:** Returns early for empty cases
- **Permission Caching:** Stores role checks to avoid repeated queries
- **Template Caching:** Leverages Smarty template compilation

## Error Handling

### JavaScript Error Management
- **AJAX State Checking:** Validates `readyState` and `status` before processing
- **Loading State Management:** Shows/hides loading dialog appropriately
- **Graceful Degradation:** Provides fallback for non-AJAX environments

### PHP Error Prevention
- **Null Checks:** Validates objects before method calls
- **Array Validation:** Checks array existence before iteration
- **Permission Validation:** Prevents unauthorized access to functionality

## Integration Points

### Module Dependencies
- **Cases:** Primary module for case management
- **AOP_Case_Updates:** Case update records and relationships
- **Notes:** File attachment handling
- **ACLRoles:** Permission checking
- **Users:** User information display

### Theme Integration
- **SugarThemeRegistry:** Dynamic asset URL generation
- **CSS Classes:** Relies on theme-specific styling for update display
- **Image Assets:** Uses theme-provided toggle indicators

### JavaScript Dependencies
- **jQuery:** DOM manipulation and AJAX handling
- **YAHOO.widget.SimpleDialog:** Loading dialog display
- **SUGAR.language:** Client-side internationalization 