# AccessDeniedException.php Documentation

## @fileoverview
Specialized exception class for handling access control violations within SuiteCRM. Extends the base Exception class to provide specific handling for authentication and authorization failures.

## @package
SuiteCRM\Exception

## @copyright
Copyright (C) 2011 - 2019 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Class Overview

### AccessDeniedException
Security-focused exception class that handles scenarios where users or processes are denied access to resources, modules, or operations within SuiteCRM. Provides clear identification of access control violations through specialized message formatting.

## Dependencies

### Internal Dependencies
- **SuiteCRM\Exception\Exception**: Base exception class with standardized error handling
- **SuiteCRM\Enumerator\ExceptionCode**: Centralized error code definitions

## Methods

### __construct($message = '', $code = ExceptionCode::APPLICATION_UNHANDLED_BEHAVIOUR, $previous = null)
**Purpose**: Constructs an access denied exception with specialized message formatting for security violations.

**Parameters**:
- `$message` (string): Description of the access violation
- `$code` (int): Error code from ExceptionCode enumeration, defaults to APPLICATION_UNHANDLED_BEHAVIOUR
- `$previous` (Exception|null): Previous exception for exception chaining

**Behavior**:
- Prepends "[AccessDeniedException] " prefix for clear identification
- Inherits "[SuiteCRM] " prefix from parent class
- Uses standardized error code system for consistent handling

## Security Features

### Access Control Integration
- Specifically designed for authentication and authorization failures
- Provides clear security violation identification
- Supports audit trail requirements for access control systems

### Message Format
- Clear "[AccessDeniedException] " prefix for security log filtering
- Maintains security context in exception messages
- Enables automated security monitoring and alerting

## Use Cases

### Authentication Failures
- Invalid user credentials
- Expired authentication tokens
- Disabled or inactive user accounts

### Authorization Violations
- Insufficient role permissions
- Module access restrictions
- Record-level security violations

### API Security
- Invalid API keys or tokens
- Unauthorized API endpoint access
- Rate limiting violations

## Integration Points

### Security System
- Works with SuiteCRM's access control mechanisms
- Integrates with user authentication systems
- Supports role-based permission checking

### Logging and Monitoring
- Enables security event tracking
- Supports compliance audit requirements
- Facilitates security incident response

### Error Handling
- Provides consistent security error responses
- Maintains security context through exception chaining
- Supports automated security response workflows 