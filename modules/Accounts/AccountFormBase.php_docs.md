# AccountFormBase.php Documentation

## @fileoverview
Form processing and duplicate detection base class for Account module that handles form generation, duplicate checking, data validation, and save operations with comprehensive UI form building capabilities.

## @package SuiteCRM\Modules\Accounts
## @copyright SugarCRM Inc. & SalesAgility Ltd.
## @license AGPL-3.0

## Overview

The AccountFormBase class provides comprehensive form processing functionality for the Accounts module, including duplicate detection, form generation, data validation, and save operations. It serves as the foundation for all account-related form interactions and data processing workflows.

## Database Operations

### Duplicate Detection Queries
- **`checkForDuplicates()`**: Performs intelligent duplicate detection using account name and address information
- **Query Construction**: Builds dynamic WHERE clauses based on available field data
- **Field Matching**: Compares name (LIKE matching) and billing/shipping city information
- **Database Manager**: Uses DBManagerFactory for consistent database access

### Data Validation
- **Field Sanitization**: Input validation and sanitization through database quoting
- **Required Field Checking**: Integration with formbase.php for required field validation
- **Custom Field Support**: Handles custom field validation and processing

## Internal API Calls

### Form Processing Core
- **`handleSave()`**: Main save operation coordinator with duplicate checking and redirection logic
- **Duplicate Management**: Integrated duplicate detection with user choice workflow
- **Security Validation**: ACL access checking before save operations
- **Data Population**: Uses populateFromPost() for secure data transfer from form to bean

### Form Generation Methods
- **`buildTableForm()`**: Generates HTML table forms for duplicate display and selection
- **`getForm()`**: Creates standard account creation forms with validation
- **`getFormBody()`**: Builds basic form body with essential account fields
- **`getWideFormBody()`**: Advanced form generation for contact-to-account conversion

### Navigation and Control Flow
- **Button Generation**: Save, Cancel, and Continue button creation with proper action routing
- **Redirect Handling**: Post-save redirection management with parameter preservation
- **Session Management**: Duplicate data storage in session for workflow continuity

## External API Calls

### Email Integration
- **SugarEmailAddress Integration**: Email address field handling during form processing
- **Email Field Management**: Processing of email1, email2, and email_opt_out fields
- **Email URL Generation**: Form URL building for email address relationships

### JavaScript Integration
- **Form Validation**: JavaScript validation script generation through SugarBean
- **Required Field Handling**: Dynamic required field validation setup
- **AJAX Support**: AJAX call handling for form submission and duplicate detection

## UI Functionality

### Duplicate Display Interface
- **Interactive Duplicate Lists**: Clickable duplicate selection with JavaScript handling
- **Table Formatting**: Professional table layout with alternating row colors
- **Action Buttons**: Save/Cancel button generation with proper form handling
- **Link Generation**: Detail view links for duplicate records

### Form Rendering
- **Dynamic Form Building**: Context-aware form generation based on module and prefix
- **Field Layout**: Professional form layout with proper labeling and validation indicators
- **Hidden Field Management**: Proper hidden field handling for form state management
- **Multi-Column Layout**: Advanced layout support for wide forms

### User Experience Enhancements
- **Progress Indication**: Visual feedback during form processing
- **Error Handling**: User-friendly error message display
- **Return URL Management**: Proper return navigation after form operations
- **Popup Support**: Popup window form handling for modal interfaces

### Contact Conversion Forms
- **Contact-to-Account Conversion**: Specialized form for converting contacts to accounts
- **Field Mapping**: Automatic field mapping from contact to account fields
- **Address Translation**: Contact address fields mapped to account billing/shipping addresses
- **Custom Field Conversion**: Handles custom field conversion between modules

## Form Types and Variations

### Standard Account Forms
- **Basic Creation Form**: Essential account fields with validation
- **Required Field Indicators**: Visual required field marking with proper symbols
- **User Assignment**: Automatic user assignment handling

### Duplicate Selection Forms
- **Duplicate List Display**: Table-based duplicate selection interface
- **Record Comparison**: Side-by-side comparison of potential duplicates
- **Selection Workflow**: User choice between creating new or selecting existing

### Conversion Forms
- **Contact Conversion**: Convert contact to account with field mapping
- **Address Handling**: Comprehensive address field conversion
- **Relationship Preservation**: Maintains related data during conversion

## Security and Validation

### Access Control
- **ACL Integration**: Comprehensive access control checking throughout form lifecycle
- **Edit Permission Validation**: Checks edit permissions before form display
- **Save Permission Validation**: Validates save permissions before data persistence

### Data Security
- **Input Sanitization**: Proper data sanitization through database quoting
- **XSS Prevention**: Cross-site scripting prevention in form output
- **SQL Injection Protection**: Parameterized queries for duplicate detection

### Form Validation
- **Client-Side Validation**: JavaScript validation for required fields
- **Server-Side Validation**: PHP validation before data persistence
- **Custom Validation**: Support for custom field validation rules

## Integration Points

### Module Integration
- **Return Module Handling**: Proper integration with calling modules
- **Parameter Preservation**: Maintains context through form workflows
- **Relationship Management**: Handles related record creation and updates

### Template System
- **Smarty Integration**: Template-based form rendering support
- **Theme Compatibility**: Form generation compatible with SuiteCRM themes
- **Responsive Design**: Form layouts that work across device types 