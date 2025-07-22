# vCard.php Documentation

/**
 * @fileoverview vCard implementation for importing and exporting contact information in standard vCard format
 * @package SuiteCRM
 * @copyright SugarCRM Inc. (2004-2013), SalesAgility Ltd. (2011-2018)
 * @license AGPL-3.0
 */

## Overview

The `vCard` class provides comprehensive vCard (RFC 2426) functionality for importing and exporting contact information. It handles the creation, manipulation, and storage of vCard files, enabling SuiteCRM to interoperate with external contact management systems and email clients.

## Class Definition

### vCard
- **Type**: Contact data interchange utility
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility
- **API Status**: Public API (`@api` annotation)

## Properties

### $properties
- **Type**: `array`
- **Visibility**: `protected`
- **Purpose**: Stores vCard properties and their values

### $name
- **Type**: `string`
- **Visibility**: `protected`
- **Purpose**: Filename for vCard export (default: 'no_name')

## Database Operations

### Contact Loading
- **Method**: `loadContact()` - Retrieves contact data from SuiteCRM modules
- **Integration**: Uses `BeanFactory` and global `$beanFiles`, `$beanList` arrays
- **Modules**: Supports Contacts and other contact-type modules
- **Encoding**: Handles special characters with quoted-printable encoding

## Internal API Calls

### SuiteCRM Framework Integration
- **BeanFactory**: Module bean instantiation and data retrieval
- **Localization**: Character set handling and translation
- **TimeDate**: HTTP timestamp generation for headers
- **Global Arrays**: `$app_list_strings` for field value mapping

### PHP Functions
- **String Processing**: `from_html()`, `str_replace()`, `strtr()`
- **File Operations**: `file()` for vCard import
- **Header Management**: HTTP headers for file download

## Methods

### Core Functionality

#### clear()
```php
public function clear()
```
**Purpose**: Resets vCard properties array to empty state

#### loadContact()
```php
public function loadContact($contactid, $module = 'Contacts')
```
**Purpose**: Loads contact data from SuiteCRM module into vCard format

**Parameters**:
- `$contactid` (string): ID of contact record to load
- `$module` (string): Module name (default: 'Contacts')

**Data Mapping**:
- Personal: Name, title, birthday, organization, department
- Communication: Phone numbers (work, home, mobile, fax), email
- Address: Street, city, state, postal code, country with encoding support

#### setProperty() / getProperty()
```php
public function setProperty($name, $value)
public function getProperty($name)
```
**Purpose**: Generic property management for vCard fields

### Contact Information Methods

#### setName()
```php
public function setName($first_name, $last_name, $prefix)
```
**Purpose**: Sets contact name fields (N and FN properties)
- Generates filename from first and last name
- Creates formatted display name with prefix

#### setEmail()
```php
public function setEmail($address)
```
**Purpose**: Sets internet email address (EMAIL;INTERNET property)

#### setPhoneNumber()
```php
public function setPhoneNumber($number, $type)
```
**Purpose**: Sets phone numbers with type classification
- **Types**: HOME, WORK, CELL, FAX
- **Special Handling**: FAX numbers get TEL;WORK;FAX format

#### setAddress()
```php
public function setAddress($address, $city, $state, $postal, $country, $type, $encoding = '')
```
**Purpose**: Sets address information with optional encoding
- **Encoding**: Quoted-printable for addresses with line breaks
- **Format**: ADR property with structured address components

#### setBirthDate()
```php
public function setBirthDate($date)
```
**Purpose**: Sets birthday (BDAY property)

#### setTitle()
```php
public function setTitle($title)
```
**Purpose**: Sets job title (TITLE property)

#### setORG()
```php
public function setORG($org, $dep)
```
**Purpose**: Sets organization and department (ORG property)

### Export/Import Methods

#### toString()
```php
public function toString()
```
**Purpose**: Generates vCard format string from properties
- **Format**: Standard vCard with BEGIN/END markers
- **Charset**: Uses locale export charset
- **Structure**: Property;CHARSET=charset:value format

#### saveVCard()
```php
public function saveVCard()
```
**Purpose**: Outputs vCard file for download
- **Headers**: Content-Disposition, Content-Type, caching directives
- **Charset**: Locale-aware character set handling
- **Compatibility**: IIS server compatibility for Content-Length header

#### importVCard()
```php
public function importVCard($filename, $module = 'Contacts')
```
**Purpose**: Imports vCard file data into SuiteCRM module
- **File Processing**: Reads vCard file line by line
- **Encoding**: Handles character set conversion
- **Bean Creation**: Creates new records with current user assignment

## External API Calls

### File System Operations
- **Function**: `file()` - Reads vCard file for import
- **Headers**: HTTP response headers for file download
- **Output**: Direct print to output buffer

### Character Encoding
- **Locale Integration**: Character set detection and conversion
- **Encoding Types**: UTF-8, quoted-printable, locale-specific charsets
- **BOM Support**: Byte order mark handling

## UI Functionality

### File Download
- Generates appropriate HTTP headers for vCard download
- Sets filename based on contact name
- Handles different browser compatibility requirements
- Manages character encoding for international characters

### Data Import
- Processes uploaded vCard files
- Creates new contact records in specified module
- Assigns imported contacts to current user
- Handles multiple contacts in single vCard file

## Security Considerations

### Input Sanitization
- Uses `from_html()` to sanitize contact data
- Quoted-printable encoding for special characters
- Server software detection for header compatibility
- PHPUnit environment detection for testing

### File Handling
- Validates vCard file format during import
- Proper character encoding to prevent corruption
- Secure file download with appropriate headers

## Integration Points

### Module System
- Works with any SuiteCRM module containing contact-type data
- Uses BeanFactory for consistent data access
- Integrates with global module definitions

### Localization
- Respects user locale settings for character encoding
- Handles international characters in contact data
- Provides charset conversion for export/import

## Usage Examples

### Creating vCard from Contact
```php
$vcard = new vCard();
$vcard->loadContact('contact-id-123', 'Contacts');
$vcard->saveVCard(); // Downloads vCard file
```

### Manual vCard Creation
```php
$vcard = new vCard();
$vcard->setName('John', 'Doe', 'Mr.');
$vcard->setEmail('john.doe@example.com');
$vcard->setPhoneNumber('555-1234', 'WORK');
$vcard->setAddress('123 Main St', 'City', 'State', '12345', 'USA', 'WORK');
$vcard_string = $vcard->toString();
```

### vCard Import
```php
$vcard = new vCard();
$vcard->importVCard('/path/to/contacts.vcf', 'Contacts');
```

## Standards Compliance

- **RFC 2426**: vCard MIME Directory Profile specification
- **Character Encoding**: Multi-charset support with proper conversion
- **File Format**: Standard BEGIN:VCARD / END:VCARD structure
- **Property Format**: Compliant property;parameter:value syntax

## Error Handling

- Graceful handling of missing contact data
- Empty property filtering in vCard generation
- Encoding fallback for unsupported characters
- Server compatibility adjustments for different environments 