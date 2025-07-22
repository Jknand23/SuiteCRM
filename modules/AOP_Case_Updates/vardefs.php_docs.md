# AOP_Case_Updates Variable Definitions

**File**: `modules/AOP_Case_Updates/vardefs.php`  
**Type**: PHP Configuration File  
**Purpose**: Defines field definitions, relationships, and database structure for the AOP_Case_Updates module

## Overview

This file contains the variable definitions (vardefs) for the AOP_Case_Updates module, which manages case update records in SuiteCRM's Advanced OpenPortal (AOP) system. It defines the data structure, field properties, and complex relationships that enable comprehensive case communication tracking between cases, contacts, and file attachments.

## Database Operations

### Table Configuration
- **Table Name**: `aop_case_updates`
- **Auditing**: Enabled (`audited => true`)
- **Duplicate Merge**: Enabled (`duplicate_merge => true`)
- **Optimistic Locking**: Enabled (`optimistic_locking => true`)
- **Unified Search**: Enabled (`unified_search => true`)

### Field Definitions

#### Case Relationship Fields
- **`case`**: Link field to establish relationship with Cases module
  - Type: Link field (non-database)
  - Relationship: `cases_aop_case_updates`
  - Module: Cases
  - Bean: Case
  - Link Type: One-to-one from update perspective
- **`case_name`**: Related field for displaying case name
  - Type: Relate field (non-database)
  - Pulls name from Cases table
  - Associated with `case_id` field
- **`case_id`**: Foreign key to Cases table
  - Type: ID field
  - Non-reportable
  - Stores reference to parent case

#### Contact Relationship Fields
- **`contact`**: Link field to establish relationship with Contacts module
  - Type: Link field (non-database)
  - Relationship: `contacts_aop_case_updates`
  - Module: Contacts
  - Bean: Contact
  - Purpose: Tracks which contact made the update
- **`contact_name`**: Related field for displaying contact name
  - Type: Relate field (non-database)
  - Pulls name from Contacts table
  - Associated with `contact_id` field
- **`contact_id`**: Foreign key to Contacts table
  - Type: ID field
  - Non-reportable
  - Stores reference to updating contact

#### Case Update Specific Fields
- **`internal`**: Boolean field for internal-only updates
  - Type: Boolean field
  - Label: References `LBL_INTERNAL` from language file
  - Purpose: Controls visibility of updates to portal users
- **`notes`**: Link field for file attachments
  - Type: Link field (non-database)
  - Relationship: `aop_case_updates_notes`
  - Source: Non-database
  - Purpose: Links to file attachments and documents

## Internal API Integration

### VardefManager Integration
The file uses VardefManager to automatically include standard SuiteCRM field definitions:
- **Basic Fields**: Includes standard fields like id, name, date_entered, date_modified
- **Assignable Fields**: Adds assignment functionality (assigned_user_id, assigned_user_name)

### Template Inheritance
Inherits from two templates:
1. `basic` - Provides core SugarBean functionality
2. `assignable` - Adds user assignment capabilities

### Relationship Configuration

#### cases_aop_case_updates Relationship
- **Type**: One-to-many relationship
- **Left Hand Side (LHS)**:
  - Module: Cases
  - Table: cases
  - Key: id
- **Right Hand Side (RHS)**:
  - Module: AOP_Case_Updates
  - Table: aop_case_updates
  - Key: case_id
- **Purpose**: Links case updates to their parent cases

#### contacts_aop_case_updates Relationship
- **Type**: One-to-many relationship
- **Left Hand Side (LHS)**:
  - Module: Contacts
  - Table: contacts
  - Key: id
- **Right Hand Side (RHS)**:
  - Module: AOP_Case_Updates
  - Table: aop_case_updates
  - Key: contact_id
- **Purpose**: Tracks which contact created each update

#### aop_case_updates_notes Relationship
- **Type**: One-to-many relationship with role column
- **Left Hand Side (LHS)**:
  - Module: AOP_Case_Updates
  - Table: aop_case_updates
  - Key: id
