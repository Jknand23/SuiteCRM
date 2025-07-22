# My Accounts Dashlet Data Configuration Documentation

## File Overview
**File**: `modules/Accounts/Dashlets/MyAccountsDashlet/MyAccountsDashlet.data.php`
**Type**: Dashlet Configuration Data
**Purpose**: Defines field configurations, search parameters, and display options for the My Accounts dashlet widget

## Description
This configuration file defines the structure and behavior of the My Accounts dashlet by specifying available fields, search criteria, column definitions, and display properties. It serves as the data layer configuration that controls how account information is presented in the dashboard widget.

## Configuration Structure

### Global Data Array
```php
$dashletData['MyAccountsDashlet']['searchFields'] = array(/* search configuration */);
$dashletData['MyAccountsDashlet']['columns'] = array(/* column configuration */);
```

## Search Field Configuration

### Available Search Fields
The dashlet supports filtering by the following criteria:

#### Date-Based Filtering
```php
'date_entered' => array('default' => '')
```
- **Purpose**: Filter accounts by creation date
- **Type**: Date picker field
- **Default**: Empty (no date filter applied)

#### Business Classification Filters
```php
'account_type' => array('default' => '')
'industry' => array('default' => '')
```
- **Account Type**: Filter by business type (Customer, Prospect, etc.)
- **Industry**: Filter by industry classification
- **Default**: Empty (show all types/industries)

#### Geographic Filtering
```php
'billing_address_country' => array('default' => '')
```
- **Purpose**: Filter accounts by billing country
- **Use Case**: Geographic territory management
- **Default**: Empty (global view)

#### User Assignment Filtering
```php
'assigned_user_id' => array(
    'type' => 'assigned_user_name',
    'default' => $current_user->name,
    'label' => 'LBL_ASSIGNED_TO'
)
```
- **Purpose**: Filter accounts by assigned user
- **Default**: Current user's accounts
- **Type**: User selection field with name display
- **Personalization**: Automatically focuses on user's assigned accounts

## Column Configuration

### Default Display Columns
The dashlet shows these fields by default:

#### Primary Account Information
```php
'name' => array(
    'width' => '40%',
    'label' => 'LBL_LIST_ACCOUNT_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name'
)
```
- **Account Name**: Primary identifier (40% width)
- **Clickable**: Links to account detail view
- **Priority**: Always shown by default

#### Business Classification
```php
'account_type' => array(
    'type' => 'enum',
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'default' => true,
    'name' => 'account_type'
)
```
- **Type**: Business type classification
- **Display**: Dropdown/enum values
- **Width**: Compact 10% column

#### Contact Information
```php
'website' => array(
    'width' => '8%',
    'label' => 'LBL_WEBSITE',
    'default' => true,
    'name' => 'website'
)
'phone_office' => array(
    'width' => '15%',
    'label' => 'LBL_LIST_PHONE',
    'default' => true,
    'name' => 'phone_office'
)
```
- **Website**: Company website URL (8% width)
- **Office Phone**: Primary contact number (15% width)

### Optional Display Columns
Additional fields available for user customization:

#### Extended Business Information
- **Annual Revenue**: Financial performance indicator
- **Employee Count**: Organization size metric
- **SIC Code**: Standard Industrial Classification
- **Industry**: Business sector classification
- **Rating**: Account rating/priority
- **Ticker Symbol**: Stock exchange symbol
- **Ownership**: Business ownership type

#### Complete Address Information
- **Billing Address Fields**:
  - Street, City, State, Postal Code, Country
  - Each with 8% width allocation
  - Comprehensive geographic data

- **Shipping Address Fields**:
  - Street, City, State, Postal Code, Country  
  - Mirror structure of billing address
  - Support for different shipping locations

#### Relationship Information
```php
'email1' => array(
    'width' => '8%',
    'label' => 'LBL_EMAIL_ADDRESS_PRIMARY', 
    'name' => 'email1',
    'default' => false
)
'parent_name' => array(
    'width' => '15%',
    'label' => 'LBL_MEMBER_OF',
    'sortable' => false,
    'name' => 'parent_name',
    'default' => false
)
```
- **Primary Email**: Main email contact (requires custom join)
- **Parent Account**: Hierarchical relationship (non-sortable due to join)

#### System Metadata
- **Date Entered**: Record creation timestamp
- **Date Modified**: Last update timestamp  
- **Created By**: User who created the record
- **Assigned User**: Currently responsible user

## UI Functionality

### Column Width Management
- **Responsive Design**: Percentage-based widths adapt to container size
- **Priority Allocation**: Name field gets largest allocation (40%)
- **Balanced Distribution**: Secondary fields get appropriate space allocation
- **Compact Display**: Efficient use of dashboard real estate

### Default vs. Optional Fields
- **Default Fields**: Shown immediately upon dashlet creation
- **Optional Fields**: Available through user customization
- **Flexibility**: Users can add/remove fields based on needs
- **Performance**: Default selection optimized for common use cases

### Field Types and Behavior
- **Links**: Name field provides navigation to detail view
- **Enums**: Account type shows dropdown values
- **Text Fields**: Standard text display for most fields
- **Special Handling**: Email and parent account require custom processing

## Integration Points

### Language System Integration
- **Label References**: All labels reference language file keys
- **Internationalization**: Supports multi-language deployments
- **Consistency**: Uses same labels as main module views

### User Preference Integration
- **Default User**: Assigned user filter defaults to current user
- **Personalization**: Configuration respects user-specific settings
- **Context Awareness**: Adapts to user's working context

### Database Integration
- **Field Mapping**: Direct mapping to database field names
- **Special Fields**: Email1 and parent_name require custom SQL handling
- **Performance**: Configuration optimized for efficient database queries

## Customization Capabilities

### Field Selection
- **Add Fields**: Additional fields can be added to columns array
- **Remove Fields**: Existing fields can be set to default false
- **Reorder Fields**: Array order determines display sequence
- **Width Adjustment**: Column widths can be modified for optimal display

### Search Enhancement
- **Additional Filters**: New search fields can be added
- **Default Values**: Search defaults can be customized
- **Filter Types**: Various field types supported for filtering

### Display Customization
- **Label Override**: Field labels can be customized
- **Width Optimization**: Column widths adjustable for content
- **Link Behavior**: Link properties can be modified
- **Sorting Control**: Sortable property can be toggled per field

This data configuration file provides comprehensive control over the My Accounts dashlet behavior, enabling both default functionality and extensive customization options while maintaining performance and user experience standards. 