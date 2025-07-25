# RequestLoggingMiddleware.php Documentation

/**
 * @fileoverview Request Logging and Monitoring Middleware for SuiteCRM V8 API providing
 * comprehensive request logging, performance monitoring, and security analysis for all
 * API requests while integrating with existing SuiteCRM logging infrastructure.
 * @package SuiteCRM.Api.V8.Middleware
 * @copyright SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `RequestLoggingMiddleware.php` file, located in `Api/V8/Middleware/`, defines the `RequestLoggingMiddleware` class. This middleware provides comprehensive request logging, performance monitoring, and security analysis for all API requests. It integrates with both existing SuiteCRM logging infrastructure and the new Enhanced Logger Service, providing advanced structured logging and performance monitoring capabilities for API performance and security tracking.

### Key Responsibilities
- Comprehensive request and response logging
- Performance monitoring with timing and memory usage
- Security event tracking and anomaly detection
- Integration with existing SuiteCRM logging infrastructure
- Configurable logging levels and filtering
- Request correlation IDs for tracing
- Structured logging for analysis and monitoring

## Database Operations

### No Direct Database Operations
This middleware does not perform direct database operations but leverages existing infrastructure:
- **Configuration Access**: Reads logging settings from global `$sugar_config` array
- **Session Integration**: Works with existing session management for user identification
- **Logging**: Uses existing SuiteCRM logging infrastructure (`$GLOBALS['log']`)

## Internal API Calls

### Configuration Integration
- **Global Config Access**: Reads request logging configuration from `$sugar_config['request_logging']`
- **Dynamic Configuration**: Supports environment-specific logging levels and settings
- **Secure Defaults**: Provides sensible defaults when configuration is not present

### Middleware Pipeline Integration
- **Slim 3 Compatibility**: Integrates seamlessly with existing middleware pipeline
- **Request Processing**: Logs all incoming API requests with comprehensive data
- **Response Enhancement**: Adds logging headers to responses for debugging
- **Performance Tracking**: Monitors and logs performance metrics for all requests

### Session and Authentication Integration
- **User Context**: Integrates with existing authentication and session systems
- **Context Gathering**: Automatically gathers user and request context
- **Privacy Respect**: Respects user privacy and data protection requirements

## Request Logging Methods

### Configuration and Initialization
```php
public function __construct()
```
- **Configuration Loading**: Loads logging settings with secure defaults
- **Performance Thresholds**: Configurable performance monitoring thresholds
- **Security Events**: Configurable security event monitoring
- **Exempt Paths**: Configurable paths that bypass detailed logging
- **Body Logging**: Configurable request/response body logging with size limits

### Request Processing
```php
public function __invoke(Request $request, Response $response, callable $next): Response
```
- **Request ID Generation**: Generates unique correlation IDs for request tracing
- **Performance Tracking**: Tracks execution time and memory usage
- **Request Logging**: Logs incoming request details
- **Response Logging**: Logs outgoing response with performance metrics
- **Header Enhancement**: Adds logging headers to responses

### Incoming Request Logging
```php
private function logIncomingRequest(Request $request, string $requestId): void
```
- **Comprehensive Data**: Logs method, URI, headers, and parameters
- **Security Context**: Includes IP address, user agent, and user context
- **Body Content**: Optionally logs request body with size and type restrictions
- **Exempt Path Handling**: Skips detailed logging for exempt paths

### Outgoing Response Logging
```php
private function logOutgoingResponse(Request $request, Response $response, string $requestId, array $metrics): void
```
- **Response Details**: Logs status code, headers, and response metadata
- **Performance Metrics**: Includes response time, memory usage, and peak memory
- **Error Context**: Enhanced logging for error responses
- **Security Analysis**: Triggers security event analysis for failed requests

## Performance Monitoring

### Performance Metrics Tracking
```php
private function monitorPerformance(string $requestId, float $responseTime, int $memoryUsage, int $peakMemory): void
```
- **Response Time Monitoring**: Tracks API response times with configurable thresholds
- **Memory Usage Tracking**: Monitors memory consumption during request processing
- **Threshold Alerting**: Logs warnings and errors when thresholds are exceeded
- **Performance Analysis**: Provides data for performance optimization

### Performance Thresholds
- **Response Time Warning**: 1 second by default
- **Response Time Critical**: 5 seconds by default  
- **Memory Usage Warning**: 50MB by default
- **Memory Usage Critical**: 100MB by default

### Performance Headers
- **X-Request-ID**: Unique request correlation identifier
- **X-Response-Time**: Request processing time in milliseconds
- **X-Memory-Peak**: Peak memory usage during request processing

## Security Event Monitoring

### Security Event Detection
```php
private function checkSecurityEvents(Request $request, Response $response, string $requestId): void
```
- **Authentication Failures**: Monitors 401 Unauthorized responses
- **Authorization Failures**: Tracks 403 Forbidden responses
- **Rate Limiting**: Detects 429 Too Many Requests responses
- **Validation Failures**: Identifies 400 Bad Request responses
- **Comprehensive Context**: Logs IP address, user agent, and user context

### Security Event Types
- **authentication_failure**: Failed authentication attempts
- **authorization_failure**: Access denied events
- **rate_limit_exceeded**: Rate limiting violations
- **validation_failure**: Input validation failures
- **suspicious_patterns**: Unusual request patterns
- **error_threshold_exceeded**: High error rates

### Security Context Logging
- **IP Address Detection**: Handles proxy headers for accurate IP identification
- **User Agent Tracking**: Logs user agent for behavior analysis
- **User Context**: Includes authenticated user information when available
- **Request Patterns**: Analyzes request patterns for anomaly detection

## User and Request Context

### User Context Management
```php
private function getUserContext(): array
```
- **Authentication Status**: Determines if user is authenticated
- **User Identification**: Includes user ID and username when available
- **Session Integration**: Works with existing session management
- **Privacy Protection**: Respects user privacy and data protection

### Request Context Collection
- **HTTP Method**: Request method (GET, POST, PUT, DELETE, etc.)
- **URI Information**: Complete request URI and path
- **Headers**: Request headers with sensitive data filtering
- **IP Address**: Client IP address with proxy header support
- **Content Information**: Content type and length

### Client IP Detection
```php
private function getClientIpAddress(Request $request): string
```
- **Proxy Header Support**: Checks X-Forwarded-For, X-Real-IP headers
- **IP Validation**: Validates IP addresses and filters private ranges
- **Fallback Handling**: Graceful fallback for missing IP information
- **Security Filtering**: Validates IP addresses for security

## Sensitive Data Protection

### Header Filtering
```php
private function filterSensitiveHeaders(array $headers): array
```
- **Authorization Headers**: Filters authorization tokens and API keys
- **Cookie Data**: Removes sensitive cookie information
- **Custom Headers**: Filters custom authentication headers
- **Security Focus**: Maintains security while preserving debugging capability

### Body Content Logging
```php
private function shouldLogBody(Request $request): bool
private function shouldLogResponseBody(Response $response): bool
```
- **Size Limits**: Respects maximum body log size (4KB default)
- **Content Type Filtering**: Only logs appropriate content types
- **Binary Content Protection**: Avoids logging binary content
- **Security Considerations**: Prevents logging of sensitive data

### Content Truncation
```php
private function getLoggableBody(string $body): string
```
- **Size Management**: Truncates large content with clear indicators
- **Performance Optimization**: Prevents memory issues with large payloads
- **Debugging Support**: Maintains useful debugging information
- **Security Compliance**: Respects data protection requirements

## Configuration Options

### Basic Configuration
```php
$sugar_config['request_logging'] = [
    'enabled' => true,
    'log_level' => 'info',
    'exempt_paths' => ['/V8/docs', '/access_token']
];
```

### Advanced Configuration
```php
$sugar_config['request_logging'] = [
    'enabled' => true,
    'log_level' => 'debug',
    'log_request_body' => true,
    'log_response_body' => true,
    'max_body_log_size' => 2048,
    'performance_thresholds' => [
        'response_time_warning' => 500,
        'response_time_critical' => 2000,
        'memory_usage_warning' => 25165824, // 25MB
        'memory_usage_critical' => 52428800  // 50MB
    ]
];
```

### Security Event Configuration
```php
$sugar_config['request_logging'] = [
    'enabled' => true,
    'security_events' => [
        'authentication_failure',
        'authorization_failure',
        'rate_limit_exceeded',
        'validation_failure',
        'custom_security_event'
    ]
];
```

## Log Output Examples

### Successful Request Log
```json
{
    "message": "API Request Received",
    "context": {
        "request_id": "req_65a4b8f0c1234_abc123",
        "method": "GET",
        "path": "/V8/module/Accounts",
        "ip_address": "192.168.1.100",
        "user_context": {
            "authenticated": true,
            "user_id": "user123",
            "user_name": "admin"
        },
        "timestamp": "2024-01-15T10:30:00Z"
    }
}
```

### Performance Warning Log
```json
{
    "message": "Slow API response time detected",
    "context": {
        "request_id": "req_65a4b8f0c1234_abc123",
        "response_time_ms": 1500,
        "threshold_ms": 1000,
        "method": "POST",
        "path": "/V8/module"
    }
}
```

### Security Event Log
```json
{
    "message": "API security events detected",
    "context": {
        "request_id": "req_65a4b8f0c1234_abc123",
        "security_events": ["authentication_failure"],
        "status_code": 401,
        "ip_address": "192.168.1.100",
        "user_agent": "Custom API Client/1.0"
    }
}
```

## Performance Considerations

### Logging Performance
- **Exempt Path Optimization**: Fast path checking for exempt endpoints
- **Conditional Logging**: Logs only when enabled and appropriate
- **Efficient Filtering**: Optimized sensitive data filtering
- **Memory Management**: Efficient memory usage during logging

### Configuration Caching
- **Static Configuration**: Configuration loaded once per request
- **Default Optimization**: Sensible defaults minimize configuration overhead
- **Path Optimization**: Efficient exempt path checking
- **Performance Monitoring**: Self-monitoring for performance impact

### Resource Management
- **Memory Efficiency**: Optimized memory usage for large requests
- **CPU Optimization**: Efficient processing for high-volume APIs
- **I/O Management**: Optimized logging I/O operations
- **Scalability**: Designed for high-traffic API environments

## Integration Notes

### Middleware Pipeline Integration
- **Order Independence**: Can be placed at any position in middleware pipeline
- **Header Enhancement**: Adds debugging headers without conflicts
- **Request Enhancement**: Adds correlation ID to request attributes
- **Response Preservation**: Does not modify response content

### Existing Logging Integration
- **Logger Compatibility**: Uses existing `$GLOBALS['log']` infrastructure
- **Log Level Respect**: Respects existing log level configurations
- **Format Consistency**: Maintains consistent log format with existing logs
- **Monitoring Integration**: Compatible with existing monitoring systems

### Development and Production
- **Environment Awareness**: Adapts behavior based on environment
- **Debug Mode**: Enhanced logging in debug environments
- **Production Optimization**: Optimized for production performance
- **Security Considerations**: Appropriate security for production use

## Security Considerations

### Data Protection
- **Sensitive Data Filtering**: Automatically filters sensitive information
- **Privacy Compliance**: Respects user privacy and data protection
- **Context Limitation**: Limits context exposure in production environments
- **Security Logging**: Provides appropriate security event logging

### Performance Security
- **Resource Protection**: Prevents resource exhaustion from logging
- **Memory Limits**: Protects against memory exhaustion attacks
- **Performance Monitoring**: Monitors for performance-based attacks
- **Threshold Management**: Configurable thresholds for security

### Monitoring Security
- **Anomaly Detection**: Detects unusual request patterns
- **Security Events**: Comprehensive security event monitoring
- **Audit Trail**: Provides comprehensive audit trail for security
- **Integration Support**: Supports security monitoring and alerting systems 