# ShowDuplicates.php Documentation

## @fileoverview
Duplicate account resolution interface that presents potential duplicate accounts to users and manages the duplicate selection workflow during account creation with comprehensive form handling and data preservation.

## @package SuiteCRM\Modules\Accounts
## @copyright SugarCRM Inc. & SalesAgility Ltd.
## @license AGPL-3.0

## Overview

This file provides the user interface for resolving duplicate account detection during the account creation process. It retrieves duplicate candidates from session data, presents them in a formatted table, and allows users to either select an existing account or proceed with creating a new one.

## Database Operations

### Duplicate Record Retrieval
- **Query Construction**: Builds SELECT query to retrieve duplicate account records based on session data
- **Field Selection**: Retrieves id, name, website, and billing_address_city for comparison
- **Dynamic WHERE Clause**: Constructs WHERE clause using duplicate IDs from $_POST array
- **Database Security**: Uses proper quoting through DBManagerFactory for SQL injection prevention

### Record Processing
- **Result Iteration**: Processes query results into structured array for display
- **Data Formatting**: Prepares duplicate records for table presentation
- **Field Mapping**: Maps database fields to display columns

## Internal API Calls

### Session Management
- **`$_SESSION['SHOW_DUPLICATES']`**: Retrieves duplicate detection data from session
- **Session Security**: Validates session data existence and clears after use
- **URL Data Reconstruction**: Parses session string data back to $_POST format

### Form Processing
- **AccountFormBase Integration**: Uses AccountFormBase for table form generation
- **XTemplate Processing**: Leverages XTemplate system for HTML rendering
- **Data Population**: Populates template with duplicate records and form data

### Security Validation
- **Entry Point Security**: Standard sugarEntry validation
- **Session Authorization**: Validates authorized access through session data
- **Data Sanitization**: Applies securexss() to all POST data

## External API Calls

### Template System Integration
- **XTemplate Rendering**: Uses XTemplate for professional HTML output
- **Template Assignment**: Assigns module strings, app strings, and form data
- **Popup Handling**: Special handling for popup window contexts

### Email Address Integration
- **SugarEmailAddress Widget**: Integrates email address duplicate handling
- **Widget Generation**: Creates email address comparison widgets
- **Form Integration**: Includes email fields in duplicate resolution form

## UI Functionality

### Duplicate Display Interface
- **Table Presentation**: Professional table layout showing duplicate candidates
- **Record Comparison**: Side-by-side comparison of potential duplicates
- **Selection Mechanism**: Interactive selection interface for choosing existing records
- **Action Buttons**: Save and Cancel button management with proper context

### Form Data Preservation
- **Hidden Field Generation**: Creates hidden fields for all account data
- **Column Fields**: Preserves all standard account fields during duplicate resolution
- **Additional Fields**: Maintains additional column fields and relationships
- **Custom Field Support**: Handles custom field data preservation

### Navigation Management
- **Return Module Handling**: Manages return navigation context
- **Return Action Processing**: Preserves return action information
- **Return ID Management**: Maintains return record ID for proper navigation
- **Parameter Preservation**: Preserves popup, PDF, and creation context

### Template Integration
- **Module Title Display**: Professional module title with breadcrumb navigation
- **Localized Strings**: Full localization support through mod_strings and app_strings
- **Print URL Support**: Print-friendly URL generation
- **Error Handling**: Error message display through template system

## Form Context Management

### Special Relationship Handling
- **Many-to-Many Relationships**: Special handling for Contact-to-Account relationships
- **Related Record IDs**: Preserves relate_to and relate_id for relationship context
- **Relationship Continuity**: Maintains relationship context through duplicate resolution

### Context Preservation
- **Popup Context**: Maintains popup window state through resolution process
- **PDF Generation**: Preserves PDF generation context
- **Creation Context**: Maintains creation workflow context
- **Module Context**: Preserves calling module context

### Data Integrity
- **URL Decoding**: Proper URL decoding of preserved form data
- **Field Validation**: Ensures all required fields are preserved
- **Data Sanitization**: Security sanitization while preserving data integrity

## Workflow Integration

### Duplicate Resolution Process
1. **Session Data Retrieval**: Gets duplicate candidates from session
2. **Security Validation**: Validates authorized access
3. **Data Reconstruction**: Rebuilds form data from session
4. **Query Execution**: Retrieves duplicate records from database
5. **UI Generation**: Creates duplicate selection interface
6. **Form Preservation**: Maintains all form data for resolution
7. **Template Rendering**: Outputs professional duplicate resolution interface

### User Experience Flow
- **Clear Instructions**: Displays appropriate messages for duplicate resolution
- **Visual Comparison**: Professional table layout for easy comparison
- **Decision Support**: Clear options for proceeding or selecting existing records
- **Context Preservation**: Maintains all workflow context through resolution

## Security Implementation

### Access Control
- **Session-Based Authorization**: Validates access through session data
- **Entry Point Security**: Standard SuiteCRM entry point validation
- **Data Security**: Proper data sanitization and validation

### Data Protection
- **SQL Injection Prevention**: Parameterized queries with proper quoting
- **XSS Prevention**: Cross-site scripting prevention through data sanitization
- **Session Security**: Secure session data handling and cleanup

## Integration Points

### Account Creation Workflow
- **AccountFormBase Integration**: Seamless integration with account form processing
- **Save Workflow**: Integration with account save operations
- **Validation Workflow**: Maintains validation context through duplicate resolution

### Module Integration
- **Return Module Support**: Supports calls from various modules
- **Relationship Preservation**: Maintains relationships during duplicate resolution
- **Context Continuity**: Preserves module context throughout workflow 