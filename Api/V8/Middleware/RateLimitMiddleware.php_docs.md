# RateLimitMiddleware.php Documentation

/**
 * @fileoverview Rate Limiting Middleware for SuiteCRM V8 API providing configurable
 * API protection against abuse and ensuring fair usage across all endpoints while
 * integrating seamlessly with existing Slim 3 middleware infrastructure.
 * @package SuiteCRM.Api.V8.Middleware
 * @copyright SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `RateLimitMiddleware.php` file, located in `Api/V8/Middleware/`, defines the `RateLimitMiddleware` class. This middleware provides comprehensive rate limiting for API endpoints to prevent abuse and ensure fair usage. It builds upon the existing rate limiting patterns established in the OAuth2 SecurityValidator while extending protection to all API endpoints.

### Key Responsibilities
- Sliding window rate limiting for accurate request tracking
- Different rate limits for authenticated vs unauthenticated users
- IP-based and user-based client identification
- Comprehensive security logging and monitoring
- Integration with existing SuiteCRM session management
- Configurable exempt paths for critical endpoints

## Database Operations

### Session-Based Storage
This middleware uses session storage for rate limiting data rather than database operations:
- **Session Storage**: Stores request timestamps in `$_SESSION` for sliding window tracking
- **Memory Efficiency**: Automatically cleans expired request data from session
- **No Database Load**: Avoids database queries for performance-sensitive rate limiting

### Configuration Integration
- **Global Config Access**: Reads rate limit settings from `$sugar_config['api_rate_limit']`
- **Dynamic Configuration**: Supports environment-specific rate limit configurations
- **Secure Defaults**: Provides sensible defaults when configuration is not present

## Internal API Calls

### Middleware Pipeline Integration
- **Slim 3 Compatibility**: Integrates seamlessly with existing middleware pipeline
- **Request Processing**: Processes all incoming API requests for rate limit validation
- **Response Enhancement**: Adds rate limit headers to all responses
- **Bypass Mechanisms**: Provides exempt paths for critical endpoints

### Session Integration
- **User Identification**: Uses existing `$_SESSION['authenticated_user_id']` for user-based limiting
- **Session Management**: Stores rate limiting data in existing session infrastructure
- **Cleanup Operations**: Automatically removes expired rate limit data

## Rate Limiting Methods

### Configuration and Initialization
```php
public function __construct()
```
- **Configuration Loading**: Loads rate limit settings with secure defaults
- **Flexible Limits**: Different limits for authenticated and unauthenticated users
- **Exempt Paths**: Configurable paths that bypass rate limiting
- **Logging Setup**: Initializes comprehensive rate limiting monitoring

### Request Processing
```php
public function __invoke(Request $request, Response $response, callable $next): Response
```
- **Path Exemption**: Checks if request path is exempt from rate limiting
- **Client Identification**: Creates unique identifier for client tracking
- **Limit Checking**: Validates current request count against configured limits
- **Request Recording**: Records successful requests for sliding window tracking

### Client Identification System
```php
private function getClientIdentifier(Request $request): string
```
- **IP-Based Tracking**: Uses client IP address for unauthenticated requests
- **User-Based Tracking**: Combines user ID and IP for authenticated requests
- **Proxy Support**: Handles proxy headers for accurate IP detection
- **Unique Identifiers**: Creates consistent identifiers for reliable tracking

### Sliding Window Implementation
```php
private function getCurrentRequestCount(string $clientId): int
```
- **Time-Based Windows**: Uses configurable time windows for rate limiting
- **Automatic Cleanup**: Removes expired request timestamps automatically
- **Accurate Counting**: Provides precise request counts within time windows
- **Memory Efficiency**: Maintains minimal memory footprint for tracking

## Security Features

### Rate Limiting Configuration
- **Default Limits**: 100 requests/hour for unauthenticated, 1000 for authenticated
- **Configurable Windows**: Default 1-hour sliding window with configuration support
- **Exempt Endpoints**: OAuth2 token and logout endpoints exempt by default
- **Enable/Disable**: Can be completely disabled via configuration

### Attack Prevention
- **Brute Force Protection**: Prevents rapid-fire API requests
- **Resource Conservation**: Protects server resources from abuse
- **Fair Usage**: Ensures equitable API access across users
- **Monitoring Integration**: Comprehensive logging for security analysis

### Response Headers
- **X-RateLimit-Limit**: Informs clients of their rate limit
- **X-RateLimit-Remaining**: Shows remaining requests in window
- **X-RateLimit-Reset**: Provides reset timestamp for client planning
- **Retry-After**: Tells clients when to retry after limit exceeded

## Configuration Options

### Basic Configuration Structure
```php
$sugar_config['api_rate_limit'] = [
    'enabled' => true,
    'default_limit' => 100,           // Unauthenticated users per hour
    'authenticated_limit' => 1000,    // Authenticated users per hour
    'window_seconds' => 3600,         // 1 hour window
    'exempt_paths' => ['/access_token', '/V8/logout']
];
```

### Environment-Specific Examples
```php
// Development environment (relaxed limits)
$sugar_config['api_rate_limit'] = [
    'enabled' => true,
    'default_limit' => 1000,
    'authenticated_limit' => 5000,
    'window_seconds' => 3600
];

// Production environment (strict limits)
$sugar_config['api_rate_limit'] = [
    'enabled' => true,
    'default_limit' => 50,
    'authenticated_limit' => 500,
    'window_seconds' => 3600
];

// Rate limiting disabled
$sugar_config['api_rate_limit'] = [
    'enabled' => false
];
```

## Response Handling

### Successful Requests
- **Rate Limit Headers**: All successful responses include rate limit information
- **Request Recording**: Successful requests are recorded for tracking
- **Sliding Window**: Request timestamps are maintained for accurate counting
- **Automatic Cleanup**: Expired request data is automatically removed

### Rate Limit Exceeded (429 Response)
```json
{
    "error": "rate_limit_exceeded",
    "message": "API rate limit exceeded. Please try again later.",
    "limit": 100,
    "window_seconds": 3600,
    "reset_time": 1641234567
}
```

### Standard Headers for Rate Limited Responses
- **HTTP 429**: Standard "Too Many Requests" status code
- **X-RateLimit-Limit**: Shows the rate limit that was exceeded
- **X-RateLimit-Remaining**: Always 0 for exceeded responses
- **X-RateLimit-Reset**: Timestamp when limit resets
- **Retry-After**: Seconds to wait before retrying

## Security Monitoring

### Comprehensive Logging
- **Rate Limit Violations**: Logs all rate limit exceeded events
- **Client Information**: Records IP address, user agent, and user ID
- **Request Patterns**: Tracks request counts and timing patterns
- **Security Analysis**: Provides data for identifying abuse patterns

### Log Examples
```php
// Successful rate limiting initialization
$GLOBALS['log']->info('Rate limiting middleware initialized', [
    'default_limit' => 100,
    'authenticated_limit' => 1000,
    'window_seconds' => 3600
]);

// Rate limit exceeded
$GLOBALS['log']->warning('API rate limit exceeded', [
    'client_id' => 'user_12345_192.168.1.100',
    'request_count' => 101,
    'request_limit' => 100,
    'ip_address' => '192.168.1.100'
]);
```

## Integration Benefits

### Performance Protection
- **Server Resource Protection**: Prevents API server overload
- **Database Protection**: Reduces excessive database queries
- **Fair Resource Allocation**: Ensures equitable API access
- **Scalability Support**: Helps maintain performance under load

### Security Enhancement
- **Abuse Prevention**: Stops malicious high-frequency requests
- **DoS Protection**: Provides basic denial-of-service protection
- **Attack Detection**: Logs suspicious request patterns
- **User Behavior Monitoring**: Tracks normal vs abnormal usage

### Developer Experience
- **Clear Headers**: Provides clear rate limit information to API clients
- **Predictable Behavior**: Consistent rate limiting across all endpoints
- **Configuration Flexibility**: Easy to adjust limits based on needs
- **Graceful Degradation**: Clear error messages when limits exceeded

## Future Enhancement Points

### Advanced Rate Limiting
- **Burst Allowances**: Support for burst request patterns
- **Endpoint-Specific Limits**: Different limits for different API endpoints
- **Dynamic Limits**: Rate limits that adjust based on server load
- **Redis Integration**: Distributed rate limiting for multi-server setups

### Enhanced Monitoring
- **Rate Limit Metrics**: Detailed analytics on API usage patterns
- **Abuse Detection**: Automated detection of suspicious request patterns
- **Alert Integration**: Automated alerts for rate limit violations
- **Usage Analytics**: Comprehensive API usage reporting

### Client Support
- **API Key Tiers**: Different rate limits based on API key tiers
- **Quota Management**: Monthly/daily quota limits in addition to rate limits
- **Client Whitelisting**: Bypass rate limits for trusted clients
- **Custom Rate Plans**: Support for different rate limiting plans 