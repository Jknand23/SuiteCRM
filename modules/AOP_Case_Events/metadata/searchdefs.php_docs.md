# AOP_Case_Events Search Form Definition

**File**: `modules/AOP_Case_Events/metadata/searchdefs.php`  
**Type**: PHP Search Form Configuration File  
**Purpose**: Defines search form layout and field arrangement for the AOP_Case_Events module

## Overview

This file configures the search form interface for the AOP_Case_Events module, defining the layout, field arrangement, and display properties of both basic and advanced search forms. It controls how users interact with search functionality and determines the visual organization of search criteria.

## UI Functionality

### Template Metadata Configuration

#### Layout Properties
- **Maximum Columns**: 3 columns for advanced search layout
- **Basic Search Columns**: 4 columns maximum for basic search
- **Column Widths**: Label (10%) and Field (30%) proportions
- **Responsive Design**: Adapts to different screen sizes

#### Form Structure
- **Basic Search**: Simplified interface with essential fields
- **Advanced Search**: Comprehensive search with all available criteria
- **Layout Flexibility**: Configurable field arrangement
- **User Experience**: Intuitive search form organization

### Basic Search Configuration

#### Core Search Fields
- **`name`**: Primary text search on event name/description
  - Type: Text input field
  - Behavior: Partial matching with wildcards
  - Position: Primary search criterion

#### User Filter Options
- **`current_user_only`**: Boolean filter for personal events
  - Label: References `LBL_CURRENT_USER_FILTER` from language file
  - Type: Checkbox for quick filtering
  - Function: Shows only current user's assigned events
  - User Experience: One-click personal filter

### Advanced Search Configuration

#### Comprehensive Search Fields
- **`name`**: Extended text search with advanced options
  - Enhanced search capabilities
  - Support for complex text queries
  - Integration with search highlighting

#### User Assignment Search
- **`assigned_user_id`**: User selection dropdown
  - Label: References `LBL_ASSIGNED_TO` from language file
  - Type: Enumeration dropdown
  - Function: Calls `get_user_array(false)` for user list
  - Behavior: Includes all system users for selection

## Internal API Integration

### Search Form Generation
The configuration integrates with:
- SuiteCRM's search form generation engine
- Template meta system for layout control
- Field type handlers for input widgets
- Language system for label resolution

### Function Integration
- **`get_user_array(false)`**: Populates user dropdown
  - Parameter: `false` includes inactive users
  - Return: Array of user ID/name pairs
  - Caching: User list cached for performance
  - Security: Respects user visibility permissions

### Layout Engine Integration
- **Template Meta**: Controls form structure and appearance
- **Field Positioning**: Automatic field arrangement
- **Responsive Behavior**: Adapts layout to screen size
- **Accessibility**: Screen reader and keyboard navigation support

## Database Operations

### Query Generation
Search form configuration drives:
- WHERE clause generation from form inputs
- Parameter binding for secure queries
- Search result optimization
- Index utilization for performance

### Performance Optimization
- **Indexed Fields**: Name and assigned_user_id use database indexes
- **Query Caching**: Repeated searches leverage cached results
- **Result Limiting**: Pagination prevents performance issues
- **Efficient Joins**: User assignment queries optimized

## User Experience Features

### Progressive Search Complexity
- **Basic Search**: Simple interface for common searches
- **Advanced Search**: Comprehensive options for power users
- **Form Toggle**: Easy switching between search modes
- **Clear Functionality**: Quick reset of search criteria

### Search Interface Design
- **Logical Field Grouping**: Related fields positioned together
- **Intuitive Layout**: Left-to-right, top-to-bottom flow
- **Visual Hierarchy**: Important fields prominently placed
- **Consistent Styling**: Matches SuiteCRM design standards

### User Interaction Features
- **Auto-complete**: Text fields support type-ahead suggestions
- **Field Validation**: Real-time input validation
- **Search Persistence**: Form state maintained across sessions
- **Quick Search**: Keyboard shortcuts for common operations

## Form Layout Structure

### Column Organization
```
Basic Search (4 columns max):
[Name Field                    ] [Current User Filter]

Advanced Search (3 columns max):
[Name Field         ] [Assigned User    ] [Additional Field]
```

### Responsive Behavior
- **Desktop**: Full column layout with optimal spacing
- **Tablet**: Adjusted column widths for medium screens
- **Mobile**: Stacked layout for narrow screens
- **Accessibility**: Maintains usability across devices

## Integration with Module Components

### Search Results Integration
- **ListView**: Search results display using listviewdefs
- **Pagination**: Seamless integration with result pagination
- **Sorting**: Search results maintain sort preferences
- **Export**: Search criteria included in export operations

### Save Search Functionality
- **Search Persistence**: Form configuration supports saved searches
- **User Preferences**: Personal search preferences storage
- **Shared Searches**: Team-based search sharing capabilities
- **Search History**: Recent search criteria tracking

### Advanced Features Integration
- **Filter Panels**: Integration with advanced filter interfaces
- **Dashboard Filters**: Search criteria for dashboard widgets
- **Report Integration**: Search forms for report generation
- **Workflow Integration**: Search criteria for automated workflows

## Security and Access Control

### Field-Level Security
- **ACL Integration**: Field visibility controlled by permissions
- **Role-Based Access**: Search fields respect user roles
- **Team Security**: User dropdown filtered by team membership
- **Data Protection**: Sensitive fields protected in search forms

### Input Validation
- **SQL Injection Prevention**: All inputs properly sanitized
- **XSS Protection**: Form inputs filtered for security
- **CSRF Protection**: Form submission includes security tokens
- **Data Validation**: Field-specific validation rules applied

## Customization and Extension

### Studio Integration
The search form configuration supports:
- **Drag-and-Drop**: Field repositioning through Studio
- **Field Addition**: New custom fields automatically included
- **Layout Modification**: Form structure customization
- **Label Customization**: Multi-language label management

### Developer Customization
- **Additional Search Fields**: Easy integration of new criteria
- **Custom Field Types**: Support for specialized input widgets
- **Layout Templates**: Custom form layout templates
- **Search Logic**: Configurable search behavior

### Performance Tuning
- **Field Indexing**: Database optimization recommendations
- **Query Optimization**: Search query performance monitoring
- **Caching Strategy**: Form and result caching configuration
- **Load Balancing**: Search load distribution for high-traffic sites

## Best Practices

### User Interface Design
- **Field Prioritization**: Most important fields prominently placed
- **Logical Grouping**: Related search criteria grouped together
- **Clear Labeling**: Descriptive field labels and help text
- **Progressive Disclosure**: Basic to advanced search progression

### Search Experience Optimization
- **Quick Access**: Common searches easily accessible
- **Result Relevance**: Search algorithms tuned for relevance
- **Performance**: Fast search response times
- **Feedback**: Clear indication of search progress and results 