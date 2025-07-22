# AOK_KnowledgeBase.php Documentation

/**
 * @fileoverview Main Knowledge Base class providing extensible foundation for knowledge base article management
 * @package modules/AOK_KnowledgeBase
 * @copyright SugarCRM Inc. & SalesAgility Ltd.
 * @license AGPL v3
 */

## Overview
The `AOK_KnowledgeBase` class serves as the primary module class for managing knowledge base articles in SuiteCRM. It extends the generated sugar class to provide a foundation for customizations while maintaining the core functionality for creating, managing, and organizing knowledge base content.

## Class Structure

### Class Hierarchy
- **AOK_KnowledgeBase** (Main class for customizations)
  - Extends **AOK_KnowledgeBase_sugar** (Generated base class)

### Constructor
The constructor initializes the parent class to ensure proper inheritance of all base functionality from the sugar-generated class.

## Database Operations
- Inherits standard SugarBean database operations through parent class
- Uses table: `aok_knowledgebase`
- Supports full CRUD operations for knowledge base article management
- Includes audit trail for tracking article changes and revisions

## Internal API Integration
- Integrates with Knowledge Base Categories module (AOK_Knowledge_Base_Categories) for article organization
- Uses SuiteCRM's standard module architecture patterns
- Implements ACL (Access Control List) integration for security
- Connects with Users module for author and approver relationships

## UI Functionality
- Provides foundation for knowledge base article creation, editing, and management
- Supports list view, detail view, and edit view interfaces
- Integrates with standard SuiteCRM navigation and menu systems
- Enables rich content management through description and additional_info fields

## Integration Points
- **AOK_Knowledge_Base_Categories Module**: Categorization relationship for organizing articles
- **Users Module**: Author and approver relationships for article workflow
- **ACL System**: Controls user access to knowledge base operations
- **Module Builder**: Generated base class supports studio customizations
- **Assignment System**: Inherits user assignment capabilities
- **Security Groups**: Supports security group integration for access control

## File Dependencies
- Requires: `modules/AOK_KnowledgeBase/AOK_KnowledgeBase_sugar.php`
- Related: `modules/AOK_KnowledgeBase/vardefs.php`
- Related: `modules/AOK_KnowledgeBase/Menu.php`
- Related: `modules/AOK_Knowledge_Base_Categories/` (for categorization)

## Customization Notes
This class is specifically designed for developer customizations. All modifications should be made in this file rather than the sugar-generated base class to prevent loss during upgrades. The modular design supports extension of knowledge base functionality while maintaining compatibility with the SuiteCRM framework. 