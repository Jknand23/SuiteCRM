# SugarLoggerHandler.php Documentation

## @fileoverview
Monolog handler that integrates modern PSR-3 logging with SuiteCRM's legacy LoggerManager system. Provides seamless bridge between Monolog logging infrastructure and SuiteCRM's traditional logging mechanisms.

## @package
SuiteCRM\Log

## @copyright
Copyright (C) 2011 - 2018 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Class Overview

### SugarLoggerHandler
Bridge handler that extends Monolog's AbstractProcessingHandler to integrate modern logging capabilities with SuiteCRM's existing LoggerManager infrastructure. Converts PSR-3 log levels to SuiteCRM-compatible logging levels.

## Dependencies

### External Libraries
- **Monolog\Handler\AbstractProcessingHandler**: Base Monolog handler for log processing
- **LoggerManager**: SuiteCRM's legacy logging management system

### Security
- Includes sugarEntry validation for secure access control

## Methods

### write(array $record): void
**Purpose**: Processes log records and forwards them to SuiteCRM's LoggerManager system.

**Parameters**:
- `$record` (array): Monolog log record containing message, level, and channel information

**Behavior**:
- Extracts message, level, and channel from Monolog record
- Converts PSR-3 log level to SuiteCRM log level
- Forwards formatted log entry to LoggerManager
- Prefixes message with channel information for context

**Integration**: Connects modern Monolog logging with legacy SuiteCRM logging infrastructure.

### psrToSugarLevel(int $level): string
**Purpose**: Converts Monolog/PSR-3 numeric log levels to SuiteCRM string-based log levels.

**Parameters**:
- `$level` (int): Numeric PSR-3 log level (100-600 range)

**Returns**: String log level compatible with SuiteCRM's LoggerManager.

**Level Mapping**:
- 100 (DEBUG) → 'debug'
- 200 (INFO) → 'info'
- 300 (WARNING) → 'warn'
- 400 (ERROR) → 'error'
- 500 (CRITICAL) → 'fatal'
- 550/600 (ALERT/EMERGENCY) → 'security'
- Default → 'debug'

## Logging Integration

### PSR-3 Compatibility
- Maintains PSR-3 logging standard compliance
- Supports standard log level hierarchy
- Enables modern logging framework integration

### Legacy System Bridge
- Preserves compatibility with existing SuiteCRM logging
- Maintains log format consistency across system
- Supports existing log analysis tools and processes

### Channel Support
- Preserves Monolog channel information in log entries
- Enables log categorization and filtering
- Supports component-specific logging contexts

## Security Features

### Access Control
- Validates sugarEntry for secure system access
- Prevents unauthorized access to logging infrastructure
- Maintains system security boundaries

### Log Level Security
- Maps high-priority levels (ALERT/EMERGENCY) to 'security' category
- Ensures security events receive appropriate priority
- Supports security monitoring and alerting

## Use Cases

### Modern Framework Integration
- Enables use of modern logging libraries with SuiteCRM
- Supports dependency injection of logging services
- Facilitates testing with mock logging frameworks

### Legacy System Compatibility
- Maintains existing log file formats and locations
- Preserves compatibility with monitoring tools
- Supports gradual migration to modern logging

### Multi-Component Logging
- Handles logging from multiple system components
- Supports component identification through channels
- Enables unified logging across diverse subsystems

## Implementation Details

### Error Handling
- Graceful handling of malformed log records
- Default fallback to 'debug' level for unknown levels
- Maintains logging stability during error conditions

### Performance Considerations
- Minimal processing overhead for log level conversion
- Efficient integration with existing LoggerManager
- Optimized for high-volume logging scenarios

## Integration Points

### Monolog Framework
- Extends standard Monolog handler infrastructure
- Supports Monolog processor and formatter chains
- Enables advanced Monolog features and plugins

### SuiteCRM Core
- Integrates with existing LoggerManager configuration
- Respects system logging settings and policies
- Maintains compatibility with log rotation and management

### Development Tools
- Supports debugging and development logging
- Enables testing with modern logging frameworks
- Facilitates log analysis and monitoring tools 