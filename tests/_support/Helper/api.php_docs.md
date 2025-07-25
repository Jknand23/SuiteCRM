# Enhanced API Testing Helper Documentation

/**
 * @fileoverview Enhanced API Testing Helper for SuiteCRM V8 API Testing providing
 * comprehensive utilities for testing enhanced middleware, validation, security, and
 * documentation features implemented in the API infrastructure enhancement.
 * @package SuiteCRM.Tests.Helper
 * @copyright SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `api.php` file, located in `tests/_support/Helper/`, defines the enhanced `api` class that extends the existing Codeception API testing framework. This helper provides comprehensive utilities for testing enhanced middleware, validation, security, and documentation features implemented in the API infrastructure enhancement phase.

### Key Responsibilities
- Enhanced validation middleware testing utilities
- Security headers validation helpers
- API key authentication testing support
- Performance testing utilities for endpoints
- Documentation testing and validation helpers
- Response enhancement testing utilities
- Comprehensive test reporting and analysis
- **Configurable testing parameters and thresholds**
- **Environment-based configuration support**

## Configuration Management

### Configurable Properties
The helper now supports configurable testing parameters instead of hardcoded values:

```php
// Default security headers (configurable)
private array $defaultSecurityHeaders = [
    'X-Frame-Options' => 'DENY',
    'X-Content-Type-Options' => 'nosniff',
    'X-XSS-Protection' => '1; mode=block',
    'Referrer-Policy' => 'strict-origin-when-cross-origin',
    'X-Permitted-Cross-Domain-Policies' => 'none',
    'X-API-Security' => 'SuiteCRM-Enhanced'
];

// Performance thresholds (configurable)
private array $performanceThresholds = [
    'max_avg_response_time' => 2000, // milliseconds
    'max_response_time' => 5000, // milliseconds
    'min_security_compliance' => 80, // percentage
    'min_documentation_health' => 75 // percentage
];
```

### Configuration Methods
```php
public function configureSecurityHeaders(array $headers): void
public function configurePerformanceThresholds(array $thresholds): void
public function getPerformanceThreshold(string $thresholdKey): int|float
```

### Usage Examples
```php
// Customize security headers for specific test environment
$helper->configureSecurityHeaders([
    'X-Custom-Header' => 'custom-value',
    'X-Frame-Options' => 'SAMEORIGIN' // Override default
]);

// Adjust performance thresholds for slower test environment
$helper->configurePerformanceThresholds([
    'max_avg_response_time' => 3000,
    'min_security_compliance' => 70
]);

// Get current threshold in tests
$maxTime = $helper->getPerformanceThreshold('max_avg_response_time');
```

## Enhanced Middleware Testing

### Validation Middleware Testing
```php
public function testEnhancedValidation(string $endpoint, array $maliciousPayload, array $expectedErrors = []): array
```
- **Security Testing**: Tests enhanced validation middleware with malicious payloads
- **Validation Triggers**: Verifies that security violations are properly detected
- **Error Response Validation**: Ensures proper error response structure and metadata
- **Performance Tracking**: Measures validation processing time and response characteristics
- **Comprehensive Results**: Returns detailed validation test results with security violations

**Test Coverage**:
- XSS attack pattern detection
- SQL injection attempt detection
- Oversized payload handling
- Deeply nested array attacks
- Security violation logging verification

### Security Headers Testing
```php
public function testSecurityHeaders(string $endpoint, array $expectedHeaders = []): array
```
- **Header Validation**: Tests presence and correctness of security headers
- **Compliance Checking**: Calculates security compliance percentage
- **Default Headers**: Tests standard security headers (X-Frame-Options, CSP, etc.)
- **Custom Headers**: Supports testing of custom security headers
- **Comprehensive Analysis**: Provides detailed header presence analysis

**Default Security Headers Tested**:
- `X-Frame-Options: DENY`
- `X-Content-Type-Options: nosniff`
- `X-XSS-Protection: 1; mode=block`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `X-Permitted-Cross-Domain-Policies: none`
- `X-API-Security: SuiteCRM-Enhanced`

### API Key Authentication Testing
```php
public function testApiKeyAuthentication(string $endpoint, string $apiKey, string $apiSecret): array
```
- **Valid Authentication**: Tests successful API key authentication
- **Invalid Authentication**: Verifies rejection of invalid credentials
- **Missing Authentication**: Tests fallback behavior for missing API keys
- **Security Validation**: Ensures proper authentication security measures
- **Comprehensive Coverage**: Tests all authentication scenarios

**Authentication Test Scenarios**:
1. Valid API key and secret (expects 200 response)
2. Invalid API key credentials (expects 401 response)
3. Missing API key header (expects proper fallback handling)

## Performance Testing Utilities

### Endpoint Performance Measurement
```php
public function measureEndpointPerformance(string $endpoint, array $testCases = [], int $iterations = 5): array
```
- **Multi-Case Testing**: Tests multiple scenarios with different parameters
- **Statistical Analysis**: Calculates average, min, max, and median response times
- **Memory Tracking**: Monitors memory usage during endpoint execution
- **Load Testing**: Supports multiple iterations for consistent measurements
- **Comprehensive Metrics**: Provides detailed performance analytics

**Performance Metrics Collected**:
- Response time (milliseconds)
- Memory usage per request
- Response status codes
- Statistical analysis (avg, min, max, median)
- Performance trends across iterations

**Test Case Structure**:
```php
$testCases = [
    'simple_get' => ['method' => 'GET', 'params' => []],
    'filtered_get' => ['method' => 'GET', 'params' => ['filter' => 'value']],
    'create_post' => ['method' => 'POST', 'payload' => ['data' => 'value']]
];
```

## Documentation Testing

### API Documentation Validation
```php
public function testApiDocumentation(string $swaggerEndpoint, array $expectedEndpoints = []): array
```
- **OpenAPI Specification**: Validates OpenAPI/Swagger specification structure
- **Endpoint Coverage**: Checks documentation coverage for expected endpoints
- **UI Accessibility**: Tests documentation interface accessibility
- **Specification Validation**: Ensures required OpenAPI fields are present
- **Coverage Analysis**: Analyzes documentation completeness

**Documentation Validation Areas**:
- OpenAPI specification structure validation
- Required fields presence (`openapi`, `info`, `paths`)
- Endpoint documentation completeness
- Documentation UI accessibility
- API version and metadata validation

## Enhanced Response Testing

### Response Enhancement Validation
```php
public function testEnhancedResponse(string $endpoint, array $expectedEnhancements = []): array
```
- **Metadata Validation**: Tests presence of enhanced response metadata
- **Performance Metrics**: Checks for performance metrics in responses
- **Correlation IDs**: Validates request correlation and tracking
- **Enhanced Headers**: Tests for enhanced response headers
- **Feature Scoring**: Calculates enhancement feature score

**Enhanced Features Tested**:
- Enhanced metadata presence and structure
- Performance metrics inclusion
- Request correlation ID presence
- Enhanced error handling capabilities
- Response header enhancements

**Enhancement Headers Checked**:
- `X-Request-ID`: Request correlation identifier
- `X-Response-Time`: Response processing time
- `X-API-Version`: API version information

## Test Data Tracking and Analysis

### Performance Metrics Storage
```php
private array $performanceMetrics = []
```
- **Endpoint Tracking**: Stores performance data per endpoint
- **Historical Data**: Maintains performance history for trend analysis
- **Statistical Analysis**: Enables performance comparison and optimization
- **Test Coverage**: Tracks which endpoints have been performance tested

### Security Test Results
```php
private array $securityTestResults = []
```
- **Security Compliance**: Tracks security header compliance across endpoints
- **Violation Tracking**: Records security violations and patterns
- **Coverage Analysis**: Monitors security testing coverage
- **Compliance Reporting**: Enables security compliance reporting

### Validation Test Results
```php
private array $validationTestResults = []
```
- **Validation Coverage**: Tracks validation testing across endpoints
- **Security Pattern Detection**: Records detected security patterns and violations
- **Response Time Tracking**: Monitors validation processing performance
- **Error Pattern Analysis**: Analyzes validation error patterns

## Comprehensive Test Reporting

### Test Report Generation
```php
public function generateTestReport(): array
```
- **Consolidated Results**: Combines all test results into comprehensive report
- **Summary Statistics**: Provides high-level test execution summary
- **Coverage Analysis**: Analyzes testing coverage across different areas
- **Execution Metadata**: Includes test execution timestamps and context

**Report Structure**:
```json
{
    "performance_tests": {...},
    "security_tests": {...},
    "validation_tests": {...},
    "summary": {
        "total_performance_tests": 5,
        "total_security_tests": 3,
        "total_validation_tests": 2,
        "test_execution_time": "2024-01-15 10:30:00"
    }
}
```

## Utility Methods

### Statistical Calculations
```php
private function calculateMedian(array $numbers): float
```
- **Performance Analysis**: Provides accurate median calculations for performance metrics
- **Statistical Accuracy**: Handles both even and odd number arrays correctly
- **Data Analysis**: Enables statistical analysis of test results

### Enhancement Scoring
```php
private function calculateEnhancementScore(array $features): float
```
- **Feature Assessment**: Calculates enhancement feature adoption percentage
- **Compliance Scoring**: Provides quantitative enhancement compliance measurement
- **Progress Tracking**: Enables tracking of enhancement implementation progress

## Integration with Existing Framework

### Codeception Integration
- **Module Extension**: Extends existing Codeception Module class
- **ApiTester Integration**: Seamlessly integrates with existing ApiTester functionality
- **Non-Destructive**: Does not modify existing test infrastructure
- **Backward Compatible**: Maintains compatibility with existing test suite

### Authentication Integration
- **OAuth2 Support**: Uses existing OAuth2 authentication methods
- **JWT Integration**: Leverages existing JWT authorization utilities
- **Session Management**: Works with existing session management system
- **API Key Testing**: Tests new API key authentication middleware

### Response Validation
- **JSON:API Compatibility**: Works with existing JSON:API response validation
- **Error Handling**: Integrates with existing error response testing
- **Content Negotiation**: Uses existing content negotiation utilities
- **Status Code Validation**: Leverages existing HTTP status code testing

## Usage Examples

### Basic Validation Testing
```php
$I = new ApiTester($scenario);
$helper = $I->getHelper('api');

$maliciousPayload = [
    'data' => [
        'attributes' => [
            'name' => '<script>alert("xss")</script>',
            'description' => 'DROP TABLE accounts;'
        ]
    ]
];

$result = $helper->testEnhancedValidation('/V8/module/Accounts', $maliciousPayload);
```

### Performance Testing
```php
$testCases = [
    'simple_list' => ['method' => 'GET'],
    'filtered_list' => ['method' => 'GET', 'params' => ['filter[name]' => 'test']],
    'create_account' => ['method' => 'POST', 'payload' => ['data' => [...]]]
];

$performance = $helper->measureEndpointPerformance('/V8/module/Accounts', $testCases, 10);
```

### Security Headers Testing
```php
$result = $helper->testSecurityHeaders('/V8/module/Accounts');
$compliancePercentage = $result['compliance_percentage'];
```

### Documentation Testing
```php
$expectedEndpoints = ['/V8/module/Accounts', '/V8/module/Contacts', '/V8/module/Leads'];
$docResult = $helper->testApiDocumentation('/V8/meta/swagger.json', $expectedEndpoints);
```

## Performance Considerations

### Test Execution Performance
- **Efficient Authentication**: Authenticates once per test method when possible
- **Minimal Overhead**: Designed for minimal impact on test execution time
- **Memory Management**: Efficient memory usage during performance testing
- **Statistical Accuracy**: Balances accuracy with execution speed

### Data Storage Optimization
- **In-Memory Storage**: Uses in-memory arrays for test execution efficiency
- **Selective Tracking**: Only tracks data when tests are executed
- **Memory Cleanup**: Proper memory management for long test suites
- **Efficient Serialization**: Optimized data serialization for reporting

## Security Considerations

### Test Data Security
- **Secure Test Credentials**: Uses secure test credentials for authentication
- **Payload Sanitization**: Ensures test payloads don't contain real sensitive data
- **Response Data Protection**: Protects sensitive response data in test results
- **Secure Reporting**: Ensures test reports don't expose sensitive information

### Testing Environment Security
- **Isolated Testing**: Designed for isolated test environment execution
- **Credential Management**: Proper handling of test credentials and API keys
- **Data Cleanup**: Ensures test data cleanup after execution
- **Security Pattern Testing**: Safely tests security patterns without exploitation

## Error Handling

### Test Execution Errors
- **Graceful Degradation**: Handles test failures gracefully without breaking test suite
- **Comprehensive Error Reporting**: Provides detailed error information for debugging
- **Exception Handling**: Proper exception handling for all test scenarios
- **Fallback Behavior**: Implements fallback behavior for failed tests

### Validation Error Testing
- **Expected Failures**: Properly handles expected validation failures
- **Error Response Validation**: Validates error response structure and content
- **Security Violation Handling**: Proper handling of intentional security violations
- **Error Pattern Recognition**: Recognizes and validates expected error patterns

## Future Enhancements

### Planned Improvements
- **Load Testing**: Enhanced load testing capabilities for stress testing
- **Security Scanning**: Integration with security scanning tools
- **Performance Benchmarking**: Performance benchmarking against baseline metrics
- **Automated Reporting**: Automated test report generation and distribution

### Extension Points
- **Custom Test Cases**: Support for custom test case implementations
- **Plugin Architecture**: Extensible architecture for custom testing plugins
- **Integration Points**: Additional integration points for external testing tools
- **Monitoring Integration**: Integration with application performance monitoring 