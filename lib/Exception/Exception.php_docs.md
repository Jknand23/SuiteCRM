# Exception.php Documentation

## @fileoverview
Base exception class for the SuiteCRM system that extends PHP's standard Exception class. Provides standardized exception handling with integrated logging capabilities and consistent error code management throughout the application.

## @package
SuiteCRM\Exception

## @copyright
Copyright (C) 2011 - 2018 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Class Overview

### Exception
Foundation exception class that serves as the base for all custom SuiteCRM exceptions. Integrates with the PSR-3 logging standard and the centralized ExceptionCode enumeration system to provide consistent error handling across the application.

## Dependencies

### External Libraries
- **Psr\Log\LogLevel**: PSR-3 logging standard for consistent log level definitions
- **SuiteCRM\Enumerator\ExceptionCode**: Centralized error code definitions

### Inheritance
- Extends PHP's native `\Exception` class
- Maintains compatibility with standard exception handling patterns

## Methods

### __construct($message = '', $code = ExceptionCode::APPLICATION_UNHANDLED_BEHAVIOUR, $previous = null)
**Purpose**: Constructs a new SuiteCRM exception instance with standardized message formatting.

**Parameters**:
- `$message` (string): Human-readable exception description
- `$code` (int): Error code from ExceptionCode enumeration, defaults to APPLICATION_UNHANDLED_BEHAVIOUR
- `$previous` (Exception|null): Previous exception for exception chaining

**Behavior**:
- Prepends "[SuiteCRM] " prefix to all exception messages for system identification
- Uses standardized error codes from ExceptionCode enumeration
- Supports exception chaining for debugging complex error scenarios

### getDetail()
**Purpose**: Provides additional contextual information about the exception cause.

**Returns**: String with detailed exception description for debugging and logging purposes.

**Default Behavior**: Returns generic message indicating an unhandled exception occurred in SuiteCRM.

### getLogLevel()
**Purpose**: Determines the appropriate PSR-3 log level for the exception.

**Returns**: String constant from PSR-3 LogLevel class (defaults to LogLevel::CRITICAL).

**Usage**: Integrates with SuiteCRM's logging system to ensure proper log categorization.

## Error Handling Features

### Standardized Message Format
- All exceptions include "[SuiteCRM] " prefix for system identification
- Consistent message structure across all exception types
- Maintains exception message integrity for debugging

### Integration with Error Codes
- Default error code: `ExceptionCode::APPLICATION_UNHANDLED_BEHAVIOUR` (6000)
- Supports all error codes defined in ExceptionCode enumeration
- Enables consistent error categorization and handling

### PSR-3 Logging Integration
- Implements standardized logging levels
- Supports integration with modern logging frameworks
- Critical level default ensures important exceptions are properly logged

## Implementation Patterns

### Exception Inheritance
This base class is designed to be extended by specific exception types:
- API exceptions for web service errors
- Security exceptions for access control
- Data validation exceptions for input processing
- Module-specific exceptions for business logic

### Exception Chaining
- Supports exception chaining through `$previous` parameter
- Enables comprehensive error debugging and tracing
- Maintains full exception context for complex error scenarios

## Integration Points

### Logging System
- Integrates with SuiteCRM's central logging infrastructure
- Supports PSR-3 compatible logging handlers
- Enables automated error tracking and monitoring

### Error Code Management
- References centralized ExceptionCode enumeration
- Ensures consistent error identification across system components
- Supports automated error handling and recovery workflows

### Module Integration
- Used throughout SuiteCRM modules for consistent error handling
- Supports module-specific error customization through inheritance
- Enables system-wide exception handling policies

## Best Practices

### Exception Usage
- Always use specific error codes from ExceptionCode enumeration
- Provide meaningful exception messages for debugging
- Utilize exception chaining for complex error scenarios

### Inheritance Guidelines
- Extend this class for domain-specific exceptions
- Override `getDetail()` for specialized error context
- Customize `getLogLevel()` based on exception severity

### Error Context
- Include relevant system state information in messages
- Use appropriate error codes for different failure scenarios
- Maintain exception message clarity for both developers and logs 