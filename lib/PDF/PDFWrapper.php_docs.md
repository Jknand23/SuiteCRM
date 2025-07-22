# PDFWrapper.php Documentation

## @fileoverview
Central wrapper class that manages multiple PDF generation engines within SuiteCRM. Provides unified interface for PDF engine registration, selection, and instantiation, supporting both built-in engines (TCPDF, LegacyMPDF) and custom engine extensions.

## @package
SuiteCRM\PDF

## @copyright
Copyright (C) 2011 - 2021 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Class Overview

### PDFWrapper
Factory and registry class that abstracts PDF engine management for SuiteCRM. Handles engine registration, configuration-based selection, and dynamic loading of PDF generation libraries while maintaining extensibility for custom implementations.

## Dependencies

### Internal Dependencies
- **SuiteCRM\PDF\PDFEngine**: Abstract base class for PDF engines
- **SuiteCRM\PDF\TCPDF\TCPDFEngine**: TCPDF-based PDF generation engine
- **SuiteCRM\PDF\LegacyMPDF\LegacyMPDFEngine**: Legacy MPDF-based engine
- **SuiteCRM\PDF\Exceptions\PDFEngineNotFoundException**: Exception for missing engines

### Security
- Includes sugarEntry validation for secure access control

## Properties

### Static Properties
- **$engines** (array): Registry of available PDF engines with metadata

**Engine Registry Structure**:
```php
[
    'name' => 'EngineName',
    'lbl' => 'Language label key',
    'FQN' => 'Fully\Qualified\Name',
    'filepath' => 'path/to/engine/file.php'
]
```

## Methods

### addEngine(string $engineName, string $file, string $fqn): void
**Purpose**: Registers custom PDF engines in the system registry.

**Parameters**:
- `$engineName` (string): Unique identifier for the PDF engine
- `$file` (string): Relative file path to engine implementation
- `$fqn` (string): Fully qualified namespace and class name

**Behavior**:
- Adds engine to static registry for later instantiation
- Enables runtime registration of custom PDF engines
- Supports dynamic extension of PDF capabilities

### getPDFEngine(): PDFEngine
**Purpose**: Returns configured PDF engine instance based on system settings.

**Returns**: Instantiated PDFEngine subclass ready for use.

**Behavior**:
- Loads custom engine extensions from custom/application/Ext/PDF/
- Merges custom engines with built-in engine registry
- Uses default engine from configuration or first available
- Returns fully initialized engine instance

**Integration**: Primary entry point for obtaining PDF engines throughout SuiteCRM.

### getEngines(): array
**Purpose**: Retrieves list of available PDF engine names for configuration.

**Returns**: Array of engine names available for selection.

**Behavior**:
- Filters engines based on PHP version compatibility
- Removes LegacyMPDFEngine for PHP 8.0+ or missing MPDF library
- Loads custom engine extensions from filesystem
- Returns merged list of available engines

**Use Cases**: Configuration interfaces, engine selection dropdowns, validation.

### getDefaultEngine(): string
**Purpose**: Determines the default PDF engine from system configuration.

**Returns**: Engine name to use as default for PDF generation.

**Fallback Logic**:
- Returns configured engine from $sugar_config['pdf']['defaultEngine']
- Falls back to first engine in registry if no configuration exists
- Ensures system always has a default engine available

### getController(): ?string
**Purpose**: Retrieves configured PDF controller class from system settings.

**Returns**: PDF controller class name or null if not configured.

**Integration**: Supports advanced PDF processing workflows and custom controllers.

## Private Methods

### fetchEngine(string|PDFEngine $engineName): PDFEngine
**Purpose**: Instantiates and validates PDF engine implementations.

**Parameters**:
- `$engineName` (string|PDFEngine): Engine identifier or existing instance

**Returns**: Validated PDFEngine instance.

**Validation Process**:
- Checks if input is already a PDFEngine instance
- Validates engine exists in registry
- Verifies engine file exists and is readable
- Confirms class implements PDFEngine interface
- Returns new instance of validated engine

**Throws**: PDFEngineNotFoundException for invalid engines or missing files.

### getPDFConfig(string $key): mixed
**Purpose**: Retrieves PDF-specific configuration values from global settings.

**Parameters**:
- `$key` (string): Configuration key to retrieve

**Returns**: Configuration value or null if not set.

**Configuration Access**: Provides centralized access to $sugar_config['pdf'] settings.

## Engine Management

### Built-in Engines
- **TCPDFEngine**: Modern TCPDF-based PDF generation with advanced features
- **LegacyMPDFEngine**: Legacy MPDF support for backward compatibility

### Custom Engine Support
- Dynamic loading from custom/application/Ext/PDF/ directory
- Runtime registration through addEngine() method
- Extensible architecture for third-party PDF libraries

### Version Compatibility
- Automatic filtering of incompatible engines
- PHP version checking for engine availability
- Graceful handling of missing dependencies

## Configuration Integration

### System Configuration
- Integrates with SuiteCRM's global configuration system
- Supports admin-configurable default engines
- Respects user preferences and system policies

### Extension Framework
- Loads custom engine definitions from extension directory
- Supports module-based PDF engine extensions
- Enables third-party PDF engine integration

## Error Handling

### Engine Validation
- Comprehensive validation of engine implementations
- Clear error messages for missing or invalid engines
- Graceful fallback to available engines

### File System Checks
- Validates engine file existence before loading
- Handles missing dependencies gracefully
- Provides detailed error context for troubleshooting

## Use Cases

### PDF Template Generation
- Document generation for quotes, invoices, reports
- Customizable PDF output for various modules
- Multi-format support through different engines

### Custom PDF Solutions
- Integration of specialized PDF libraries
- Custom formatting and layout requirements
- Third-party PDF service integration

### System Administration
- Configuration of default PDF engines
- Management of PDF generation capabilities
- Monitoring and troubleshooting PDF operations

## Performance Considerations

### Engine Instantiation
- Lazy loading of PDF engines for optimal performance
- Caching of engine instances where appropriate
- Minimal overhead for engine selection

### Configuration Access
- Efficient configuration retrieval
- Minimal file system operations
- Optimized engine registry management 