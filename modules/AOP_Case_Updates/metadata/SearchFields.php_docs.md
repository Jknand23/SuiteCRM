# SearchFields.php Documentation

## Overview
**File:** `modules/AOP_Case_Updates/metadata/SearchFields.php`  
**Purpose:** Search field definitions and query configurations for AOP_Case_Updates module  
**Package:** Advanced OpenPortal  

## Configuration Structure

### Search Fields Definition Array
**Global Variable:** `$searchFields[$module_name]`  
**Module Name:** `AOP_Case_Updates`

## Database Operations

### Basic Search Fields

#### name Field
- **Query Type:** 'default'
- **Purpose:** Text-based search on case update names
- **Database Field:** aop_case_updates.name
- **Search Behavior:** LIKE query with wildcards
- **Integration:** Used in basic and advanced search forms

#### current_user_only Field
- **Query Type:** 'default'
- **Database Field:** ['assigned_user_id']
- **my_items:** true (enables current user filtering)
- **vname:** 'LBL_CURRENT_USER_FILTER'
- **type:** 'bool'
- **Purpose:** Filters results to current user's assignments
- **Behavior:** Adds WHERE clause for assigned_user_id = current_user_id

#### assigned_user_id Field
- **Query Type:** 'default'
- **Purpose:** Filter by specific assigned user
- **Database Field:** aop_case_updates.assigned_user_id
- **Search Behavior:** Exact match comparison
- **Integration:** Dropdown selection in advanced search

### Range Search Support

#### Date Entered Range Fields
**Primary Range Field:**
- **range_date_entered:**
  - **query_type:** 'default'
  - **enable_range_search:** true
  - **is_date_field:** true
  - **Purpose:** General date range search capability

**Start Range Field:**
- **start_range_date_entered:**
  - **query_type:** 'default'
  - **enable_range_search:** true
  - **is_date_field:** true
  - **Purpose:** Beginning of date range filter

**End Range Field:**
- **end_range_date_entered:**
  - **query_type:** 'default'
  - **enable_range_search:** true
  - **is_date_field:** true
  - **Purpose:** End of date range filter

#### Date Modified Range Fields
**Primary Range Field:**
- **range_date_modified:**
  - **query_type:** 'default'
  - **enable_range_search:** true
  - **is_date_field:** true
  - **Purpose:** General modification date range search

**Start Range Field:**
- **start_range_date_modified:**
  - **query_type:** 'default'
  - **enable_range_search:** true
  - **is_date_field:** true
  - **Purpose:** Beginning of modification date range

**End Range Field:**
- **end_range_date_modified:**
  - **query_type:** 'default'
  - **enable_range_search:** true
  - **is_date_field:** true
  - **Purpose:** End of modification date range

## Internal API Calls

### Query Building
- **Default Query Type:** Standard WHERE clause generation
- **Range Queries:** Between clause generation for date ranges
- **Boolean Filters:** Checkbox-based filtering for current_user_only

### Search Integration
- **Basic Search:** Uses name and current_user_only fields
- **Advanced Search:** Utilizes all defined search fields
- **Quick Search:** Primarily uses name field for auto-complete

### Date Range Processing
- **Range Validation:** Ensures start date <= end date
- **Date Formatting:** Converts user input to database format
- **Timezone Handling:** Applies user timezone preferences

## UI Functionality

### Search Form Integration
- **Field Types:** Maps to appropriate form controls
  - **name:** Text input
  - **current_user_only:** Checkbox
  - **assigned_user_id:** User dropdown
  - **date ranges:** Date picker controls

### User Experience Features
- **Auto-Complete:** Name field supports type-ahead search
- **Date Pickers:** Visual date selection for range fields
- **User Selection:** Dropdown populated with active users
- **Current User Filter:** Quick toggle for personal items

## Performance Optimization

### Query Efficiency
- **Index Usage:** Search fields typically have database indexes
- **Range Optimization:** Date range queries use efficient BETWEEN operations
- **User Filtering:** current_user_only reduces result set size significantly

### Search Performance
- **Field Selection:** Limited set of searchable fields for performance
- **Default Query Types:** Use standard, optimized query patterns
- **Caching:** Search results and user lists cached where appropriate

## Security Integration

### Access Control
- **User Visibility:** Only shows users accessible to current user
- **Data Filtering:** Respects module-level ACL permissions
- **Record Access:** Ensures search results respect security settings

### Input Validation
- **Date Validation:** Validates date format and ranges
- **User ID Validation:** Ensures assigned users exist and are valid
- **SQL Injection Prevention:** All search inputs sanitized

## Internationalization

### Language Support
- **Labels:** 'LBL_CURRENT_USER_FILTER' translatable
- **Date Formats:** Respects user locale settings
- **User Names:** Displays in appropriate locale format

### Localization Features
- **Date Ranges:** Locale-specific date formatting
- **Field Labels:** Automatic translation from language files
- **Search Messages:** Localized search result messages

## Integration Points

### Related Metadata Files
- **searchdefs.php:** Provides UI layout for these search fields
- **listviewdefs.php:** Displays search results using these filters
- **vardefs.php:** Field definitions referenced by search configuration

### SuiteCRM Framework
- **Search Framework:** Core search engine uses these definitions
- **ListView Controller:** Applies search filters to list queries
- **Advanced Search:** Extended search interface utilizes all fields
- **Export Functionality:** Respects search filters for data export

### Module Dependencies
- **Users:** For assigned_user_id field population and filtering
- **Cases:** For contextual case update searches
- **Activities:** For date-based activity tracking

## Advanced Features

### Range Search Capabilities
- **Flexible Date Ranges:** Supports various date range combinations
- **Partial Range Support:** Allows open-ended ranges (start only or end only)
- **Multiple Range Types:** Both creation and modification date ranges

### Extensibility
- **Custom Fields:** Additional search fields can be added
- **Custom Query Types:** Specialized query behaviors can be implemented
- **Dynamic Filters:** Context-aware filtering based on user role or module state

### Search Enhancement
- **Saved Searches:** Configuration supports saved search functionality
- **Quick Filters:** Enables rapid filtering of common search criteria
- **Combined Searches:** Multiple criteria can be combined for complex queries

## Query Generation Examples

### Basic Searches
- **Name Search:** `WHERE aop_case_updates.name LIKE '%search_term%'`
- **Current User:** `WHERE aop_case_updates.assigned_user_id = 'current_user_id'`

### Range Searches
- **Date Range:** `WHERE aop_case_updates.date_entered BETWEEN 'start_date' AND 'end_date'`
- **Open Range:** `WHERE aop_case_updates.date_entered >= 'start_date'`

### Combined Searches
- **Multiple Criteria:** `WHERE name LIKE '%term%' AND assigned_user_id = 'user_id' AND date_entered >= 'start_date'` 