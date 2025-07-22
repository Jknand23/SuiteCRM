# NotFoundException.php Documentation

## @fileoverview
Specialized exception class for handling resource not found errors within SuiteCRM. Extends the base Exception class to provide specific handling for scenarios where requested resources, records, or system components cannot be located.

## @package
SuiteCRM\Exception

## @copyright
Copyright (C) 2011 - 2019 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Class Overview

### NotFoundException
Resource location exception class that handles scenarios where requested entities such as database records, files, modules, or system components cannot be found within SuiteCRM.

## Dependencies

### Internal Dependencies
- **SuiteCRM\Exception\Exception**: Base exception class with standardized error handling
- **SuiteCRM\Enumerator\ExceptionCode**: Uses API_CONTENT_NEGOTIATION_FAILED error code

## Methods

### __construct($message = '', $code = ExceptionCode::API_CONTENT_NEGOTIATION_FAILED, $previous = null)
**Purpose**: Constructs a not found exception with specialized message formatting for missing resource scenarios.

**Parameters**:
- `$message` (string): Description of the missing resource
- `$code` (int): Error code from ExceptionCode, defaults to API_CONTENT_NEGOTIATION_FAILED (8005)
- `$previous` (Exception|null): Previous exception for exception chaining

**Behavior**:
- Prepends "[Not Found] " prefix for clear identification
- Inherits "[SuiteCRM] " prefix from parent class
- Defaults to API content negotiation error code

## Resource Categories

### Database Records
- Missing user accounts, contacts, or leads
- Non-existent relationship records
- Deleted or archived data references

### System Components
- Missing module definitions
- Unavailable view definitions
- Non-existent configuration files

### File System Resources
- Missing template files
- Unavailable custom modules
- Missing uploaded documents

## Use Cases

### Database Operations
- Record lookups by invalid IDs
- References to deleted records
- Queries for non-existent relationships

### API Resource Access
- Requests for non-existent API endpoints
- Invalid resource identifiers in API calls
- References to unavailable data objects

### System File Access
- Missing module template files
- Unavailable language files
- Non-existent custom components

## Integration Points

### Database Layer
- Works with SuiteCRM's ORM and database access systems
- Integrates with record validation mechanisms
- Supports data integrity checking

### API Framework
- Handles API resource not found scenarios
- Supports RESTful API error responses
- Enables consistent 404-style error handling

### Module System
- Integrates with module loading and validation
- Supports dynamic module availability checking
- Enables graceful handling of missing components

## Error Response Features

### Clear Resource Context
- Descriptive "[Not Found] " prefix for immediate identification
- Maintains context about missing resources
- Supports detailed error messaging for debugging

### API Compatibility
- Default error code supports API response generation
- Consistent error format for client applications
- Enables proper HTTP status code mapping

### System Resilience
- Graceful handling of missing resources
- Prevents system crashes from broken references
- Supports fallback mechanisms for missing components

## Best Practices

### Error Context
- Include specific resource identifiers in messages
- Provide helpful suggestions for resolution
- Maintain security by not exposing sensitive system information

### Resource Validation
- Check resource existence before processing
- Implement proper error handling for missing dependencies
- Use appropriate error codes for different resource types 