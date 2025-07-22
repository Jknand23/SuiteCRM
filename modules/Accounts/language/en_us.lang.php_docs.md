# Accounts Module English Language Strings Documentation

## File Overview
**File**: `modules/Accounts/language/en_us.lang.php`
**Type**: Internationalization Language File
**Purpose**: Provides English language labels, messages, and text strings for the Accounts module user interface

## Description
This file contains all English language translations for the Accounts module, including field labels, error messages, navigation links, and user interface text. It follows SuiteCRM's internationalization (i18n) system to support multi-language deployments.

## Language Array Structure

### Main Language Array
```php
$mod_strings = array(
    // Language key-value pairs
);
```

## UI Functionality

### Field Labels
The file provides human-readable labels for all account fields:

#### Basic Information Fields
- **LBL_ACCOUNT_NAME**: Account Name field label
- **LBL_NAME**: Generic name field label
- **LBL_DESCRIPTION**: Description field label
- **LBL_TYPE**: Account type field label
- **LBL_INDUSTRY**: Industry classification label
- **LBL_RATING**: Account rating label

#### Contact Information Fields
- **LBL_EMAIL**: Primary email field label
- **LBL_OTHER_EMAIL_ADDRESS**: Secondary email label
- **LBL_EMAIL_OPT_OUT**: Email preference control label
- **LBL_INVALID_EMAIL**: Email validation status label
- **LBL_PHONE**: Primary phone field label
- **LBL_PHONE_OFFICE**: Office phone label
- **LBL_PHONE_ALT**: Alternative phone label
- **LBL_PHONE_FAX**: Fax number label
- **LBL_FAX**: General fax label
- **LBL_WEBSITE**: Website URL label

#### Address Information Fields
- **Billing Address Fields**:
  - LBL_BILLING_ADDRESS: Complete billing address
  - LBL_BILLING_ADDRESS_STREET: Street address
  - LBL_BILLING_ADDRESS_STREET_2/3/4: Additional address lines
  - LBL_BILLING_ADDRESS_CITY: City
  - LBL_BILLING_ADDRESS_STATE: State/Province
  - LBL_BILLING_ADDRESS_POSTALCODE: Postal/ZIP code
  - LBL_BILLING_ADDRESS_COUNTRY: Country

- **Shipping Address Fields**:
  - LBL_SHIPPING_ADDRESS: Complete shipping address
  - LBL_SHIPPING_ADDRESS_STREET: Street address
  - LBL_SHIPPING_ADDRESS_STREET_2/3/4: Additional address lines
  - LBL_SHIPPING_ADDRESS_CITY: City
  - LBL_SHIPPING_ADDRESS_STATE: State/Province
  - LBL_SHIPPING_ADDRESS_POSTALCODE: Postal/ZIP code
  - LBL_SHIPPING_ADDRESS_COUNTRY: Country

#### Business Information Fields
- **LBL_ANNUAL_REVENUE**: Annual revenue field
- **LBL_EMPLOYEES**: Employee count field
- **LBL_SIC_CODE**: Standard Industrial Classification code
- **LBL_TICKER_SYMBOL**: Stock ticker symbol
- **LBL_OWNERSHIP**: Ownership type field

#### Relationship Fields
- **LBL_MEMBER_OF**: Parent account relationship
- **LBL_PARENT_ACCOUNT_ID**: Parent account ID field
- **LBL_ASSIGNED_TO_NAME**: User assignment field
- **LBL_ASSIGNED_TO_ID**: Assigned user ID field

### List View Labels
Specific labels for list view display:
- **LBL_LIST_ACCOUNT_NAME**: Account name column header
- **LBL_LIST_CITY**: City column header
- **LBL_LIST_STATE**: State column header
- **LBL_LIST_PHONE**: Phone column header
- **LBL_LIST_EMAIL_ADDRESS**: Email column header
- **LBL_LIST_WEBSITE**: Website column header
- **LBL_LIST_CONTACT_NAME**: Contact name column header

### Section Headers
Organizational labels for form sections:
- **LBL_ACCOUNT_INFORMATION**: Main account information section
- **LBL_ADDRESS_INFORMATION**: Address details section
- **LBL_DESCRIPTION_INFORMATION**: Description section

