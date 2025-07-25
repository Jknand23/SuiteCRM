# Lead Table View Component Documentation

## Overview

The `lead-table-view.js` component implements **Phase 2, Feature 1, Steps 1-3** of the SuiteCRM modernization project by providing an interactive, responsive table view for displaying lead data with advanced filtering, sorting, pagination, and comprehensive column management capabilities.

## Purpose

This component serves as the **core data display component** for the lead list view by:
- ✅ **Reactive data management** - Real-time updates and state management using Alpine.js stores
- ✅ **Responsive Bootstrap 5 layout** - Mobile-friendly table and card views
- ✅ **Loading states and skeleton screens** - Enhanced user experience during data loading
- ✅ **Error handling and fallback UI** - Comprehensive error handling with user-friendly messages
- ✅ **Filter integration** - Seamless integration with the lead-list-filter component

## Key Features

### Data Management
- **Alpine.js Store Integration**: Uses `Alpine.store('leadData')` for centralized state management
- **Reactive Data Binding**: Automatic UI updates when data changes
- **Pagination Support**: Infinite scroll with configurable page sizes
- **Sorting Capabilities**: Multi-column sorting with direction indicators
- **Selection Management**: Individual and bulk lead selection with actions

### User Interface
- **Responsive Design**: Automatically switches between table and card views based on screen size
- **Loading States**: Skeleton screens and loading indicators for better UX
- **Error Handling**: User-friendly error messages with retry functionality
- **Empty States**: Informative displays when no data is available

### Column Management (v1.1.0)
- **Dynamic Configuration**: Show/hide columns with real-time UI updates
- **Multi-Column Sorting**: Ctrl+click to add/remove sort columns with priority indicators
- **Resizable Columns**: Drag handles for column width adjustment with live preview
- **User Preferences**: Automatic saving and loading of column configurations
- **Mobile Adaptation**: Column management panel responsive to mobile screens

### Performance Optimization
- **Infinite Scroll**: Loads additional data as needed without full page reloads
- **Debounced Interactions**: Prevents excessive API calls during user interactions
- **Optimized Rendering**: Efficient DOM updates using Alpine.js reactivity
- **Preference Caching**: Column settings cached for fast subsequent loads

## Technical Implementation

### Component Structure

#### Alpine.js Store (`leadData`)
```javascript
Alpine.store('leadData', {
    // Data state
    leads: [],              // Array of lead records
    totalCount: 0,          // Total number of filtered leads
    currentPage: 1,         // Current pagination page
    pageSize: 20,           // Records per page
    
    // Loading and error state
    isLoading: false,       // Loading indicator
    hasError: false,        // Error state flag
    errorMessage: '',       // Error message text
    isInitialLoad: true,    // First load indicator
    
    // Sort state
    sortField: 'date_modified',  // Current sort field
    sortDirection: 'desc',       // Sort direction (asc/desc)
    
    // Selection state
    selectedLeads: [],      // Array of selected lead IDs
    selectAll: false        // Select all checkbox state
});
```

#### Enhanced Column Management Store (`leadData` v1.1.0)
```javascript
Alpine.store('leadData', {
    // Column configuration state
    columnConfig: {
        name: { id: 'name', label: 'Lead Name', visible: true, width: 200, resizable: true, sortable: true, order: 1 },
        email: { id: 'email', label: 'Email Address', visible: true, width: 180, resizable: true, sortable: true, order: 2 },
        // ... additional column definitions
    },
    
    // Multi-column sorting
    sortColumns: [
        { field: 'date_modified', direction: 'desc', priority: 1 }
    ],
    
    // Column management methods
    toggleColumnVisibility(columnId),   // Show/hide columns
    updateColumnWidth(columnId, width), // Resize columns
    updateColumnSort(field, addToSort), // Multi-column sorting
    saveColumnPreferences(),            // Persist to API
    loadColumnPreferences()             // Load from API
});
```

#### Enhanced Component Function (`leadTableView()` v1.1.0)
```javascript
function leadTableView() {
    return {
        // Component state
        showMobileView: false,      // Mobile/desktop view toggle
        isResizing: false,          // Column resize state
        resizingColumn: null,       // Current resizing column
        
        // Lifecycle methods
        init(),                     // Enhanced initialization with preference loading
        setupColumnResizing(),      // Column resize event handlers
        
        // Column management
        startColumnResize(),        // Begin column resize operation
        handleColumnSort(),         // Multi-column sort with Ctrl+click
        getSortIcon(),              // Enhanced sort indicators
        getSortPriority(),          // Priority badges for multi-sort
        
        // Event handlers
        handleFilterChange(),       // Filter update handling
        viewLeadDetail(),           // Lead detail navigation
        
        // Utility methods
        formatDate(),               // Date formatting
        getStatusBadgeClass(),      // Status styling
    };
}
```

### API Integration

#### Data Loading
- **Endpoint**: `/Api/V8/Leads/filtered`
- **Method**: GET with query parameters
- **Authentication**: Uses SuiteCRM session authentication
- **Response Format**: JSON with data array and metadata

