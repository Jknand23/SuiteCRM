# SearchQuery.php Documentation

/**
 * @fileoverview SearchQuery class - Represents and manages search queries with pagination, engine selection, and query manipulation methods for the SuiteCRM search framework
 * @package SuiteCRM\Search
 * @copyright SalesAgility Ltd
 * @license AGPL-3.0
 */

## Overview

The SearchQuery class is a fundamental component of SuiteCRM's search framework that encapsulates search query data, pagination parameters, engine selection, and provides various methods for query manipulation. It implements JsonSerializable for easy data transfer and provides factory methods for creating queries from different sources.

## Class Structure

```php
namespace SuiteCRM\Search;

class SearchQuery implements JsonSerializable
{
    public const DEFAULT_SEARCH_SIZE = 10;
    
    private $query;      // Search query string
    private $size;       // Number of results per page  
    private $from;       // Pagination offset
    private $engine;     // Search engine to use
    private $options;    // Additional search parameters
}
```

## Core Functionality

### Search Query Management

#### Constructor and Factory Methods
- **Private Constructor**: `__construct(string $searchString, $engine = null, $size = null, $from = 0, array $options = [])`
  - Creates SearchQuery instance with specified parameters
  - Sets default values for size and engine if not provided
  - Validates and normalizes input parameters

- **fromString()**: Creates query from search string with pagination parameters
  ```php
  SearchQuery::fromString('user search', 50, 0, 'BasicSearchEngine', [])
  ```

- **fromRequestArray()**: Creates query from request array (POST/GET data)
  - Sanitizes input using FILTER_SANITIZE_STRING and FILTER_SANITIZE_NUMBER_INT
  - Supports multiple field names: 'search-query-string', 'query_string' 
  - Extracts pagination, engine, and additional options from request
  - Returns remaining request data as options array

- **fromGetRequest()**: Convenience method for creating from $_GET superglobal

### Configuration Management

#### Engine Selection
- **getDefaultEngine()**: Determines search engine based on configuration
  - Reads from `$sugar_config['search']['defaultEngine']`
  - Handles special case 'BasicAndAodEngine' with AOD/Lucene detection
  - Falls back to 'BasicSearchEngine' if no configuration found
  - Considers request parameters for search fallback scenarios

#### Size Configuration  
- **getDefaultSearchSize()**: Gets default result count from configuration
  - Checks `$sugar_config['search']['query_size']` 
  - Falls back to `$sugar_config['search']['pagination']['min']`
  - Uses DEFAULT_SEARCH_SIZE constant (10) as final fallback
  - Ensures minimum size of 1 result via getSize() validation

### Query Manipulation Methods

#### String Operations
- **toLowerCase()**: Converts query string to lowercase
- **trim()**: Removes leading/trailing whitespace  
- **replace($what, $with)**: String replacement in query
- **stripSlashes()**: Removes escaping backslashes
- **escapeRegex()**: Escapes regex special characters using preg_quote()
- **convertEncoding()**: Converts HTML entities to UTF-8
  - Uses preg_match_all() to find HTML entities
  - Converts via mb_convert_encoding() from 'HTML-ENTITIES' to 'UTF-8'
  - Handles multiple entities in single query string

### Data Access Methods

#### Getters
- **getSearchString()**: Returns query string
- **getSize()**: Returns pagination size (minimum 1)  
- **getFrom()**: Returns pagination offset
- **getEngine()**: Returns selected search engine
- **getOption($key)**: Gets specific option value
- **getOptions()**: Returns all options array
- **isEmpty()**: Checks if query string is empty

#### JSON Serialization
- **jsonSerialize()**: Implements JsonSerializable interface
  - Returns array with: query, size, from, engine, options
  - Enables direct JSON encoding of SearchQuery objects

## Input Validation and Security

### Request Processing
- **filterArray()**: Validates and filters array values
  - Uses filter_var() with specified filter constants
  - Returns default value for invalid/missing data
  - Prevents injection attacks through input sanitization

### Data Sanitization
- Search strings: FILTER_SANITIZE_STRING
- Numeric values: FILTER_SANITIZE_NUMBER_INT  
- Engine names: FILTER_SANITIZE_STRING
- HTML entity conversion for UTF-8 compatibility

## Integration Points

### Global Configuration
- Integrates with `$sugar_config['search']` settings
- Supports AOD (Advanced OpenDiscovery) engine detection
- Respects fallback mechanisms for legacy search

### Request Handling
- Processes GET/POST request data safely
- Supports multiple request parameter formats
- Maintains backward compatibility with existing search forms

### Search Framework
- Provides standardized query interface for search engines
- Enables engine-specific option passing
- Supports pagination across different search implementations

## Usage Patterns

### Basic Search Query
```php
$query = SearchQuery::fromString('customer name', 25, 0);
$results = $searchEngine->search($query);
```

### Advanced Query with Options
```php
$query = SearchQuery::fromRequestArray($_POST);
$query->toLowerCase();
$query->trim();
$results = $searchEngine->search($query);
```

### Configuration-based Query
```php
$query = SearchQuery::fromGetRequest();
// Engine and size automatically determined from config
$results = $searchEngine->search($query);
```

## Dependencies

### Core Components
- JsonSerializable interface for JSON encoding
- Global $sugar_config for configuration access
- filter_var() for input validation
- preg_match_all() and mb_convert_encoding() for text processing

### Integration Requirements
- Search engine implementations must accept SearchQuery objects
- Configuration system must provide search-related settings
- Request handlers must format data according to expected field names 