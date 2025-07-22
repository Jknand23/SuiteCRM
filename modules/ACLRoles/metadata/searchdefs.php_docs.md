# searchdefs.php Documentation

## @fileoverview
Search form definitions for the ACLRoles module. This file configures the basic and advanced search forms, defining layout, field organization, and search behavior for role management interfaces.

## @package
SuiteCRM ACLRoles Module - Search form configuration

## @copyright
Copyright (C) 2004-2013 SugarCRM Inc., 2011-2018 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Search Configuration Structure

### Template Metadata Configuration
```php
'templateMeta' => array(
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => array('label' => '10', 'field' => '30'),
)
```

#### Layout Parameters
- **maxColumns**: '3' - Maximum columns in advanced search layout
- **maxColumnsBasic**: '4' - Maximum columns in basic search layout
- **widths**: Responsive width configuration for form elements
  - **label**: '10' - Label width percentage
  - **field**: '30' - Field width percentage

### Search Layout Definitions

#### Basic Search Configuration
```php
'basic_search' => array(
    'name' => array(
        'name' => 'name', 
        'label' => 'LBL_NAME'
    ),
)
```

**Basic Search Fields:**
- **name**: Role name search field
  - **Field Name**: 'name' - Database field reference
  - **Label**: 'LBL_NAME' - Language string for field label
  - **Search Type**: Text input with partial matching

#### Advanced Search Configuration
```php
'advanced_search' => array(),
```

**Current State:**
- Advanced search currently empty (no additional fields configured)
- Extensible structure for future advanced search features
- Placeholder for custom search field additions

## Internal API Calls

### SugarCRM Search Framework Integration
- **$searchdefs**: Global search definitions array
- Integration with SuiteCRM's search form generation system
- Automatic field validation and processing

### Template System Integration
- Automatic search form generation based on definitions
- Smarty template integration for search form rendering
- Theme-compatible search form styling

### Field Processing Integration
- **vardefs.php**: Field definitions for search field validation
- **language files**: Label resolution for search form display
- **database schema**: Field mapping for search query generation

## UI Functionality

### Basic Search Form Generation

#### Search Form Layout
- Single column layout for basic search
- Role name field with text input
- Responsive design for mobile compatibility
- Integration with SuiteCRM's search styling

#### Search Field Behavior
- **Name Field**: 
  - Partial text matching (LIKE query)
  - Case-insensitive search
  - Wildcard support for flexible role lookup

### Advanced Search Form (Extensible)

#### Future Extension Points
- Additional search fields can be added to advanced_search array
- Support for date range searches (creation/modification dates)
- User assignment search capabilities
- Permission-based search filtering

#### Custom Field Integration
- Custom fields automatically available for search
- Dynamic form generation based on field definitions
- Extensible search criteria configuration

### Search Result Integration
- Results displayed in list view format
- Integration with listviewdefs.php for result formatting
- Pagination support for large role sets
- Export functionality for search results

## Database Operations

### Search Query Generation

#### Basic Search Query Pattern
```sql
SELECT * FROM acl_roles 
WHERE name LIKE '%[search_term]%' 
AND deleted = 0
ORDER BY name ASC
```

#### Query Optimization
- Uses database indices for name field searches
- Deleted flag filtering for active roles only
- Efficient LIKE query processing for text searches

### Search Performance Considerations
- Name field indexed for fast text searches
- Limit results for performance with large datasets
- Efficient query caching for repeated searches

## Security and Access Control

### Entry Point Validation
```php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
```

### Search Permission Control
- Inherits module-level access permissions
- Role visibility based on user privileges
- Administrative role filtering for security

### Input Sanitization
- Automatic SQL injection prevention
- XSS protection for search input
- Proper escaping of search parameters

## Performance Considerations

### Search Form Optimization
- Minimal form field loading for basic search
- Efficient template compilation
- Responsive layout calculations

### Query Performance
- Indexed name field for fast searches
- Limited result sets for UI performance
- Efficient pagination for large role collections

### Memory Management
- Minimal memory footprint for search forms
- Efficient search result processing
- Proper cleanup of search query objects

## Integration Points

### Module Integration
- **List View**: Search results display integration
- **Detail View**: Direct navigation from search results
- **Edit View**: Role modification from search results

### Search Framework Integration
- **SugarCRM Search Engine**: Core search functionality
- **Template System**: Form generation and rendering
- **Pagination System**: Result set management

### Custom Search Extensions
- **Custom Fields**: Automatic integration in search forms
- **Related Modules**: Cross-module search capabilities
- **Advanced Filters**: Extension points for complex search criteria

## Customization Support

### Search Field Customization

#### Adding Custom Search Fields
```php
'advanced_search' => array(
    'description' => array(
        'name' => 'description',
        'label' => 'LBL_DESCRIPTION'
    ),
    'date_entered' => array(
        'name' => 'date_entered',
        'label' => 'LBL_DATE_ENTERED',
        'type' => 'datetime'
    ),
)
```

#### Custom Search Types
- Date range searches for role creation/modification
- User-based searches for role assignments
- Permission-level searches for access control

### Layout Customization
- Adjustable column counts for different screen sizes
- Custom width configurations for form elements
- Theme-specific styling integration

### Search Behavior Customization
- Custom search operators (exact match, starts with, etc.)
- Multi-field search combinations
- Saved search functionality integration

## Search Field Types

### Text Field Searches
- **name**: Standard text input with partial matching
- **description**: Full-text search capabilities
- Support for wildcard and regex patterns

### Date Field Searches
- Date range selection for creation/modification dates
- Relative date searches (last week, month, etc.)
- Calendar widget integration

### Relationship Field Searches
- User assignment searches
- SecurityGroup relationship searches
- Related module integration

## Error Handling

### Search Validation
- Required field validation for search forms
- Input format validation for date/number fields
- Error message display for invalid search criteria

### Query Error Handling
- Database error handling for malformed queries
- Fallback behavior for search failures
- User-friendly error messages

### Performance Error Handling
- Timeout handling for complex searches
- Memory limit protection for large result sets
- Graceful degradation for search service failures

## Future Enhancement Points

### Advanced Search Features
- Role permission matrix searches
- User assignment quantity searches
- Module-specific permission searches
- Cross-module role impact searches

### Search Performance Enhancements
- Full-text search integration
- Elasticsearch compatibility
- Advanced caching for frequent searches
- Real-time search suggestions

### User Experience Improvements
- Auto-complete for role name searches
- Recently searched roles history
- Saved search queries functionality
- Export search criteria capability 