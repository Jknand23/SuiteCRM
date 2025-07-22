# CliLoggerFormatter.php Documentation

## @fileoverview
Specialized Monolog formatter designed for command-line interface logging with colorized output and enhanced readability. Provides visual log level differentiation and improved debugging experience for CLI-based SuiteCRM operations.

## @package
SuiteCRM\Log

## @copyright
Copyright (C) 2011 - 2018 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Class Overview

### CliLoggerFormatter
Advanced CLI log formatter that implements Monolog's FormatterInterface to provide colorized, structured log output optimized for terminal environments. Features dynamic color coding, level-specific symbols, and multi-line message support.

## Dependencies

### External Libraries
- **Monolog\Formatter\FormatterInterface**: Standard Monolog formatter contract
- **Monolog\Logger**: Monolog logging level constants

### Security
- Includes sugarEntry validation for secure access control

## Properties

### Private Properties
- **$colors** (array): Color code mappings for terminal output
- **$format** (string): Log line format template with color placeholders
- **$padding** (string): Multi-line message padding format
- **$alwaysColourLine** (bool): Controls color application for all log levels

## Methods

### __construct()
**Purpose**: Initializes color schemes and formatting templates for CLI output.

**Behavior**:
- Sets up comprehensive color palette for terminal output
- Configures log line format with bold timestamps and level indicators
- Establishes multi-line padding format for structured output
- Enables color formatting by default for all log levels

### formatBatch(array $records): mixed
**Purpose**: Formats multiple log records for batch output operations.

**Parameters**:
- `$records` (array): Collection of Monolog log records to format

**Returns**: Formatted collection of log entries ready for terminal output.

**Behavior**:
- Iterates through record collection
- Applies individual formatting to each record
- Maintains consistent formatting across batch operations

### format(array $record): mixed
**Purpose**: Formats individual log records with colorized output and level-specific styling.

**Parameters**:
- `$record` (array): Monolog log record containing level, message, and metadata

**Returns**: Formatted log string with terminal color codes and styling.

**Behavior**:
- Determines appropriate color and symbol based on log level
- Applies colorization based on level severity and configuration
- Handles multi-line message formatting with consistent padding
- Includes timestamp and channel information in formatted output

### getColors(): array
**Purpose**: Defines comprehensive color palette for terminal output formatting.

**Returns**: Array mapping color names to terminal escape codes.

**Color Categories**:
- **Basic Colors**: white, gray, cyan, blue, green, yellow, purple, red
- **Light Variants**: cyan-light, blue-light, green-light, yellow-light, red-light
- **Background Colors**: bg-red, bg-red-light
- **Text Formatting**: bold, reverse, reset
- **Special**: gray-dark for subtle elements

### code(int $code): string
**Purpose**: Generates terminal escape sequences for color and formatting codes.

**Parameters**:
- `$code` (int): ANSI color/formatting code number

**Returns**: Terminal escape sequence string for specified formatting.

### getColourAndCode(int $level): string[]
**Purpose**: Maps log levels to appropriate colors and symbolic indicators.

**Parameters**:
- `$level` (int): Monolog log level constant

**Returns**: Array containing color code and level symbol.

**Level Mapping**:
- **DEBUG**: Blue color with '@' symbol
- **INFO**: Cyan color with '=' symbol
- **NOTICE**: Green color with '?' symbol
- **WARNING**: Yellow color with '*' symbol
- **ERROR**: Red color with '!' symbol
- **CRITICAL**: Light red background with '!' symbol
- **EMERGENCY**: Red background with '!' symbol

## CLI Features

### Visual Differentiation
- Color-coded log levels for immediate visual recognition
- Level-specific symbols for quick scanning
- Bold formatting for timestamps and important elements

### Multi-line Support
- Consistent indentation for multi-line messages
- Subtle visual separators for message continuation
- Maintains readability across complex log entries

### Terminal Compatibility
- Standard ANSI color codes for broad terminal support
- Configurable color application based on preferences
- Reset codes to prevent color bleeding between entries

## Configuration Options

### Color Control
- `$alwaysColourLine`: Controls whether all levels receive color formatting
- Default behavior colors all levels for enhanced readability
- Can be configured to color only warning-level and above

### Format Customization
- Configurable log line format template
- Customizable padding for multi-line messages
- Flexible color scheme adaptation

## Use Cases

### Development Debugging
- Enhanced readability for development logging
- Quick visual identification of error conditions
- Improved debugging experience for CLI operations

### System Administration
- Clear visual distinction between log levels
- Enhanced monitoring capabilities for CLI tools
- Improved troubleshooting workflows

### Automated Scripts
- Structured output for automated processing
- Consistent formatting for log analysis
- Visual feedback for long-running operations

## Integration Points

### CLI Applications
- Optimized for command-line SuiteCRM tools
- Enhanced user experience for CLI operations
- Integration with development and maintenance scripts

### Development Environment
- Improved debugging capabilities
- Enhanced developer experience
- Better visibility of system operations

### Terminal Environments
- Cross-platform terminal compatibility
- Support for various color schemes
- Adaptive formatting based on terminal capabilities 