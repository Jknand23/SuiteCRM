# AOR_Chart.php Documentation

## Overview
**File:** `modules/AOR_Charts/AOR_Chart.php`  
**Purpose:** Main chart class for Advanced OpenReports (AOR) charting functionality  
**Package:** Advanced OpenReports for SugarCRM  
**Dependencies:** pChart library, Chart.js, RGraph library  

## Class Definition

### AOR_Chart extends Basic
Main chart management class that provides comprehensive charting functionality for SuiteCRM reports with support for multiple chart libraries and types.

**Key Properties:**
- `COLOUR_DEFAULTS`: Static array of default chart colors (24 predefined colors)
- `$colours`: Dynamic color palette for charts
- `$table_name`: 'aor_charts' (database table)
- `$module_dir`: 'AOR_Charts'
- `$disable_row_level_security`: true (charts inherit report security)

**Chart Types Supported:**
- bar, line, pie, radar, rose, grouped_bar, stacked_bar

## Database Operations

### Chart Record Management
- **Method:** `save_lines($post, $bean, $postKey)`
- **Purpose:** Saves chart configuration data from form submissions
- **Database Tables:** `aor_charts`
- **Operations:**
  - Creates or updates chart records based on form data
  - Links charts to parent AOR_Report records
  - Removes deleted chart configurations
  - Handles multiple charts per report

### Field Definitions
**Core Fields:**
- `id`: Primary key
- `name`: Chart title/name
- `type`: Chart type (enum from aor_chart_types)
- `x_field`: X-axis field reference (integer)
- `y_field`: Y-axis field reference (integer)
- `aor_report_id`: Foreign key to AOR_Reports

### Relationship Management
- **Relationship:** `aor_charts_aor_reports`
- **Type:** One-to-many (report to charts)
- **Purpose:** Links charts to their parent reports

## Internal API Calls

### Chart Generation Methods

#### Image-Based Charts (pChart Library)
- **Method:** `buildChartImage($reportData, $fields, $asDataURI, $generateImageMapId)`
- **Returns:** Base64 encoded PNG image or raw image data
- **Features:**
  - Supports bar, line, pie, radar chart types
  - Generates interactive image maps
  - Customizable dimensions (700x700 default)
  - Anti-aliasing and professional styling

#### HTML5 Canvas Charts (Chart.js)
- **Method:** `buildChartHTMLChartJS($reportData, $fields)`
- **Returns:** HTML with embedded JavaScript for Chart.js
- **Features:**
  - Client-side rendering
  - Interactive tooltips and legends
  - Responsive design

#### Advanced Charts (RGraph Library)
- **Method:** `buildChartHTMLRGraph($reportData, $fields, $mainGroupField)`
- **Returns:** HTML with embedded JavaScript for RGraph
- **Features:**
  - Extensive chart type support
  - Advanced grouping and stacking
  - Dynamic color generation
  - Interactive tooltips

### Data Processing Methods

#### Color Management
- **Method:** `getColour($seed, $rgbArray)`
- **Purpose:** Generates consistent colors based on data values
- **Algorithm:** MD5 hash-based color generation
- **Returns:** RGB arrays or hex color codes with highlights

- **Method:** `generateChartColoursFromLabels($labels)`
- **Purpose:** Creates color palette from data labels
- **Features:** Consistent colors for same labels across charts

#### Data Transformation
- **Method:** `getRGraphBarChartData($reportData, $xName, $yName)`
- **Returns:** Formatted data arrays for chart libraries
- **Processing:**
  - Extracts chart data from report results
  - Creates labels and tooltips
  - Handles data type conversion

- **Method:** `getRGraphGroupedBarChartData($reportData, $xName, $yName, $mainGroupField)`
- **Purpose:** Processes complex grouped data for advanced charts
- **Features:**
  - Multi-dimensional data grouping
  - Tooltip generation for grouped values

### Chart-Specific Builders

#### Bar Charts
- **Method:** `buildChartImageBar($chartPicture, $recordImageMap)`
- **Features:** Rotated labels, customizable scale settings
- **Interactive:** Supports image map generation

#### Pie Charts  
- **Method:** `buildChartImagePie($chartPicture, $chartData, $reportData, $imageHeight, $imageWidth, $xName, $recordImageMap)`
- **Features:** 2D pie charts, automatic legends, border styling
- **Color Management:** Dynamic slice coloring based on data

#### Line Charts
- **Method:** `buildChartImageLine($chartPicture, $recordImageMap)`
- **Features:** Grid backgrounds, anti-aliased lines, margin control

#### Radar Charts
- **Method:** `buildChartImageRadar($chartPicture, $chartData, $recordImageMap)`
- **Features:** Horizontal label positioning, interactive mapping

