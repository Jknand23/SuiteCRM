# AOD_Index.php Documentation

## @fileoverview
**AOD_Index module - Advanced OpenDiscovery Lucene-based search index management system**
- **Package**: modules/AOD_Index
- **Copyright**: SalesAgility Ltd
- **License**: GNU AFFERO GENERAL PUBLIC LICENSE
- **Purpose**: Manages full-text search indexing using Zend Lucene framework for SuiteCRM records and documents
- **Deprecation**: Deprecated since v7.12.0, replaced by newer search implementations

## Class Overview
The `AOD_Index` class extends `AOD_Index_sugar` and provides comprehensive full-text search indexing capabilities using the Zend Lucene search engine. It handles indexing of various document types and SuiteCRM module records.

## Database Operations

### Index Configuration
- **Primary Index**: Creates and manages a single index instance with ID = 1
- **Table**: `aod_index` (via parent class)
- **Location**: Stores index files at `modules/AOD_Index/Index/Index`
- **Optimization Tracking**: Records `last_optimised` timestamp

### Index Document Structure
- **Keyword Fields**: `aod_id` (module + record ID), `filename`
- **UnIndexed Fields**: `record_id`, `record_module` 
- **Searchable Content**: Text/varchar/name fields with boost values
- **Field Type Support**: enum, multienum, name, phone, html, text, url, varchar

## Internal API Calls

### Core Indexing Methods

#### `isEnabled(): bool`
- Checks `$sugar_config['aod']['enable_aod']` configuration
- Returns boolean indicating if AOD indexing is active

#### `getIndex(): AOD_Index`
- Retrieves or creates the primary search index (ID = 1)
- Auto-creates index with default settings if not exists
- Returns singleton index instance

#### `index(string $module, string $beanId): bool`
- **Purpose**: Indexes a specific record into the Lucene search index
- **Process**:
  1. Validates module and bean existence
  2. Checks if module is searchable via `isModuleSearchable()`
  3. Creates Lucene document from bean data
  4. Removes existing document and adds updated version
  5. Records indexing event with success/error status
- **Event Tracking**: Creates/updates `AOD_IndexEvent` record

#### `remove(string $module, string $beanId): void`
- Removes specific document from Lucene index
- Uses term query on `aod_id` field for precise removal

#### `find(string $queryString): array`
- Performs Lucene search with UTF-8 case-insensitive analysis
- Returns array of search hits

#### `optimise(): void`
- Optimizes Lucene index for improved performance
- Updates `last_optimised` timestamp
- Only runs if AOD is enabled

### Document Processing

#### `getDocumentForBean(SugarBean $bean): array`
- **Purpose**: Converts SugarBean into searchable Lucene document
- **Document Revision Support**: Special handling for DocumentRevisions module
- **Field Processing**:
  - **enum**: Keyword fields (exact match)
  - **multienum**: Unstored fields (full-text search)
  - **text/varchar/name/html**: Unstored fields with boost values
- **Metadata**: Adds module, ID, and searchability markers

#### `getDocumentForRevision(DocumentRevision $revision): array`
- **Supported MIME Types**:
  - PDF: `application/pdf`
  - MS Word: `application/msword`, `application/vnd.openxmlformats-officedocument.wordprocessingml.document`
  - OpenDocument: `application/vnd.oasis.opendocument.text`
  - HTML: `text/html`
  - Excel: `application/vnd.openxmlformats-officedocument.spreadsheetml.sheet`
  - Text: `text/plain`, `text/csv`
  - RTF: `application/rtf`
  - PowerPoint: `application/vnd.openxmlformats-officedocument.presentationml.presentation`
- **Error Handling**: Returns error array for unsupported types or missing files

### Search Boost System

#### `getBoost(string $module, string $field): float`
- **Field Boosts**: name, first_name, last_name (+0.5)
- **Module Boosts**: Accounts, Contacts, Leads, Opportunities (+0.5)
- **Base Boost**: 1.0 for all fields
- **Purpose**: Prioritizes important fields/modules in search results

### Module Management

#### `isModuleSearchable(string $module, string $beanName): bool`
- **Static Method**: Determines if module can be indexed
- **Whitelist**: DocumentRevisions, Cases (always searchable)
- **Blacklist**: AOD_IndexEvent, AOD_Index, AOW_*, SchedulersJobs
- **Unified Search Check**: Requires `$GLOBALS['dictionary'][$beanName]['unified_search']`

#### `getIndexableModules(): array`
- Returns sorted array of all modules available for indexing
- Filters modules through `isModuleSearchable()` validation

### Index Event Management

#### `getIndexEvent(string $module, string $beanId): AOD_IndexEvent`
- **Purpose**: Creates or retrieves indexing event record for tracking
- **Duplicate Handling**: Removes duplicate events, keeps first occurrence
- **Timestamp Management**: Explicitly sets current date_modified for long-running processes

## External API Calls

### Zend Lucene Framework Integration
- **Analysis**: `Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive`
- **Query Parser**: UTF-8 encoding with `Zend_Search_Lucene_Search_QueryParser`
- **Document Types**: Integrates with Zend document parsers for various file formats
- **Search Queries**: Term queries and fuzzy search with configurable prefix length

### File System Operations
- **Index Storage**: Creates/manages Lucene index files at specified location
- **Document Access**: Reads uploaded files via `getDocumentRevisionPath()`
- **File Validation**: Checks file existence before processing

## Configuration Dependencies

### Global Configuration
- **Enable Flag**: `$sugar_config['aod']['enable_aod']`
- **Module Dictionary**: `$GLOBALS['dictionary']` for field definitions
- **Bean List**: `$GLOBALS['beanList']` for module validation

### Lucene Settings
- **Max Buffered Docs**: 64 documents
- **Merge Factor**: 5 (performance optimization)
- **Fuzzy Search**: Default prefix length of 1
- **Encoding**: UTF-8 throughout system

## Error Handling

### Exception Management
- **Index Operations**: Try-catch blocks with error logging
- **Document Processing**: Returns error arrays with descriptive messages
- **File Operations**: Validates file existence and MIME type support
- **Event Logging**: Records errors in AOD_IndexEvent for debugging

### Validation Checks
- **Module Existence**: Validates against `$GLOBALS['beanList']`
- **Bean Instantiation**: Checks SugarBean instance validity
- **Searchability**: Enforces module whitelist/blacklist rules
- **Configuration**: Verifies AOD enablement before operations

## Deprecation Notice
**⚠️ Important**: This entire module is deprecated since SuiteCRM v7.12.0 and should not be used in new implementations. Modern search functionality has replaced this Lucene-based system. 