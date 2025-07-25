# EnhancedErrorResponse.php Documentation

/**
 * @fileoverview Enhanced Error Response for SuiteCRM V8 API extending the existing
 * ErrorResponse class with comprehensive error handling, enhanced logging, structured
 * error data, and improved debugging capabilities while maintaining JSON:API compliance.
 * @package SuiteCRM.Api.V8.JsonApi.Response
 * @copyright SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `EnhancedErrorResponse.php` file, located in `Api/V8/JsonApi/Response/`, defines the `EnhancedErrorResponse` class. This class extends the existing `ErrorResponse` class with comprehensive error handling, enhanced logging, structured error data, and improved debugging capabilities while maintaining full compatibility with the existing JSON:API error format.

### Key Responsibilities
- Enhanced error structure with metadata and context
- Comprehensive error logging with structured data
- Request correlation IDs for error tracking
- Security-conscious error message filtering
- Integration with existing error handling patterns
- Backward compatibility with existing ErrorResponse

## Database Operations

### No Direct Database Operations
This class does not perform direct database operations but leverages existing infrastructure:
- **Configuration Access**: Uses existing API configuration and debug settings
- **Logging**: Integrates with existing SuiteCRM logging infrastructure
- **Session Integration**: Works with existing session management for user context

## Internal API Calls

### Enhanced Error Context Management
- **Exception Handling**: Comprehensive exception processing and context extraction
- **Request Context**: Automatic request context gathering from server variables
- **User Context**: Integration with existing authentication and session systems
- **Memory Tracking**: Automatic memory usage and performance monitoring

### Logging Infrastructure Integration
- **Structured Logging**: Provides structured data for existing logging systems
- **Severity Mapping**: Maps error severity to appropriate log levels
- **Context Filtering**: Filters sensitive data from log output
- **Performance Tracking**: Includes performance metrics in error context

## Enhanced Error Response Methods

### Constructor and Initialization
```php
public function __construct($debugExceptions = null, ?string $requestId = null)
```
- **Parent Compatibility**: Maintains full compatibility with existing ErrorResponse
- **Request Correlation**: Supports request correlation IDs for error tracking
- **Debug Mode**: Respects existing debug mode settings
- **Context Initialization**: Sets up error context and metadata structures

### Error Context Management
```php
public function setErrorContext(Exception $exception, array $additionalContext = [], string $severity = 'medium'): self
```
- **Comprehensive Context**: Builds detailed error context from exception and request
- **Severity Management**: Supports error severity levels (low, medium, high, critical)
- **Automatic Logging**: Automatically logs errors with comprehensive context
- **Fluent Interface**: Provides fluent interface for method chaining

### Severity Management
```php
public function setSeverity(string $severity): self
public function getSeverity(): string
```
- **Severity Levels**: Supports low, medium, high, and critical severity levels
- **Validation**: Validates severity levels against allowed values
- **Default Handling**: Provides sensible defaults for invalid severity values
- **Logging Integration**: Maps severity to appropriate logging levels

### Metadata Management
```php
public function setMetadata(array $metadata): self
public function addMetadata(string $key, $value): self
public function getMetadata(): array
```
- **Custom Metadata**: Supports custom metadata for error analysis
- **Flexible Structure**: Allows arbitrary metadata structure
- **Fluent Interface**: Provides convenient metadata management
- **Integration Support**: Supports integration with monitoring systems

### Context and Correlation
```php
public function getRequestId(): string
public function getContext(): array
```
- **Request Correlation**: Provides unique request correlation IDs
- **Context Access**: Allows access to comprehensive error context
- **Debugging Support**: Facilitates error debugging and analysis
- **Monitoring Integration**: Supports error monitoring and alerting

## Comprehensive Logging System

### Automatic Error Logging
```php
private function logError(Exception $exception): void
```
- **Severity-Based Logging**: Maps error severity to appropriate log levels
- **Comprehensive Context**: Includes full error context in log entries
- **Duplicate Prevention**: Prevents duplicate logging of the same error
- **Performance Tracking**: Includes performance metrics in log entries

### Log Message Construction
```php
private function buildLogMessage(Exception $exception): string
private function buildLogContext(Exception $exception): array
```
- **Structured Messages**: Creates consistent, structured log messages
- **Request Correlation**: Includes request correlation IDs in log messages
- **Context Aggregation**: Builds comprehensive log context from all sources
- **Security Filtering**: Filters sensitive data from log context

### Stack Trace Management
```php
private function getTraceSmary(Exception $exception): array
```
- **Abbreviated Traces**: Provides manageable stack trace summaries
- **Performance Optimization**: Limits trace depth for log readability
- **Context Preservation**: Maintains essential debugging information
- **Memory Efficiency**: Optimizes memory usage for large stack traces

### Log Level Mapping
```php
private function getLogLevelForSeverity(string $severity): string
```
- **Severity Mapping**: Maps error severity to logging levels
- **Consistent Levels**: Provides consistent log level usage
- **Integration Support**: Supports existing logging infrastructure
- **Monitoring Compatibility**: Compatible with log monitoring systems

## Request and User Context

### Automatic Context Collection
```php
private function addRequestContext(): void
```
- **HTTP Context**: Automatically collects HTTP request information
- **User Context**: Integrates with existing authentication systems
- **Server Context**: Includes relevant server environment information
- **Privacy Respect**: Respects user privacy and data protection requirements

### Context Filtering and Security
```php
private function filterSensitiveData(string $message): string
private function filterDebugContext(array $context): array
```
- **Sensitive Data Protection**: Automatically filters sensitive information
- **Security Patterns**: Recognizes and filters common sensitive patterns
- **Debug Mode Respect**: Respects debug mode settings for context inclusion
- **Privacy Compliance**: Supports privacy and compliance requirements

### Request Correlation Management
```php
private function generateRequestId(): string
```
- **Unique Identifiers**: Generates cryptographically secure request IDs
- **Collision Avoidance**: Uses high-entropy generation for uniqueness
- **Debugging Support**: Provides consistent correlation across systems
- **Monitoring Integration**: Supports distributed tracing and monitoring

## Enhanced JSON Serialization

### Comprehensive Error Data
```php
public function jsonSerialize(): array
```
- **Parent Compatibility**: Maintains compatibility with existing ErrorResponse
- **Enhanced Metadata**: Adds comprehensive metadata to error responses
- **Request Correlation**: Includes request correlation information
- **Context Integration**: Includes filtered context in debug mode

### Response Structure Enhancement
- **Standard Compliance**: Maintains JSON:API specification compliance
- **Metadata Enrichment**: Adds enhanced metadata for error analysis
- **Correlation Support**: Includes correlation IDs for error tracking
- **Debug Information**: Provides appropriate debug information based on settings

### Security and Privacy
- **Data Filtering**: Filters sensitive data from client responses
- **Context Limitation**: Limits context inclusion based on debug settings
- **Privacy Protection**: Protects user privacy in error responses
- **Security Compliance**: Maintains security best practices

## Error Severity System

### Severity Levels
- **Low**: Minor issues, informational errors, user input errors
- **Medium**: Standard application errors, business logic failures
- **High**: System errors, integration failures, security issues
- **Critical**: System failures, data corruption, security breaches

### Severity Mapping to Log Levels
```php
private function getLogLevelForSeverity(string $severity): string
```
- **Low → Info**: Informational logging for minor issues
- **Medium → Warning**: Warning level for standard errors
- **High → Error**: Error level for significant issues
- **Critical → Critical**: Critical level for system failures

### Automatic Severity Detection
- **Exception Analysis**: Analyzes exception types for severity hints
- **Context Evaluation**: Considers error context for severity determination
- **Default Assignment**: Provides sensible defaults for unknown cases
- **Override Support**: Allows manual severity specification

## Performance and Memory Management

### Memory Tracking
- **Usage Monitoring**: Tracks memory usage during error processing
- **Peak Detection**: Records peak memory usage for analysis
- **Context Inclusion**: Includes memory metrics in error context
- **Performance Analysis**: Supports performance problem identification

### Processing Efficiency
- **Lazy Initialization**: Initializes context data only when needed
- **Efficient Logging**: Optimizes logging operations for performance
- **Memory Cleanup**: Manages memory usage during error processing
- **Resource Management**: Efficient resource usage and cleanup

### Context Size Management
- **Context Limiting**: Limits context size for memory efficiency
- **Data Truncation**: Truncates large context data appropriately
- **Selective Inclusion**: Includes only relevant context information
- **Performance Optimization**: Optimizes for both memory and performance

## Integration and Compatibility

### Backward Compatibility
- **ErrorResponse Extension**: Extends existing ErrorResponse without breaking changes
- **Method Preservation**: Preserves all existing ErrorResponse methods
- **JSON Compatibility**: Maintains JSON:API response format compatibility
- **Debug Mode Respect**: Respects existing debug mode configurations

### Enhanced Features
- **Request Correlation**: Adds request correlation without breaking existing features
- **Enhanced Logging**: Provides enhanced logging while maintaining compatibility
- **Metadata Support**: Adds metadata support without affecting existing functionality
- **Context Enhancement**: Enhances context without breaking existing patterns

### Monitoring Integration
- **Structured Data**: Provides structured data for monitoring systems
- **Correlation Support**: Supports distributed tracing and correlation
- **Alerting Integration**: Compatible with existing alerting systems
- **Analytics Support**: Provides data for error analytics and reporting

## Security Considerations

### Data Protection
- **Sensitive Filtering**: Automatically filters sensitive data patterns
- **Privacy Compliance**: Respects user privacy and data protection
- **Context Limitation**: Limits context exposure in production
- **Security Logging**: Provides appropriate security event logging

### Error Information Disclosure
- **Debug Mode Control**: Controls error information disclosure based on debug settings
- **Production Safety**: Limits error information in production environments
- **Client Protection**: Protects clients from sensitive server information
- **Security Best Practices**: Follows security best practices for error handling

### Logging Security
- **Structured Logging**: Uses structured logging for security analysis
- **Context Filtering**: Filters security-sensitive context information
- **Audit Trail**: Provides comprehensive audit trail for security events
- **Monitoring Support**: Supports security monitoring and alerting systems 