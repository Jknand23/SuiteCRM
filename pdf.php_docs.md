/**
 * @fileoverview PDF generation entry point for SuiteCRM that handles secure PDF document creation from CRM data. This file validates user permissions, processes module records, and coordinates with PDF generation utilities to create formatted PDF documents for reports, invoices, and other business documents.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# SuiteCRM PDF Generation Handler

## Overview

The `pdf.php` file serves as the secure entry point for PDF document generation within SuiteCRM. It validates user permissions, processes record data, and coordinates with PDF generation utilities to create formatted business documents including reports, invoices, quotes, and other CRM-related documents.

## Database Operations

### Record Retrieval
- **Bean Factory**: Uses `$beanList` and `$beanFiles` for module validation and instantiation
- **Record Loading**: Retrieves specific records based on module and record ID
- **Data Validation**: Validates record existence and user access permissions
- **Related Data**: Handles related record data for comprehensive PDF generation

### Module Validation
- **Module Verification**: Validates module existence in system module registry
- **Bean File Check**: Verifies corresponding bean file availability
- **Security Check**: Ensures module supports PDF generation functionality
- **Access Control**: Validates user permissions for PDF generation

### User Context
- **Current User**: Maintains user context for permission validation
- **Session Validation**: Ensures valid user session for PDF operations
- **Permission Check**: Validates user access to specific records and modules
- **Audit Logging**: Logs PDF generation activities for audit purposes

## Internal API Calls

### Security Entry Point
- **Validation**: `if (!defined('sugarEntry') || !sugarEntry)` prevents direct access
- **Die Statement**: `die('Not A Valid Entry Point')` for unauthorized access
- **Parameter Validation**: Validates required parameters (module, action, record)
- **Input Sanitization**: `clean_string()` for secure parameter handling

### Global System Integration
- **Bean List**: `global $beanList` for module-to-bean mapping
- **Bean Files**: `global $beanFiles` for bean file location mapping
- **Locale**: `global $locale` for localization and formatting
- **Configuration**: Access to system configuration for PDF settings

### Parameter Processing
- **Module Parameter**: `$_REQUEST['module']` for target module specification
- **Action Parameter**: `$_REQUEST['action']` for PDF action specification
- **Record Parameter**: `$_REQUEST['record']` for specific record identification
- **Clean Processing**: `clean_string()` for all user input parameters

## External API Calls

### HTTP Response Management
- **Content-Type**: Sets `application/pdf` MIME type for PDF responses
- **Content-Disposition**: Controls PDF download vs inline display behavior
- **Cache Control**: Manages browser caching for PDF documents
- **Content-Length**: Sets appropriate content length for PDF files

### PDF Generation Libraries
- **TCPDF Integration**: Uses TCPDF library for PDF document creation
- **Font Management**: Handles font embedding and character encoding
- **Image Processing**: Processes and embeds images in PDF documents
- **Template Processing**: Processes PDF templates with dynamic data

### File System Operations
- **Temporary Files**: Creates temporary PDF files during generation
- **Template Files**: Reads PDF template files from file system
- **Asset Files**: Accesses image and font assets for PDF generation
- **Cleanup**: Manages cleanup of temporary files after PDF generation

## UI Functionality

### PDF Generation Interface
- **Module Selection**: Processes module parameter for PDF context
- **Record Selection**: Handles specific record identification for PDF content
- **Action Processing**: Manages different PDF generation actions
- **Format Options**: Supports various PDF formatting options

### Template Integration
- **Template Selection**: Chooses appropriate PDF template based on module and action
- **Dynamic Content**: Populates templates with dynamic record data
- **Layout Control**: Manages PDF layout and formatting
- **Custom Fields**: Handles custom field data in PDF generation

### Download Control
- **Inline Display**: Supports inline PDF display in browser
- **Download Mode**: Enables PDF file download
- **File Naming**: Generates appropriate file names for PDF documents
- **Multiple Formats**: Supports various PDF output formats

### Error Handling
- **Parameter Errors**: Handles missing or invalid parameters
- **Permission Errors**: Manages access denial scenarios
- **Generation Errors**: Handles PDF generation failures
- **Template Errors**: Manages template processing errors

## PDF Generation Architecture

### Generation Process
1. **Parameter Validation**: Validate module, action, and record parameters
2. **Security Check**: Verify user permissions and access rights
3. **Record Loading**: Load and validate target record data
4. **Template Selection**: Choose appropriate PDF template
5. **Data Processing**: Process and format record data for PDF
6. **PDF Generation**: Generate PDF document with processed data
7. **Response Delivery**: Deliver PDF to user with appropriate headers

### Template System
- **Template Selection**: Dynamic template selection based on module and context
- **Template Processing**: Processes templates with record data
- **Layout Management**: Manages PDF page layout and formatting
- **Custom Templates**: Supports custom PDF template creation

### Data Processing
- **Field Formatting**: Formats field data for PDF display
- **Relationship Data**: Includes related record information
- **Localization**: Applies user locale settings to PDF content
- **Currency Formatting**: Handles currency and numeric formatting

## Security Features

### Access Control
- **Entry Point Validation**: Requires proper application entry point
- **User Authentication**: Validates user session and authentication
- **Record Permissions**: Checks user access to specific records
- **Module Permissions**: Validates module-level access rights

### Input Validation
- **Parameter Cleaning**: Sanitizes all input parameters
- **Module Validation**: Validates module existence and availability
- **Record Validation**: Ensures record existence and access
- **Action Validation**: Validates PDF generation action

### Output Security
- **Content Headers**: Sets secure content headers for PDF delivery
- **File Protection**: Protects generated PDF files from unauthorized access
- **Temporary Cleanup**: Ensures cleanup of temporary files
- **Error Containment**: Prevents information disclosure through errors

## PDF Features

### Document Types
- **Reports**: Generates formatted business reports
- **Invoices**: Creates professional invoice documents
- **Quotes**: Generates sales quote documents
- **Letters**: Creates formatted business correspondence
- **Labels**: Generates mailing and product labels

### Formatting Options
- **Layout Control**: Manages page layout and orientation
- **Font Management**: Handles font selection and embedding
- **Image Integration**: Embeds logos and images in documents
- **Table Formatting**: Creates formatted data tables
- **Header/Footer**: Manages document headers and footers

### Customization
- **Template Customization**: Supports custom template creation
- **Field Selection**: Allows custom field inclusion/exclusion
- **Branding**: Supports company branding and logos
- **Styling**: Enables custom styling and formatting
- **Localization**: Supports multi-language PDF generation

## Performance Considerations

### Generation Optimization
- **Memory Management**: Optimizes memory usage during PDF generation
- **Template Caching**: Caches frequently used templates
- **Image Optimization**: Optimizes images for PDF inclusion
- **Font Caching**: Caches font information for performance

### Large Document Handling
- **Streaming Output**: Supports streaming for large PDF documents
- **Progressive Generation**: Generates large documents progressively
- **Memory Limits**: Respects system memory limits during generation
- **Resource Cleanup**: Ensures proper cleanup of generation resources

## Integration Points

### Module System
- **Module Integration**: Integrates with all SuiteCRM modules
- **Custom Modules**: Supports custom module PDF generation
- **Field Integration**: Handles all field types in PDF generation
- **Relationship Integration**: Includes related data in PDF documents

### Template System
- **Template Engine**: Integrates with SuiteCRM template system
- **Custom Templates**: Supports custom template development
- **Template Management**: Manages template storage and retrieval
- **Template Versioning**: Supports template version control

### Reporting System
- **Report Integration**: Integrates with SuiteCRM reporting system
- **Chart Integration**: Includes charts and graphs in PDF reports
- **Data Visualization**: Supports data visualization in PDF format
- **Export Integration**: Provides PDF export for various data types

## Error Recovery

### Generation Error Handling
- **Template Errors**: Handles template processing failures
- **Data Errors**: Manages data formatting and processing errors
- **Resource Errors**: Handles resource availability errors
- **System Errors**: Manages system-level error conditions

### User Experience
- **Error Messages**: Provides clear error messages for users
- **Fallback Options**: Offers alternative options when PDF generation fails
- **Retry Mechanisms**: Provides retry options for transient failures
- **Support Information**: Includes support information for complex errors 