### Subpanel Titles
Labels for related record subpanels:
- **LBL_CONTACTS_SUBPANEL_TITLE**: Related contacts
- **LBL_OPPORTUNITIES_SUBPANEL_TITLE**: Related sales opportunities
- **LBL_CASES_SUBPANEL_TITLE**: Related support cases
- **LBL_PROJECTS_SUBPANEL_TITLE**: Related projects
- **LBL_ACTIVITIES_SUBPANEL_TITLE**: Related activities
- **LBL_HISTORY_SUBPANEL_TITLE**: Activity history
- **LBL_DOCUMENTS_SUBPANEL_TITLE**: Related documents
- **LBL_BUGS_SUBPANEL_TITLE**: Related bug reports
- **LBL_LEADS_SUBPANEL_TITLE**: Related leads
- **LBL_CAMPAIGNS**: Campaign relationships
- **LBL_MEMBER_ORG_SUBPANEL_TITLE**: Member organizations

#### Sales Module Integration
- **LBL_AOS_QUOTES**: Related quotes
- **LBL_AOS_INVOICES**: Related invoices  
- **LBL_AOS_CONTRACTS**: Related contracts
- **LBL_PRODUCTS_SERVICES_PURCHASED_SUBPANEL_TITLE**: Products and services

### Navigation Links
Links for module navigation:
- **LNK_ACCOUNT_LIST**: View accounts list link
- **LNK_NEW_ACCOUNT**: Create new account link
- **LNK_IMPORT_ACCOUNTS**: Import accounts link

### Page Titles
- **LBL_MODULE_NAME**: Module name for navigation
- **LBL_MODULE_TITLE**: Module home page title
- **LBL_MODULE_ID**: Module identifier
- **LBL_HOMEPAGE_TITLE**: Homepage widget title
- **LBL_NEW_FORM_TITLE**: New record form title
- **LBL_LIST_FORM_TITLE**: List view page title
- **LBL_SEARCH_FORM_TITLE**: Search form title

### Action Labels
- **LBL_SAVE_ACCOUNT**: Save button text
- **LBL_PUSH_CONTACTS_BUTTON_LABEL**: Address copy functionality
- **LBL_PUSH_CONTACTS_BUTTON_TITLE**: Address copy button title

### System Messages

#### Error Messages
- **ERR_DELETE_RECORD**: Record deletion error message
- **LBL_DUPLICATE**: Duplicate record warning label

#### Duplicate Detection Messages
- **MSG_DUPLICATE**: Duplicate prevention message for new records
- **MSG_SHOW_DUPLICATES**: Duplicate detection message during save

### Database Field Mappings
Special mappings for database field display:
```php
'db_name' => 'LBL_LIST_ACCOUNT_NAME',
'db_website' => 'LBL_LIST_WEBSITE', 
'db_billing_address_city' => 'LBL_LIST_CITY',
```
These map database field names to their corresponding display labels.

### Search Fields
- **LBL_ANY_ADDRESS**: Universal address search field
- **LBL_ANY_EMAIL**: Universal email search field  
- **LBL_ANY_PHONE**: Universal phone search field

### Dashlet Categories
- **LBL_CHARTS**: Chart-based dashlets category
- **LBL_DEFAULT**: Standard view dashlets category

## Integration Points

### Module System Integration
- **Module Registration**: Provides display names for module registration
- **Navigation**: Supplies labels for main navigation and breadcrumbs
- **Permissions**: Provides text for permission-related messages

### Search System Integration
- **Universal Search**: Labels for cross-field search functionality
- **Advanced Search**: Field labels for detailed search forms
- **Export/Import**: Labels for data exchange operations

### Relationship System Integration
- **Subpanels**: Titles for all related record displays
- **Popup Selection**: Labels for relationship selection interfaces
- **Cross-Module Links**: Text for navigating between related records

## Customization Considerations

### Extensibility
- **Custom Fields**: New field labels follow same naming convention
- **Custom Relationships**: Additional subpanel titles can be added
- **Custom Actions**: New action labels integrate seamlessly

### Translation Framework
- **Key Structure**: Consistent LBL_ prefix for labels, MSG_ for messages
- **Inheritance**: Can be extended by custom language files
- **Override Support**: Custom language files can override specific strings

### Maintenance
- **Version Control**: Changes tracked through standard version control
- **Consistency**: Labels maintain consistent terminology across module
- **Validation**: Missing keys gracefully degrade to key names

This language file serves as the foundation for all user-facing text in the Accounts module, ensuring consistent terminology and proper internationalization support throughout the SuiteCRM application. 