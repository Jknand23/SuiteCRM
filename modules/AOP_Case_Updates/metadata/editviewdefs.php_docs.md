# editviewdefs.php Documentation

## Overview
**File:** `modules/AOP_Case_Updates/metadata/editviewdefs.php`  
**Purpose:** Edit view layout and field definitions for AOP_Case_Updates module  
**Package:** Advanced OpenPortal  

## Configuration Structure

### Edit View Definition Array
**Global Variable:** `$viewdefs[$module_name]['EditView']`  
**Module Name:** `AOP_Case_Updates`

## UI Functionality

### Template Metadata Configuration

#### Layout Properties
- **maxColumns:** '2' (two-column layout)
- **widths:** Column width specifications
  - **Column 0:** label='10', field='30' (40% total)
  - **Column 1:** label='10', field='30' (40% total)
  - **Remaining:** 20% for spacing and form controls

### Panel Layout Structure

#### Row 0: Basic Information
**Fields:**
1. **name** (Column 1)
   - **Purpose:** Case update title/summary input
   - **Type:** Text input field
   - **Validation:** Required field
   - **Max Length:** Defined in vardefs

2. **assigned_user_name** (Column 2)
   - **Purpose:** User assignment selector
   - **Type:** User lookup field
   - **Integration:** User selection popup
   - **Default:** Current user

#### Row 1: Content and Relationships
**Fields:**
1. **description** (Column 1)
   - **Purpose:** Main case update content editor
   - **Type:** Rich text area
   - **Features:** HTML editing capabilities
   - **Validation:** Content filtering for security

2. **aop_case_updates_contacts_1_name** (Column 2)
   - **Purpose:** Related contact selector
   - **Type:** Contact lookup field
   - **Integration:** Contact selection popup
   - **Behavior:** Auto-populate from case context

#### Row 2: Case Association
**Fields:**
1. **cases_aop_case_updates_1_name** (Full Width)
   - **Purpose:** Parent case selector
   - **Type:** Case lookup field
   - **Integration:** Case selection popup
   - **Validation:** Required relationship

## Internal API Calls

### Field Validation
- **name Field:**
  - **Required:** Validates non-empty input
  - **Length:** Checks maximum character limit
  - **Sanitization:** Removes harmful characters

- **description Field:**
  - **HTML Filtering:** Sanitizes HTML content
  - **Length Validation:** Checks content size limits
  - **Required Content:** Validates meaningful content

### Relationship Management
- **assigned_user_name:**
  - **User Lookup:** Integrates with user selection popup
  - **Validation:** Ensures valid user ID
  - **Default Value:** Sets to current user if empty

- **aop_case_updates_contacts_1_name:**
  - **Contact Lookup:** Integrates with contact selection popup
  - **Relationship Creation:** Establishes contact link on save
  - **Context Awareness:** Pre-filters to case-related contacts

- **cases_aop_case_updates_1_name:**
  - **Case Lookup:** Integrates with case selection popup
  - **Required Relationship:** Validates case selection
  - **Parent Context:** Maintains case hierarchy

### Form Processing
- **Save Operations:**
  - **Field Validation:** Validates all required fields
  - **Relationship Updates:** Updates relationship tables
  - **Audit Trail:** Records modification timestamps

- **Cancel Operations:**
  - **Data Preservation:** Maintains original values
  - **Navigation:** Returns to previous view
  - **Cleanup:** Removes temporary data

## Database Operations

### Record Creation/Update
- **Field Mapping:** Maps form fields to database columns
- **Relationship Storage:** Updates relationship tables
- **Audit Information:** Automatically sets date_modified and modified_user_id

### Validation Queries
- **User Validation:** Verifies assigned user exists and is active
- **Contact Validation:** Confirms contact exists and is accessible
- **Case Validation:** Ensures case exists and user has access

## Security Integration

### Input Validation
- **XSS Prevention:** All text inputs filtered for malicious content
- **SQL Injection Protection:** Parameterized queries for all operations
- **File Upload Security:** If file uploads enabled, validates file types

### Access Control
- **Edit Permissions:** Validates user can modify case updates
- **Relationship Access:** Ensures user can access related records
- **Field-Level Security:** Respects field-level ACL permissions

### Data Integrity
- **Required Fields:** Enforces mandatory field completion
- **Relationship Constraints:** Validates foreign key relationships
- **Business Logic:** Applies custom validation rules

## Performance Optimization

### Form Rendering
- **Progressive Loading:** Loads form elements as needed
- **JavaScript Optimization:** Minimizes client-side processing
- **Template Caching:** Caches compiled view templates

### Database Optimization
- **Selective Updates:** Only updates modified fields
- **Relationship Caching:** Caches relationship data during form display
- **Index Usage:** Leverages database indexes for lookups

## Internationalization

### Language Support
- **Field Labels:** Automatic translation from language files
- **Validation Messages:** Localized error messages
- **Help Text:** Translatable field help content

### Localization Features
- **Date Formats:** Respects user locale settings
- **Number Formats:** Applies locale-specific formatting
- **RTL Support:** Layout adapts for right-to-left languages

## Integration Points

### Related Metadata Files
- **detailviewdefs.php:** Target view after successful save
- **vardefs.php:** Field definitions and validation rules
- **searchdefs.php:** Search integration for lookup fields

### SuiteCRM Framework
- **EditView Controller:** Processes form submission and validation
- **SugarView Classes:** Core view rendering and form processing
- **Relationship Engine:** Manages relationship creation and updates
- **Validation Framework:** Applies field and business rule validation

### Module Dependencies
- **Cases:** Parent case lookup and validation
- **Contacts:** Contact lookup and relationship management
- **Users:** User assignment and validation
- **Notes:** For file attachment handling (if enabled)

## Advanced Features

### Dynamic Behavior
- **Conditional Fields:** Fields can be shown/hidden based on other values
- **Auto-Population:** Fields can auto-populate based on relationships
- **Real-Time Validation:** Client-side validation for immediate feedback

### Extensibility
- **Custom Fields:** Additional fields can be added to layout
- **Studio Integration:** Visual form designer support
- **Custom Validation:** Business-specific validation rules

### Workflow Integration
- **Logic Hooks:** Supports before/after save processing
- **Workflow Rules:** Integrates with SuiteCRM workflow engine
- **Email Notifications:** Triggers notification logic hooks

## Form Behavior

### Client-Side Features
- **Field Dependencies:** Changes to one field affect others
- **Lookup Integration:** Popup selectors for relationship fields
- **Form Validation:** JavaScript validation before submission

### Server-Side Processing
- **Save Logic:** Comprehensive save processing with error handling
- **Relationship Management:** Creates and updates relationship records
- **Hook Execution:** Triggers appropriate logic hooks during save process 