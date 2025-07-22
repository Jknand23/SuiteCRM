# tabs.php Documentation

/**
 * @fileoverview Subpanel tab display widget for organizing related records in tabbed interface
 * @package SuiteCRM
 * @copyright SugarCRM Inc. (2004-2013), SalesAgility Ltd. (2011-2018)
 * @license AGPL-3.0
 */

## Overview

The `tabs.php` file contains the `SugarWidgetTabs` class that provides a tabbed interface for displaying subpanels in SuiteCRM. This widget organizes related records into tabs, improving user experience by reducing interface clutter and providing organized access to related data.

## Class Definition

### SugarWidgetTabs
- **Type**: UI widget class
- **Purpose**: Displays subpanels in a tabbed interface
- **Attributes**: `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility

## Properties

### $tabs
- **Type**: `array`
- **Visibility**: `public`
- **Purpose**: Stores the collection of tab data to be displayed

### $current_key
- **Type**: `mixed`
- **Visibility**: `public`
- **Purpose**: Identifies the currently active tab

### $jscallback
- **Type**: `string`
- **Visibility**: `public`
- **Purpose**: JavaScript callback function for tab interactions

## Methods

### __construct()
```php
public function __construct(&$tabs, $current_key, $jscallback)
```

**Purpose**: Initializes the tab widget with data and configuration

**Parameters**:
- `$tabs` (array): Reference to array of tab data
- `$current_key` (mixed): Key identifying the currently active tab
- `$jscallback` (string): JavaScript callback function name

**Integration**: Sets up widget state for rendering

### display()
```php
public function display()
```

**Purpose**: Renders the tabbed interface using Smarty template

**Returns**: `string` - Rendered HTML for the tabbed interface

**Template Integration**: Uses `include/tabs.tpl` template for rendering

## UI Functionality

### Template Rendering
- Uses Smarty templating engine for consistent UI rendering
- Assigns tab data and configuration to template variables
- Renders through `include/tabs.tpl` template file

### Template Variables
- `subpanel_tabs`: Array of tab data for rendering
- `subpanel_tabs_count`: Number of tabs (using `is_countable()` for safety)
- `jscallback`: JavaScript callback function for tab interactions
- `subpanel_current_key`: Currently selected tab identifier

### JavaScript Integration
- Provides callback mechanism for tab switching
- Enables AJAX loading of tab content
- Supports dynamic tab interactions

## Internal API Calls

### Smarty Template Engine
- **Class**: `Sugar_Smarty`
- **Method**: `assign()` - Sets template variables
- **Method**: `display()` - Renders template to HTML
- **Template**: `include/tabs.tpl` - Tab interface template

### PHP Functions
- **Function**: `is_countable()` - Safely counts array elements
- **Function**: `count()` - Gets array length when countable

## Subpanel Integration

### Related Records
- Organizes related module records into logical tabs
- Provides clean interface for accessing associated data
- Reduces visual complexity of detail views

### Module Relationships
- Displays one-to-many relationships as tabs
- Shows many-to-many relationships in organized manner
- Provides context-aware navigation between related records

## Usage Examples

### Basic Tab Widget
```php
$tabs = [
    'contacts' => ['title' => 'Contacts', 'data' => $contact_data],
    'opportunities' => ['title' => 'Opportunities', 'data' => $opp_data],
    'cases' => ['title' => 'Cases', 'data' => $case_data]
];

$widget = new SugarWidgetTabs($tabs, 'contacts', 'switchTab');
$html = $widget->display();
```

### Tab Configuration
```php
// Tab data structure
$tabs = [
    'tab_key' => [
        'title' => 'Tab Display Name',
        'data' => $records_array,
        'count' => count($records_array),
        'module' => 'ModuleName'
    ]
];
```

## Template Integration

### Smarty Variables
- Template receives all necessary data for rendering
- Count is safely calculated using `is_countable()`
- JavaScript callback enables interactive behavior

### CSS Classes
- Tabs use standard SuiteCRM styling classes
- Active tab receives special highlighting
- Responsive design considerations built into template

## Performance Considerations

- Uses reference passing (`&$tabs`) to avoid data copying
- Efficiently counts array elements with safety checks
- Template caching through Smarty engine
- Minimal JavaScript footprint for tab switching

## Security Features

- Template escaping through Smarty engine
- JavaScript callback validation in template
- No direct user input processing in widget class

## Related Components

- **Template**: `include/tabs.tpl` - Tab rendering template
- **JavaScript**: Tab switching and AJAX functionality
- **Subpanels**: Related record display components
- **DetailView**: Main container for tab widgets 