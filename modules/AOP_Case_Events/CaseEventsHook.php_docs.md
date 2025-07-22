# CaseEventsHook.php Documentation

/**
 * @fileoverview Logic hook class for automatically creating case events when significant case changes occur
 * @package modules/AOP_Case_Events
 * @copyright SugarCRM Inc. & SalesAgility Ltd.
 * @license AGPL v3
 */

## Overview
The `CaseEventsHook` class provides automated case event tracking functionality through SuiteCRM's logic hook system. It monitors specific case field changes and automatically creates corresponding case events to maintain a comprehensive audit trail of case modifications.

## Class Structure

### Class Properties
#### Monitored Fields Configuration
```php
private static $diffFields = array(
    array('field' => 'priority', 'display_field' => 'priority', 'display_name' => 'Priority'),
    array('field' => 'status', 'display_field' => 'status', 'display_name' => 'Status'),
    array('field' => 'assigned_user_id', 'display_field' => 'assigned_user_name', 'display_name' => 'Assigned User'),
    array('field' => 'type', 'display_field' => 'type', 'display_name' => 'Type'),
);
```

The `$diffFields` array defines which case fields are monitored for changes:
- **priority**: Case priority level changes
- **status**: Case status transitions
- **assigned_user_id**: Assignment changes (displays assigned_user_name)
- **type**: Case type modifications

## Core Functionality

### Change Detection and Event Creation
The class provides automated change detection through two primary methods:

#### compareBeans() Method
```php
private function compareBeans($old, $new)
```
- **Purpose**: Compares old and new case bean states to detect field changes
- **Parameters**: 
  - `$old` (SugarBean) - Original case state before changes
  - `$new` (SugarBean) - Updated case state after changes
- **Returns**: Array of AOP_Case_Events beans representing detected changes
- **Logic**: Iterates through monitored fields, comparing values and creating event records

#### saveUpdate() Method
```php
public function saveUpdate($bean)
```
- **Purpose**: Hook entry point called during case save operations
- **Parameters**: `$bean` (SugarBean) - The case bean being saved
- **Trigger**: Called by SuiteCRM's logic hook system on case updates
- **Protection**: Includes safeguards against new cases and import operations

## Database Operations
- Creates new AOP_Case_Events records automatically
- Uses BeanFactory for proper bean instantiation
- Retrieves original case state for comparison
- Saves multiple event records in a single operation when multiple fields change

## Internal API Integration

### SugarBean Integration
- Utilizes BeanFactory for creating Cases and AOP_Case_Events beans
- Leverages standard SugarBean retrieval and save operations
- Integrates with SuiteCRM's logic hook architecture

### Logic Hook System
- Designed to be called from logic hook definitions
- Typically registered as a `before_save` or `after_save` hook
- Integrates seamlessly with SuiteCRM's event-driven architecture

### Module Interoperability
- Creates cross-module relationships between Cases and AOP_Case_Events
- Maintains data consistency across related modules
- Supports Advanced OpenPortal (AOP) workflow requirements

## Change Tracking Logic

### Field Change Detection
For each monitored field, the system:
1. Compares old and new values using strict equality (`!==`)
2. Handles null values gracefully with isset() checks
3. Creates descriptive event descriptions including old and new values
4. Generates both name and description fields for the event record

### Event Description Generation
```php
$desc = $name . ' changed from ' . $oldDisplay . ' to ' . $newDisplay . '.';
$event->name = $desc;
$event->description = $desc;
```
- Creates human-readable descriptions of changes
- Uses display fields for user-friendly presentation
- Maintains consistent formatting across all event types

## Security and Data Protection

### Import Operation Protection
```php
if (isset($_REQUEST['module']) && $_REQUEST['module'] === 'Import') {
    return;
}
```
- Prevents event creation during data import operations
- Avoids cluttering event history with bulk import changes
- Maintains clean audit trails for actual user interactions

### New Case Protection
```php
if (!$bean->id) {
    //New case so do nothing.
    return;
}
```
- Prevents event creation for new case records
- Focuses on tracking changes to existing cases
- Avoids unnecessary events for initial case creation

## Integration Points
- **Cases Module**: Primary target for change monitoring
- **AOP_Case_Events Module**: Target for event record creation
- **Logic Hook System**: Integration point for automatic execution
- **BeanFactory**: Bean creation and management
- **Portal System**: Supports AOP workflow requirements

## Performance Considerations
- Single database query to retrieve original case state
- Efficient field comparison using predefined field list
- Batch creation of multiple events when multiple fields change
- Minimal processing overhead through targeted field monitoring

## Configuration and Extensibility
- Field monitoring easily configurable through `$diffFields` array
- Display names customizable for different languages/contexts
- Extensible to additional case fields as needed
- Supports custom field types through field/display_field separation

## File Dependencies
- Requires: `data/BeanFactory.php` (for bean creation)
- Related: `modules/Cases/` (target module for monitoring)
- Related: `modules/AOP_Case_Events/` (event record creation)
- Integration: SuiteCRM logic hook system 