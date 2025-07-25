# EnhancedBaseController.php Documentation

/**
 * @fileoverview Enhanced Base Controller for SuiteCRM V8 API extending the existing
 * BaseController with enhanced response standardization, improved error handling, and
 * comprehensive logging while maintaining full backward compatibility.
 * @package SuiteCRM.Api.V8.Controller
 * @copyright SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `EnhancedBaseController.php` file, located in `Api/V8/Controller/`, defines the `EnhancedBaseController` class. This abstract class extends the existing `BaseController` with enhanced response standardization, improved error handling, and comprehensive logging. It maintains full backward compatibility while adding enhanced features for consistent API responses and better error tracking across all endpoints.

### Key Responsibilities
- Standardized JSON:API response format across all endpoints
- Enhanced error response handling with correlation IDs
- Integration with enhanced error response and logging systems
- Backward compatibility with existing BaseController patterns
- Comprehensive response metadata and timing information
- Consistent response headers and content type handling

## Database Operations

### No Direct Database Operations
This controller does not perform direct database operations but leverages existing infrastructure:
- **Parent Compatibility**: Inherits all existing BaseController functionality
- **Configuration Access**: Uses existing API configuration and debug settings
- **Session Integration**: Works with existing session and authentication systems

## Internal API Calls

### Enhanced Response Generation
- **Response Standardization**: Ensures consistent JSON:API format across all endpoints
- **Metadata Enhancement**: Adds comprehensive metadata to all responses
- **Header Management**: Provides consistent header handling
- **Performance Tracking**: Includes performance metrics in response metadata

### Error Handling Integration
- **Enhanced Error Responses**: Uses EnhancedErrorResponse for comprehensive error handling
- **Correlation Support**: Provides request correlation IDs for error tracking
- **Context Enhancement**: Adds request context to error responses
- **Logging Integration**: Automatic logging of enhanced error responses

### Backward Compatibility
- **Method Preservation**: Maintains all existing BaseController method signatures
- **Response Compatibility**: Ensures existing response formats continue to work
- **Error Compatibility**: Backward compatible error response generation
- **Controller Integration**: Seamless integration with existing controller patterns

## Enhanced Response Methods

### Enhanced Response Generation
```php
public function generateEnhancedResponse(HttpResponse $httpResponse, $response, $status, array $metadata = []): HttpResponse
```
- **Standardized Formatting**: Ensures consistent JSON:API response format
- **Metadata Enhancement**: Adds comprehensive response metadata
- **Performance Tracking**: Includes execution time and memory usage
- **Header Enhancement**: Adds enhanced headers for debugging and monitoring
- **Logging Integration**: Optional response logging for monitoring

### Enhanced Error Response Generation
```php
public function generateEnhancedErrorResponse(HttpResponse $httpResponse, Exception $exception, $status, array $additionalContext = [], string $severity = 'medium'): HttpResponse
```
- **Comprehensive Error Handling**: Uses EnhancedErrorResponse for detailed error information
- **Context Integration**: Includes request context and additional context data
- **Severity Management**: Supports error severity levels for appropriate logging
- **Correlation Support**: Includes request correlation IDs for error tracking
- **Enhanced Headers**: Adds error-specific headers for debugging

### Backward-Compatible Methods
```php
public function generateResponse(HttpResponse $httpResponse, $response, $status): HttpResponse
public function generateErrorResponse(HttpResponse $httpResponse, Exception $exception, $status): HttpResponse
```
- **Full Compatibility**: Maintains exact compatibility with existing BaseController methods
- **Enhancement Detection**: Automatically enhances responses when possible
- **Graceful Fallback**: Falls back to parent implementation when needed
- **Seamless Integration**: Works with existing controller code without changes

## Response Standardization

### JSON:API Format Standardization
```php
private function standardizeResponseFormat($response, int $status, array $metadata): array
```
- **Format Detection**: Detects existing JSON:API format and preserves it
- **Structure Enhancement**: Converts simple responses to JSON:API format
- **Metadata Integration**: Adds standard metadata to all responses
- **Backward Compatibility**: Preserves existing response structures

### Response Metadata Enhancement
```php
private function addResponseMetadata(array $response, int $status): array
```
- **Request Correlation**: Adds request correlation IDs to responses
- **Performance Metrics**: Includes execution time and memory usage
- **Server Information**: Adds PHP version and SuiteCRM version
- **User Context**: Includes authenticated user information when available
- **Custom Metadata**: Supports controller-specific metadata

### Enhanced Header Management
```php
private function addEnhancedHeaders(HttpResponse $response, int $status, bool $isError = false): HttpResponse
```
- **Correlation Headers**: Adds request correlation ID headers
- **API Version**: Includes API version information
- **Performance Headers**: Adds timing and memory usage headers
- **Security Headers**: Adds security-specific headers for error responses
- **Caching Headers**: Appropriate caching headers based on response type

## Error Handling Enhancement

### Error Title Generation
```php
private function getErrorTitle(int $status, Exception $exception): string
```
- **Standard HTTP Titles**: Provides standard HTTP status code titles
- **Context Awareness**: Considers exception context for appropriate titles
- **Fallback Handling**: Graceful fallback for unknown status codes
- **Consistency**: Ensures consistent error titles across the API

### Request Correlation Management
```php
private function getRequestId(HttpResponse $httpResponse): string
```
- **Multi-Source Detection**: Attempts to get correlation ID from multiple sources
- **Header Detection**: Checks existing response headers for correlation ID
- **Request Attributes**: Uses request attributes set by middleware
- **ID Generation**: Generates new correlation ID when none exists

### Response Logging
```php
private function logResponse(array $response, int $status): void
```
- **Conditional Logging**: Logs responses only when enhanced logging is enabled
- **Error Prioritization**: Uses appropriate log levels for error responses
- **Structured Data**: Provides structured data for log analysis
- **Performance Tracking**: Includes performance metrics in log entries

## Metadata and Context Management

### Response Metadata Management
```php
protected function setResponseMetadata(array $metadata): self
protected function addResponseMetadata(string $key, $value): self
```
- **Fluent Interface**: Provides fluent interface for metadata management
- **Flexible Structure**: Supports arbitrary metadata structure
- **Controller Integration**: Allows controllers to add custom metadata
- **Response Enhancement**: Enhances responses with controller-specific data

### Enhanced Logging Control
```php
protected function setEnhancedLogging(bool $enabled): self
```
- **Logging Control**: Allows controllers to enable/disable enhanced logging
- **Performance Optimization**: Reduces overhead when logging not needed
- **Debug Integration**: Supports debug-specific logging behaviors
- **Flexible Configuration**: Allows per-controller logging configuration

## Response Format Examples

### Enhanced JSON:API Response
```json
{
    "data": {
        "type": "accounts",
        "id": "12345",
        "attributes": {
            "name": "Sample Account",
            "email": "contact@example.com"
        }
    },
    "meta": {
        "status": 200,
        "timestamp": "2024-01-15T10:30:00Z",
        "api_version": "8.0",
        "request_id": "req_65a4b8f0c1234_abc123",
        "performance": {
            "memory_usage": 8388608,
            "peak_memory": 16777216,
            "execution_time": 0.125
        },
        "server": {
            "php_version": "8.1.0",
            "suite_version": "8.5.0"
        },
        "user": {
            "id": "user123",
            "authenticated": true
        }
    }
}
```

### Enhanced Error Response
```json
{
    "errors": [{
        "id": "req_65a4b8f0c1234_abc123",
        "status": "400",
        "title": "Bad Request",
        "detail": "Invalid parameter format",
        "meta": {
            "severity": "medium",
            "request_id": "req_65a4b8f0c1234_abc123",
            "timestamp": "2024-01-15T10:30:00Z",
            "correlation_id": "req_65a4b8f0c1234_abc123"
        }
    }]
}
```

## Integration and Compatibility

### Existing Controller Integration
- **Seamless Extension**: Controllers can extend EnhancedBaseController instead of BaseController
- **No Code Changes**: Existing controller code continues to work without modification
- **Gradual Migration**: Supports gradual migration from BaseController to EnhancedBaseController
- **Feature Detection**: Automatically detects when enhanced features are appropriate

### Middleware Integration
- **Request Correlation**: Works with RequestLoggingMiddleware for correlation IDs
- **Enhanced Validation**: Integrates with EnhancedValidationMiddleware for validation metadata
- **Error Enhancement**: Uses EnhancedErrorResponse for comprehensive error handling
- **Pipeline Compatibility**: Compatible with existing middleware pipeline

### Response Enhancement Strategy
- **Automatic Enhancement**: Automatically enhances responses when possible
- **Compatibility Preservation**: Preserves existing response formats
- **Progressive Enhancement**: Adds enhancements without breaking existing functionality
- **Flexible Application**: Allows selective use of enhanced features

## Performance Considerations

### Response Generation Performance
- **Efficient Processing**: Optimized response generation for minimal overhead
- **Conditional Enhancement**: Applies enhancements only when beneficial
- **Memory Management**: Efficient memory usage during response generation
- **Header Optimization**: Optimized header generation and management

### Metadata Generation
- **Lazy Evaluation**: Generates metadata only when needed
- **Efficient Collection**: Optimized metadata collection and aggregation
- **Memory Tracking**: Efficient memory usage tracking
- **Performance Metrics**: Low-overhead performance metric collection

### Logging Performance
- **Conditional Logging**: Logs only when enhanced logging is enabled
- **Efficient Serialization**: Optimized JSON serialization for logging
- **Memory Management**: Prevents memory leaks during logging
- **Performance Monitoring**: Self-monitoring for performance impact

## Security Considerations

### Response Data Security
- **Sensitive Data Protection**: Protects sensitive data in response metadata
- **Context Filtering**: Filters sensitive context information
- **User Privacy**: Respects user privacy in response enhancement
- **Debug Mode Control**: Controls information disclosure based on debug settings

### Error Information Disclosure
- **Production Safety**: Limits error information in production environments
- **Debug Control**: Provides detailed error information only in debug mode
- **Security Headers**: Adds appropriate security headers for error responses
- **Context Limitation**: Limits error context exposure for security

### Request Correlation Security
- **ID Generation**: Uses cryptographically secure request ID generation
- **Correlation Tracking**: Secure correlation ID handling and transmission
- **Privacy Protection**: Protects correlation IDs from unauthorized access
- **Audit Trail**: Provides secure audit trail through correlation IDs

## Migration Guide

### From BaseController to EnhancedBaseController

#### Simple Migration
```php
// Before
class MyController extends BaseController {
    // existing code
}

// After  
class MyController extends EnhancedBaseController {
    // existing code continues to work unchanged
}
```

#### Enhanced Features Usage
```php
class MyController extends EnhancedBaseController {
    public function myAction($request, $response, $args) {
        try {
            $data = $this->getMyData();
            
            // Add custom metadata
            $this->addResponseMetadata('custom_field', 'value');
            
            // Use enhanced response generation
            return $this->generateEnhancedResponse($response, $data, 200);
            
        } catch (Exception $e) {
            // Use enhanced error response
            return $this->generateEnhancedErrorResponse(
                $response, 
                $e, 
                400, 
                ['context' => 'additional context'], 
                'high'
            );
        }
    }
}
```

### Gradual Enhancement Strategy
1. **Initial Migration**: Change extends from BaseController to EnhancedBaseController
2. **Metadata Addition**: Add custom metadata using addResponseMetadata()
3. **Enhanced Responses**: Switch to generateEnhancedResponse() for new endpoints
4. **Error Enhancement**: Use generateEnhancedErrorResponse() for better error handling
5. **Full Enhancement**: Leverage all enhanced features for optimal API consistency 