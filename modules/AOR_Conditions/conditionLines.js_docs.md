# AOR_Conditions Client-Side JavaScript Documentation

**@fileoverview** Advanced client-side JavaScript functionality for dynamic condition line management in Advanced OpenReports. Provides interactive form handling, logical operator management, parenthesis grouping, and drag-and-drop condition ordering.

**@package** Advanced OpenReports for SugarCRM  
**@copyright** SalesAgility Ltd http://www.salesagility.com  
**@license** GNU AFFERO GENERAL PUBLIC LICENSE

## Overview

The `conditionLines.js` file implements comprehensive client-side functionality for managing complex report conditions. It provides interactive form elements, logical operator handling, parenthesis grouping for advanced expressions, and dynamic field selection based on module relationships.

## UI Functionality

### Global Variables and State Management

#### Core State Variables
- `condln` (integer): Current condition line counter for unique element IDs
- `condln_count` (integer): Total count of active condition lines
- `report_fields` (array): Available fields for current report module
- `report_module` (string): Current report's base module name

#### Callback Management
- `moduleFieldsPendingFinished` (integer): Counter for pending AJAX field requests
- `moduleFieldsPendingFinishedCallback` (function): Callback for completion handling

### Logical Operator Management (LogicalOperatorHandler)

#### getLogicalOperatorSelectHTML() Method
**Purpose**: Generates HTML select element for logical operators (AND/OR)

**Parameters**:
- `value` (string): Current operator value
- `_condln` (integer): Condition line number
- `forcedValue` (string): Default value override (defaults to 'AND')

**Features**:
- **Default Logic**: Automatically defaults to 'AND' for most scenarios
- **State Management**: Properly handles selected state for existing conditions
- **Localization**: Uses SUGAR.language for operator labels
- **Dynamic IDs**: Generates unique names for form submission

#### hideUnnecessaryLogicSelects() Method
**Purpose**: Manages visibility of logical operator selects based on parenthesis context

**Logic**:
- **First Condition**: Hides logic operator for first condition in groups
- **Parenthesis Aware**: Considers opening parentheses when determining visibility
- **State Tracking**: Maintains proper operator visibility throughout form

### Condition Ordering (ConditionOrderHandler)

#### getConditionOrderHiddenInput() Method
**Purpose**: Creates hidden input fields for condition execution order

**Features**:
- **Sequential Ordering**: Maintains proper condition evaluation sequence
- **Form Integration**: Hidden inputs ensure order submission with form data
- **Dynamic Updates**: Order automatically recalculated on condition changes

#### setConditionOrders() Method
**Purpose**: Updates order values for all visible conditions

**Logic**:
- **Sequential Assignment**: Assigns incrementing order numbers to visible conditions
- **Hidden Exclusion**: Sets order to -1 for hidden/deleted conditions
- **Real-time Updates**: Called automatically when conditions are modified

#### Drag-and-Drop Support
- **Position Detection**: `getConditionLineByPageEvent()` determines drop targets
- **Element Placement**: `putPositionedConditionLines()` handles condition reordering
- **Visual Feedback**: Provides user feedback during drag operations

### Parenthesis Management (ParenthesisHandler)

#### Complex Logical Grouping
**START Parentheses**:
- **Opening Groups**: `getParenthesisStartHtml()` creates opening parenthesis elements
- **ID Tracking**: Maintains condition IDs for proper pairing
- **Visual Indicators**: Clear visual representation of group boundaries

**END Parentheses**:
- **Closing Groups**: `getParenthesisEndHtml()` creates closing parenthesis elements
- **Pairing Logic**: Links closing parentheses to their opening counterparts
- **Nested Support**: Handles multiple levels of parenthesis nesting

#### Parenthesis Operations
- **Pair Deletion**: `deleteParenthesisPair()` removes matched parenthesis pairs
- **Batch Removal**: `deleteParenthesisPairs()` clears all parentheses
- **Visual Indentation**: `addParenthesisLineIdent()` provides visual nesting indicators

### Dynamic Form Generation

#### Condition Line Creation
**insertConditionLine() Function**:
- **Row Generation**: Creates new table rows for condition input
- **Field Population**: Populates form fields with existing condition data
- **Event Binding**: Attaches event handlers for dynamic behavior
- **View Adaptation**: Adjusts interface elements based on EditView vs DetailView

