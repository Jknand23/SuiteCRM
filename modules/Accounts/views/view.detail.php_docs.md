# Accounts Detail View Controller Documentation

## File Overview
**File**: `modules/Accounts/views/view.detail.php`
**Type**: MVC View Controller
**Purpose**: Handles the display and functionality of the Account detail view with custom address push functionality

## Description
This class extends the base `ViewDetail` class to provide Account-specific functionality for the detail view. It includes specialized features for pushing account address information to related contacts and integrates with PDF template generation.

## Class Structure

### Class Definition
```php
class AccountsViewDetail extends ViewDetail
```

### Dependencies
- `modules/AOS_PDF_Templates/formLetter.php` - PDF template integration
- Base `ViewDetail` class functionality
- ACL permission checking system

## Database Operations

### Record Validation
- **Purpose**: Ensures valid account record exists before display
- **Method**: Checks `$this->bean->id` for existence
- **Error Handling**: Dies with error message if no valid record found
- **Security**: Prevents unauthorized access to invalid records

## Internal API Integration

### Base View Extension
```php
public function display()
```
- **Inheritance**: Calls parent functionality while adding custom features
- **Processing**: Executes `$this->dv->process()` for standard detail view processing
- **Output**: Renders view using `$this->dv->display()`

### ACL Integration
```php
ACLController::checkAccess('Contacts', 'edit', true)
```
- **Permission Check**: Validates user can edit contacts before showing push functionality
- **Conditional Display**: Only shows address push buttons if user has appropriate permissions
- **Security**: Prevents unauthorized contact modification attempts

### Smarty Template Integration
- **Template Variables**: Assigns custom code for billing and shipping address push functionality
- **Variable Names**: `custom_code_billing` and `custom_code_shipping`
- **Integration Point**: Works with SugarFields address templates

## External API Integration

### PDF Template System
```php
formLetter::DVPopupHtml('Accounts')
```
- **Service**: AOS_PDF_Templates module integration
- **Context**: Detail view specific template functionality
- **Module**: Scoped to Accounts module for relevant templates

### Contact Integration
```php
open_contact_popup("Contacts", 600, 600, "&account_name=' . $this->bean->name . '&html=change_address..."
```
- **Cross-module**: Opens Contacts module popup for address updates
- **Data Transfer**: Passes account name and address information
- **Popup Dimensions**: 600x600 pixel modal window

## UI Functionality

### Address Push Feature
The `generatePushCode()` method creates JavaScript functionality for:

#### Supported Address Types
- **Billing Address**: Generated via `generatePushCode('billing')`
- **Shipping Address**: Generated via `generatePushCode('shipping')`

#### Address Field Mapping
```php
$address_fields = array('street', 'city', 'state', 'postalcode', 'country');
```
- **Complete Address**: Maps all standard address components
- **Field Prefixing**: Handles billing_address_* and shipping_address_* field naming
- **URL Encoding**: Properly encodes address data for URL transmission

#### Button Generation
- **HTML Output**: Creates clickable button with proper styling
- **JavaScript Integration**: Embedded onclick handler for popup functionality
- **Internationalization**: Uses module strings for button labels and titles

### Data Sanitization
```php
str_replace(array("\rn", "\r", "\n"), array('','','<br>'), urlencode($this->bean->$field_name))
```
- **Line Break Handling**: Converts various line break formats to HTML breaks
- **URL Safety**: Proper URL encoding for address data transmission
- **XSS Prevention**: Sanitizes user input before including in JavaScript

## Security Considerations

### Permission Validation
- **Contact Edit Rights**: Verifies user can edit contacts before showing push functionality
- **Graceful Degradation**: Hides functionality if permissions insufficient
- **Module Security**: Respects both Accounts and Contacts module permissions

### Input Validation
- **Record Existence**: Validates account record exists before processing
- **Data Sanitization**: Properly escapes and encodes data for JavaScript inclusion
- **Error Handling**: Proper error messages for invalid states

### Cross-Module Security
- **Contact Access**: Validates access to Contacts module before integration
- **Data Transfer**: Secure transfer of address data between modules
- **Session Handling**: Proper handling of cross-module data transfer

## Integration Points

### PDF Generation System
- **Template Access**: Provides access to account-specific PDF templates
- **Context Awareness**: Templates understand they're being used from detail view
- **Module Scope**: Limits templates to those relevant for Accounts

### Contact Management System
- **Address Synchronization**: Enables pushing account addresses to related contacts
- **Relationship Respect**: Works within existing account-contact relationships
- **Data Consistency**: Ensures address data remains consistent across modules

### Template System Integration
- **SugarFields**: Integrates with address field templates
- **Locale Support**: Supports locale-specific template variations (e.g., en_us.DetailView.tpl)
- **Customization**: Allows for custom address display templates

## Performance Considerations

### Conditional Loading
- **Permission-based**: Only generates push functionality if user has required permissions
- **Lazy Generation**: Address push code generated only when needed
- **Template Efficiency**: Leverages Smarty template caching

### JavaScript Optimization
- **Inline Generation**: Generates JavaScript inline for immediate availability
- **Minimal Footprint**: Generates only necessary JavaScript code
- **Event Handling**: Efficient onclick event handling

This view.detail.php file provides the Account detail view with enhanced functionality for address management and PDF generation while maintaining security and performance standards. 