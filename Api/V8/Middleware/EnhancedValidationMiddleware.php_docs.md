# EnhancedValidationMiddleware.php Documentation

/**
 * @fileoverview Enhanced Input Validation Middleware for SuiteCRM V8 API providing
 * comprehensive input sanitization, type validation, and security checks that extend
 * the existing parameter validation system without breaking existing functionality.
 * @package SuiteCRM.Api.V8.Middleware
 * @copyright SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `EnhancedValidationMiddleware.php` file, located in `Api/V8/Middleware/`, defines the `EnhancedValidationMiddleware` class. This middleware provides comprehensive input validation, sanitization, and security checks that enhance the existing parameter validation system without replacement. It builds upon the current ParamsMiddleware patterns while adding enhanced validation capabilities without breaking existing functionality.

### Key Responsibilities
- Extended input validation beyond basic parameter checking
- Comprehensive input sanitization and type validation
- Security-focused validation rules (XSS, SQL injection prevention)
- Integration with existing parameter validation system
- Enhanced error reporting with detailed validation feedback
- Request size and complexity limits for security

## Database Operations

### No Direct Database Operations
This middleware does not perform direct database operations but leverages existing infrastructure:
- **Configuration Access**: Reads validation settings from global `$sugar_config` array
- **Session Storage**: Uses existing session management without modifications
- **Logging**: Uses existing SuiteCRM logging infrastructure for security events

## Internal API Calls

### Configuration Integration
- **Global Config Access**: Reads enhanced validation configuration from `$sugar_config['enhanced_validation']`
- **Dynamic Configuration**: Supports environment-specific validation rules and limits
- **Secure Defaults**: Provides sensible security-focused defaults when configuration is not present

### Middleware Pipeline Integration
- **Slim 3 Compatibility**: Integrates seamlessly with existing middleware pipeline
- **Request Processing**: Processes all incoming API requests for validation
- **Request Enhancement**: Adds validation metadata to request attributes for downstream processing
- **Bypass Mechanisms**: Provides exempt paths for critical endpoints that have their own validation

## Enhanced Validation Methods

### Configuration and Initialization
```php
public function __construct()
```
- **Configuration Loading**: Loads validation settings with secure defaults
- **Security Rules**: Comprehensive validation rules for XSS and SQL injection prevention
- **Flexible Limits**: Configurable request size, parameter count, and complexity limits
- **Exempt Paths**: Configurable paths that bypass enhanced validation
- **Logging Setup**: Initializes comprehensive validation monitoring

### Request Processing
```php
public function __invoke(Request $request, Response $response, callable $next): Response
```
- **Path Exemption**: Checks if request path is exempt from enhanced validation
- **Comprehensive Validation**: Validates request size, parameter count, and content
- **Sanitization**: Sanitizes all input data for security
- **Request Enhancement**: Adds validation metadata to request for downstream use

### Validation Engine
```php
private function validateRequest(Request $request): array
```
- **Request Size Limits**: Validates against maximum request size thresholds
- **Parameter Count**: Checks parameter count against security limits
- **Content Validation**: Validates and sanitizes each parameter recursively
- **Security Monitoring**: Logs security violations for monitoring and analysis

### Parameter Validation System
```php
private function validateParameter(string $key, $value): array
```
- **Type-Specific Validation**: Different validation rules for strings, arrays, and numbers
- **Recursive Processing**: Handles nested arrays and complex data structures
- **Sanitization**: Comprehensive input sanitization for each data type
- **Security Checks**: Pattern matching for malicious content detection

### String Validation and Security
```php
private function validateStringParameter(string $key, string $value): array
```
- **Length Limits**: Enforces maximum string length limits
- **Pattern Detection**: Detects XSS, script injection, and other malicious patterns
- **SQL Injection Prevention**: Pattern matching for SQL injection attempts
- **Content Sanitization**: Removes null bytes, dangerous HTML, and encodes special characters

### Array Validation
```php
private function validateArrayParameter(string $key, array $value, int $depth = 1): array
```
- **Depth Limits**: Prevents deeply nested array attacks
- **Size Limits**: Enforces maximum array item count
- **Recursive Validation**: Validates all array items recursively
- **Memory Protection**: Prevents memory exhaustion from oversized arrays

### Numeric Validation
```php
private function validateNumericParameter(string $key, $value): array
```
- **Type Validation**: Ensures numeric values are properly formatted
- **Range Checking**: Validates numeric ranges and formats
- **Sanitization**: Proper numeric type casting and validation

## Security Features

### Input Sanitization
- **Null Byte Removal**: Removes null bytes that could bypass security filters
- **HTML Sanitization**: Strips dangerous HTML while preserving safe tags
- **Special Character Encoding**: Encodes special characters to prevent injection
- **Whitespace Normalization**: Trims and normalizes whitespace

### Security Pattern Detection
- **XSS Prevention**: Detects script tags, JavaScript URLs, and event handlers
- **SQL Injection Detection**: Pattern matching for common SQL injection techniques
- **CSS Expression Detection**: Prevents CSS expression attacks
- **VBScript Detection**: Blocks VBScript execution attempts

### Request Limits and Protection
- **Request Size Limits**: Maximum 10MB request size by default
- **Parameter Count Limits**: Maximum 500 parameters by default
- **String Length Limits**: Maximum 10,000 character strings by default
- **Array Depth Limits**: Maximum 10 levels of nesting by default
- **Array Size Limits**: Maximum 1,000 items per array by default

## Error Handling

### Validation Error Response
```json
{
    "errors": [{
        "status": "400",
        "title": "Input Validation Failed",
        "detail": "The request contains invalid or potentially malicious input data.",
        "meta": {
            "validation_errors": ["Parameter 'email' contains forbidden content"],
            "security_violations": ["forbidden_content"],
            "timestamp": "2024-01-15T10:30:00Z",
            "request_id": "req_65a4b8f0c1234_abc123"
        }
    }]
}
```

### Security Violation Logging
- **Comprehensive Logging**: All security violations are logged with context
- **Request Tracking**: IP address, user agent, and user context included
- **Pattern Analysis**: Detailed logging for security pattern analysis
- **Monitoring Integration**: Structured logging for security monitoring systems

### Error Types and Handling
- **Validation Errors**: Input format and content validation failures
- **Security Violations**: Detected malicious content or patterns
- **Size Violations**: Request or parameter size limit exceeded
- **Complexity Violations**: Array depth or complexity limits exceeded

## Configuration Options

### Basic Configuration
```php
$sugar_config['enhanced_validation'] = [
    'enabled' => true,
    'exempt_paths' => ['/access_token', '/V8/docs']
];
```

### Advanced Configuration
```php
$sugar_config['enhanced_validation'] = [
    'enabled' => true,
    'rules' => [
        'max_string_length' => 5000,
        'max_array_depth' => 5,
        'max_array_items' => 500,
        'allowed_html_tags' => '<p><br><strong><em>'
    ],
    'limits' => [
        'max_request_size' => 5242880, // 5MB
        'max_request_parameters' => 250
    ],
    'exempt_paths' => ['/custom/endpoint']
];
```

### Security Configuration
```php
$sugar_config['enhanced_validation'] = [
    'enabled' => true,
    'rules' => [
        'forbidden_patterns' => [
            '/custom-pattern/i'
        ],
        'sql_injection_patterns' => [
            '/custom-sql-pattern/i'
        ]
    ]
];
```

## Performance Considerations

### Validation Performance
- **Efficient Pattern Matching**: Optimized regex patterns for performance
- **Early Termination**: Stops validation on first security violation
- **Memory Management**: Prevents memory exhaustion from oversized inputs
- **Request Streaming**: Handles large requests efficiently

### Configuration Caching
- **Static Configuration**: Configuration loaded once per request
- **Pattern Compilation**: Regex patterns compiled efficiently
- **Default Optimization**: Sensible defaults minimize configuration overhead
- **Exempt Path Optimization**: Fast path checking for exempt endpoints

## Integration Notes

### Middleware Pipeline Integration
- **Order Dependent**: Should be placed after rate limiting but before authentication
- **Request Enhancement**: Adds validation metadata to request attributes
- **Compatibility**: Fully compatible with existing parameter validation
- **Bypass Support**: Supports selective bypass for specific endpoints

### Existing Parameter System Integration
- **Non-Destructive**: Does not modify existing parameter validation
- **Additive Enhancement**: Adds additional validation layer
- **Metadata Preservation**: Preserves existing parameter processing
- **Error Compatibility**: Uses consistent error response format

### Logging Integration
- **Existing Infrastructure**: Uses existing SuiteCRM logging system
- **Structured Logging**: Provides structured data for analysis
- **Security Focus**: Emphasizes security event logging
- **Performance Tracking**: Logs validation performance metrics

## Security Considerations

### Defense in Depth
- **Multiple Validation Layers**: Works alongside existing validation
- **Pattern-Based Detection**: Multiple pattern matching approaches
- **Content Sanitization**: Comprehensive input sanitization
- **Size Limiting**: Prevents resource exhaustion attacks

### Attack Vector Protection
- **XSS Prevention**: Comprehensive cross-site scripting protection
- **SQL Injection**: Pattern-based SQL injection detection
- **DoS Protection**: Request size and complexity limits
- **Data Exfiltration**: Prevents oversized response generation

### Privacy and Data Protection
- **Sensitive Data Filtering**: Removes sensitive patterns from logs
- **Context Limitation**: Logs only necessary context for security
- **User Privacy**: Respects user privacy in security logging
- **Compliance**: Supports compliance with data protection regulations 