# chartLines.js Documentation

## Overview
**File:** `modules/AOR_Charts/chartLines.js`  
**Purpose:** JavaScript functionality for dynamic chart configuration management  
**Dependencies:** jQuery, SUGAR JavaScript framework  
**Package:** Advanced OpenReports for SugarCRM  

## Function Definitions

### loadChartLine(chart)
Main function that creates dynamic chart configuration rows in the report interface.

**Parameters:**
- `chart`: Object containing chart configuration data
  - `id`: Chart record ID (if editing existing chart)
  - `name`: Chart title
  - `type`: Chart type selection
  - `x_field`: X-axis field reference
  - `y_field`: Y-axis field reference

**Purpose:** Dynamically generates chart configuration form rows

## UI Functionality

### Dynamic Row Generation
- **Method:** Creates HTML table rows with form elements
- **Structure:** Each row contains chart configuration fields
- **Integration:** Seamlessly integrates with existing report form

### Form Element Creation

#### Remove Button
- **Element:** `<button type="button" class="removeChartButton button">-</button>`
- **Function:** Removes chart configuration row
- **Event:** Click handler attached
- **Cleanup:** Hides chart header when no charts remain

#### Chart ID Field
- **Element:** Hidden input field
- **Purpose:** Stores chart record ID for existing charts
- **Name:** `aor_chart_id[]` (array format for multiple charts)
- **Value:** Chart ID or empty string for new charts

#### Chart Title Field
- **Element:** Text input field
- **Purpose:** Chart title/name configuration
- **Name:** `aor_chart_title[]`
- **Placeholder:** Dynamically translated using SUGAR.language
- **Translation:** 'LBL_CHART_TITLE' from AOR_Reports module

#### Chart Type Selector
- **Element:** Select dropdown
- **Purpose:** Chart type selection
- **Name:** `aor_chart_type[]`
- **Options:** Populated from `SUGAR.language.languages['app_list_strings']['aor_chart_types']`
- **Selection:** Preserves existing selection when editing

#### Axis Field Selectors
- **X-Axis Element:** `<select name='aor_chart_x_field[]' class='chartDimensionSelect'>`
- **Y-Axis Element:** `<select name='aor_chart_y_field[]' class='chartDimensionSelect'>`
- **Data Attributes:** Stores current field selection for preservation
- **Dynamic Population:** Options populated by `updateChartDimensionSelects()`

## Internal API Calls

### jQuery Integration
- **DOM Manipulation:** Uses jQuery for efficient DOM operations
- **Event Handling:** jQuery event handlers for user interactions
- **AJAX Support:** Supports asynchronous form operations

### SUGAR Framework Integration
- **Language System:** `SUGAR.language.translate()` for internationalization
- **Language Data:** Accesses `SUGAR.language.languages` for dropdown options
- **Module Integration:** Integrates with SUGAR module architecture

### Chart Management Functions

#### updateChartDimensionSelects()
**Purpose:** Populates axis field dropdowns with available report fields
**Process:**
1. Iterates through existing report fields (`fieldln_count`)
2. Skips deleted fields (`aor_fields_deleted` = '1')
3. Builds option array from active fields
4. Updates all `.chartDimensionSelect` elements
5. Preserves existing selections using data attributes

**Field Sources:**
- **Field Path:** `$('#aor_fields_module_path_display'+x).text()`
- **Field Label:** `$('#aor_fields_label'+x).val()`
- **Display Format:** "Module Path - Field Label"

#### clearChartLines()
**Purpose:** Removes all chart configuration rows
**Method:** Triggers click event on all remove buttons
**Usage:** Called when clearing/resetting chart configuration

## External API Calls

### Language Translation
- **Function:** `SUGAR.language.translate()`
- **Module:** 'AOR_Reports'
- **Key:** 'LBL_CHART_TITLE'
- **Purpose:** Provides localized placeholder text

### Language Data Access
- **Object:** `SUGAR.language.languages['app_list_strings']['aor_chart_types']`
- **Purpose:** Retrieves chart type options with translations
- **Format:** Key-value pairs for dropdown population

## Event Handling

### Remove Button Event
- **Trigger:** Click on `.removeChartButton`
- **Action:** 
  1. Removes parent table row
  2. Checks remaining chart count
  3. Hides chart header if no charts remain
- **Selector:** `$("[name='aor_chart_id\\[\\]']")` for counting

### Chart Type Selection
- **Event:** Change event on chart type dropdown
- **Preservation:** Maintains selected value when rebuilding options
- **Attribute:** `selected='selected'` for current selection

## Integration Points

### Report Field Integration
- **Field Counting:** Uses global `fieldln_count` variable
- **Field Status:** Checks `aor_fields_deleted` status
- **Field Data:** Accesses field module path and labels
- **Dynamic Updates:** Responds to report field changes

### Form Integration
- **Array Fields:** Uses array notation for multiple chart support
- **Form Submission:** Integrates with standard form submission
- **Field Validation:** Relies on server-side validation

### Chart Display Integration
- **Header Management:** Shows/hides chart configuration header
- **Row Management:** Maintains proper table structure
- **Visual Feedback:** Provides clear add/remove functionality

## Performance Optimization

### Efficient DOM Operations
- **jQuery Optimization:** Uses efficient jQuery selectors
- **Event Delegation:** Minimizes event handler overhead
- **Selective Updates:** Only updates necessary elements

### Memory Management
- **Event Cleanup:** Properly removes event handlers
- **DOM Cleanup:** Removes chart rows completely
- **Variable Scope:** Uses appropriate variable scoping

## User Experience Features

### Dynamic Interface
- **Real-Time Updates:** Field options update as report fields change
- **Visual Feedback:** Clear add/remove button functionality
- **Progressive Enhancement:** Works with JavaScript enabled

### Internationalization
- **Translated Labels:** All user-facing text translated
- **Dynamic Language:** Supports language switching
- **Placeholder Text:** Localized placeholder content

### Validation Support
- **Data Preservation:** Maintains selections during updates
- **Error Recovery:** Preserves user input during validation failures
- **User Guidance:** Clear field labeling and placeholders

## Error Handling

### Field Availability
- **Field Validation:** Checks field existence before populating
- **Graceful Degradation:** Handles missing fields appropriately
- **User Feedback:** Clear indication when fields unavailable

### DOM Manipulation Safety
- **Element Existence:** Checks element existence before manipulation
- **Safe Operations:** Uses jQuery's safe DOM operations
- **Error Prevention:** Prevents JavaScript errors from UI operations

## Integration Workflow

### Chart Configuration Process
1. **Report Setup:** User configures report fields
2. **Chart Addition:** User clicks to add chart
3. **Row Creation:** `loadChartLine()` creates configuration row
4. **Field Population:** `updateChartDimensionSelects()` populates field options
5. **User Configuration:** User selects chart type and axis fields
6. **Form Submission:** Chart configuration saved with report

### Dynamic Updates
1. **Field Changes:** Report field modifications trigger updates
2. **Option Refresh:** Chart field selectors updated automatically
3. **Selection Preservation:** Existing selections maintained where possible
4. **Visual Updates:** Interface updates reflect current state 