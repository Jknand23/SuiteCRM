# vardefs.php Documentation

/**
 * @fileoverview Variable definitions for the Alerts module database schema and field configuration
 * @package Alerts
 * @copyright SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

This file defines the database schema, field configurations, and relationships for the Alerts module. It configures the `alerts` table structure and field properties that govern how alert data is stored, validated, and displayed throughout SuiteCRM.

## Database Operations

### Table Configuration
```php
$dictionary['Alert'] = array(
    'table' => 'alerts',
    'audited' => false,
    'duplicate_merge' => true,
    'optimistic_locking' => true,
    'unified_search' => false,
);
```

- **Table Name**: `alerts`
- **Auditing**: Disabled for performance on high-volume notifications
- **Duplicate Merge**: Enabled to handle potential duplicate alerts
- **Optimistic Locking**: Enabled for concurrent access protection
- **Unified Search**: Disabled as alerts are user-specific

### Field Definitions

#### is_read Field
```php
'is_read' => array(
    'name' => 'is_read',
    'vname' => 'LBL_IS_READ',
    'type' => 'bool',
    'massupdate' => false,
    'studio' => 'false',
)
```
- **Purpose**: Tracks whether user has viewed the alert
- **Type**: Boolean (0 = unread, 1 = read)
- **Studio**: Hidden from Studio customization
- **Mass Update**: Disabled for data integrity

#### target_module Field  
```php
'target_module' => array(
    'name' => 'target_module',
    'vname' => 'LBL_TYPE',
    'type' => 'varchar',
    'massupdate' => false,
    'studio' => 'false',
)
```
- **Purpose**: Identifies which SuiteCRM module the alert relates to
- **Type**: Variable character string
- **Examples**: 'Accounts', 'Contacts', 'Opportunities'
- **Usage**: Enables module-specific alert handling

#### type Field
```php
'type' => array(
    'name' => 'type',
    'vname' => 'LBL_TYPE', 
    'type' => 'varchar',
    'massupdate' => false,
    'studio' => 'false',
)
```
- **Purpose**: Categorizes alert type for display styling
- **Type**: Variable character string
- **Common Values**: 'info', 'warning', 'error', 'success'
- **UI Integration**: Used for color coding and icon selection

#### url_redirect Field
```php
'url_redirect' => array(
    'name' => 'url_redirect',
    'vname' => 'LBL_TYPE',
    'type' => 'varchar', 
    'massupdate' => false,
    'studio' => 'false',
)
```
- **Purpose**: URL destination when user clicks the alert
- **Type**: Variable character string
- **Format**: Relative or absolute URLs
- **Functionality**: Enables navigation from alert to relevant record/page

#### reminder_id Field
```php
'reminder_id' => array(
    'name' => 'reminder_id',
    'type' => 'id',
    'required' => false,
    'reportable' => false,
    'studio' => 'false',
    'comment' => 'The id of the reminder that created this alert'
)
```
- **Purpose**: Links alert to originating reminder record
- **Type**: ID field (36-character UUID)
- **Relationship**: Foreign key to reminders system
- **Optional**: Not required, allows manual alert creation

## Internal API Calls

### VardefManager Integration
```php
if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('Alerts', 'Alert', array('basic','assignable'));
```

#### Template Application
- **basic**: Adds standard SugarBean fields (id, name, description, dates, etc.)
- **assignable**: Adds user assignment fields (assigned_user_id, assigned_user_name, etc.)

#### Generated Fields from Templates
**From 'basic' template**:
- `id`: Primary key
- `name`: Alert title
- `description`: Alert content  
- `date_entered`: Creation timestamp
- `date_modified`: Last update timestamp
- `created_by`: Creating user ID
- `modified_user_id`: Last modifying user ID
- `deleted`: Soft delete flag

**From 'assignable' template**:
- `assigned_user_id`: Target user for alert
- `assigned_user_name`: Target user display name
- `assigned_user_link`: Relationship to Users module

## External API Calls

### Database Schema Creation
- VardefManager processes definitions to create/update database schema
- Field validation rules applied at ORM level
- Indexes automatically created for performance optimization

### Studio Integration
- Fields marked `'studio' => 'false'` are hidden from admin customization
- Protects core alert functionality from accidental modification
- Maintains system integrity while allowing custom field additions

## UI Functionality

### Field Display Configuration
- **vname Labels**: Reference to language files for internationalization
- **Mass Update**: Disabled on all custom fields to prevent accidental bulk changes
- **Studio Access**: Restricted to prevent corruption of alert system

### Form Integration
- Field definitions control how fields appear in forms
- Validation rules enforce data integrity at UI level
- Type definitions determine appropriate form widgets

## Performance Considerations

### Database Optimization
- **No Auditing**: Prevents audit table bloat on high-volume alerts
- **Optimistic Locking**: Prevents data corruption in multi-user scenarios
- **Index Strategy**: ID fields automatically indexed for relationship performance

### Memory Management
- **Minimal Field Set**: Only essential fields defined to reduce memory footprint
- **Type Optimization**: Appropriate field types chosen for efficient storage

## Security Features

### Data Protection
- **Studio Restrictions**: Core fields protected from modification
- **Mass Update Disabled**: Prevents bulk data corruption
- **Type Validation**: Field types enforce data format requirements

### Access Control Integration
- Assignable template provides user-based access control
- Integration with SuiteCRM ACL system through assigned_user_id
- Row-level security through user assignment

## Relationships

### Standard Relationships
- **Assigned User**: Through assignable template
- **Created By**: Through basic template  
- **Modified By**: Through basic template

### Custom Relationships
```php
'relationships' => array(),
```
- Currently empty array allows for future relationship additions
- Can be extended for module-specific alert relationships

## Integration Points

### Reminder System
- `reminder_id` field enables integration with SuiteCRM reminder functionality
- Allows automated alert creation from calendar events and reminders
- Maintains link for audit trail and cleanup operations

### Module System
- `target_module` enables alerts to reference any SuiteCRM module
- Supports contextual alert display based on current user location
- Enables module-specific alert processing and display logic

## Error Handling

### Field Validation
- Type definitions provide automatic validation
- Required field enforcement at database level
- Data integrity maintained through schema constraints

### Schema Integrity
- VardefManager ensures proper field creation
- Handles database updates when field definitions change
- Maintains backwards compatibility during upgrades 