# en_us.lang.php Documentation

## Overview
**File:** `modules/AOR_Charts/language/en_us.lang.php`  
**Purpose:** English (US) language strings for AOR_Charts module  
**Package:** Advanced OpenReports for SugarCRM  

## Language Configuration

### Module String Array
**Global Variable:** `$mod_strings`  
**Purpose:** Contains all translatable strings for the AOR_Charts module

## UI Functionality

### Standard Field Labels
**Standard SugarBean Fields:**

#### Record Identification
- **LBL_ID:** 'ID' - Primary key identifier
- **LBL_NAME:** 'Name' - Chart name/title
- **LBL_LIST_NAME:** 'Name' - Name column in list views

#### Audit Trail Labels
- **LBL_DATE_ENTERED:** 'Date Created' - Record creation timestamp
- **LBL_DATE_MODIFIED:** 'Date Modified' - Last modification timestamp
- **LBL_CREATED:** 'Created By' - Record creator
- **LBL_MODIFIED:** 'Modified By' - Last modifier
- **LBL_MODIFIED_NAME:** 'Modified By Name' - Full name of modifier
- **LBL_CREATED_USER:** 'Created by User' - Detailed creator information
- **LBL_MODIFIED_USER:** 'Modified by User' - Detailed modifier information

#### Content Fields
- **LBL_DESCRIPTION:** 'Description' - Chart description field
- **LBL_DELETED:** 'Deleted' - Soft delete status indicator

#### Assignment Labels
- **LBL_ASSIGNED_TO_ID:** 'Assigned User Id' - Assigned user identifier
- **LBL_ASSIGNED_TO_NAME:** 'Assigned to' - Assigned user display

#### Action Labels
- **LBL_EDIT_BUTTON:** 'Edit' - Edit action button
- **LBL_REMOVE:** 'Remove' - Remove/delete action

### Module-Specific Labels

#### Navigation and Interface
- **LBL_LIST_FORM_TITLE:** 'Charts List' - List view page title
- **LBL_MODULE_NAME:** 'Charts' - Module display name
- **LBL_MODULE_TITLE:** 'Charts' - Module title in headers
- **LBL_HOMEPAGE_TITLE:** 'My Charts' - Dashboard/homepage title
- **LBL_SEARCH_FORM_TITLE:** 'Search Charts' - Search form title
- **LBL_NEW_FORM_TITLE:** 'New Charts' - Create form title

#### Navigation Links
- **LNK_NEW_RECORD:** 'Create Charts' - Create new chart link
- **LNK_LIST:** 'View Charts' - View charts list link

#### Subpanel Integration
- **LBL_HISTORY_SUBPANEL_TITLE:** 'View History' - History subpanel title
- **LBL_ACTIVITIES_SUBPANEL_TITLE:** 'Activities' - Activities subpanel title
- **LBL_AOR_CHARTS_SUBPANEL_TITLE:** 'Charts' - Charts subpanel title

## Internationalization

### Language Support
- **Locale:** English (United States)
- **Character Encoding:** UTF-8 support
- **Standard Compliance:** Follows SuiteCRM language file conventions

### Consistent Terminology
- **Chart References:** Consistent use of "Charts" terminology
- **Action Verbs:** Standard SuiteCRM action terminology
- **Field Naming:** Follows SuiteCRM field naming patterns

## Integration Points

### SuiteCRM Framework
- **Language Loading:** Loaded by SuiteCRM's language system
- **Module Integration:** Provides labels for all module interfaces
- **Theme Integration:** Labels work with all SuiteCRM themes

### User Interface Components
- **Form Labels:** Provides labels for all form fields
- **List Views:** Column headers and interface elements
- **Detail Views:** Field labels and action buttons
- **Navigation:** Menu items and breadcrumbs

### Related Modules
- **AOR_Reports:** Chart labels integrate with report interface
- **Administration:** Module appears in admin interfaces
- **Dashboard:** Chart widgets use these labels

## Usage Scenarios

### Form Interfaces
- **Create/Edit Forms:** Field labels and help text
- **Search Forms:** Search field labels and buttons
- **Quick Create:** Simplified form labels

### List and Detail Views
- **Column Headers:** List view column titles
- **Field Labels:** Detail view field displays
- **Action Buttons:** Button text and tooltips

### Navigation Elements
- **Module Menu:** Module navigation items
- **Subpanels:** Related record displays
- **Breadcrumbs:** Navigation path elements

## Extensibility

### Custom Labels
- **Additional Fields:** New labels can be added for custom fields
- **Custom Actions:** Custom button and action labels
- **Integration Labels:** Labels for third-party integrations

### Localization Support
- **Translation Base:** Serves as base for other language translations
- **Key Consistency:** Standardized keys for translation consistency
- **Cultural Adaptation:** Labels can be culturally adapted for different regions

## Development Guidelines

### Label Conventions
- **Key Format:** Standard SuiteCRM label key format (LBL_FIELD_NAME)
- **Descriptive Keys:** Keys clearly indicate their purpose
- **Consistent Naming:** Follows established SuiteCRM patterns

### Content Guidelines
- **Clear Language:** Simple, clear terminology
- **Action-Oriented:** Action labels clearly indicate function
- **Professional Tone:** Business-appropriate language

### Maintenance
- **Version Control:** Language changes tracked in version control
- **Translation Sync:** Changes propagated to other language files
- **Consistency Checks:** Regular review for terminology consistency 