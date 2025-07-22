# PDFConfigurator.php Documentation

## @fileoverview
Configuration management class for SuiteCRM's PDF generation system. Provides fluent interface for setting and persisting PDF engine preferences and system-wide PDF configuration options through integration with SuiteCRM's Configurator system.

## @package
SuiteCRM\PDF

## @copyright
Copyright (C) 2011 - 2021 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Class Overview

### PDFConfigurator
Configuration manager that provides a fluent interface for managing PDF-related system settings. Integrates with SuiteCRM's core Configurator to persist PDF engine selections and configuration options across the system.

## Dependencies

### Internal Dependencies
- **Configurator**: SuiteCRM's core configuration management system
- **InvalidArgumentException**: Standard PHP exception for parameter validation

### Security
- Includes sugarEntry validation for secure access control

## Properties

### Private Properties
- **$configurator** (Configurator): SuiteCRM's configuration manager instance

## Methods

### __construct(Configurator $configurator = null)
**Purpose**: Initializes PDF configurator with optional dependency injection support.

**Parameters**:
- `$configurator` (Configurator|null): Optional Configurator instance for dependency injection

**Behavior**:
- Creates new Configurator instance if none provided
- Enables dependency injection for testing and customization
- Ensures configurator is always available for operations

**Design Pattern**: Supports dependency injection while providing sensible defaults.

### make(): PDFConfigurator
**Purpose**: Factory method providing fluent interface entry point for configuration.

**Returns**: New PDFConfigurator instance ready for chaining.

**Usage Pattern**: Enables clean, readable configuration syntax:
```php
PDFConfigurator::make()->setEngine('TCPDFEngine')->save();
```

### setEngine(string $engine): PDFConfigurator
**Purpose**: Configures the default PDF engine for system-wide use.

**Parameters**:
- `$engine` (string): PDF engine name to set as default

**Returns**: Self for method chaining in fluent interface.

**Behavior**:
- Validates engine name is not empty
- Sets pdf.defaultEngine configuration key
- Stores configuration in memory for later persistence

**Throws**: InvalidArgumentException if engine name is empty.

**Validation**: Ensures engine parameter meets basic requirements.

### save(): PDFConfigurator
**Purpose**: Persists current PDF configuration to system configuration files.

**Returns**: Self for method chaining continuation.

**Behavior**:
- Triggers Configurator::saveConfig() to write changes
- Persists all PDF configuration changes to sugar_config.override.php
- Makes configuration changes available system-wide immediately

**Side Effects**: Updates global configuration files on disk.

## Configuration Management

### Fluent Interface Design
- Method chaining for readable configuration syntax
- Self-returning methods for continued operation
- Clean separation of configuration and persistence

### System Integration
- Direct integration with SuiteCRM's core Configurator
- Consistent configuration storage with other system settings
- Immediate availability of configuration changes

### Validation Framework
- Input validation for configuration parameters
- Exception-based error handling for invalid inputs
- Type safety for configuration values

## Use Cases

### Administrative Configuration
- Setting default PDF engines through admin interfaces
- Bulk configuration updates during system setup
- Integration with system configuration workflows

### Module-Specific Configuration
- Module-specific PDF engine preferences
- Custom configuration for specialized PDF requirements
- Integration with module installation processes

### Programmatic Configuration
- Automated configuration during upgrades
- Script-based system configuration
- Testing and development environment setup

## Integration Points

### Core Configuration System
- Uses SuiteCRM's standard Configurator for persistence
- Maintains consistency with other system configurations
- Supports configuration caching and optimization

### PDF Engine Management
- Works with PDFWrapper for engine selection
- Validates engine configurations against available engines
- Supports dynamic engine registration and configuration

### Administrative Interfaces
- Backend for PDF configuration screens
- Integration with system administration workflows
- Support for configuration import/export operations

## Error Handling

### Input Validation
- Comprehensive validation of configuration parameters
- Clear error messages for invalid configurations
- Exception-based error reporting for integration

### Configuration Persistence
- Error handling for file system operations
- Validation of configuration write operations
- Rollback support for failed configuration changes

## Security Considerations

### Access Control
- sugarEntry validation for secure system access
- Integration with SuiteCRM's permission system
- Controlled access to configuration modification

### Configuration Integrity
- Validation of configuration values before persistence
- Protection against configuration corruption
- Secure handling of configuration data

## Best Practices

### Usage Patterns
- Use factory method for fluent interface entry
- Chain configuration methods for readability
- Always call save() to persist changes

### Error Handling
- Catch InvalidArgumentException for validation errors
- Verify configuration persistence success
- Implement appropriate fallback mechanisms

### Testing Support
- Dependency injection enables unit testing
- Mock configurator support for isolated testing
- Fluent interface supports test readability

## Performance Considerations

### Configuration Operations
- Minimal overhead for configuration changes
- Efficient integration with core Configurator
- Lazy persistence through explicit save() calls

### Memory Management
- Lightweight configuration object creation
- Efficient configuration storage and retrieval
- Minimal impact on system performance 