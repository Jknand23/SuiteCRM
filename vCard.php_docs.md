/**
 * @fileoverview vCard generation handler for SuiteCRM that creates standard vCard contact files from CRM contact data. This file processes contact information from various modules and generates RFC-compliant vCard files for import into address books and contact management applications.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# SuiteCRM vCard Generator

## Overview

The `vCard.php` file provides vCard generation functionality for SuiteCRM, creating standard contact files that can be imported into address books, email clients, and other contact management applications. It processes contact data from various CRM modules and generates RFC 2426/6350 compliant vCard files.

## Database Operations

### Contact Data Retrieval
- **Contact Loading**: `$vcard->loadContact($_REQUEST['contact_id'], $module)` retrieves contact information
- **Module Support**: Supports contact data from multiple modules including Contacts, Leads, and custom modules
- **Field Mapping**: Maps CRM contact fields to vCard properties
- **Related Data**: Includes related contact information such as addresses and phone numbers

### Module Integration
- **Primary Module**: Default contact handling through Contacts module
- **Module Flexibility**: `clean_string($_REQUEST['module'])` for dynamic module selection
- **Custom Modules**: Supports custom modules with contact-like data structures
- **Data Validation**: Validates contact data before vCard generation

### User Context
- **Session Management**: Maintains user session context for contact access
- **Permission Validation**: Ensures user has access to requested contact data
- **Security Checks**: Validates user permissions for contact export
- **Audit Logging**: Logs vCard generation activities for audit purposes

## Internal API Calls

### Security Entry Point
- **Validation**: `if (!defined('sugarEntry') || !sugarEntry)` prevents direct access
- **Die Statement**: `die('Not A Valid Entry Point')` for unauthorized access
- **Parameter Validation**: Validates contact_id and module parameters
- **Input Sanitization**: `clean_string($_REQUEST['module'])` for secure parameter handling

### vCard Library Integration
- **vCard Class**: `require_once('include/vCard.php')` for vCard functionality
- **Utility Functions**: `require_once('include/utils.php')` for helper functions
- **Class Instantiation**: `new vCard()` creates vCard generator instance
- **File Generation**: `$vcard->saveVCard()` generates and outputs vCard file

### Language and Localization
- **Current Language**: `get_current_language()` for user language detection
- **App Strings**: `return_application_language($current_language)` for localized messages
- **List Strings**: `return_app_list_strings_language($current_language)` for dropdown values
- **Global Context**: Maintains language context for vCard labels

### System Integration
- **Module System**: Integrates with SuiteCRM module framework
- **Contact Framework**: Leverages contact management framework
- **File System**: Uses file system for vCard file generation
- **Output Management**: Manages vCard file output and delivery

## External API Calls

### vCard File Output
- **Content-Type**: Sets `text/vcard` MIME type for vCard files
- **Content-Disposition**: Controls vCard file download behavior
- **File Naming**: Generates appropriate filenames for vCard files
- **Character Encoding**: Ensures proper character encoding for international contacts

### HTTP Response Management
- **Header Setting**: Sets appropriate HTTP headers for vCard delivery
- **Cache Control**: Manages browser caching for vCard files
- **Content-Length**: Sets file size for download progress indication
- **Error Handling**: Provides appropriate error responses for failed generation

### Client Integration
- **Address Books**: Compatible with major address book applications
- **Email Clients**: Supports import into email client contact lists
- **Mobile Devices**: Enables contact import on mobile devices
- **Contact Management**: Integrates with various contact management systems

## UI Functionality

### vCard Generation Interface
- **Contact Selection**: Accepts contact ID for vCard generation
- **Module Selection**: Supports module specification for contact source
- **Format Options**: Provides vCard format configuration options
- **Output Control**: Controls vCard file output and delivery

### Contact Data Processing
- **Field Mapping**: Maps CRM fields to standard vCard properties
- **Data Formatting**: Formats contact data according to vCard specifications
- **Image Handling**: Includes contact photos in vCard files when available
- **Custom Fields**: Handles custom contact fields in vCard generation

### File Management
- **File Generation**: Generates vCard files with proper formatting
- **Download Handling**: Manages vCard file download process
- **Temporary Files**: Manages temporary file creation and cleanup
- **Error Recovery**: Handles file generation errors gracefully

### User Experience
- **Quick Export**: Provides quick contact export functionality
- **Batch Processing**: Supports batch vCard generation for multiple contacts
- **Format Validation**: Validates vCard format compliance
- **Import Compatibility**: Ensures vCard compatibility with major applications

## vCard Architecture

### vCard Standards Compliance
- **RFC 2426**: Complies with vCard 2.1 specification
- **RFC 6350**: Supports vCard 4.0 specification features
- **Standard Properties**: Implements standard vCard properties (FN, N, ORG, etc.)
- **Custom Properties**: Supports custom vCard properties for extended data

### Data Mapping
- **Contact Fields**: Maps CRM contact fields to vCard properties
- **Address Mapping**: Maps address fields to vCard address properties
- **Phone Mapping**: Maps phone numbers to vCard telephone properties
- **Email Mapping**: Maps email addresses to vCard email properties

### File Format
- **Text Format**: Generates vCard files in standard text format
- **Line Folding**: Implements proper line folding for long values
- **Character Encoding**: Uses UTF-8 encoding for international characters
- **Property Formatting**: Formats vCard properties according to specifications

## Contact Data Support

### Supported Fields
- **Name Fields**: Full name, first name, last name, title, suffix
- **Organization**: Company name, department, job title
- **Addresses**: Business and home addresses with full formatting
- **Communication**: Phone numbers, email addresses, websites
- **Personal**: Birthday, notes, categories

### Address Handling
- **Multiple Addresses**: Supports multiple address types (home, work, other)
- **International Format**: Handles international address formats
- **Postal Codes**: Includes postal codes and country information
- **Address Types**: Distinguishes between different address types

### Communication Methods
- **Phone Numbers**: Multiple phone number types (mobile, work, home, fax)
- **Email Addresses**: Primary and secondary email addresses
- **Websites**: Personal and business website URLs
- **Social Media**: Social media profile information

## Performance Considerations

### File Generation Optimization
- **Memory Usage**: Optimizes memory usage during vCard generation
- **Processing Speed**: Fast processing for single contact vCard generation
- **File Size**: Generates compact vCard files
- **Resource Cleanup**: Proper cleanup of generation resources

### Batch Processing
- **Multiple Contacts**: Supports batch generation of multiple vCards
- **Memory Management**: Manages memory for large batch operations
- **Progress Tracking**: Tracks progress for large batch exports
- **Error Handling**: Handles errors in batch processing scenarios

### Caching Strategy
- **Contact Caching**: Caches contact data for repeated vCard generation
- **Template Caching**: Caches vCard templates for performance
- **File Caching**: Caches generated vCard files when appropriate
- **Session Optimization**: Optimizes session data for vCard operations

## Integration Points

### Contact Management
- **Contacts Module**: Primary integration with Contacts module
- **Leads Module**: Integration with Leads for prospect vCard generation
- **Accounts Module**: Integration for account contact information
- **Custom Modules**: Support for custom contact-related modules

### Export System
- **Export Framework**: Integration with SuiteCRM export system
- **Bulk Export**: Support for bulk contact export operations
- **Format Options**: Multiple export format options including vCard
- **Data Filtering**: Filtered export capabilities for specific contact sets

### Third-Party Integration
- **Address Books**: Integration with popular address book applications
- **CRM Systems**: vCard export for migration to other CRM systems
- **Email Marketing**: Contact export for email marketing platforms
- **Mobile Sync**: Contact synchronization with mobile devices

## Security Framework

### Access Control
- **User Authentication**: Requires user authentication for vCard generation
- **Contact Permissions**: Validates user permissions for specific contacts
- **Module Security**: Enforces module-level security restrictions
- **Data Privacy**: Protects sensitive contact information

### Data Protection
- **Input Validation**: Validates all input parameters for security
- **Contact Validation**: Ensures contact exists and is accessible
- **Output Sanitization**: Sanitizes vCard output for security
- **File Security**: Secures generated vCard files

### Privacy Considerations
- **Personal Data**: Handles personal contact data with appropriate privacy controls
- **Consent Management**: Considers consent for contact data export
- **Data Minimization**: Exports only necessary contact information
- **Audit Trail**: Maintains audit trail for contact data exports

## Configuration Management

### vCard Configuration
- **Format Options**: Configurable vCard format options
- **Field Mapping**: Configurable field mapping for vCard generation
- **Custom Properties**: Configuration for custom vCard properties
- **Output Settings**: Configurable output and delivery settings

### Export Settings
- **Default Module**: Default module for vCard generation
- **File Naming**: Configurable vCard file naming conventions
- **Character Encoding**: Configurable character encoding options
- **Batch Settings**: Configuration for batch vCard generation

## Error Handling

### Generation Errors
- **Contact Not Found**: Handles missing contact scenarios
- **Invalid Module**: Manages invalid module specification errors
- **Data Errors**: Handles contact data validation errors
- **File Generation**: Manages vCard file generation failures

### User Experience
- **Error Messages**: User-friendly error messages for generation failures
- **Fallback Options**: Fallback options when vCard generation fails
- **Retry Mechanisms**: Retry mechanisms for transient failures
- **Support Information**: Provides support information for complex errors

## Compatibility

### Application Support
- **Outlook**: Microsoft Outlook contact import compatibility
- **Apple Contacts**: macOS and iOS Contacts app compatibility
- **Google Contacts**: Google Contacts import compatibility
- **Thunderbird**: Mozilla Thunderbird address book compatibility

### Mobile Devices
- **iOS**: iPhone and iPad contact import support
- **Android**: Android device contact import support
- **BlackBerry**: BlackBerry device compatibility (legacy)
- **Windows Mobile**: Windows Mobile device support

### Standards Compliance
- **vCard 2.1**: Full compliance with vCard 2.1 specification
- **vCard 3.0**: Support for vCard 3.0 features
- **vCard 4.0**: Limited support for vCard 4.0 enhancements
- **Cross-Platform**: Cross-platform compatibility for vCard files 