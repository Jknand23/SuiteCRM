# SearchWrapper.php Documentation

/**
 * @fileoverview Unified search wrapper managing multiple search engines in SuiteCRM
 * @package SuiteCRM\Search
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `SearchWrapper` class provides a unified interface for performing searches across different search engines in SuiteCRM. It acts as a factory and dispatcher, managing multiple search engine implementations and providing a consistent API for search operations throughout the application.

## Class Structure

### Namespace
`SuiteCRM\Search`

### Dependencies
- `SuiteCRM\Search\AOD\LuceneSearchEngine` - Lucene-based search
- `SuiteCRM\Search\BasicSearch\BasicSearchEngine` - Basic database search
- `SuiteCRM\Search\ElasticSearch\ElasticSearchEngine` - ElasticSearch integration
- `SuiteCRM\Search\Exceptions\SearchEngineNotFoundException` - Exception handling
- `SearchQuery` - Search query container
- `SearchResults` - Search results container
- `SearchEngine` - Base search engine interface
- `SearchModules` - Module configuration management

## Properties

### Search Engine Registry

#### $engines (private static array)
Contains the registry of available search engines with their metadata.

**Structure:**
```php
[
    'EngineName' => [
        'name' => 'EngineName',
        'FQN' => 'Fully\Qualified\ClassName',
        'filepath' => 'path/to/engine/file.php'
    ]
]
```

**Default Engines:**
- **ElasticSearchEngine:** Advanced full-text search with ElasticSearch
- **BasicSearchEngine:** Simple database-based search
- **LuceneSearchEngine:** Lucene-powered search (AOD integration)

#### $customEnginePath (private static string)
Path to the custom search engines directory.
- **Default:** `custom/Extension/SearchEngines/`
- **Purpose:** Allows custom search engine implementations

## Core Methods

### Search Operations

#### searchAndDisplay()
Performs a search and directly displays results.
```php
public static function searchAndDisplay(SearchQuery $query): void
```

**Parameters:**
- `$query` (SearchQuery) - Search query configuration

**Process:**
1. Determines search engine from query or uses default
2. Fetches and initializes the engine
3. Delegates to engine's `searchAndDisplay()` method

**Usage:** Direct search result display in UI controllers

#### search()
Performs a search and returns structured results.
```php
public static function search($engine, SearchQuery $query): SearchResults
```

**Parameters:**
- `$engine` (string|SearchEngine) - Engine name or instance
- `$query` (SearchQuery) - Search query configuration

**Returns:** SearchResults - Structured search results with metadata

**Features:**
- Results grouped by module
- Flexible engine specification
- Consistent return format across engines

### Engine Management

#### addEngine()
Registers a new search engine dynamically.
```php
public static function addEngine(string $engineName, string $file, $fqn): void
```

**Parameters:**
- `$engineName` (string) - Engine identifier name
- `$file` (string) - Path to engine implementation file
- `$fqn` (string) - Fully qualified class name

**Usage:**
- Runtime engine registration
- Custom engine integration
- Plugin-based engine addition

#### getEngines()
Retrieves all available search engine names.
```php
public static function getEngines(): array
```

**Returns:** array - Array of engine names (default + custom)

**Process:**
1. Gets default engines from registry
2. Scans custom engine directory
3. Merges and returns combined list

### Configuration Methods

#### getDefaultEngine()
Retrieves the configured default search engine.
```php
public static function getDefaultEngine(): string
```

**Returns:** string - Default engine name

**Fallback Logic:**
1. Returns configured default from `$sugar_config['search']['defaultEngine']`
2. Falls back to first engine in registry if no configuration

#### getController()
Gets the configured search controller class.
```php
public static function getController(): ?string
```

**Returns:** string|null - Controller class name or null

**Source:** `$sugar_config['search']['controller']`

#### getModules()
Retrieves modules enabled for search.
```php
public static function getModules(): ?array
```

**Returns:** array|null - Enabled module list

**Delegation:** Uses `SearchModules::getEnabledModules()`

### Internal Methods

#### fetchEngine()
Validates and instantiates a search engine.
```php
private static function fetchEngine($engineName): SearchEngine
```

**Parameters:**
- `$engineName` (string|SearchEngine) - Engine identifier or instance

**Returns:** SearchEngine - Initialized engine instance

**Process:**
1. **Instance Check:** Returns if already a SearchEngine instance
2. **Registry Lookup:** Searches default engine registry
3. **Custom Engine Lookup:** Checks custom engine directory
4. **File Validation:** Verifies engine file exists
5. **Class Loading:** Includes engine file
6. **Interface Validation:** Confirms SearchEngine interface
7. **Instantiation:** Creates and returns engine instance

**Exception Handling:**
- Throws `SearchEngineNotFoundException` for missing engines
- Validates file existence and class inheritance

#### getSearchConfig()
Retrieves search configuration values.
```php
private static function getSearchConfig($key)
```

**Parameters:**
- `$key` (string) - Configuration key

**Returns:** mixed|null - Configuration value or null

**Source:** `$sugar_config['search'][$key]`

## Engine Architecture

### Supported Search Engines

#### ElasticSearchEngine
- **Type:** Advanced full-text search
- **Features:** Relevance scoring, faceted search, analytics
- **Performance:** High-performance for large datasets
- **Requirements:** ElasticSearch server installation

#### BasicSearchEngine
- **Type:** Database-native search
- **Features:** SQL LIKE queries, basic filtering
- **Performance:** Suitable for small to medium datasets
- **Requirements:** No external dependencies

#### LuceneSearchEngine
- **Type:** Lucene-powered search (AOD)
- **Features:** Full-text indexing, advanced queries
- **Performance:** Good for medium to large datasets
- **Requirements:** AOD module installation

### Custom Engine Requirements

Custom search engines must:
1. Extend or implement the `SearchEngine` interface
2. Provide `search()` and `searchAndDisplay()` methods
3. Handle `SearchQuery` objects
4. Return `SearchResults` objects
5. Be placed in the custom engines directory

## Configuration Integration

### System Configuration
Search configuration is stored in `$sugar_config['search']`:

```php
$sugar_config['search'] = [
    'defaultEngine' => 'ElasticSearchEngine',
    'controller' => 'SearchController',
    'modules' => ['Accounts', 'Contacts', 'Leads'],
    // Engine-specific configurations
];
```

### Module Configuration
- Integrates with `SearchModules` for enabled module management
- Supports dynamic module enabling/disabling
- Respects ACL permissions for module access

## Integration Points

### SearchQuery Integration
- Accepts `SearchQuery` objects with search parameters
- Supports engine specification within queries
- Handles query validation and transformation

### SearchResults Integration
- Returns standardized `SearchResults` objects
- Maintains consistent result format across engines
- Supports metadata and scoring information

### Module System Integration
- Respects module access permissions
- Supports custom modules through BeanFactory
- Handles module-specific search configurations

### Custom Extension Integration
- Supports custom engine development
- Provides plugin architecture for search extensions
- Enables third-party search integrations

## Error Handling

### Exception Types
- `SearchEngineNotFoundException` - Engine not found or invalid
- File existence validation
- Class inheritance validation
- Configuration error handling

### Graceful Degradation
- Falls back to default engine if configured engine unavailable
- Handles missing configuration gracefully
- Provides meaningful error messages

## Performance Considerations

### Engine Selection
- Static engine registry for fast lookups
- Lazy loading of engine classes
- Minimal overhead for engine dispatching

### Caching
- Engine instances can be cached
- Configuration values retrieved efficiently
- File system checks minimized

### Scalability
- Supports multiple engine types for different use cases
- Allows performance tuning per engine
- Enables horizontal scaling with appropriate engines

## Security Considerations

### Input Validation
- Engine name validation
- File path validation for custom engines
- Class inheritance verification

### Access Control
- Module-level permissions respected
- Engine access can be restricted
- Custom engine validation

## Common Usage Patterns

### Basic Search
```php
$query = new SearchQuery('search term', ['Accounts', 'Contacts']);
$results = SearchWrapper::search('ElasticSearchEngine', $query);
```

### Engine-Agnostic Search
```php
$query = new SearchQuery('search term');
SearchWrapper::searchAndDisplay($query); // Uses default engine
```

### Custom Engine Registration
```php
SearchWrapper::addEngine(
    'CustomEngine',
    'custom/engines/CustomEngine.php',
    'Custom\\Search\\CustomEngine'
);
```

### Configuration-Based Search
```php
$defaultEngine = SearchWrapper::getDefaultEngine();
$availableEngines = SearchWrapper::getEngines();
$enabledModules = SearchWrapper::getModules();
```

## Extension Points

### Custom Engine Development
- Implement `SearchEngine` interface
- Place in custom engines directory
- Register via `addEngine()` method

### Configuration Extensions
- Add engine-specific configurations
- Extend module filtering logic
- Customize default engine selection

### Integration Extensions
- Custom result formatting
- Engine-specific optimizations
- Performance monitoring integration 