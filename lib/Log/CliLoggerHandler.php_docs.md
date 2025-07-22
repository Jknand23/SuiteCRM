# CliLoggerHandler.php Documentation

## @fileoverview
Specialized Monolog handler optimized for command-line interface logging operations. Extends StreamHandler to provide colorized terminal output with minimal overhead, specifically designed for CLI-based SuiteCRM tools and development workflows.

## @package
SuiteCRM\Log

## @copyright
Copyright (C) 2011 - 2018 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Class Overview

### CliLoggerHandler
Command-line optimized logging handler that extends Monolog's StreamHandler to provide enhanced terminal logging capabilities. Automatically configures stderr output with colorized formatting for improved CLI debugging and monitoring.

## Dependencies

### External Libraries
- **Monolog\Handler\StreamHandler**: Base stream handling for log output
- **Monolog\Logger**: Monolog logging level constants

### Internal Dependencies
- **SuiteCRM\Log\CliLoggerFormatter**: Specialized CLI formatting for colorized output

### Security
- Includes sugarEntry validation for secure access control

## Methods

### __construct(int $level = Logger::DEBUG)
**Purpose**: Configures CLI logging handler with stderr output and colorized formatting.

**Parameters**:
- `$level` (int): Minimum log level to handle, defaults to DEBUG for comprehensive logging

**Behavior**:
- Configures output stream to php://stderr for CLI compatibility
- Sets minimum logging level for handler operation
- Automatically applies CliLoggerFormatter for colorized output
- Enables immediate log visibility in terminal environments

**Throws**: Exception if stream configuration fails

## CLI Optimization Features

### Stream Configuration
- **Output Target**: php://stderr for standard CLI error output
- **Real-time Display**: Immediate log visibility in terminal
- **Buffering**: Minimal buffering for responsive feedback

### Formatting Integration
- **Automatic Formatter**: Pre-configured with CliLoggerFormatter
- **Color Support**: Built-in colorized output for level differentiation
- **Terminal Compatibility**: Optimized for various terminal environments

### Level Management
- **Default Level**: DEBUG level for comprehensive development logging
- **Configurable Threshold**: Adjustable minimum log level
- **Performance**: Efficient level filtering for production use

## Use Cases

### Development Environment
- Enhanced debugging experience for SuiteCRM development
- Real-time log monitoring during development workflows
- Improved error visibility during testing and debugging

### CLI Tools and Scripts
- User-friendly output for command-line utilities
- Progress monitoring for long-running operations
- Error reporting for automated scripts

### System Administration
- Enhanced monitoring capabilities for CLI-based administration
- Improved troubleshooting workflows
- Clear visual feedback for maintenance operations

## Integration Points

### SuiteCRM CLI Tools
- Optimized for SuiteCRM command-line utilities
- Integration with development and maintenance scripts
- Support for automated deployment and migration tools

### Development Workflow
- Enhanced developer experience during CLI operations
- Improved debugging capabilities for CLI-based development
- Better visibility of system operations and errors

### Monitoring and Debugging
- Real-time log monitoring during development
- Enhanced error tracking for CLI applications
- Improved troubleshooting capabilities

## Configuration Considerations

### Log Level Selection
- **Development**: DEBUG level for comprehensive logging
- **Production**: Higher levels (INFO/WARNING) for performance
- **Debugging**: DEBUG level for detailed troubleshooting

### Terminal Compatibility
- ANSI color support in most modern terminals
- Graceful degradation in color-limited environments
- Cross-platform compatibility considerations

### Performance Characteristics
- **Low Overhead**: Minimal processing for CLI output
- **Real-time Output**: Immediate log visibility
- **Efficient Filtering**: Level-based filtering for performance

## Best Practices

### Development Usage
- Use DEBUG level during active development
- Monitor stderr output for immediate feedback
- Leverage color coding for quick issue identification

### Production Deployment
- Consider higher log levels for production CLI tools
- Monitor performance impact of logging overhead
- Implement appropriate log level filtering

### Integration Guidelines
- Combine with appropriate log processors for enhanced context
- Configure alongside other handlers for comprehensive logging
- Use in development environments for enhanced debugging

## Security Features

### Access Control
- sugarEntry validation prevents unauthorized access
- Secure integration with SuiteCRM security model
- Protected logging infrastructure

### Information Security
- Stderr output for proper CLI error handling
- Maintains log separation from standard output
- Supports secure CLI application patterns 