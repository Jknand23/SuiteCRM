# AOP_Case_Events Search Fields Configuration

**File**: `modules/AOP_Case_Events/metadata/SearchFields.php`  
**Type**: PHP Search Configuration File  
**Purpose**: Defines searchable fields and search behavior for the AOP_Case_Events module

## Overview

This file configures the search functionality for the AOP_Case_Events module, defining which fields can be searched, their search types, and special search behaviors like range searches and user filtering. It enables comprehensive search capabilities across case event records.

## Database Operations

### Basic Search Fields

#### Standard Text Search
- **`name`**: Default text search on event name/description
  - Query Type: `default` (standard text matching)
  - Supports partial matches and wildcards
  - Case-insensitive search behavior

#### User Assignment Search
- **`assigned_user_id`**: Search by assigned user
  - Query Type: `default` (exact ID matching)
  - Links to user management system
  - Supports user selection interfaces

### Advanced Search Features

#### Current User Filter
- **`current_user_only`**: Filter to show only current user's events
  - Database Field: Maps to `assigned_user_id`
  - Type: Boolean filter (`type => 'bool'`)
  - Label: References `LBL_CURRENT_USER_FILTER`
  - Behavior: `my_items => true` enables personal filtering
  - Purpose: Quick access to user's own case events

#### Date Range Search
Comprehensive date range search functionality:

**Date Entered Range**:
- **`range_date_entered`**: General date entered range search
- **`start_range_date_entered`**: Start date for creation date range
- **`end_range_date_entered`**: End date for creation date range

**Date Modified Range**:
- **`range_date_modified`**: General date modified range search  
- **`start_range_date_modified`**: Start date for modification date range
- **`end_range_date_modified`**: End date for modification date range

All date fields configured with:
- `enable_range_search => true`: Enables range selection UI
- `is_date_field => true`: Provides date picker functionality
- `query_type => 'default'`: Standard date comparison logic

## Internal API Integration

### Search Framework Integration
The configuration integrates with:
- SuiteCRM's core search engine
- ListView search functionality
- Advanced search form generation
- Quick search capabilities

### Query Generation
- Search fields map to database columns
- Optimized WHERE clause generation
- Range searches generate BETWEEN clauses
- Boolean filters create efficient query conditions

### User Interface Integration
- Search form field generation
- Date picker widget integration
- User selection dropdown population
- Filter checkbox functionality

## UI Functionality

### Search Form Features

#### Basic Search Capabilities
- Text search on event names and descriptions
- User assignment filtering
- Current user quick filter
- Simple and intuitive interface

#### Advanced Search Options
- Date range selection for creation dates
- Date range selection for modification dates
- User assignment dropdown selection
- Complex query combination logic

#### User Experience Enhancements
- Date picker widgets for range selection
- User-friendly filter labels
- Quick access to personal events
- Comprehensive search coverage

### Performance Optimization

#### Efficient Query Design
- Indexed field searches for optimal performance
- Range searches optimized for date columns
- User ID searches leverage foreign key indexes
- Boolean filters minimize query complexity

#### Search Result Management
- Pagination-friendly search queries
- Sort capability on search results
- Export functionality for search results
- Count optimization for large result sets

## Search Behavior Configuration

### Text Search Logic
- **Name Field**: Supports partial matching with LIKE queries
- **Case Sensitivity**: Configured for case-insensitive searches
- **Wildcard Support**: Automatic wildcard appending for user-friendly searching
- **Phrase Matching**: Supports quoted phrase searches

### Date Range Logic
- **Inclusive Ranges**: Start and end dates included in results
- **Partial Range Support**: Start-only or end-only date filtering
- **Date Format Handling**: Automatic date format conversion
- **Timezone Awareness**: Respects user timezone preferences

### User Filter Logic
- **Current User Filter**: Filters to assigned_user_id = current_user.id
- **Assignment Search**: Supports dropdown selection of any system user
- **Team Integration**: Respects team-based security restrictions
- **ACL Compliance**: User visibility controlled by access permissions

## Integration with Module Components

### ListView Integration
- Search results display using listviewdefs configuration
- Seamless transition between search and browse modes
- Maintains sort preferences across search operations
- Supports saved search functionality

### Advanced Search Form
- Dynamic form generation based on field configuration
- Date range widgets automatically generated
- User selection dropdowns populated from system users
- Responsive form layout for mobile compatibility

### Security and Access Control
- Search respects ACL field-level permissions
- User filters honor team security settings
- Role-based search field availability
- Audit trail integration for search activity

## Customization and Extension

### Developer Customization
- Additional search fields easily added
- Custom search logic implementation supported
- Third-party field integration possible
- Search behavior modification through configuration

### Studio Integration
- Search field visibility controlled through Studio
- Field properties modifiable via admin interface
- Custom field search configuration automatic
- User-friendly customization workflow

### Performance Tuning
- Database index recommendations for new search fields
- Query optimization opportunities identified
- Caching integration for complex searches
- Search performance monitoring capabilities 