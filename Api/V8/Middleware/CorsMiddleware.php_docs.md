# CorsMiddleware.php Documentation

/**
 * @fileoverview Enhanced CORS Middleware for SuiteCRM V8 API providing secure,
 * configurable cross-origin resource sharing that replaces the basic wildcard
 * implementation while maintaining full backward compatibility with existing API infrastructure.
 * @package SuiteCRM.Api.V8.Middleware
 * @copyright SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `CorsMiddleware.php` file, located in `Api/V8/Middleware/`, defines the `CorsMiddleware` class. This middleware enhances the basic CORS implementation found in `Api/Core/app.php` by providing configurable origin validation, proper OPTIONS preflight handling, and comprehensive security logging while maintaining full compatibility with existing API clients.

### Key Responsibilities
- Enhanced CORS header management with configurable origins
- Secure preflight OPTIONS request handling
- Origin validation with security logging for unauthorized requests
- Integration with existing SuiteCRM configuration system
- Backward compatibility with current API usage patterns
- Security monitoring for CORS-related events

## Database Operations

### No Direct Database Operations
This middleware does not perform direct database operations but leverages existing infrastructure:
- **Configuration Access**: Reads CORS settings from global `$sugar_config` array
- **Logging**: Uses existing SuiteCRM logging infrastructure for security events
- **Session Integration**: Works with existing session management without modifications

## Internal API Calls

### Configuration Integration
- **Global Config Access**: Reads CORS configuration from `$sugar_config['cors']`
- **Site URL Integration**: Uses `$sugar_config['site_url']` as secure default origin
- **Environment Compatibility**: Supports environment-specific CORS configurations

### Middleware Pipeline Integration
- **Slim 3 Compatibility**: Integrates seamlessly with existing middleware pipeline
- **Request Processing**: Processes all incoming requests for CORS headers
- **Response Enhancement**: Adds appropriate CORS headers to all responses
- **OPTIONS Handling**: Intercepts and properly handles preflight requests

## CORS Security Methods

### Enhanced Origin Validation
```php
public function __construct()
```
- **Configuration Loading**: Loads CORS settings with secure defaults
- **Origin Restrictions**: Replaces wildcard (*) with configurable origin list
- **Security Defaults**: Uses site URL as default allowed origin for security
- **Logging Setup**: Initializes security monitoring for CORS events

### Request Processing
```php
public function __invoke(Request $request, Response $response, callable $next): Response
```
- **Origin Extraction**: Extracts origin from request headers safely
- **Header Application**: Adds appropriate CORS headers to all responses
- **Preflight Detection**: Identifies and handles OPTIONS preflight requests
- **Middleware Chain**: Continues processing for non-preflight requests

### Secure Header Management
```php
private function addCorsHeaders(Response $response, string $origin): Response
```
- **Origin Validation**: Validates request origin against allowed list
- **Credential Handling**: Manages credential headers for authenticated requests
- **Fallback Behavior**: Provides secure fallback for requests without origin
- **Security Logging**: Logs unauthorized origin attempts for monitoring

### Preflight Request Handling
```php
private function handlePreflightRequest(Request $request, Response $response, string $origin): Response
```
- **Method Validation**: Validates requested HTTP methods against allowed list
- **Header Validation**: Validates requested headers for security compliance
- **Response Generation**: Generates proper preflight response with caching headers
- **Error Handling**: Returns appropriate HTTP status codes for invalid requests

## Enhanced Security Features

### Origin Validation System
- **Configurable Origins**: Supports multiple allowed origins instead of wildcard
- **Pattern Matching**: Extensible pattern matching for subdomain support
- **Backward Compatibility**: Supports wildcard (*) for existing configurations
- **Security Logging**: Comprehensive logging of origin validation events

### Security Monitoring
- **Unauthorized Origins**: Logs requests from disallowed origins
- **Method Violations**: Logs attempts to use disallowed HTTP methods
- **User Agent Tracking**: Records user agent information for security analysis
- **IP Address Logging**: Tracks IP addresses for security monitoring

### Configuration Security
- **Secure Defaults**: Uses site URL instead of wildcard as default origin
- **Environment Variables**: Supports environment-based configuration overrides
- **Credential Control**: Configurable credential handling for enhanced security
- **Cache Control**: Configurable preflight cache duration for performance

## Configuration Options

### Basic Configuration Structure
```php
$sugar_config['cors'] = [
    'allowed_origins' => ['https://your-domain.com', 'https://app.your-domain.com'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
    'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'],
    'allow_credentials' => true,
    'max_age' => 3600
];
```

### Security Configuration Examples
```php
// Production configuration (restrictive)
$sugar_config['cors'] = [
    'allowed_origins' => ['https://yourdomain.com'],
    'allow_credentials' => true,
    'max_age' => 86400 // 24 hours
];

// Development configuration (permissive but logged)
$sugar_config['cors'] = [
    'allowed_origins' => ['*'], // Maintains backward compatibility
    'allow_credentials' => false,
    'max_age' => 300 // 5 minutes
];
```

## Integration Benefits

### Enhanced Security
- **Origin Restriction**: Prevents unauthorized cross-origin requests
- **Method Validation**: Validates HTTP methods for preflight requests
- **Header Control**: Restricts allowed request headers for security
- **Credential Protection**: Configurable credential handling

### Improved Compatibility
- **Standards Compliance**: Full CORS specification compliance
- **Browser Support**: Proper preflight handling for all modern browsers
- **API Tool Support**: Compatible with Postman, Swagger UI, and other tools
- **Mobile App Support**: Proper handling for mobile application requests

### Operational Benefits
- **Security Monitoring**: Comprehensive logging for security analysis
- **Configuration Flexibility**: Environment-specific configuration support
- **Performance Optimization**: Proper preflight caching for reduced requests
- **Debugging Support**: Detailed logging for troubleshooting CORS issues

## Migration Strategy

### From Current Implementation
1. **Backward Compatibility**: Existing wildcard configuration continues to work
2. **Gradual Migration**: Can be deployed without breaking existing clients
3. **Configuration Override**: Existing behavior maintained unless explicitly configured
4. **Security Enhancement**: Immediate security improvements with default configuration

### Integration Steps
1. **Middleware Registration**: Add to middleware pipeline in service configuration
2. **Configuration Setup**: Add CORS configuration to `config_override.php`
3. **Testing**: Verify API functionality with existing clients
4. **Security Hardening**: Restrict origins to specific domains as needed

## Future Enhancement Points

### Advanced Pattern Matching
- **Subdomain Support**: Pattern-based subdomain validation
- **Environment Detection**: Automatic origin detection based on environment
- **Dynamic Configuration**: Runtime configuration updates without restart

### Enhanced Monitoring
- **Rate Limiting Integration**: Integration with rate limiting for suspicious origins
- **Security Alerts**: Automated alerts for repeated unauthorized attempts
- **Analytics Integration**: CORS metrics for API usage analysis

### Performance Optimization
- **Origin Caching**: Cache origin validation results for performance
- **Header Optimization**: Minimize header overhead for frequently used origins
- **Preflight Optimization**: Smart preflight caching based on request patterns 