- **Right Hand Side (RHS)**:
  - Module: Notes
  - Table: notes
  - Key: parent_id
- **Role Column**: parent_type with value 'AOP_Case_Updates'
- **Purpose**: Links file attachments to case updates

## Module Structure

### Automatic Field Generation
Through VardefManager, the following fields are automatically added:
- **Standard Identification**: id, name fields
- **Audit Trail**: date_entered, date_modified, created_by, modified_user_id
- **Assignment**: assigned_user_id, assigned_user_name, assigned_user_link
- **Standard Relationships**: created_by_link, modified_user_link

### Data Integrity Features
- **Auditing**: All changes to case updates are tracked
- **Optimistic Locking**: Prevents concurrent update conflicts
- **Duplicate Merge**: Supports merging duplicate update records
- **Unified Search**: Updates can be found through global search

## Integration Points

### Cases Module Integration
- Updates are linked to parent cases for communication tracking
- Each update maintains reference to parent case via `case_id`
- Case name is displayed through relate field for user convenience
- Supports subpanel display of updates within case detail view

### Contacts Module Integration
- Updates can be linked to specific contacts for attribution
- Contact name displayed through relate field
- Supports portal scenarios where contacts create updates
- Enables customer communication tracking

### Notes Module Integration
- File attachments are managed through Notes relationship
- Uses parent_type/parent_id pattern for polymorphic relationships
- Supports multiple file attachments per update
- Integrates with document management workflows

### User Assignment Integration
- Updates can be assigned to specific users
- Supports standard assignment workflows
- Integrates with user management system
- Enables workload tracking and distribution

## Data Model Relationships

```
Cases (1) ←→ (Many) AOP_Case_Updates ←→ (Many) Notes
  ↑                    ↑                    ↑
  id                case_id              parent_id
                       ↓
Contacts (1) ←→ (Many) AOP_Case_Updates
  ↑                    ↑
  id               contact_id
```

The relationship model enables:
- Case updates to reference their parent case
- Contacts to be attributed as update creators
- File attachments to be linked to specific updates
- Complete audit trail of case communications

## Security and Access Control

### Row-Level Security
- Inherits security model from assignable template
- Updates follow standard SuiteCRM ACL rules
- Access controlled through user assignments and team membership
- Internal flag controls portal visibility

### Data Protection
- Foreign key constraints ensure data integrity
- Optimistic locking prevents data corruption
- Audit trail maintains change history
- Internal updates protected from portal access

## Advanced Features

### Portal Integration
- **Internal Flag**: Controls visibility in customer portal
- **Contact Attribution**: Tracks portal user who created update
- **Case Communication**: Enables two-way case communication
- **Email Integration**: Supports email-based case updates

### File Attachment System
- **Polymorphic Relationships**: Uses parent_type/parent_id pattern
- **Multiple Attachments**: Supports multiple files per update
- **Document Integration**: Links to document management system
- **Version Control**: Integrates with document versioning

### Communication Tracking
- **Bi-directional Communication**: Tracks both internal and external updates
- **Attribution**: Links updates to specific contacts or users
- **Timeline**: Maintains chronological order of case communications
- **Audit Trail**: Complete history of case interactions

## Best Practices

### Relationship Management
- **Foreign Key Integrity**: Ensure valid case_id and contact_id references
- **Relationship Cleanup**: Proper cleanup when parent records deleted
- **Performance**: Index foreign key fields for optimal query performance
- **Data Consistency**: Maintain consistency across related modules

### Security Implementation
- **Internal Flag Usage**: Properly implement internal update visibility
- **Access Control**: Respect user permissions for update access
- **Portal Security**: Ensure internal updates not visible in portal
- **Data Validation**: Validate all foreign key relationships

### File Management
- **Attachment Limits**: Consider file size and quantity limits
- **Storage Management**: Implement appropriate file storage strategies
- **Cleanup Procedures**: Remove orphaned file attachments
- **Version Control**: Maintain file version history when needed 