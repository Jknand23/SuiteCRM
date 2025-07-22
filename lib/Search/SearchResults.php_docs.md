# SearchResults.php Documentation

/**
 * @fileoverview Search results container with metadata and display formatting capabilities
 * @package SuiteCRM\Search
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `SearchResults` class provides a comprehensive container for search results with associated metadata in SuiteCRM. It handles the conversion of search hits to SugarBean objects, formats data for display, resolves relationships, and generates appropriate links for the user interface.

## Class Structure

### Namespace
`SuiteCRM\Search`

### Dependencies
- `BeanFactory` - For bean instantiation
- `LoggerManager` - For logging warnings and errors
- `SugarBean` - Base bean functionality
- `SuiteCRM\Exception\Exception` - Exception handling
- `SuiteCRM\Exception\InvalidArgumentException` - Input validation

## Properties

### Core Data Properties

#### $hits (private array)
Contains the search result IDs, structured as:
- **Grouped by module:** `['ModuleName' => ['id1', 'id2', ...]]`
- **Flat structure:** `['id1', 'id2', ...]`

#### $scores (private array)
Contains relevance scores for each hit, matching the structure of `$hits`.
- Used for search result ranking
- Optional parameter - can be null

#### $options (private array)
Customizable metadata array for search engine-specific data.
- Provides flexibility for different search implementations
- Stores additional search metadata

### Metadata Properties

#### $searchTime (private float)
The number of seconds required to perform the search.
- Used for performance monitoring
- Optional parameter - can be null

#### $total (private int)
Total number of hits without pagination.
- Enables proper pagination controls
- Optional parameter - can be null

#### $groupedByModule (private bool)
Flag indicating whether hits are organized by module.
- Affects data structure interpretation
- Defaults to true

## Constructor

### __construct()
```php
public function __construct(
    array $hits,
    $groupedByModule = true,
    float $searchTime = null,
    int $total = null,
    array $scores = null,
    array $options = null
)
```

**Parameters:**
- `$hits` (array) - Search result IDs
- `$groupedByModule` (bool) - Structure flag (default: true)
- `$searchTime` (float|null) - Search execution time
- `$total` (int|null) - Total hits without pagination
- `$scores` (array|null) - Relevance scores
- `$options` (array|null) - Additional metadata

**Validation:**
- Throws `InvalidArgumentException` if hits and scores arrays don't match in size

## Core Methods

### Data Retrieval Methods

#### getHits()
Returns the raw search results as stored.
- **Returns:** array - Search hit IDs
- **Structure:** Depends on `$groupedByModule` flag

#### getHitsAsBeans()
Converts search hits to fully loaded SugarBean objects.
- **Returns:** array - Array of formatted SugarBean objects
- **Features:**
  - Automatic bean instantiation via BeanFactory
  - Relationship loading
  - Display formatting
  - Link generation for UI
  - Error recovery with index rebuilding

**Process:**
1. Iterates through hits by module
2. Instantiates beans using BeanFactory
3. Handles missing beans with index repair
4. Loads relationships
5. Applies display formatting
6. Updates field definition links

### Metadata Methods

#### getScores()
- **Returns:** array|null - Relevance scores matching hits structure

#### getTotal()
- **Returns:** int|null - Total number of hits without pagination

#### getOptions()
- **Returns:** array|null - All custom options/metadata

#### getOption($key)
- **Parameters:** `$key` (string) - Option key to retrieve
- **Returns:** array - Specific option value

#### getSearchTime()
- **Returns:** float|null - Search execution time in seconds

#### isGroupedByModule()
- **Returns:** bool - Whether results are grouped by module

## Display Formatting Methods

### formatForDisplay()
Formats bean data for proper display in search results.
- **Parameters:**
  - `$obj` (SugarBean) - Bean to format
  - `$fieldDefs` (array) - Field definitions
- **Returns:** SugarBean - Formatted bean object

**Field Type Processing:**

#### Enum/Dynamic Enum Fields
- Translates option keys to display labels
- Uses `$app_list_strings` for translation
- Handles missing translations gracefully

#### Multi-Enum Fields
- Decodes encoded multi-value selections
- Translates individual values
- Joins values with comma separator

#### Currency Fields
- Applies currency formatting with locale settings
- Handles USD conversion for `_usdollar` fields
- Includes currency symbols and proper number formatting

### updateFieldDefLinks()
Updates field definitions to include proper UI links.
- **Parameters:**
  - `$obj` (SugarBean) - Bean to update
  - `$fieldDefs` (array) - Field definitions
- **Returns:** SugarBean - Bean with updated links

### updateObjLinks()
Creates HTML links for related fields and name fields.
- **Parameters:**
  - `$obj` (SugarBean) - Bean to update
  - `$fieldDef` (array) - Specific field definition
- **Returns:** SugarBean - Bean with updated field links

**Link Types:**
- **Related Fields:** Links to related record DetailView
- **Name Fields:** Links to current record DetailView

### getRelatedId()
Resolves related record IDs for link generation.
- **Parameters:**
  - `$obj` (SugarBean) - Source bean
  - `$idName` (string) - ID field name
  - `$link` (string) - Link field name
- **Returns:** string|null - Related record ID

**Resolution Process:**
1. Checks for loaded relationship links
2. Handles single vs. multiple related beans
3. Falls back to direct ID field access
4. Logs warnings for unresolved IDs

### getLink()
Generates HTML links for search result display.
- **Parameters:**
  - `$label` (string) - Link text
  - `$module` (string) - Target module
  - `$record` (string) - Target record ID
  - `$action` (string) - Target action
- **Returns:** string - Complete HTML link

## Integration Points

### BeanFactory Integration
- Uses BeanFactory for consistent bean instantiation
- Handles module validation through BeanFactory
- Supports custom module beans

### ElasticSearch Integration
- Automatic index repair for missing beans
- Handles ElasticSearch-specific search results
- Supports ElasticSearch metadata in options

### Locale Integration
- Currency formatting with user locale
- Character encoding for international data
- Timezone-aware date formatting

### ACL Integration
- Respects field-level permissions
- Module access validation through bean loading
- Secure link generation

## Error Handling

### Missing Bean Recovery
- Automatic ElasticSearch index repair
- Graceful degradation for missing records
- Comprehensive error logging

### Validation
- Constructor parameter validation
- Array size consistency checking
- Type safety for critical operations

### Logging
- Warning logs for unresolved relationships
- Error tracking for missing beans
- Performance monitoring support

## Common Usage Patterns

### Basic Search Results Display
```php
$results = new SearchResults($hits, true, $searchTime, $total);
$beans = $results->getHitsAsBeans();
foreach ($beans as $module => $moduleBeans) {
    foreach ($moduleBeans as $bean) {
        echo $bean->name; // Formatted with links
    }
}
```

### Performance Monitoring
```php
$searchTime = $results->getSearchTime();
$total = $results->getTotal();
echo "Found {$total} results in {$searchTime} seconds";
```

### Relevance Scoring
```php
$scores = $results->getScores();
foreach ($scores as $module => $moduleScores) {
    // Display results with relevance indicators
}
```

### Custom Metadata Handling
```php
$engineSpecific = $results->getOption('elasticsearch_highlights');
// Process search highlighting data
```

## Performance Considerations

- Bean loading is performed on-demand via `getHitsAsBeans()`
- Relationship loading only occurs when needed
- Efficient link generation with minimal database queries
- Caching of field definitions during formatting

## Security Considerations

- All links generated through secure AJAX methods
- Bean access validated through BeanFactory
- Field-level permissions respected during formatting
- XSS prevention in link generation

## Extension Points

- Custom field type formatting can be added to `formatForDisplay()`
- Additional metadata can be stored in `$options`
- Link generation can be customized via `getLink()`
- Custom search engines can extend scoring mechanisms 