#### Field Discovery and Population
**showConditionCurrentModuleFields() Function**:
- **AJAX Field Loading**: Dynamically loads available fields for selected modules
- **Relationship Traversal**: Supports fields from related modules via module paths
- **Option Population**: Updates select elements with available field choices

#### Dynamic Field Type Handling
**showConditionModuleField() Function**:
- **Multi-Step Loading**: Loads operators, value types, and value inputs via separate AJAX calls
- **Type-Specific Forms**: Generates appropriate input controls based on field types
- **Validation Integration**: Ensures field selections are valid for chosen operators

## Internal API Integration

### AJAX Communication

#### Field Discovery Endpoints
- **Module Fields**: `AOR_Reports&action=getModuleFields` for field enumeration
- **Operator Options**: `AOR_Reports&action=getModuleOperatorField` for available operators
- **Value Types**: `AOR_Reports&action=getFieldTypeOptions` for value type selection
- **Value Inputs**: `AOR_Reports&action=getModuleFieldType` for value input generation

#### Asynchronous Request Management
- **YAHOO.util.Connect**: Uses SugarCRM's AJAX framework for server communication
- **Callback Handling**: Proper success/failure callback management
- **Script Evaluation**: Dynamic JavaScript execution from AJAX responses
- **Error Handling**: Graceful degradation on AJAX failures

### Form Integration

#### Data Structure
**Expected Form Fields**:
```javascript
aor_conditions_field[n] = "field_name"
aor_conditions_operator[n] = "operator"
aor_conditions_value_type[n] = "value_type"
aor_conditions_value[n] = "value"
aor_conditions_logic_op[n] = "AND|OR"
aor_conditions_order[n] = "0"
```

#### State Management
- **Deletion Tracking**: Hidden inputs track deleted conditions
- **ID Preservation**: Maintains condition IDs for existing records
- **Order Management**: Hidden order inputs ensure proper submission sequence

### Module System Integration

#### Field Metadata Access
- **Dynamic Loading**: Fields loaded based on selected report module
- **Relationship Support**: Cross-module field access through relationship chains
- **Type Detection**: Field type information for appropriate form controls

#### Localization Support
- **SUGAR.language**: Client-side access to localized labels
- **Dynamic Labels**: Real-time language switching support
- **Cultural Formatting**: Proper formatting for dates, numbers, and other locale-specific data

## External API Integration

### SugarCRM Framework Integration

#### JavaScript Framework Dependencies
- **YAHOO UI Library**: Core AJAX and UI functionality
- **SUGAR Global Object**: SuiteCRM-specific utilities and language support
- **jQuery Integration**: Modern JavaScript functionality where available

#### QuickSearch Integration
- **enableQS() Function**: Enables QuickSearch functionality for lookup fields
- **Dynamic Activation**: QuickSearch enabled as new condition fields are created
- **Search Configuration**: Proper QuickSearch setup for different field types

### Third-Party Integration Points

#### Drag-and-Drop Libraries
- **Sortable Conditions**: Support for reordering conditions via drag-and-drop
- **Visual Feedback**: User-friendly reordering with visual cues
- **Touch Support**: Mobile-friendly interaction patterns

## Advanced Features

### Complex Expression Support

#### Nested Parentheses
- **Multi-Level Nesting**: Supports unlimited parenthesis nesting levels
- **Visual Indentation**: Clear visual representation of nesting structure
- **Validation**: Ensures proper parenthesis pairing and balance

#### Logical Operator Precedence
- **Proper Evaluation**: Maintains correct logical evaluation order
- **Visual Grouping**: Clear indication of operator precedence through grouping
- **Error Prevention**: Prevents creation of invalid logical expressions

### Performance Optimization

#### Efficient DOM Manipulation
- **Minimal Reflows**: Optimized DOM operations to prevent layout thrashing
- **Event Delegation**: Efficient event handling for dynamic content
- **Memory Management**: Proper cleanup of event handlers and DOM references

#### AJAX Optimization
- **Request Batching**: Multiple field requests managed efficiently
- **Caching**: Appropriate caching of field metadata and options
- **Parallel Loading**: Concurrent AJAX requests for faster field population

This comprehensive JavaScript framework provides an intuitive and powerful interface for creating complex report conditions while maintaining performance and usability across different browsers and devices. 