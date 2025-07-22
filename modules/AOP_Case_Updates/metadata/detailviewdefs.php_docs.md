# detailviewdefs.php Documentation

## Overview
**File:** `modules/AOP_Case_Updates/metadata/detailviewdefs.php`  
**Purpose:** Detail view layout and field definitions for AOP_Case_Updates module  
**Package:** Advanced OpenPortal  

## Configuration Structure

### Detail View Definition Array
**Global Variable:** `$viewdefs[$module_name]['DetailView']`  
**Module Name:** `AOP_Case_Updates`

## UI Functionality

### Template Metadata Configuration

#### Form Configuration
- **buttons:** Standard action buttons array
  - **EDIT:** Navigate to edit view
  - **DUPLICATE:** Create duplicate record
  - **DELETE:** Remove record with confirmation
  - **FIND_DUPLICATES:** Search for potential duplicates

#### Layout Properties
- **maxColumns:** '2' (two-column layout)
- **widths:** Column width specifications
  - **Column 0:** label='10', field='30' (40% total)
  - **Column 1:** label='10', field='30' (40% total)
  - **Remaining:** 20% for spacing and margins
- **useTabs:** false (single panel layout)

#### Tab Configuration
- **tabDefs.DEFAULT:**
  - **newTab:** false (no tab interface)
  - **panelDefault:** 'expanded' (panel visible by default)

### Panel Layout Structure

#### Row 0: Basic Information
**Fields:**
1. **name** (Column 1)
   - **Purpose:** Case update title/summary
   - **Type:** Text field display
   - **Width:** Standard field width

2. **assigned_user_name** (Column 2)
   - **Purpose:** Shows assigned user display name
   - **Type:** User relationship field
   - **Integration:** Links to user profile

#### Row 1: Audit Information
**Fields:**
1. **date_entered** (Column 1)
   - **Purpose:** Record creation timestamp
   - **Type:** Date/time display
   - **Format:** Localized date format

2. **date_modified** (Column 2)
   - **Purpose:** Last modification timestamp
   - **Type:** Date/time display
   - **Format:** Localized date format

#### Row 2: Content and Relationships
**Fields:**
1. **description** (Column 1)
   - **Purpose:** Main case update content
   - **Type:** Text area display
   - **Processing:** HTML rendering with security filtering

2. **aop_case_updates_contacts_1_name** (Column 2)
   - **Purpose:** Related contact display
   - **Type:** Relationship field
   - **Integration:** Links to contact detail view

#### Row 3: Case Relationship
**Fields:**
1. **case_name** (Full Width)
   - **Label:** 'LBL_CASE_NAME'
   - **Purpose:** Parent case identification
   - **Type:** Relationship field
   - **Integration:** Links to case detail view

#### Row 4: Contact Information
**Fields:**
1. **contact_name** (Column 1)
   - **Label:** 'LBL_CONTACT_NAME'
   - **Purpose:** Primary contact display
   - **Type:** Relationship field
   - **Integration:** Links to contact detail view

2. **Empty Field** (Column 2)
   - **Purpose:** Layout spacing
   - **Type:** Placeholder

## Internal API Calls

### Field Resolution
- **name:** Direct field display from database
- **assigned_user_name:** Resolves via Users relationship
- **date_entered/date_modified:** Automatic timestamp formatting
- **description:** Processes HTML content with security filtering
- **case_name:** Resolves via Cases relationship
- **contact_name:** Resolves via Contacts relationship

### Relationship Loading
- **Cases Relationship:**
  - **Field:** cases_aop_case_updates_1
  - **Purpose:** Parent case connection
  - **Loading:** Lazy loading on demand

- **Contacts Relationship:**
  - **Field:** aop_case_updates_contacts_1
  - **Purpose:** Related contact connection
  - **Loading:** Lazy loading on demand

### Button Functionality
- **EDIT:** Redirects to EditView with record ID
- **DUPLICATE:** Creates new record with copied values
- **DELETE:** Executes soft delete with confirmation
- **FIND_DUPLICATES:** Searches based on name and description

## Security Integration

### Access Control
- **Button Visibility:** Respects module ACL permissions
- **Field Access:** Honors field-level security settings
- **Relationship Security:** Validates access to related records

### Data Protection
- **HTML Filtering:** Description field filtered for XSS prevention
- **Relationship Validation:** Ensures user can access linked records
- **Audit Trail:** date_entered and date_modified protected from editing

## Performance Optimization

### Rendering Efficiency
- **Two-Column Layout:** Optimized for standard screen sizes
- **Relationship Caching:** Related data cached after first load
- **Button State Caching:** Permission checks cached per request

### Data Loading
- **Selective Loading:** Only loads displayed fields
- **Relationship Optimization:** Lazy loading prevents unnecessary queries
- **Template Caching:** View definition cached for repeated use

## Internationalization

### Language Label Integration
- **LBL_CASE_NAME:** Translatable case relationship label
- **LBL_CONTACT_NAME:** Translatable contact relationship label
- **Button Labels:** Standard SuiteCRM button translations
- **Field Labels:** Automatic label resolution from vardefs

### Localization Support
- **Date Formatting:** Automatic locale-based date display
- **RTL Layouts:** Column percentages support right-to-left languages
- **Character Encoding:** UTF-8 support for international content

## Integration Points

### Related Metadata Files
- **editviewdefs.php:** Target for EDIT button action
- **listviewdefs.php:** Source for navigation to detail view
- **vardefs.php:** Field definitions and relationship specifications

### SuiteCRM Framework
- **DetailView Controller:** Renders view using this configuration
- **SugarView Classes:** Core view rendering engine
- **Security Framework:** Integrates with ACL and field-level security
- **Relationship Engine:** Handles related record loading

### Module Dependencies
- **Cases:** Parent case relationship and navigation
- **Contacts:** Contact relationship and display
- **Users:** Assigned user relationship and display
- **Notes:** For potential file attachments (via subpanels)

## Advanced Features

### Extensibility
- **Custom Fields:** Additional fields can be added to panels
- **Studio Integration:** Visual layout customization
- **Custom Buttons:** Additional action buttons can be defined

### Layout Flexibility
- **Panel Management:** Supports multiple panels and sections
- **Tab Interface:** Can be converted to tabbed layout
- **Responsive Design:** Adapts to different screen sizes

### Workflow Integration
- **Logic Hooks:** Supports before/after display hooks
- **Field Dependencies:** Can implement conditional field display
- **Custom Actions:** Supports custom button actions and workflows 