# CaseUpdatesHook.php Documentation

## Overview
**File:** `modules/AOP_Case_Updates/CaseUpdatesHook.php`  
**Purpose:** Logic hook handler for AOP (Advanced OpenPortal) case updates functionality  
**Dependencies:** `util.php`, `AOPAssignManager.php`  

## Class Definition

### CaseUpdatesHook
Main hook handler class that manages case update operations, email notifications, and file attachments for the Advanced OpenPortal case management system.

**Key Properties:**
- `$slug_size` (private int): Maximum length for case update name slugs (50 characters)

## Database Operations

### Case Update Creation
- **Method:** `saveUpdate($case)`
- **Purpose:** Creates new case update records from case form submissions
- **Database Tables:** `aop_case_updates`, `notes`, `documents`
- **Operations:**
  - Validates case state and status for new cases
  - Assigns default status values from `$app_list_strings`
  - Creates AOP_Case_Updates bean with update text and metadata
  - Links uploaded files and selected documents as notes

### Case-Contact Linking
- **Method:** `linkAccountAndCase($case_id, $account_id)`
- **Purpose:** Establishes relationship between cases and accounts
- **Database Tables:** `cases`
- **Operations:**
  - Updates case record with account_id if not already set
  - Ensures data integrity for case-account relationships

### Email Logging
- **Method:** `logEmail($email, $mailer, $caseId)`
- **Purpose:** Creates email records for sent notifications
- **Database Tables:** `emails`
- **Operations:**
  - Stores outbound email details
  - Links emails to parent case records
  - Tracks email send status and metadata

## Internal API Calls

### File Upload Management
- **Method:** `arrangeFilesArray()`
- **Returns:** Number of uploaded files processed
- **Purpose:** Reorganizes $_FILES array for multi-file uploads
- **Integration:** Works with SuiteCRM's UploadFile class

### Note Creation
- **Method:** `newNote($caseUpdateId)`
- **Returns:** Note bean instance
- **Purpose:** Creates note beans linked to case updates
- **Properties Set:**
  - `parent_type`: 'AOP_Case_Updates'
  - `parent_id`: Case update ID
  - `not_use_rel_in_req`: true

### User Assignment
- **Method:** `getAssignToUser()`
- **Returns:** User ID string
- **Purpose:** Determines next assigned user for new cases
- **Integration:** Uses AOPAssignManager for assignment logic

### Template Processing
- **Method:** `populateTemplate($template, $bean, $contact)`
- **Returns:** Array with populated subject, body, and alt body
- **Purpose:** Processes email templates with case and contact data
- **Integration:** Uses `aop_parse_template()` function

### Configuration Access
- **Method:** `getAOPConfig()`
- **Returns:** AOP configuration array from `$sugar_config`
- **Purpose:** Retrieves portal-specific settings

## External API Calls

### Email Sending
- **Method:** `sendClosureEmail($case)`, `sendCreationEmail($bean, $contact)`
- **Purpose:** Sends notification emails via SugarPHPMailer
- **External Dependencies:**
  - SugarPHPMailer for SMTP operations
  - Email template system
  - Portal email settings

### Email Processing
- **Method:** `saveEmailUpdate($email)`
- **Purpose:** Processes inbound emails and creates case updates
- **External Integration:**
  - SugarEmailAddress for email-to-contact mapping
  - Email parsing and HTML cleaning

## UI Functionality

### Hook Registration
The class provides several logic hook methods:

#### Case Saving Hooks
- **Method:** `saveUpdate($case)`
- **Hook Type:** `after_save`
- **Purpose:** Processes case updates during save operations

#### Relationship Hooks
- **Method:** `assignAccount($case, $event, $arguments)`
- **Hook Type:** `after_relationship_save`
- **Purpose:** Auto-assigns accounts when contacts are linked to cases

- **Method:** `creationNotify($bean, $event, $arguments)`
- **Hook Type:** `after_relationship_save` 
- **Purpose:** Sends creation emails when contacts are added to cases

#### State Change Hooks
- **Method:** `closureNotifyPrep($case)`, `closureNotify($case)`
- **Hook Type:** `before_save`, `after_save`
- **Purpose:** Manages case closure email notifications

#### Content Processing
- **Method:** `filterHTML($bean)`
- **Hook Type:** `before_save`
- **Purpose:** Sanitizes HTML content using SugarCleaner

#### Email Processing
- **Method:** `sendCaseUpdate($caseUpdate)`
- **Hook Type:** `after_save`
- **Purpose:** Sends update notifications to relevant contacts and users

### Case Status Management
- **Method:** `updateCaseStatus($caseId)`
- **Purpose:** Updates case status based on configuration rules
- **Configuration:** Uses `$sugar_config['aop']['case_status_changes']`
- **Logic:** Maps old status to new status and updates case state

### Content Processing
- **Method:** `unquoteEmail($text)`
- **Purpose:** Removes quoted content from email replies
- **Features:**
  - HTML entity decoding
  - Line ending normalization
  - Reply delimiter detection using `$app_strings['LBL_AOP_EMAIL_REPLY_DELIMITER']`

## Error Handling

### Validation Checks
- AOP enablement validation via `isAOPEnabled()`
- File upload error checking
- Email template existence validation
- Contact and account relationship validation

### Logging
- Uses `$GLOBALS['log']` for warning and error messages
- Tracks email sending failures with detailed error information
- Logs hook execution status for debugging

### Exception Handling
- Catches `phpmailerException` for email sending failures
- Graceful degradation when templates or contacts are missing
- Import module detection to skip processing during data imports

## Integration Points

### Module Dependencies
- **Cases:** Primary module for case management
- **AOP_Case_Updates:** Case update records
- **Contacts:** Contact management and email addresses
- **Notes:** File attachment storage
- **Documents:** Document linking functionality
- **EmailTemplates:** Email notification templates
- **Users:** User assignment and signatures

### Configuration Dependencies
- `$sugar_config['aop']`: Portal configuration settings
- `$app_list_strings`: Default status and state values
- Portal email settings via `getPortalEmailSettings()`

### Utility Functions
- `isAOPEnabled()`: Portal enablement check
- `aop_parse_template()`: Template variable substitution
- `getPortalEmailSettings()`: Email configuration retrieval
- `SugarCleaner::cleanHtml()`: HTML sanitization 