# SearchConfigurator.php Documentation

/**
 * @fileoverview SearchConfigurator class - Fluent configuration API for SuiteCRM search framework settings, engine selection, and configuration persistence
 * @package SuiteCRM\Search
 * @copyright SalesAgility Ltd  
 * @license AGPL-3.0
 */

## Overview

The SearchConfigurator class provides a fluent interface for configuring the SuiteCRM search framework. It handles search engine selection, controller configuration, and AOD (Advanced OpenDiscovery) settings through a chainable API that simplifies search system setup and configuration management.

## Class Structure

```php
namespace SuiteCRM\Search;

class SearchConfigurator
{
    private $configurator;  // Core Configurator instance
    
    public function __construct(Configurator $configurator = null)
    public static function make(): SearchConfigurator
    public function setEngine(string $engine): SearchConfigurator  
    public function save(): SearchConfigurator
}
```

## Core Functionality

### Configuration Management

#### Constructor and Factory
- **Constructor**: `__construct(Configurator $configurator = null)`
  - Accepts optional Configurator dependency injection
  - Creates new Configurator instance if none provided
  - Enables testing and custom configuration scenarios

- **make()**: Static factory method for fluent syntax
  - Returns new SearchConfigurator instance
  - Enables method chaining from instantiation
  - Preferred method for most use cases

#### Fluent Interface Design
- All configuration methods return `$this` for chaining
- Enables readable configuration syntax
- Separates configuration building from persistence

### Search Engine Configuration

#### Engine Selection Logic
- **setEngine()**: Configures search framework based on engine type
  - Validates engine parameter (throws InvalidArgumentException if empty)
  - Sets appropriate search controller and default engine
  - Configures AOD enablement based on engine requirements

#### Supported Engine Types

**BasicSearchEngine**:
- Uses 'UnifiedSearch' controller
- Basic search functionality only
- AOD disabled (`$config['aod']['enable_aod'] = false`)

**BasicAndAodEngine**:  
- Uses 'UnifiedSearch' controller
- Enables AOD search capabilities
- AOD enabled (`$config['aod']['enable_aod'] = true`)

**Custom Engines** (ElasticSearchEngine, LuceneSearchEngine, etc.):
- Uses 'Search' controller (SearchWrapper)
- Engine-specific search implementation
- AOD disabled by default
- Custom engine handling via SearchWrapper

#### Configuration Targets
- **Search Controller**: `$config['search']['controller']`
  - 'UnifiedSearch' for legacy search system
  - 'Search' for SearchWrapper-based engines

- **Default Engine**: `$config['search']['defaultEngine']`
  - Stores selected engine class name
  - Used by SearchQuery for engine resolution

- **AOD Setting**: `$config['aod']['enable_aod']`
  - Boolean flag for Advanced OpenDiscovery features
  - Affects search indexing and capabilities

### Configuration Persistence

#### Save Operation
- **save()**: Persists configuration changes
  - Calls underlying Configurator::saveConfig()
  - Writes changes to SuiteCRM configuration files
  - Returns $this for continued method chaining

## Input Validation

### Parameter Validation
- **Engine Name Validation**: Ensures non-empty string input
- **Error Handling**: Throws InvalidArgumentException for invalid parameters
- **Type Safety**: String type hints for engine parameter

## Integration Points

### Core Framework Integration
- **Configurator Class**: Leverages existing configuration management
- **Global Configuration**: Updates `$sugar_config` arrays
- **Search Framework**: Integrates with SearchQuery and SearchWrapper

### Search System Components
- **SearchQuery**: Reads defaultEngine configuration
- **SearchWrapper**: Used for custom engine implementations  
- **UnifiedSearch**: Legacy search controller system
- **AOD System**: Advanced search and indexing features

## Configuration Architecture

### Layered Configuration
- **Framework Level**: Search controller selection
- **Engine Level**: Specific search implementation  
- **Feature Level**: AOD and other search features

### Configuration Flow
1. Engine selection via setEngine()
2. Controller and AOD determination
3. Configuration update in memory
4. Persistence via save() method

## Usage Patterns

### Basic Engine Configuration
```php
SearchConfigurator::make()
    ->setEngine('BasicSearchEngine')
    ->save();
```

### Advanced Engine Setup
```php
$configurator = new SearchConfigurator();
$configurator->setEngine('ElasticSearchEngine')
             ->save();
```

### AOD-Enabled Configuration
```php
SearchConfigurator::make()
    ->setEngine('BasicAndAodEngine')
    ->save();
```

### Testing with Dependency Injection
```php
$mockConfigurator = new MockConfigurator();
$searchConfig = new SearchConfigurator($mockConfigurator);
$searchConfig->setEngine('TestEngine')->save();
```

## Error Handling

### Validation Errors
- **InvalidArgumentException**: Thrown for empty engine names
- **Early Validation**: Parameter checking before configuration changes
- **Clear Error Messages**: Descriptive exception messages

### Configuration Errors  
- **Save Failures**: Handled by underlying Configurator class
- **File Permission Issues**: Managed by core configuration system
- **Graceful Degradation**: Method chaining continues after non-fatal errors

## Dependencies

### Core Dependencies
- **Configurator**: SuiteCRM core configuration management class
- **InvalidArgumentException**: Standard PHP exception for parameter validation

### Framework Integration
- Configuration file system for persistence
- Global $sugar_config array for runtime settings
- Search framework components for engine resolution

## Extension Points

### Custom Engine Support
- **Open Architecture**: Supports any engine name
- **SearchWrapper Integration**: Custom engines via Search controller
- **Flexible Configuration**: Engine-specific settings via options

### Future Enhancement
- **Additional Configuration Methods**: Can be added to fluent interface
- **Complex Configuration**: Support for nested configuration structures
- **Validation Enhancement**: Extended parameter validation capabilities

## Configuration Reference

### Key Configuration Paths
- `search.controller`: 'UnifiedSearch' or 'Search'
- `search.defaultEngine`: Engine class name
- `aod.enable_aod`: Boolean AOD enablement

### Engine Mapping
- BasicSearchEngine → UnifiedSearch + AOD disabled
- BasicAndAodEngine → UnifiedSearch + AOD enabled  
- Custom Engines → Search + AOD disabled 