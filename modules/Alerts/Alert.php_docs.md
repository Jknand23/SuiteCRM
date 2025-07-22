# Alert.php Documentation

/**
 * @fileoverview Alert Bean class implementation for the SuiteCRM Alerts module
 * @package Alerts  
 * @copyright SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

The `Alert` class extends the `Basic` SugarBean class to provide notification and alerting functionality within SuiteCRM. This module manages user notifications, reminders, and system alerts that can be displayed to users through the interface.

## Database Operations

### Table Structure
- **Table Name**: `alerts`
- **Primary Key**: `id` (inherited from Basic)
- **Auditing**: Disabled (`audited => false`)
- **Row Level Security**: Disabled (`disable_row_level_security => true`)

### Core Fields
- `id`: Primary key identifier
- `name`: Alert title/subject
- `description`: Detailed alert content
- `is_read`: Boolean flag indicating if user has read the alert
- `target_module`: Module the alert relates to
- `type`: Alert type classification (info, warning, error, etc.)
- `url_redirect`: URL to redirect to when alert is clicked
- `reminder_id`: Foreign key linking to reminder that created this alert
- `assigned_user_id`: User who should see this alert
- `date_entered`: Creation timestamp
- `date_modified`: Last modification timestamp

### Relationships
- **assigned_user_link**: Links to Users module through assignable template
- **created_by_link**: Links to creating user
- **modified_user_link**: Links to last modifying user

## Internal API Calls

### Class Configuration
```php
public $new_schema = true;           // Uses new schema format
public $module_dir = 'Alerts';       // Module directory
public $object_name = 'Alert';       // Object name for system
public $table_name = 'alerts';       // Database table
public $importable = false;          // Cannot be imported
```

### Bean Implementation Interface
```php
public function bean_implements($interface)
```
- **Purpose**: Declares which interfaces this bean implements
- **Supported Interfaces**: 
  - `ACL`: Returns true, enabling Access Control List functionality
- **Returns**: Boolean indicating interface support

### Constructor
```php
public function __construct()
```
- **Purpose**: Initializes the Alert bean
- **Functionality**: Calls parent Basic class constructor
- **Usage**: Automatic instantiation through BeanFactory

## External API Calls

### VardefManager Integration
- Automatically creates vardefs using 'basic' and 'assignable' templates
- Inherits standard SugarBean functionality through VardefManager

### BeanFactory Integration
- Supports creation through `BeanFactory::newBean('Alerts')`
- Supports retrieval through `BeanFactory::getBean('Alerts', $id)`

## UI Functionality

### Alert Display Properties
- **Alert Classification**: Uses `type` field for visual styling
- **Read Status**: `is_read` boolean controls display state
- **Navigation**: `url_redirect` provides clickable navigation
- **Module Context**: `target_module` provides context for alert origin

### User Assignment
- **Target User**: `assigned_user_id` determines alert visibility
- **Permission Model**: Each alert is private to assigned user
- **Notification System**: Integrates with SuiteCRM notification framework

## Security Features

### Access Control
- **ACL Support**: Implements ACL interface for permission checking
- **User Isolation**: Row-level security disabled but user-specific through assignment
- **Data Protection**: Standard SuiteCRM security through assigned_user_id filtering

### Data Validation
- **Schema Validation**: New schema format ensures data integrity
- **Required Fields**: Minimal required fields for flexible alert creation
- **Type Safety**: Boolean and varchar fields with appropriate validation

## Integration Points

### Reminder System
- **reminder_id**: Foreign key linking alerts to reminder system
- **Automated Creation**: Alerts can be auto-generated from reminders
- **Lifecycle Management**: Alerts persist independently of creating reminders

### Module Targeting
- **target_module**: Links alerts to specific SuiteCRM modules
- **Contextual Navigation**: Enables module-specific alert handling
- **Cross-Module Integration**: Supports alerts from any module

## Performance Considerations

### Database Optimization
- **No Auditing**: Disabled for performance on high-volume alert creation
- **Optimistic Locking**: Disabled for simpler concurrent access
- **Unified Search**: Disabled as alerts are user-specific, not searchable

### Memory Management
- **Lightweight Structure**: Minimal field set for efficient memory usage
- **Dynamic Properties**: Uses `#[\AllowDynamicProperties]` for PHP 8.2+ compatibility

## Error Handling

### Bean Validation
- Inherits validation from Basic class
- Standard SugarBean error handling mechanisms
- Database constraint enforcement through schema

### Interface Compliance
- ACL interface implementation ensures proper permission handling
- Standard bean lifecycle methods available through inheritance 