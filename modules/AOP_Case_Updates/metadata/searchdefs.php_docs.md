# searchdefs.php Documentation

## Overview
**File:** `modules/AOP_Case_Updates/metadata/searchdefs.php`  
**Purpose:** Search form layout and field definitions for AOP_Case_Updates module  
**Package:** Advanced OpenPortal  

## Configuration Structure

### Search Definition Array
**Global Variable:** `$searchdefs[$module_name]`  
**Module Name:** `AOP_Case_Updates`

## UI Functionality

### Template Metadata Configuration

#### Layout Properties
- **maxColumns:** '3' (maximum columns in advanced search)
- **maxColumnsBasic:** '4' (maximum columns in basic search)
- **widths:** Label and field width specifications
  - **label:** '10' (10% width for field labels)
  - **field:** '30' (30% width for input fields)

### Search Form Layouts

#### Basic Search Configuration
**Layout Type:** `basic_search`

**Fields:**
1. **name**
   - **Type:** Text input field
   - **Purpose:** Search by case update name/title
   - **Behavior:** Partial matching with LIKE query

2. **current_user_only**
   - **Label:** 'LBL_CURRENT_USER_FILTER'
   - **Type:** Boolean checkbox
   - **Purpose:** Filter to show only current user's case updates
   - **Integration:** Applies assigned_user_id filter

#### Advanced Search Configuration  
**Layout Type:** `advanced_search`

**Fields:**
1. **name**
   - **Type:** Text input field
   - **Purpose:** Search by case update name/title
   - **Behavior:** Supports wildcard matching

2. **assigned_user_id**
   - **Label:** 'LBL_ASSIGNED_TO'
   - **Type:** 'enum' (dropdown selection)
   - **Function:** 'get_user_array' with parameters [false]
   - **Purpose:** Filter by specific assigned user
   - **Integration:** Populates dropdown with all system users

## Internal API Calls

### User Data Population
- **Function:** `get_user_array(false)`
- **Purpose:** Retrieves all users for assignment dropdown
- **Parameters:** false = include inactive users
- **Return:** Array of user_id => user_name pairs

### Search Query Construction
- **name Field:**
  - **Query Type:** LIKE with wildcards
  - **Database Field:** aop_case_updates.name
  - **Processing:** Handles partial string matching

- **current_user_only Field:**
  - **Query Type:** Exact match filter
  - **Database Field:** aop_case_updates.assigned_user_id
  - **Processing:** Compares with $current_user->id

- **assigned_user_id Field:**
  - **Query Type:** Exact match
  - **Database Field:** aop_case_updates.assigned_user_id
  - **Processing:** Direct ID comparison

## Database Operations

### Search Query Generation
The search framework uses these definitions to build SQL WHERE clauses:

#### Basic Search Queries
- **Name Search:** `WHERE aop_case_updates.name LIKE '%{search_term}%'`
- **Current User Filter:** `WHERE aop_case_updates.assigned_user_id = '{current_user_id}'`

#### Advanced Search Queries
- **Combined Conditions:** Multiple WHERE clauses with AND logic
- **User Assignment:** `WHERE aop_case_updates.assigned_user_id = '{selected_user_id}'`
- **Name Filtering:** Supports both basic and advanced name matching

## Security Integration

### Access Control
- **User Filtering:** Respects user assignment permissions
- **Current User Context:** Validates current user access
- **Data Visibility:** Only shows accessible case updates

### Input Validation
- **SQL Injection Prevention:** All inputs sanitized before query building
- **User ID Validation:** Validates assigned user IDs against active users
- **Search Term Sanitization:** Escapes special characters in search strings

## Performance Optimization

### Query Efficiency
- **Index Usage:** name and assigned_user_id fields typically indexed
- **Selective Filtering:** current_user_only reduces result set size
- **User Array Caching:** get_user_array results cached for performance

### Form Rendering
- **Progressive Enhancement:** Basic search loads first
- **AJAX Integration:** Advanced search can load dynamically
- **Template Caching:** Search form templates cached for reuse

## Internationalization

### Language Label Integration
- **LBL_CURRENT_USER_FILTER:** Translatable current user filter label
- **LBL_ASSIGNED_TO:** Translatable assignment field label
- **Dynamic User Names:** User names displayed in current locale format

### Localization Support
- **Date Formatting:** Automatic date format localization
- **RTL Layouts:** Width percentages support right-to-left languages
- **Character Encoding:** UTF-8 support for international characters

## Integration Points

### Related Metadata Files
- **SearchFields.php:** Provides detailed field search configurations
- **listviewdefs.php:** Defines columns that can be searched
- **detailviewdefs.php:** Target for search result navigation

### SuiteCRM Framework
- **SearchForm Class:** Renders search forms using this configuration
- **ListView Controller:** Applies search filters to list queries
- **Quick Search:** Enables auto-complete functionality
- **Saved Searches:** Allows saving of search criteria

### Module Dependencies
- **Users:** For assigned user dropdown population
- **Cases:** For contextual case update filtering
- **ACLRoles:** For permission-based search restrictions

## Advanced Features

### Extensibility
- **Custom Fields:** Additional search fields can be added to layouts
- **Dynamic Layouts:** Supports conditional field display
- **Studio Integration:** Visual search form customization

### Search Enhancement
- **Wildcard Support:** Name field supports * and ? wildcards
- **Date Range Filtering:** Can be extended for date-based searches
- **Relationship Searches:** Supports searching related module data 