## External API Calls

### pChart Library Integration
- **Library:** Third-party PHP charting library
- **Classes Used:** pImage, pData, pPie, pRadar
- **Features:**
  - Server-side image generation
  - Professional chart styling
  - Font and color management
  - Image map generation for interactivity

### Chart.js Integration
- **Library:** Popular JavaScript charting library
- **Implementation:** Client-side rendering with HTML5 Canvas
- **Features:**
  - Responsive design
  - Animation support
  - Legend generation

### RGraph Integration
- **Library:** Advanced JavaScript charting library
- **Implementation:** Canvas-based charts with extensive customization
- **Features:**
  - Multiple chart types
  - Advanced styling options
  - Interactive tooltips

## UI Functionality

### Multi-Library Support
- **Method:** `buildChartHTML($reportData, $fields, $index, $chartType, $mainGroupField)`
- **Purpose:** Delegates to appropriate chart library based on configuration
- **Support:**
  - `AOR_Report::CHART_TYPE_PCHART`: Server-side image generation
  - `AOR_Report::CHART_TYPE_CHARTJS`: Chart.js implementation
  - `AOR_Report::CHART_TYPE_RGRAPH`: RGraph implementation

### Chart Type Implementations

#### RGraph Chart Generators
- **Bar Charts:** `getRGraphBarChart()` - Standard bar charts with tooltips
- **Line Charts:** `getRGraphLineChart()` - Line charts with circle markers
- **Pie Charts:** `getRGraphPieChart()` - Pie charts with labels and legends
- **Radar Charts:** `getRGraphRadarChart()` - Multi-axis radar displays
- **Rose Charts:** `getRGraphRoseChart()` - Circular statistical displays
- **Grouped Bar Charts:** `getRGraphGroupedBarChart()` - Multi-series bar charts
- **Stacked Bar Charts:** Uses grouped bar method with stacking enabled

### Interactive Features
- **Image Maps:** Clickable chart areas for drill-down functionality
- **Tooltips:** Context-sensitive data display
- **Legends:** Automatic legend generation
- **Responsive Design:** Charts adapt to container sizes

## Configuration Management

### Chart Type Validation
- **Method:** `getValidChartTypes()`
- **Returns:** Array of supported chart types
- **Validation:** Ensures only valid chart types are processed

### Chart Configuration
- **Default Dimensions:** 700x700 pixels for images, 400x400 for canvas
- **Color Palette:** 24 predefined colors with automatic overflow
- **Font Settings:** Verdana font family, configurable sizes
- **Styling:** Professional appearance with borders, grids, and backgrounds

## Performance Optimization

### Caching and Storage
- **Image Caching:** Generated charts cached for performance
- **Image Map Storage:** Interactive maps stored in user-specific directories
- **Data URI Generation:** Base64 encoding for embedded images

### Data Processing Efficiency
- **Label Truncation:** `getShortenedLabel()` prevents UI overflow
- **Color Generation:** Efficient hash-based color creation
- **Data Filtering:** Removes empty or invalid data points

## Error Handling

### Validation and Fallbacks
- **Chart Type Validation:** Returns empty string for invalid types
- **Data Validation:** Checks for required X and Y axis fields
- **Empty Data Handling:** Displays "No Results" message for empty datasets
- **Library Availability:** Graceful degradation when libraries unavailable

### Logging and Debugging
- **Error Logging:** Uses LoggerManager for error tracking
- **Validation Messages:** Detailed logging for invalid configurations
- **Debug Support:** Configurable logging levels

## Integration Points

### AOR_Reports Module
- **Relationship:** Parent-child relationship with reports
- **Data Source:** Uses report query results for chart data
- **Field Mapping:** Charts reference report field definitions

### Chart Libraries
- **pChart:** Server-side image generation
- **Chart.js:** Modern client-side charting
- **RGraph:** Advanced canvas-based charts

### SuiteCRM Framework
- **BeanFactory:** Used for record creation and retrieval
- **Security:** Inherits security from parent reports
- **Caching:** Utilizes SuiteCRM's caching mechanisms

## Advanced Features

### Multi-Chart Support
- **Multiple Charts per Report:** Single report can have multiple charts
- **Chart Management:** Add, edit, delete chart configurations
- **Chart Types:** Different chart types can coexist in one report

### Dynamic Chart Generation
- **Data-Driven Colors:** Colors generated from data values
- **Responsive Sizing:** Charts adapt to available space
- **Interactive Elements:** Clickable charts with drill-down capabilities

### Customization Options
- **Color Schemes:** Customizable color palettes
- **Chart Dimensions:** Configurable width and height
- **Styling Options:** Fonts, borders, backgrounds customizable 