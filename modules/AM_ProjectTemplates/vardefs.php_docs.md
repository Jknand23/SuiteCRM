# modules/AM_ProjectTemplates/vardefs.php Documentation

## Overview

**File**: `modules/AM_ProjectTemplates/vardefs.php`  
**Type**: Database Field Definitions  
**Purpose**: Defines the database structure, field properties, and relationships for the Advanced Project Templates module

This file establishes the complete data model for project templates, including field definitions, relationships, and database configuration parameters essential for project management functionality.

## Database Operations

### Table Configuration

**Primary Table**: `am_projecttemplates`  
**Features**:
- **Auditing Enabled**: `'audited' => true` - Tracks field changes for compliance
- **Duplicate Merge Support**: `'duplicate_merge' => true` - Enables record deduplication
- **Optimistic Locking**: `'optimistic_locking' => true` - Prevents concurrent update conflicts
- **Unified Search**: `'unified_search' => true` - Supports global search functionality

### Core Field Definitions

**Basic Information Fields**:
- **name**: Primary identifier field with name type and vname 'LBL_NAME'
- **description**: Text field for detailed project template descriptions
- **priority**: Enum field with project_priority_options, default 'High'
- **status**: Tracking field for template lifecycle management

**Assignment and Ownership**:
- **assigned_user_id**: Links to Users module for template ownership
- **assigned_user_name**: Related field for user name display
- **assigned_user_link**: Relationship link to assigned user record

**Business Logic Fields**:
- **override_business_hours**: Boolean field controlling business hours application
  - Type: 'bool', Default: '0', Required: false
  - Enables template-specific business hours configuration

### Field Properties and Attributes

**Standard Field Attributes**:
- **massupdate**: Controls bulk update capability (0 = disabled, 1 = enabled)
- **importable**: Enables field inclusion in import operations ('true'/'false')
- **duplicate_merge**: Controls field behavior during record merging
- **audited**: Enables change tracking for compliance and history
- **reportable**: Allows field inclusion in reports and analytics
- **unified_search**: Enables field participation in global search
- **studio**: Controls field visibility in Studio customization

**Validation and Requirements**:
- **required**: Mandatory field indicator for data integrity
- **len**: Maximum field length for string/text fields
- **size**: Display size hint for form rendering
- **rows/cols**: Dimensions for text area fields

## Relationship Definitions

### User Relationships

**am_projecttemplates_users_1**:
- **Type**: Link relationship to Users module
- **Purpose**: Associates project templates with system users
- **Module**: 'Users', Bean: 'User'
- **VName**: 'LBL_AM_PROJECTTEMPLATES_USERS_1_TITLE'

### Contact Relationships

**am_projecttemplates_contacts_1**:
- **Type**: Link relationship to Contacts module
- **Purpose**: Associates project templates with contact records
- **Module**: 'Contacts', Bean: 'Contact'
- **VName**: 'LBL_AM_PROJECTTEMPLATES_CONTACTS_1_TITLE'

**Non-Database Relationships**:
- **Source**: 'non-db' - Indicates virtual relationship
- **Purpose**: Provides interface connections without direct foreign keys

## VardefManager Integration

### Automatic Field Generation

**VardefManager Configuration**:
```php
VardefManager::createVardef('AM_ProjectTemplates', 'AM_ProjectTemplates', array('basic', 'assignable'));
```

**Template Application**:
- **basic**: Applies standard SuiteCRM field templates (id, date_entered, date_modified, etc.)
- **assignable**: Adds assignment-related fields and functionality

**Template Benefits**:
- Ensures consistency with SuiteCRM standards
- Reduces code duplication and maintenance overhead
- Provides standard audit and tracking capabilities

## Internal API Integration

### SugarObjects Framework

**VardefManager Dependency**:
- Conditionally requires VardefManager if not already loaded
- Ensures proper field template application
- Maintains compatibility with SuiteCRM core framework

**Template System**:
- Leverages SuiteCRM's template-based field generation
- Ensures standard field behavior and properties
- Provides consistent database structure patterns

## Field Type Specifications

### Enum Fields

**Priority Field**:
- **Options**: 'project_priority_options' dropdown
- **Default**: 'High' priority setting
- **Mass Update**: Disabled for data consistency
- **Studio**: Visible for customization

### Text Fields

**Description Field**:
- **Type**: 'text' for rich content support
- **Dimensions**: 6 rows × 80 columns default
- **Comment**: 'Full text of the note'
- **Features**: Importable, reportable, studio visible

### Boolean Fields

**Business Hours Override**:
- **Type**: 'bool' for true/false values
- **Default**: '0' (false) - standard business hours apply
- **Purpose**: Template-specific business hours configuration

### Relationship Fields

**User Assignment**:
- **assigned_user_id**: Core assignment field
- **assigned_user_name**: Display name relationship
- **assigned_user_link**: Full relationship link

## Advanced Features

### Project Management Integration

**Template Configuration**:
- Supports complex project template definitions
- Enables business hours customization per template
- Provides assignment and ownership tracking

**Workflow Integration**:
- Priority-based template organization
- Status tracking for template lifecycle
- User and contact associations for project stakeholders

### Data Integrity Features

**Audit Trail**:
- **Auditing Enabled**: Tracks all field changes
- **Optimistic Locking**: Prevents concurrent modification conflicts
- **Change History**: Maintains compliance and accountability records

**Search and Reporting**:
- **Unified Search**: Global search participation
- **Reportable Fields**: Analytics and reporting support
- **Mass Update Control**: Selective bulk operation permissions

## Usage Context

### Project Template Management

**Template Creation**:
- Comprehensive field set for detailed template definition
- Priority and status management for template organization
- Assignment capabilities for template ownership

**Project Generation**:
- Template-based project creation workflows
- Business hours integration for accurate scheduling
- User and contact relationship management

### Administrative Functions

**Data Management**:
- Import/export capabilities for template migration
- Mass update functionality for bulk modifications
- Duplicate detection and merging support

## Related Files

- `modules/AM_ProjectTemplates/AM_ProjectTemplates.php` - Bean class implementation
- `modules/AM_ProjectTemplates/controller.php` - Module controller logic
- `modules/AM_ProjectTemplates/language/en_us.lang.php` - Field label definitions
- `include/SugarObjects/VardefManager.php` - Field template management
- `modules/AM_ProjectTemplates/metadata/` - Interface definitions

## Integration Points

### SuiteCRM Core Integration

**Framework Compliance**:
- Follows SuiteCRM vardefs standards and conventions
- Integrates with core audit and tracking systems
- Supports standard module functionality patterns

**Data Model Integration**:
- Compatible with SuiteCRM's ORM and data access patterns
- Supports standard CRUD operations and workflows
- Maintains referential integrity with related modules

## Dependencies

### Core Framework
- **VardefManager**: For field template application
- **SugarObjects**: For framework integration
- **Module Framework**: For standard module functionality

### Related Modules
- **Users**: For assignment and ownership relationships
- **Contacts**: For stakeholder relationship management
- **Projects**: For template-based project generation integration

## Notes

- Comprehensive data model supporting advanced project management workflows
- Implements SuiteCRM best practices for module data structure
- Provides foundation for complex project template functionality
- Supports enterprise-level auditing and compliance requirements
- Enables sophisticated project planning and template reuse capabilities
- Critical component for project management module integration and functionality 