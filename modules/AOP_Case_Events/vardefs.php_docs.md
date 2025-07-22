# AOP_Case_Events Variable Definitions

**File**: `modules/AOP_Case_Events/vardefs.php`  
**Type**: PHP Configuration File  
**Purpose**: Defines field definitions, relationships, and database structure for the AOP_Case_Events module

## Overview

This file contains the variable definitions (vardefs) for the AOP_Case_Events module, which manages case event tracking in SuiteCRM's Advanced OpenPortal (AOP) system. It defines the data structure, field properties, and relationships that enable automatic tracking of case modifications and status changes.

## Database Operations

### Table Configuration
- **Table Name**: `aop_case_events`
- **Auditing**: Enabled (`audited => true`)
- **Duplicate Merge**: Enabled (`duplicate_merge => true`)
- **Optimistic Locking**: Enabled (`optimistic_locking => true`)
- **Unified Search**: Enabled (`unified_search => true`)

### Field Definitions

#### Case Relationship Fields
- **`case`**: Link field to establish relationship with Cases module
  - Type: Link field (non-database)
  - Relationship: `cases_aop_case_events`
  - Module: Cases
  - Bean: Case
  - Link Type: One-to-one from event perspective
- **`case_name`**: Related field for displaying case name
  - Type: Relate field (non-database)
  - Pulls name from Cases table
  - Associated with `case_id` field
- **`case_id`**: Foreign key to Cases table
  - Type: ID field
  - Non-reportable
  - Stores reference to parent case

### Relationship Configuration

#### cases_aop_case_events Relationship
- **Type**: One-to-many relationship
- **Left Hand Side (LHS)**:
  - Module: Cases
  - Table: cases
  - Key: id
- **Right Hand Side (RHS)**:
  - Module: AOP_Case_Events
  - Table: aop_case_events
  - Key: case_id
- **Purpose**: Links case events to their parent cases for audit trail functionality

## Internal API Integration

### VardefManager Integration
The file uses VardefManager to automatically include standard SuiteCRM field definitions:
- **Basic Fields**: Includes standard fields like id, name, date_entered, date_modified
- **Assignable Fields**: Adds assignment functionality (assigned_user_id, assigned_user_name)

### Template Inheritance
Inherits from two templates:
1. `basic` - Provides core SugarBean functionality
2. `assignable` - Adds user assignment capabilities

## Module Structure

### Automatic Field Generation
Through VardefManager, the following fields are automatically added:
- Standard identification fields (id, name)
- Audit trail fields (date_entered, date_modified, created_by, modified_user_id)
- Assignment fields (assigned_user_id, assigned_user_name, assigned_user_link)
- Standard relationship fields (created_by_link, modified_user_link)

### Data Integrity Features
- **Auditing**: All changes to case events are tracked
- **Optimistic Locking**: Prevents concurrent update conflicts
- **Duplicate Merge**: Supports merging duplicate event records
- **Unified Search**: Events can be found through global search

## Integration Points

### Cases Module Integration
- Events are automatically created when case fields change
- Each event maintains reference to parent case via `case_id`
- Case name is displayed through relate field for user convenience

### User Assignment Integration
- Events can be assigned to specific users
- Supports standard assignment workflows
- Integrates with user management system

## Data Model Relationships

```
Cases (1) ←→ (Many) AOP_Case_Events
  ↑                    ↑
  id                case_id
```

The relationship enables:
- Case events to reference their parent case
- Cases to display associated events in subpanels
- Audit trail functionality for case modifications
- Historical tracking of case changes

## Security and Access Control

### Row-Level Security
- Inherits security model from assignable template
- Events follow standard SuiteCRM ACL rules
- Access controlled through user assignments and team membership

### Data Protection
- Foreign key constraints ensure data integrity
- Optimistic locking prevents data corruption
- Audit trail maintains change history 