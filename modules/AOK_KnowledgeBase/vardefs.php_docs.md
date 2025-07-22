# vardefs.php Documentation

/**
 * @fileoverview Variable definitions for Knowledge Base module defining database schema, fields, and relationships
 * @package modules/AOK_KnowledgeBase
 * @copyright SugarCRM Inc. & SalesAgility Ltd.
 * @license AGPL v3
 */

## Overview
The vardefs.php file defines the comprehensive database schema, field definitions, and relationships for the Knowledge Base module. It establishes the data structure that enables knowledge base article management with workflow features including authoring, approval, categorization, and content versioning.

## Database Schema Configuration

### Table Definition
- **Table Name**: `aok_knowledgebase`
- **Audited**: `true` - Enables audit trail for tracking article changes
- **Duplicate Merge**: `true` - Supports duplicate record merging functionality
- **Optimistic Locking**: `true` - Prevents concurrent modification conflicts
- **Unified Search**: `true` - Enables global search functionality

## Field Definitions

### Core Content Fields
#### name (Title)
- **Type**: `name` - Specialized field for titles
- **Length**: 255 characters
- **Required**: `true`
- **Full Text Search**: Boost level 3 (high priority)
- **Unified Search**: `false` - Handled separately
- **Mass Update**: Disabled
- **Duplicate Merge**: Disabled

#### description (Body)
- **Type**: `text` - Large text field for article content
- **Rows**: 6, Columns: 80
- **Required**: `false`
- **Full text content of the knowledge base article

#### additional_info (Resolution)
- **Type**: `text` - Additional resolution information
- **Rows**: 6, Columns: 80
- **Required**: `false`
- **Used for solutions and additional details

### Workflow Management Fields
#### status
- **Type**: `enum` - Dropdown selection
- **Default**: `'Draft'`
- **Options**: `aok_status_list` - Predefined status values
- **Length**: 100 characters
- **Required**: `false`
- **Mass Update**: Disabled

#### revision
- **Type**: `varchar` - Text field for version tracking
- **Length**: 255 characters
- **Required**: `false`
- **Used for tracking article versions

### User Relationship Fields
#### Author Relationship
- **user_id_c**: ID field linking to Users table (36 characters)
- **author**: Relate field displaying author name
  - **Source**: `non-db` - Calculated from relationship
  - **Module**: `Users`
  - **ID Field**: `user_id_c`
  - **Display Field**: `name`
  - **Required**: `true`
  - **Quick Search**: Enabled

#### Approver Relationship
- **user_id1_c**: ID field linking to Users table (36 characters)
- **approver**: Relate field displaying approver name
  - **Source**: `non-db` - Calculated from relationship
  - **Module**: `Users`
  - **ID Field**: `user_id1_c`
  - **Display Field**: `name`
  - **Required**: `false`
  - **Quick Search**: Enabled

### Category Relationship
#### aok_knowledgebase_categories
- **Type**: `link` - Relationship link field
- **Relationship**: `aok_knowledgebase_categories`
- **Source**: `non-db` - Calculated from relationship
- **Module**: `AOK_Knowledge_Base_Categories`
- **VName**: `LBL_AOK_KB_CATEGORIES_TITLE`

## Internal API Integration

### VardefManager Integration
The file uses SuiteCRM's VardefManager system to:
- Create standardized variable definitions
- Apply template configurations ('basic', 'assignable', 'security_groups')
- Ensure consistent field behavior across modules
- Integrate with framework-level functionality

### Template Applications
- **'basic'**: Standard SugarBean fields (id, name, date_entered, date_modified, etc.)
- **'assignable'**: User assignment capabilities (assigned_user_id, assigned_user_name, etc.)
- **'security_groups'**: Security group integration for access control

## Relationship Management
- **Categories**: Many-to-many relationship with Knowledge Base Categories for organization
- **Users**: Multiple user relationships for workflow (author, approver, assigned user)
- **Security Groups**: Integration with security group system for access control

## UI Functionality
- Provides field definitions for comprehensive form rendering
- Enables workflow display in list, detail, and edit views
- Supports relationship display in subpanels and related record sections
- Quick search functionality for user selection fields
- Full-text search optimization for content discovery

## Security Features
- Audit trail enabled for tracking article changes and revisions
- Duplicate merge support for data quality management
- Optimistic locking prevents data corruption from concurrent edits
- Security group integration for role-based access control
- User-based field security through author and approver relationships

## Integration Points
- **Knowledge Base Categories Module**: Article categorization relationship
- **Users Module**: Author, approver, and assignment relationships
- **VardefManager**: Framework integration for field management
- **Language System**: UI label integration through VName references
- **Search System**: Global and full-text search inclusion
- **Security Groups**: Access control integration

## File Dependencies
- Requires: `include/SugarObjects/VardefManager.php`
- Related: `modules/AOK_Knowledge_Base_Categories/vardefs.php` (relationship target)
- Related: `modules/Users/vardefs.php` (user relationships)
- Related: `modules/AOK_KnowledgeBase/language/en_us.lang.php` (UI labels)
- Related: Global dropdown definitions for status options 