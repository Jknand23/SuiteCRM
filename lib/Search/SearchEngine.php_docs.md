# SearchEngine.php Documentation

/**
 * @fileoverview Abstract base class defining the interface for SuiteCRM search engines
 * @package SuiteCRM\Search
 * @namespace SuiteCRM\Search
 * @copyright SugarCRM Inc. (2004-2013), SalesAgility Ltd. (2011-2021)
 * @license AGPL-3.0
 */

## Overview

The `SearchEngine` abstract class defines the core interface and common functionality for all search engines in SuiteCRM's unified search framework. It provides a standardized API for performing searches and displaying results while allowing different implementations for various search backends.

## Class Definition

### SearchEngine
- **Namespace**: `SuiteCRM\Search`
- **Type**: Abstract base class
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility
- **Purpose**: Defines interface for search engine implementations

## Abstract Methods

### search()
```php
abstract public function search(SearchQuery $query): SearchResults
```

**Purpose**: Core search method that must be implemented by all search engines

**Parameters**:
- `$query` (SearchQuery): Query object containing search parameters and options

**Returns**: `SearchResults` - Object containing search results and metadata

**Implementation Requirement**: Subclasses must implement this method with their specific search logic

## Concrete Methods

### searchAndDisplay()
```php
public function searchAndDisplay(SearchQuery $query): void
```

**Purpose**: Performs search and displays both form and results in a unified interface

**Parameters**:
- `$query` (SearchQuery): Query object for search and display

**Process Flow**:
1. Validates the query using `validateQuery()`
2. Displays search form using `displayForm()`
3. If query is not empty, performs search and displays results

**Integration**: Coordinates between search execution and UI display

### displayForm()
```php
public function displayForm(SearchQuery $query): void
```

**Purpose**: Displays the search form interface

**Parameters**:
- `$query` (SearchQuery): Current query state for form population

**Controller**: Uses `SearchFormController` for form rendering and interaction

### displayResults()
```php
public function displayResults(SearchQuery $query, SearchResults $results): void
```

**Purpose**: Displays search results interface

**Parameters**:
- `$query` (SearchQuery): Original search query
- `$results` (SearchResults): Search results and metadata

**Controller**: Uses `SearchResultsController` for results rendering

### validateQuery()
```php
protected function validateQuery(SearchQuery $query): void
```

**Purpose**: Base validation for search queries (can be overridden)

**Parameters**:
- `$query` (SearchQuery): Query to validate

**Exception**: May throw `SearchInvalidRequestException` for invalid queries

## Internal API Calls

### Search Framework Integration
- **SearchFormController**: Handles search form display and user interaction
- **SearchResultsController**: Manages search results presentation and pagination
- **SearchQuery**: Query object validation and processing
- **SearchResults**: Results container and metadata management

### Exception Handling
- **SearchInvalidRequestException**: Thrown for invalid search requests
- **Query Validation**: Input sanitization and validation checks

## UI Functionality

### Form Display
- Renders search input form with current query state
- Provides search options and configuration interface
- Handles user input and form submission
- Maintains search state across interactions

### Results Display
- Formats and presents search results
- Implements pagination for large result sets
- Provides sorting and filtering options
- Displays search metadata (timing, hit counts)

### Unified Interface
- Combines form and results in single view
- Maintains consistency across different search engines
- Provides responsive design for various screen sizes

## Search Engine Implementations

### Available Engines
- **BasicSearchEngine**: Simple SQL-based search using LIKE statements
- **ElasticSearchEngine**: Full-text search using Elasticsearch
- **LuceneSearchEngine**: AOD (Advanced OpenDiscovery) Lucene-based search

### Engine Selection
- Configured through SuiteCRM administration settings
- Can be specified per query for testing or special cases
- Falls back to default engine when not specified

## Integration Points

### Module System
- Integrates with SuiteCRM module definitions
- Respects ACL permissions for search results
- Supports custom modules and fields
- Handles module-specific search configurations

### Configuration Framework
- Uses global configuration for default settings
- Supports per-user search preferences
- Integrates with search module configuration
- Allows engine-specific configuration options

## Usage Examples

### Basic Search Engine Implementation
```php
namespace SuiteCRM\Search\MyEngine;

use SuiteCRM\Search\SearchEngine;
use SuiteCRM\Search\SearchQuery;
use SuiteCRM\Search\SearchResults;

class MySearchEngine extends SearchEngine
{
    public function search(SearchQuery $query): SearchResults
    {
        $searchString = $query->getSearchString();
        
        // Implement search logic here
        $results = $this->performSearch($searchString);
        
        return new SearchResults($results, true, $timing, $totalHits);
    }
    
    protected function validateQuery(SearchQuery $query): void
    {
        parent::validateQuery($query);
        
        // Add custom validation logic
        if (strlen($query->getSearchString()) < 3) {
            throw new SearchInvalidRequestException('Query too short');
        }
    }
}
```

### Using Search Engine
```php
use SuiteCRM\Search\SearchQuery;
use SuiteCRM\Search\MyEngine\MySearchEngine;

$engine = new MySearchEngine();
$query = SearchQuery::fromString('customer account', 20, 0);

// Search and display in one call
$engine->searchAndDisplay($query);

// Or separate search and results handling
$results = $engine->search($query);
$engine->displayResults($query, $results);
```

## Extension Points

### Custom Validation
- Override `validateQuery()` for engine-specific validation
- Add custom exception types for specific error cases
- Implement input sanitization for security

### Custom Display
- Override `displayForm()` and `displayResults()` for custom UI
- Implement engine-specific search options
- Add custom result formatting and presentation

### Performance Optimization
- Implement caching in search method
- Add result pagination and lazy loading
- Optimize query parsing and execution

## Security Considerations

### Input Validation
- Query validation prevents injection attacks
- Input sanitization in search implementations
- ACL integration for secure result filtering

### Result Security
- Respects user permissions for visible results
- Filters sensitive data from search results
- Prevents unauthorized data access through search

## Performance Patterns

### Efficient Implementation
- Use appropriate indexing for search backend
- Implement result caching where possible
- Optimize query parsing and validation
- Consider memory usage for large result sets

### Scalability
- Design for horizontal scaling of search backend
- Implement connection pooling for external services
- Use asynchronous processing for large searches
- Consider search result pagination limits

## Error Handling

### Exception Management
- Graceful handling of search backend failures
- Meaningful error messages for users
- Logging of search errors for debugging
- Fallback to alternative search methods when possible

### Validation Errors
- Clear validation error messages
- Input sanitization to prevent malformed queries
- Query complexity limits to prevent resource exhaustion 