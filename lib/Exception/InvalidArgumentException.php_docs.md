# InvalidArgumentException.php Documentation

## @fileoverview
Specialized exception class for handling invalid argument errors within SuiteCRM. Extends the base Exception class to provide specific handling for parameter validation failures and input data errors.

## @package
SuiteCRM\Exception

## @copyright
Copyright (C) 2011 - 2019 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Class Overview

### InvalidArgumentException
Input validation exception class that handles scenarios where functions or methods receive invalid parameters, malformed data, or arguments that don't meet validation criteria within SuiteCRM.

## Dependencies

### Internal Dependencies
- **SuiteCRM\Exception\Exception**: Base exception class with standardized error handling
- **SuiteCRM\Enumerator\ExceptionCode**: Centralized error code definitions

## Methods

### __construct($message = '', $code = ExceptionCode::APPLICATION_UNHANDLED_BEHAVIOUR, $previous = null)
**Purpose**: Constructs an invalid argument exception with specialized message formatting for parameter validation failures.

**Parameters**:
- `$message` (string): Description of the invalid argument or validation failure
- `$code` (int): Error code from ExceptionCode enumeration, defaults to APPLICATION_UNHANDLED_BEHAVIOUR
- `$previous` (Exception|null): Previous exception for exception chaining

**Behavior**:
- Prepends "[InvalidArgumentException] " prefix for clear identification
- Inherits "[SuiteCRM] " prefix from parent class
- Uses standardized error code system for consistent handling

## Validation Features

### Parameter Validation
- Type validation for function parameters
- Range checking for numeric values
- Format validation for strings and data structures

### Data Integrity
- Input sanitization error handling
- Database constraint violation responses
- Business rule validation failures

### API Input Validation
- Request parameter validation
- JSON payload validation
- Query parameter type checking

## Use Cases

### Function Parameter Validation
- Wrong parameter types passed to methods
- Out-of-range numeric values
- Invalid enum values or constants

### Data Format Validation
- Malformed email addresses or URLs
- Invalid date/time formats
- Incorrect data structure formats

### Business Logic Validation
- Values that violate business rules
- Incompatible parameter combinations
- Missing required fields or parameters

## Integration Points

### Validation Framework
- Works with SuiteCRM's input validation systems
- Integrates with form validation mechanisms
- Supports API parameter validation

### Error Response Generation
- Provides detailed validation error information
- Supports user-friendly error messages
- Enables automated error correction suggestions

### Development and Debugging
- Clear identification of validation failures
- Facilitates unit testing of validation logic
- Supports development-time error detection 