# controller.php Documentation

## Overview
**File:** `modules/AOR_Charts/controller.php`  
**Purpose:** Controller for AOR_Charts module handling chart image map requests  
**Package:** Advanced OpenReports for SugarCRM  
**Dependencies:** pChart library  

## Class Definition

### AOR_ChartsController extends SugarController
Specialized controller that handles chart-specific actions, primarily focused on interactive chart image map generation and delivery.

## External API Calls

### Image Map Generation
- **Action:** `action_getImageMap()`
- **Purpose:** Generates and serves interactive image maps for chart images
- **HTTP Method:** GET/POST
- **Parameters:**
  - `imageMapId`: Required parameter identifying the specific image map

### pChart Library Integration
- **Library Usage:** pChart image map functionality
- **Classes Used:** pImage class for image map generation
- **Storage:** File-based image map storage system

## Internal API Calls

### Image Map Processing
- **Method:** `action_getImageMap()`
- **Workflow:**
  1. Validates `imageMapId` parameter presence
  2. Creates cache directory for user-specific image maps
  3. Initializes pImage instance for image map operations
  4. Generates unique identifier combining user ID and image map ID
  5. Outputs image map data using pChart's dump functionality

### Cache Management
- **Function:** `create_cache_directory()`
- **Path:** `modules/AOR_Charts/ImageMap/{user_id}/`
- **Purpose:** Creates user-specific directories for image map storage
- **Security:** User-isolated storage prevents cross-user access

### Output Buffer Management
- **Process:** Output buffer cleaning before image map delivery
- **Purpose:** Ensures clean image map output without interference
- **Method:** `ob_start()` and `ob_clean()` sequence

## Security Integration

### User Isolation
- **User-Specific Storage:** Image maps stored in user-specific directories
- **Access Control:** Uses `$current_user->id` for directory isolation
- **Data Protection:** Prevents unauthorized access to other users' chart data

### Input Validation
- **Parameter Validation:** Checks for required `imageMapId` parameter
- **Type Casting:** Integer casting of image map ID for security
- **Early Return:** Graceful handling of missing parameters

## Performance Optimization

### Caching Strategy
- **File-Based Caching:** Image maps cached as files for quick retrieval
- **User-Specific Caching:** Prevents cache collisions between users
- **Directory Organization:** Structured cache directory layout

### Output Optimization
- **Buffer Management:** Efficient output buffer handling
- **Direct Output:** Bypasses unnecessary processing for map delivery
- **Minimal Processing:** Lightweight controller action for fast response

## Integration Points

### Chart Generation Workflow
1. **Chart Creation:** AOR_Chart generates image with image map enabled
2. **Map Storage:** Image map data stored in user cache directory
3. **Client Request:** JavaScript requests image map via this controller
4. **Map Delivery:** Controller serves cached image map data

### JavaScript Integration
- **Client-Side Usage:** Called by JavaScript chart enhancement functions
- **AJAX Requests:** Typically called via asynchronous requests
- **Image Map Application:** Maps applied to chart images for interactivity

### pChart Framework
- **Library Dependency:** Requires pChart library for image map functionality
- **Storage Constants:** Uses pChart's IMAGE_MAP_STORAGE_FILE constant
- **API Usage:** Leverages pChart's dumpImageMap method

## Error Handling

### Parameter Validation
- **Missing imageMapId:** Silent return when parameter missing
- **Graceful Degradation:** Charts remain functional without image maps
- **No Error Output:** Prevents JavaScript errors on client side

### File System Operations
- **Directory Creation:** Safe directory creation with error handling
- **Cache Directory Management:** Handles cache directory access issues
- **File System Errors:** Graceful handling of file system problems

## Usage Scenarios

### Interactive Chart Enhancement
- **Primary Use:** Adding clickable regions to chart images
- **Drill-Down Functionality:** Enables chart element clicking for detail views
- **User Experience:** Enhances chart interactivity without page refresh

### Chart Library Support
- **pChart Integration:** Specifically supports pChart-generated charts
- **Image Map Standards:** Follows HTML image map specifications
- **Cross-Browser Compatibility:** Standard image map format works across browsers

## Development Considerations

### Lightweight Design
- **Minimal Footprint:** Simple controller focused on single responsibility
- **Fast Execution:** Optimized for quick image map delivery
- **Scalable Architecture:** User-specific caching supports multiple concurrent users

### Extensibility
- **Additional Actions:** Controller can be extended for other chart-related actions
- **Enhanced Security:** Additional security measures can be added
- **Monitoring:** Request logging and monitoring can be implemented

## Technical Specifications

### Request Handling
- **URL Pattern:** `index.php?module=AOR_Charts&action=getImageMap&imageMapId={id}`
- **Response Type:** Image map data (HTML map coordinates)
- **Caching Headers:** Appropriate caching headers could be added

### File System Layout
```
cache/
└── modules/
    └── AOR_Charts/
        └── ImageMap/
            └── {user_id}/
                └── {user_id}-{imageMapId}
```

### Integration with Chart Display
- **Image Maps:** Applied to chart images via JavaScript
- **Coordinate Mapping:** Maps pixel coordinates to data values
- **Event Handling:** Enables click events on chart elements 