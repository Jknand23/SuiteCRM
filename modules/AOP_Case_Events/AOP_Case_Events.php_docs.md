# AOP_Case_Events.php Documentation

/**
 * @fileoverview Case Events module class for tracking and managing case-related event history and changes
 * @package modules/AOP_Case_Events
 * @copyright SugarCRM Inc. & SalesAgility Ltd.
 * @license AGPL v3
 */

## Overview
The `AOP_Case_Events` class serves as the primary module class for managing case events in SuiteCRM's Advanced OpenPortal (AOP) system. It provides functionality for tracking and recording significant events and changes that occur during the lifecycle of a case, enabling comprehensive audit trails and event history management.

## Class Structure

### Class Hierarchy
- **AOP_Case_Events** (Main module class)
  - Extends **basic** (Core SuiteCRM basic module class)

### Public Properties
#### Database Configuration
- `$new_schema = true` - Uses new database schema format
- `$module_dir = 'AOP_Case_Events'` - Module directory name
- `$object_name = 'AOP_Case_Events'` - Object identifier
- `$table_name = 'aop_case_events'` - Database table name
- `$tracker_visibility = false` - Excludes from tracker navigation
- `$importable = false` - Module does not support data import
- `$disable_row_level_security = true` - Maintains compatibility across security models

#### Standard Fields
- `$id` - Primary key identifier
- `$name` - Event name/description
- `$date_entered` - Event creation timestamp
- `$date_modified` - Last modification timestamp
- `$description` - Detailed event description
- `$deleted` - Soft delete flag
- `$assigned_user_id` - Assigned user identifier
- `$assigned_user_name` - Assigned user display name

#### Case Relationship Fields
- `$case_name` - Related case name (for display)
- `$case_id` - Related case identifier

#### Audit Fields
- `$modified_user_id` - User who last modified the record
- `$modified_by_name` - Display name of modifying user
- `$created_by` - User who created the record
- `$created_by_name` - Display name of creating user

#### Relationship Links
- `$created_by_link` - Link to user who created the record
- `$modified_user_link` - Link to user who modified the record
- `$assigned_user_link` - Link to assigned user

## Database Operations
- Provides standard SugarBean database operations (save, retrieve, delete)
- Uses table: `aop_case_events`
- Supports audit trail for tracking event history
- Implements soft delete functionality
- Maintains relationship integrity with Cases module

## Internal API Integration

### ACL Implementation
The `bean_implements()` method returns true for the 'ACL' interface, enabling:
- Access control list integration
- Role-based permission checking
- Security policy enforcement for case event operations

### Cases Module Integration
- Direct relationship with Cases module through case_id field
- Supports event tracking for case lifecycle management
- Enables case history and audit trail functionality

### Portal Integration
- Part of Advanced OpenPortal (AOP) system
- Supports portal-based case management workflows
- Enables customer-facing case event visibility

## UI Functionality
- Provides data model for case event views
- Supports event history display in case subpanels
- Enables event list and detail view rendering
- Integrates with case management interfaces
- Supports assignment functionality through assigned user fields

## Security Features
- **ACL Support**: Implements access control through bean_implements() method
- **Row-Level Security**: Disabled to maintain compatibility across security models
- **Audit Trail**: Tracks creation and modification details for events
- **Soft Delete**: Maintains data integrity through logical deletion
- **Case-Based Security**: Security context tied to related case permissions

## Integration Points
- **Cases Module**: Primary relationship for event tracking
- **Users Module**: Assignment and audit trail relationships
- **Portal System**: Advanced OpenPortal case management integration
- **Hook System**: Works with logic hooks for automated event creation
- **Security System**: Integrates with ACL for permission control

## Event Tracking Features
- Automatic event creation through logic hooks
- Manual event logging capabilities
- Case lifecycle monitoring
- Change tracking and history management
- Portal event synchronization

## File Dependencies
- Related: `modules/AOP_Case_Events/CaseEventsHook.php` (automated event creation)
- Related: `modules/AOP_Case_Events/vardefs.php` (field definitions and relationships)
- Related: `modules/Cases/` (primary relationship target)
- Related: Portal-related modules for AOP integration

## Use Cases
- Case status change tracking
- Assignment history logging
- Priority modification events
- Type change notifications
- Portal interaction logging
- Customer communication events 