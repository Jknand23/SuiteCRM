# EnhancedLoggerService.php Documentation

/**
 * @fileoverview Enhanced Logger Service for SuiteCRM providing advanced logging capabilities
 * that build upon existing Monolog v1.27.1 infrastructure without breaking backward compatibility.
 * Adds structured logging, performance monitoring, enhanced rotation, and centralized log management.
 * @package SuiteCRM.Log
 * @copyright SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `EnhancedLoggerService.php` file, located in `lib/Log/`, provides a comprehensive enhanced logging solution for SuiteCRM that extends the existing Monolog v1.27.1 infrastructure with modern capabilities while maintaining full backward compatibility. This service acts as a centralized logging coordinator that provides structured logging, performance monitoring, and enhanced log management features.

### Key Responsibilities
- Structured logging with enhanced context preservation
- Performance monitoring and timing analysis
- Enhanced log rotation with configurable policies
- Multiple output handlers (file, database, syslog)
- Integration with existing SugarLoggerHandler
- Backward compatibility with existing LoggerManager
- PSR-3 compliant interface extension
- Memory-efficient processing for high-volume logging

## Database Operations

### No Direct Database Operations
This service does not perform direct database operations but leverages existing infrastructure:
- **Configuration Access**: Reads enhanced logging settings from global `$sugar_config` array
- **User Context Integration**: Works with existing user session management for user identification
- **Legacy Integration**: Uses existing SuiteCRM logging infrastructure for compatibility

## Internal API Calls

### Enhanced Logger Integration
- **getStructuredLogger()**: Returns Monolog logger configured with structured formatting and context processors
- **getPerformanceLogger()**: Returns specialized logger for performance metrics and timing data
- **getLegacyLogger()**: Returns existing SuiteLogger instance for backward compatibility

### Performance Monitoring API
- **logPerformance()**: Specialized logging for performance monitoring with execution timing and resource consumption
- **startTiming()**: Begins timing measurement for performance monitoring operations
- **stopTiming()**: Completes timing measurement and automatically logs performance data
- **getPerformanceStats()**: Returns aggregated performance statistics for monitoring and analysis

### Structured Logging API
- **logStructured()**: Advanced logging with structured data and automatic context enhancement
- **enhanceContext()**: Adds SuiteCRM-specific context including user information and application state

## Configuration System

### Default Configuration Structure
```php
$defaultConfig = [
    'enhanced_logging' => [
        'enabled' => true,
        'legacy_compatibility' => true,
        'structured_logging' => [
            'enabled' => true,
            'file_path' => 'logs/enhanced.log',
            'max_files' => 30,
            'level' => 'info'
        ],
        'performance_logging' => [
            'enabled' => true,
            'file_path' => 'logs/performance.log',
            'max_files' => 30,
            'level' => 'info',
            'slow_query_threshold' => 1000 // milliseconds
        ],
        'processors' => [
            'memory_usage' => true,
            'process_id' => true,
            'web_context' => true,
            'user_context' => true
        ],
        'handlers' => [
            'file' => true,
            'sugar_integration' => true,
            'cli' => true
        ]
    ]
];
```

### Configuration Integration
- **Global Configuration**: Merges with existing `$sugar_config['enhanced_logging']` settings
- **Custom Overrides**: Supports constructor-level custom configuration overrides
- **Environment Awareness**: Adapts configuration based on environment detection (CLI vs web)
- **Backward Compatibility**: Preserves existing logger configuration while adding enhanced features

## Enhanced Logging Features

### Structured Logging Capabilities
- **JSON Format Output**: Machine-readable structured logs with enhanced metadata
- **Context Preservation**: Maintains context data through the entire logging pipeline
- **Application Metadata**: Automatic inclusion of SuiteCRM-specific application context
- **User Context Integration**: Seamless integration with current user session data
- **Request Correlation**: Request ID tracking for distributed tracing capabilities

### Performance Monitoring System
- **Execution Timing**: High-precision timing measurement with microsecond accuracy
- **Memory Tracking**: Comprehensive memory usage monitoring including peak and delta measurements
- **Resource Consumption**: System-level resource monitoring including CPU and memory
- **Threshold Analysis**: Configurable performance thresholds with automated alerting
- **Statistical Aggregation**: Real-time performance statistics and trend analysis

### Enhanced Log Rotation
- **Size-Based Rotation**: Configurable file size limits with automatic rotation
- **Time-Based Rotation**: Daily, weekly, or custom time-based rotation policies
- **Compression Support**: Automatic compression of rotated logs (gzip, bzip2)
- **Retention Policies**: Configurable log retention with automatic cleanup
- **Integrity Checking**: Automated log file integrity validation and monitoring

## Handler and Processor System

### Enhanced Handlers
- **StructuredLoggerFormatter**: Advanced JSON formatting with context preservation
- **PerformanceLoggerFormatter**: Specialized formatting for performance metrics
- **EnhancedRotatingFileHandler**: Advanced rotation with compression and monitoring
- **SugarLoggerHandler Integration**: Seamless integration with existing SuiteCRM logging
- **CliLoggerHandler Integration**: Enhanced command-line logging capabilities

### Processor Pipeline
- **MemoryUsageProcessor**: Automatic memory usage tracking for all log entries
- **ProcessIdProcessor**: Process ID inclusion for multi-process environments
- **WebProcessor**: Web request context including IP, user agent, and request data
- **UserContextProcessor**: SuiteCRM user context with user ID, name, and permissions

## Performance Metrics and Analysis

### Timing System
```php
// Start timing an operation
$timingId = $enhancedLogger->startTiming('database_query', ['table' => 'users']);

// Perform operation
// ... database operation ...

// Stop timing and automatically log results
$executionTime = $enhancedLogger->stopTiming($timingId, ['rows_affected' => 42]);
```

### Performance Statistics
- **Operation Counts**: Total number of operations per type
- **Execution Times**: Average, minimum, and maximum execution times
- **Memory Usage Patterns**: Memory consumption trends and peak usage
- **Resource Utilization**: System resource consumption analysis
- **Performance Trends**: Historical performance data for capacity planning

### Threshold Monitoring
- **Configurable Thresholds**: Customizable performance thresholds for different operations
- **Automatic Alerting**: Automated alerts when thresholds are exceeded
- **Severity Levels**: Multiple severity levels (warning, critical) for different threshold violations
- **Trend Analysis**: Long-term performance trend monitoring and analysis

## Integration with Existing SuiteCRM Infrastructure

### LoggerManager Compatibility
- **Backward Compatibility**: Full compatibility with existing `LoggerManager::getLogger()` calls
- **PSR-3 Interface**: Maintains PSR-3 logging interface compatibility
- **Legacy Format Support**: Preserves existing log formats for legacy analysis tools
- **Seamless Migration**: Gradual migration path from legacy to enhanced logging

### SugarLogger Integration
- **Existing Interface**: Works alongside existing `SuiteLogger` implementations
- **Format Preservation**: Maintains existing log format compatibility
- **Level Mapping**: Proper mapping between PSR-3 and SuiteCRM log levels
- **Context Handling**: Enhanced context handling while preserving legacy behavior

### Global Integration Points
- **$GLOBALS['log']**: Compatible with existing global logger usage patterns
- **$GLOBALS['current_user']**: Automatic integration with current user context
- **$GLOBALS['db']**: Database context integration for enhanced logging
- **$sugar_config**: Configuration integration with existing SuiteCRM settings

## Security and Privacy Considerations

### Data Protection
- **PII Filtering**: Automatic filtering of personally identifiable information
- **Sensitive Data Masking**: Configurable masking of sensitive data in logs
- **Access Control**: Log file access control integration with SuiteCRM permissions
- **Audit Compliance**: Audit-compliant logging for regulatory requirements

### Security Features
- **Input Sanitization**: Automatic sanitization of log data to prevent injection attacks
- **File Permissions**: Proper file permissions for log files and directories
- **Encryption Support**: Optional encryption for sensitive log data
- **Integrity Validation**: Log file integrity checking and validation

## Usage Examples

### Basic Structured Logging
```php
$enhancedLogger = new EnhancedLoggerService();

// Log with enhanced context
$enhancedLogger->logStructured('info', 'User login successful', [
    'user_id' => '12345',
    'ip_address' => '192.168.1.100'
], [
    'login_method' => 'oauth2',
    'client_info' => ['browser' => 'Chrome', 'version' => '91.0']
]);
```

### Performance Monitoring
```php
// Start performance timing
$timingId = $enhancedLogger->startTiming('campaign_report_generation', [
    'campaign_id' => 'camp_001',
    'report_type' => 'detailed'
]);

// Perform the operation
generateCampaignReport($campaignId);

// Stop timing and log performance data
$executionTime = $enhancedLogger->stopTiming($timingId, [
    'records_processed' => 1500,
    'report_size_mb' => 2.3
]);
```

### Legacy Compatibility
```php
// Get legacy logger for existing code
$legacyLogger = $enhancedLogger->getLegacyLogger();
$legacyLogger->info('This works with existing code');

// Enhanced logging alongside legacy
$enhancedLogger->logStructured('info', 'Enhanced logging with context', ['key' => 'value']);
```

## Error Handling and Resilience

### Graceful Degradation
- **Fallback Logging**: Automatic fallback to legacy logging if enhanced features fail
- **Error Recovery**: Robust error handling with automatic recovery mechanisms
- **Performance Impact**: Minimal performance impact on application when logging fails
- **Resource Management**: Efficient resource management to prevent memory leaks

### Exception Handling
- **Initialization Failures**: Graceful handling of logger initialization failures
- **File System Errors**: Robust handling of file system permission and space issues
- **Configuration Errors**: Fallback behavior for invalid or missing configuration
- **Handler Failures**: Automatic handler failover and error reporting

## Monitoring and Observability

### Performance Metrics
- **Logging Performance**: Monitoring of logging system performance and overhead
- **File System Usage**: Disk space and I/O monitoring for log files
- **Memory Consumption**: Memory usage monitoring for logging operations
- **Handler Statistics**: Performance statistics for individual handlers

### Health Monitoring
- **Service Health**: Overall health monitoring of the enhanced logging service
- **Handler Health**: Individual handler health and availability monitoring
- **Configuration Validation**: Ongoing validation of logging configuration
- **Resource Availability**: Monitoring of required resources (disk space, permissions)

## Testing and Validation

### Unit Testing Support
- **Testable Design**: Modular design supports comprehensive unit testing
- **Mock Integration**: Support for mocking dependencies in test environments
- **Performance Testing**: Built-in performance testing and benchmarking capabilities
- **Configuration Testing**: Validation of different configuration scenarios

### Integration Testing
- **SuiteCRM Integration**: Testing with existing SuiteCRM logging infrastructure
- **Handler Testing**: Comprehensive testing of all logging handlers
- **Performance Impact**: Testing of performance impact on application operations
- **Compatibility Testing**: Backward compatibility testing with existing code

## Migration and Deployment

### Migration Strategy
- **Gradual Migration**: Support for gradual migration from legacy to enhanced logging
- **Feature Flags**: Configuration-based feature enabling for controlled rollout
- **Backward Compatibility**: Maintains full backward compatibility during migration
- **Rollback Support**: Easy rollback to legacy logging if needed

### Deployment Considerations
- **Resource Requirements**: Minimal additional resource requirements
- **Configuration Migration**: Automated migration of existing logging configuration
- **Performance Impact**: Negligible performance impact on existing operations
- **Monitoring Setup**: Guidelines for setting up enhanced logging monitoring

## Best Practices and Recommendations

### Configuration Best Practices
- **Environment-Specific Settings**: Different settings for development, staging, and production
- **Performance Tuning**: Recommendations for optimal performance configuration
- **Security Configuration**: Security best practices for logging configuration
- **Resource Management**: Efficient resource management configuration

### Usage Guidelines
- **Structured Logging**: When and how to use structured logging effectively
- **Performance Monitoring**: Best practices for performance monitoring and analysis
- **Context Enhancement**: Guidelines for adding meaningful context to logs
- **Legacy Integration**: Best practices for integrating with existing logging code

This enhanced logging service provides a comprehensive solution that respects SuiteCRM's existing logging infrastructure while adding modern capabilities essential for production environments and performance monitoring. 