#### Supported Parameters
```javascript
{
    page: 1,                    // Page number
    limit: 20,                  // Records per page
    sort: 'date_modified',      // Sort field
    direction: 'desc',          // Sort direction
    search: '',                 // Text search term
    campaign_id: '',            // Campaign filter
    industry: '',               // Industry filter
    activity_days: 0,           // Activity filter
    activity_type: 'any',       // Activity type
    filter_logic: 'and'         // Filter combination logic
}
```

### Filter Integration

#### Event Handling
```javascript
// Listen for filter changes
window.addEventListener('lead-filters-changed', (event) => {
    this.handleFilterChange(event.detail.filters);
});
```

#### Filter Application
- Receives filter updates from `lead-list-filter.js` component
- Automatically reloads data with new filter criteria
- Maintains current sort and pagination settings when appropriate

### Responsive Design

#### Desktop Table View
- Full-featured table with sortable columns
- Horizontal scrolling for smaller desktop screens
- Sticky header for better navigation
- Bulk selection and actions toolbar

#### Mobile Card View
- Card-based layout optimized for touch interaction
- Essential information display priority
- Touch-friendly selection controls
- Simplified action interfaces

#### Breakpoint Detection
```javascript
checkMobileView() {
    this.showMobileView = window.innerWidth < 768;
}
```

### Loading States

#### Skeleton Screens
- **Table View**: 5 skeleton rows with animated placeholders
- **Card View**: 3 skeleton cards with content placeholders
- **Animation**: CSS-based shimmer effect for loading indication

#### Loading Indicators
- Initial load: Full skeleton screen
- Pagination: Inline loading spinner at bottom
- Error retry: Button-based loading state

### Error Handling

#### Error Display
- User-friendly error messages
- Retry functionality with automatic state reset
- Dismiss option for non-critical errors
- Fallback to empty state when appropriate

#### Error Recovery
```javascript
// Retry mechanism
<button x-on:click="$store.leadData.loadLeads({}, true)">
    Try Again
</button>
```

## Integration Points

### Template Integration
**File**: `modules/Leads/tpls/ListViewEnhanced.tpl`
```smarty
{* Include Lead Table View Component *}
<script src="themes/SuiteP/js/components/lead-table-view.js"></script>
{include file='themes/SuiteP/tpls/components/lead-table-view.tpl'}
```

### Filter Component Integration
- Listens for `lead-filters-changed` events
- Receives filter criteria and applies them to data loading
- Maintains state consistency between filter and table components

### SuiteCRM Integration
- Uses existing SuiteCRM DetailView navigation patterns
- Integrates with ACL permission system via API
- Maintains compatibility with existing lead module functionality

## Styling and Theming

### CSS Custom Properties
```css
.lead-table-container {
    background: var(--theme-surface, #ffffff);
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}
```

### Bootstrap 5 Classes
- `table-responsive` for horizontal scrolling
- `btn-group` for action button grouping
- `card` components for mobile view
- `skeleton-*` classes for loading states

### Responsive Utilities
- Mobile-first design approach
- Flexible grid layouts
- Touch-friendly interaction areas

## Performance Considerations

### Efficient Data Loading
- Pagination prevents large data transfers
- Debounced search input (300ms delay)
- Optimized SQL queries in API controller

### Memory Management
- Proper cleanup of event listeners
- Efficient Alpine.js store updates
- Minimal DOM manipulation

### Network Optimization
- Cached filter options
- Conditional API requests
- Error retry with exponential backoff

## Testing and Validation

### Component Testing
- [ ] Alpine.js component initialization
- [ ] Filter integration and data loading
- [ ] Responsive design across devices
- [ ] Error handling and recovery
- [ ] Selection and sorting functionality

### API Testing
- [ ] Filtered data retrieval accuracy
- [ ] Pagination and sorting behavior
- [ ] Authentication and authorization
- [ ] Error response handling

### Integration Testing
- [ ] Filter component communication
- [ ] SuiteCRM module compatibility
- [ ] Template rendering consistency
- [ ] Cross-browser functionality

## Future Enhancements

### Additional Features
- **Column Management**: Show/hide and reorder table columns
- **Bulk Actions**: Enhanced bulk operations (edit, delete, export)
- **Real-time Updates**: SSE integration for live data updates
- **Advanced Export**: PDF and Excel export with filtering

### Performance Improvements
- **Virtual Scrolling**: Handle larger datasets efficiently
- **Data Caching**: Client-side caching for frequent queries
- **Optimistic Updates**: Immediate UI feedback for actions

### Accessibility Enhancements
- **Screen Reader Support**: Enhanced ARIA labels and descriptions
- **Keyboard Navigation**: Full keyboard accessibility
- **High Contrast**: Improved color contrast options

---

*This component successfully implements Phase 2, Feature 1, Step 1 by providing a modern, reactive, and responsive lead table view that integrates seamlessly with the existing SuiteCRM infrastructure while delivering enhanced user experience through Alpine.js and Bootstrap 5.* 