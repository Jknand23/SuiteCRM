# clean.php Documentation

/**
 * @fileoverview Backward compatibility file providing class mappings for deprecated HTML purification classes
 * @package SuiteCRM
 * @copyright SugarCRM Inc. (2004-2013), SalesAgility Ltd. (2011-2018)
 * @license AGPL-3.0
 */

## Overview

The `clean.php` file serves as a backward compatibility layer for HTML purification and sanitization classes that have been moved to the SuiteCRM namespace. This file maintains the old class names while mapping them to their new namespaced equivalents, allowing existing code to continue functioning without modification.

## Class Mappings

### HTMLPurifier_URIScheme_cid
- **Legacy Name**: `HTMLPurifier_URIScheme_cid`
- **New Location**: `\SuiteCRM\HTMLPurifierURISchemeCid`
- **Purpose**: Handles CID (Content-ID) URI schemes in HTML purification
- **Usage**: Used internally by HTMLPurifier for email content processing

### HTMLPurifier_Filter_Xmp
- **Legacy Name**: `HTMLPurifier_Filter_Xmp`
- **New Location**: `\SuiteCRM\HTMLPurifierFilterXmp`
- **Purpose**: Filters XMP (eXtensible Metadata Platform) tags during HTML purification
- **Usage**: Part of the HTML sanitization process

### SugarCleaner
- **Legacy Name**: `SugarCleaner`
- **New Location**: `\SuiteCRM\HtmlSanitizer`
- **Purpose**: Main HTML sanitization utility class
- **Usage**: Primary interface for cleaning and sanitizing HTML content

### SugarURIFilter
- **Legacy Name**: `SugarURIFilter`
- **New Location**: `\SuiteCRM\URIFilter`
- **Purpose**: Filters and validates URI schemes
- **Usage**: Used to prevent malicious URI schemes in user input

## Integration Points

### Internal API Calls
- Integrates with HTMLPurifier library for comprehensive HTML sanitization
- Used by form processing and content display functions
- Connected to the SuiteCRM autoloader for class resolution

### UI Functionality
- Ensures safe display of user-generated content
- Prevents XSS attacks through content sanitization
- Maintains backward compatibility for existing code

## Security Considerations

This file is part of SuiteCRM's security infrastructure, providing essential HTML sanitization capabilities. The backward compatibility mappings ensure that security features remain active even when using legacy class names.

## Migration Notes

- New code should use the SuiteCRM namespaced classes directly
- Existing code can continue using legacy class names
- The `#[\AllowDynamicProperties]` attribute maintains PHP 8.2+ compatibility
- All classes maintain the same interface and functionality as their predecessors 