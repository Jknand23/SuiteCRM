# popupdefs.php Documentation

## Overview
**File:** `modules/AOP_Case_Updates/metadata/popupdefs.php`  
**Purpose:** Popup window configuration for AOP_Case_Updates record selection  
**Package:** Advanced OpenPortal  

## Configuration Structure

### Popup Metadata Definition
**Global Variable:** `$popupMeta`  
**Module Context:** `AOP_Case_Updates`

## UI Functionality

### Popup Window Configuration

#### Core Settings
- **moduleMain:** 'AOP_Case_Updates' (target module for popup)
- **varName:** 'AOP_Case_Updates' (JavaScript variable name)
- **orderBy:** 'aop_case_updates.name' (default sort field)

### Search Integration

#### Where Clauses Configuration
**Field Mapping:**
- **name:** Maps to 'aop_case_updates.name' database field
- **Purpose:** Enables name-based filtering in popup search
- **Behavior:** LIKE query for partial matching

#### Search Input Fields
**Available Search Fields:**
1. **aop_case_updates_number:** Case update number search
2. **name:** Case update name/title search  
3. **priority:** Priority level filtering
4. **status:** Status-based filtering

**Note:** Priority and status fields referenced but may not be defined in current vardefs

## Internal API Calls

### Record Selection
- **Module Loading:** Loads AOP_Case_Updates records for selection
- **Search Processing:** Applies where clauses to filter results
- **Sorting:** Orders results by name field ascending
- **Result Formatting:** Prepares data for popup display

### JavaScript Integration
- **Variable Assignment:** Uses 'AOP_Case_Updates' as JavaScript object name
- **Return Handling:** Configures callback functions for record selection
- **Form Integration:** Enables integration with parent form fields

## Database Operations

### Query Building
- **Base Query:** SELECT from aop_case_updates table
- **Where Clause:** `WHERE aop_case_updates.name LIKE '%search_term%'`
- **Order Clause:** `ORDER BY aop_case_updates.name ASC`
- **Filtering:** Applies additional search input filters

### Performance Optimization
- **Index Usage:** name field typically indexed for sorting
- **Result Limiting:** Popup results usually limited for performance
- **Search Optimization:** Efficient LIKE queries for name matching

## Security Integration

### Access Control
- **Module Permissions:** Respects AOP_Case_Updates module ACL
- **Record Visibility:** Only shows accessible case updates
- **Search Filtering:** Applies security constraints to search results

### Data Protection
- **Input Sanitization:** Search inputs sanitized to prevent injection
- **XSS Prevention:** Output escaped for safe display
- **Permission Validation:** Validates user access before showing records

## Integration Points

### Parent Form Integration
- **Field Population:** Populates parent form fields on selection
- **Relationship Creation:** Establishes relationships when records selected
- **Validation:** Validates selected records against business rules

### SuiteCRM Framework
- **Popup Framework:** Uses SuiteCRM's standard popup infrastructure
- **Search Integration:** Leverages core search functionality
- **JavaScript Framework:** Integrates with SuiteCRM's JavaScript libraries

### Related Modules
- **Cases:** For case-to-case-update relationships
- **Contacts:** For contact-to-case-update associations  
- **Users:** For user assignment selections

## JavaScript Functionality

### Popup Behavior
- **Window Management:** Opens modal popup windows
- **Record Selection:** Handles single and multiple record selection
- **Callback Functions:** Executes callbacks on record selection
- **Form Updates:** Updates parent form fields with selected data

### Search Features
- **Live Search:** Real-time filtering as user types
- **Multi-Field Search:** Combines multiple search criteria
- **Result Pagination:** Handles large result sets with pagination

## Internationalization

### Language Support
- **Column Headers:** Translatable column labels
- **Search Labels:** Localized search field labels
- **Button Text:** Translated action buttons

### Localization Features
- **Date Formatting:** Respects user locale for date displays
- **Number Formatting:** Applies locale-specific number formats
- **Character Encoding:** UTF-8 support for international content

## Advanced Features

### Customization Options
- **Field Addition:** Additional search fields can be added to searchInputs
- **Custom Sorting:** orderBy can be modified for different default sorts
- **Filter Enhancement:** whereClauses can be extended with custom conditions

### Extended Functionality
- **Multi-Select:** Can be configured for multiple record selection
- **Custom Callbacks:** Supports custom JavaScript callback functions
- **Dynamic Filtering:** Can implement context-aware filtering

## Usage Examples

### Relationship Field Usage
```javascript
// Used in EditView for selecting related case updates
{
    'name': 'case_update_name',
    'type': 'relate',
    'module': 'AOP_Case_Updates',
    'popupData': $popupMeta
}
```

### Search Integration
- **Basic Search:** Name field provides quick filtering
- **Advanced Search:** Multiple criteria can be combined
- **Context Search:** Results can be filtered based on parent record context

## Error Handling

### Validation
- **Record Existence:** Validates selected records still exist
- **Permission Checks:** Ensures user can access selected records
- **Data Integrity:** Validates relationships before creation

### User Experience
- **Loading States:** Shows loading indicators during search
- **Error Messages:** Displays user-friendly error messages
- **Graceful Degradation:** Fallback for JavaScript-disabled browsers

## Performance Considerations

### Query Optimization
- **Efficient Searches:** Uses indexed fields for searching
- **Result Limiting:** Limits results to prevent performance issues
- **Caching:** Search results cached where appropriate

### User Interface
- **Responsive Design:** Popup adapts to different screen sizes
- **Fast Rendering:** Optimized for quick popup display
- **Minimal Data Transfer:** Only essential data loaded in popup 