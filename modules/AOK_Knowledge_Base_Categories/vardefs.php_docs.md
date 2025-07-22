# vardefs.php Documentation

/**
 * @fileoverview Variable definitions for Knowledge Base Categories module defining database schema and relationships
 * @package modules/AOK_Knowledge_Base_Categories
 * @copyright SugarCRM Inc. & SalesAgility Ltd.
 * @license AGPL v3
 */

## Overview
The vardefs.php file defines the database schema, field definitions, and relationships for the Knowledge Base Categories module. It establishes the data structure and relationship mappings that enable categorization of knowledge base articles.

## Database Schema Configuration

### Table Definition
- **Table Name**: `aok_knowledge_base_categories`
- **Audited**: `true` - Enables audit trail for tracking changes
- **Duplicate Merge**: `true` - Supports duplicate record merging functionality
- **Optimistic Locking**: `true` - Prevents concurrent modification conflicts
- **Unified Search**: `true` - Enables global search functionality

## Field Definitions

### Custom Relationship Fields
#### aok_knowledgebase_categories
- **Type**: `link` - Relationship link field
- **Relationship**: `aok_knowledgebase_categories` - Links to knowledge base articles
- **Source**: `non-db` - Not stored in database, calculated from relationship
- **Module**: `AOK_KnowledgeBase` - Links to Knowledge Base module
- **Bean Name**: `false` - Uses default bean handling
- **VName**: `LBL_AOK_KB_TITLE` - UI label reference for display

## Internal API Integration

### VardefManager Integration
The file uses SuiteCRM's VardefManager system to:
- Create standardized variable definitions
- Apply template configurations ('basic', 'assignable')
- Ensure consistent field behavior across modules
- Integrate with framework-level functionality

### Template Application
- **'basic'**: Applies standard SugarBean fields (id, name, date_entered, date_modified, etc.)
- **'assignable'**: Adds user assignment capabilities (assigned_user_id, assigned_user_name, etc.)

## Relationship Management
- Defines the connection between Knowledge Base Categories and Knowledge Base articles
- Enables many-to-many relationship through the `aok_knowledgebase_categories` link
- Supports categorization of knowledge base content for organization

## UI Functionality
- Provides field definitions for form rendering in list, detail, and edit views
- Enables relationship display in subpanels and related record sections
- Supports search functionality through unified search configuration
- Label integration through VName references to language strings

## Security Features
- Audit trail enabled for tracking category changes
- Duplicate merge support for data quality management
- Optimistic locking prevents data corruption from concurrent edits

## Integration Points
- **Knowledge Base Module**: Primary relationship for article categorization
- **VardefManager**: Framework integration for field management
- **Language System**: UI label integration through VName references
- **Search System**: Global search inclusion through unified_search flag

## File Dependencies
- Requires: `include/SugarObjects/VardefManager.php`
- Related: `modules/AOK_KnowledgeBase/vardefs.php` (relationship target)
- Related: `modules/AOK_Knowledge_Base_Categories/language/en_us.lang.php` (UI labels) 