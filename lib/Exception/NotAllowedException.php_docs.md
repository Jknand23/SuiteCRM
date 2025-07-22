# NotAllowedException.php Documentation

## @fileoverview
Specialized exception class for handling operation restriction violations within SuiteCRM. Extends the base Exception class to provide specific handling for actions that are not permitted under current system or user context.

## @package
SuiteCRM\Exception

## @copyright
Copyright (C) 2011 - 2019 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Class Overview

### NotAllowedException
Operation restriction exception class that handles scenarios where requested actions are not permitted due to system policies, user permissions, or operational constraints within SuiteCRM.

## Dependencies

### Internal Dependencies
- **SuiteCRM\Exception\Exception**: Base exception class with standardized error handling
- **SuiteCRM\Enumerator\ExceptionCode**: Uses API_CONTENT_NEGOTIATION_FAILED error code

## Methods

### __construct($message = '', $code = ExceptionCode::API_CONTENT_NEGOTIATION_FAILED, $previous = null)
**Purpose**: Constructs a not allowed exception with specialized message formatting for operation restrictions.

**Parameters**:
- `$message` (string): Description of the restricted operation
- `$code` (int): Error code from ExceptionCode, defaults to API_CONTENT_NEGOTIATION_FAILED (8005)
- `$previous` (Exception|null): Previous exception for exception chaining

**Behavior**:
- Prepends "[Not Allowed] " prefix for clear identification
- Inherits "[SuiteCRM] " prefix from parent class
- Defaults to API content negotiation error code

## Restriction Categories

### Permission-Based Restrictions
- User role limitations preventing specific actions
- Module-level access restrictions
- Record-level permission violations

### System Policy Restrictions
- Administrative policy enforcement
- Business rule violations
- Configuration-based operation limits

### API Operation Restrictions
- Unsupported API methods
- Content type negotiation failures
- Request format restrictions

## Use Cases

### User Permission Violations
- Actions beyond user's role capabilities
- Restricted module access attempts
- Unauthorized data modification requests

### System Configuration Limits
- Operations disabled by system configuration
- Maintenance mode restrictions
- Feature availability limitations

### API Content Negotiation
- Unsupported request content types
- Invalid response format requests
- Protocol version incompatibilities

## Integration Points

### Permission System
- Works with SuiteCRM's role-based access control
- Integrates with permission checking mechanisms
- Supports fine-grained access control

### API Framework
- Handles API content negotiation failures
- Supports RESTful API restriction enforcement
- Enables consistent API error responses

### Policy Enforcement
- Supports business rule enforcement
- Integrates with administrative policy systems
- Enables compliance requirement implementation

## Error Response Features

### Clear Operation Context
- Descriptive "[Not Allowed] " prefix for immediate identification
- Maintains context about restricted operations
- Supports user-friendly error messaging

### API Integration
- Default error code supports API response generation
- Consistent error format for API consumers
- Enables automated error handling by client applications

### System Logging
- Tracks attempted unauthorized operations
- Supports audit trail requirements
- Enables security monitoring and analysis 