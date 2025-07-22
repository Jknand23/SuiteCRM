# caseUpdateForm.tpl Documentation

## Overview
**File:** `modules/AOP_Case_Updates/tpl/caseUpdateForm.tpl`  
**Purpose:** Smarty template for case update form with file attachment functionality  
**Template Engine:** Smarty  
**Package:** Advanced OpenPortal  

## Template Structure

### Main Components
1. **Add File Button:** Triggers file attachment interface
2. **JavaScript Functionality:** Dynamic form behavior and file management
3. **Template Definition:** Hidden template for dynamic file rows

## UI Functionality

### File Attachment Interface

#### Add File Button
- **Element:** `<button id="addFileButton" class="button primary">`
- **Label:** `{$MOD.LBL_ADD_CASE_FILE}` (translatable)
- **Purpose:** Adds new file attachment rows to form
- **Behavior:** Dynamically inserts file selection controls

#### File Selection Options
**Document Type Selector:**
- **Internal Documents:** Select from existing SuiteCRM documents
- **External Files:** Upload new files from local system

### Dynamic Form Generation

#### File Row Template
**Template ID:** `updateFileRowTemplate`
**Template Type:** `text/template`

**Components:**
1. **Document Type Selector:** Dropdown for internal/external choice
2. **File Upload Control:** `<input type="file">` for external files
3. **Document Selector:** Search interface for internal documents
4. **Remove Button:** Deletes file attachment row

## Internal API Calls

### Document Search Integration
**Sugar Quick Search (SQS) Configuration:**
- **Module:** 'Documents'
- **Method:** 'query'
- **Field List:** ["name","id"]
- **Populate List:** [case_document_name, case_document_id]
- **Required List:** [case_document_id]
- **Search Conditions:** Name-based LIKE queries
- **Result Limit:** 30 records
- **No Match Text:** "No Match"

### Form Processing
- **File Array Management:** Reorganizes multi-file uploads
- **Document Relationship:** Links selected documents to case updates
- **Validation:** Ensures required fields are populated

## External API Calls

### Document Popup Integration
**Popup Function:** `open_popup()`
**Parameters:**
- **Module:** "Documents"
- **Width:** 600px
- **Height:** 400px
- **Mode:** "single" selection
- **Callback:** "set_return" function
- **Field Mapping:** id → case_document_id, name → case_document_name

### File Upload Processing
- **Upload Target:** Server-side file processing
- **File Validation:** MIME type and size checks
- **Storage:** Secure file storage system

## JavaScript Functionality

### Document Ready Events
**jQuery Integration:**
```javascript
$(document).ready(function(){
    // Initialize document type selectors
    // Set up event handlers
    // Configure search objects
});
```

### Event Handlers

#### Document Type Change
**Trigger:** `.caseDocumentTypeSelect` change event
**Behavior:**
- **Internal:** Shows document search interface, hides file upload
- **External:** Shows file upload, hides document search interface

#### Add File Button Click
**Trigger:** `#addFileButton` click event
**Process:**
1. Clone template HTML
2. Replace placeholder names with unique identifiers
3. Insert new row before button
4. Initialize search functionality
5. Increment document counter

#### Remove File Button Click
**Trigger:** `.removeFileButton` click event (delegated)
**Behavior:** Removes parent file attachment row

### Search Object Management
**SQS Objects:** Global search configuration array
**Dynamic Setup:** Creates search objects for each file row
**Auto-Complete:** Enables type-ahead search for documents

## Template Variables

### Smarty Variables
- **{$MOD.LBL_ADD_CASE_FILE}:** Add file button label
- **{$MOD.LBL_SELECT_INTERNAL_CASE_DOCUMENT}:** Internal document option
- **{$MOD.LBL_SELECT_EXTERNAL_CASE_DOCUMENT}:** External file option
- **{$MOD.LBL_SELECT_CASE_DOCUMENT}:** Document selection button
- **{$MOD.LBL_CLEAR_CASE_DOCUMENT}:** Clear selection button
- **{$MOD.LBL_REMOVE_CASE_FILE}:** Remove file button

### Dynamic Placeholders
- **case_document_name:** Replaced with unique field names
- **case_document_id:** Replaced with unique ID fields

## Security Features

### Input Validation
- **File Type Validation:** Restricts allowed file types
- **Size Limitations:** Enforces maximum file sizes
- **Document Access:** Validates user access to selected documents

### XSS Prevention
- **Output Encoding:** All dynamic content properly encoded
- **Input Sanitization:** Form inputs sanitized before processing
- **Safe HTML:** Template variables escaped for security

## Performance Optimization

### Client-Side Optimization
- **Event Delegation:** Efficient event handling for dynamic elements
- **Template Caching:** Reuses template HTML for multiple rows
- **Selective Loading:** Loads search objects only when needed

### Server-Side Efficiency
- **AJAX Integration:** Asynchronous form processing
- **Selective Queries:** Limited document search results
- **Caching:** Search results cached for performance

## Internationalization

### Language Support
- **Translatable Labels:** All user-facing text translatable
- **Dynamic Language Loading:** Supports language switching
- **UTF-8 Support:** International character support

### Localization Features
- **Button Text:** Locale-specific button labels
- **Error Messages:** Translated validation messages
- **Help Text:** Localized user guidance

## Integration Points

### SuiteCRM Framework
- **Sugar Quick Search:** Integrates with core search functionality
- **Document Management:** Leverages document module capabilities
- **File Upload System:** Uses standard SuiteCRM file handling

### Module Dependencies
- **Documents:** Document search and selection
- **Notes:** File attachment storage
- **Cases:** Case update context and relationships

### Browser Compatibility
- **Modern Browsers:** Full functionality in current browsers
- **Progressive Enhancement:** Graceful degradation for older browsers
- **Mobile Support:** Touch-friendly interface elements

## Advanced Features

### Dynamic Interface
- **Real-Time Updates:** Interface updates as user interacts
- **Context Awareness:** Adapts based on document selection
- **User Feedback:** Visual feedback for user actions

### Extensibility
- **Custom File Types:** Can be extended for specific file types
- **Additional Metadata:** Supports extra file metadata fields
- **Workflow Integration:** Can trigger workflow actions

## Error Handling

### Client-Side Validation
- **Required Fields:** Validates required document selections
- **File Validation:** Checks file types and sizes
- **User Feedback:** Clear error messaging

### Server-Side Processing
- **Upload Errors:** Handles file upload failures
- **Document Access:** Validates document permissions
- **Data Integrity:** Ensures proper relationship creation

## Template Rendering Process

### Initial Load
1. Template compiled by Smarty engine
2. Language variables resolved
3. JavaScript initialized
4. Event handlers attached

### Dynamic Updates
1. User clicks add file button
2. Template cloned and customized
3. Search objects configured
4. Form updated with new controls

### Form Submission
1. File arrays reorganized
2. Document relationships created
3. Files uploaded and processed
4. Case update record updated 