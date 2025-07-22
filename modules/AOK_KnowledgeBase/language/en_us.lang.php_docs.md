# en_us.lang.php Documentation

/**
 * @fileoverview English language strings for Knowledge Base module providing localized UI labels and workflow messages
 * @package modules/AOK_KnowledgeBase/language
 * @copyright SugarCRM Inc. & SalesAgility Ltd.
 * @license AGPL v3
 */

## Overview
The en_us.lang.php file contains English language strings for the Knowledge Base module. It provides all user-interface labels, messages, and text used throughout the module's various views and components, including specialized workflow labels for knowledge base article management.

## Language String Categories

### Standard Record Fields
- `LBL_ASSIGNED_TO_ID` - "Assigned User Id"
- `LBL_ASSIGNED_TO_NAME` - "Assigned to"
- `LBL_ID` - "ID"
- `LBL_DATE_ENTERED` - "Date Created"
- `LBL_DATE_MODIFIED` - "Date Modified"
- `LBL_DELETED` - "Deleted"

### Content Fields
- `LBL_NAME` - "Title" (article title)
- `LBL_DESCRIPTION` - "Body" (main article content)
- `LBL_ADDITIONAL_INFO` - "Resolution" (solution information)

### Audit Trail Fields
- `LBL_MODIFIED` - "Modified By"
- `LBL_MODIFIED_NAME` - "Modified By Name"
- `LBL_CREATED` - "Created By"
- `LBL_CREATED_USER` - "Created by User"
- `LBL_MODIFIED_USER` - "Modified by User"

### Module-Specific Labels
- `LBL_MODULE_NAME` - "Knowledge Base"
- `LBL_MODULE_TITLE` - "Knowledge Base"
- `LBL_LIST_NAME` - "Name" (for list view)
- `LBL_HOMEPAGE_TITLE` - "My Knowledge Base"

### Form and View Labels
- `LBL_LIST_FORM_TITLE` - "Knowledge Base List"
- `LBL_NEW_FORM_TITLE` - "New Knowledge Base"
- `LBL_SEARCH_FORM_TITLE` - "Search Knowledge Base"

### Action Labels
- `LBL_EDIT_BUTTON` - "Edit"
- `LBL_REMOVE` - "Remove"
- `LNK_NEW_RECORD` - "Create Knowledge Base"
- `LNK_LIST` - "View Knowledge Base"

### Subpanel Labels
- `LBL_HISTORY_SUBPANEL_TITLE` - "View History"
- `LBL_ACTIVITIES_SUBPANEL_TITLE` - "Activities"

### Workflow-Specific Labels
- `LBL_STATUS` - "Status" (article status field)
- `LBL_REVISION` - "Revision" (version tracking)
- `LBL_AUTHOR_USER_ID` - "Author (related User ID)"
- `LBL_AUTHOR` - "Author" (article author)
- `LBL_APPROVER_USER_ID` - "Approver (related User ID)"
- `LBL_APPROVER` - "Approver" (article approver)

### Relationship Labels
- `LBL_AOK_KB_CATEGORIES_TITLE` - "Categories" (for relationship with categories)

## UI Functionality
- Provides localized text for all user interface elements
- Supports comprehensive form labels in create, edit, and detail views
- Enables list view column headers and search form labels
- Provides navigation menu item labels
- Supports subpanel titles and relationship displays
- Includes workflow-specific terminology for knowledge base management

## Internal API Integration
- Integrates with SuiteCRM's language system through `$mod_strings` array
- Used by Menu.php for navigation labels
- Referenced in metadata files for view rendering
- Supports vardefs.php through VName references
- Provides workflow-specific labels for status and approval processes

## Workflow Support
- Article lifecycle labels (status, revision)
- User role labels (author, approver)
- Content-specific terminology (body, resolution)
- Supports knowledge base approval workflow through specialized labels

## Localization Features
- Follows SuiteCRM's standard language file structure
- Enables translation to other languages through parallel language files
- Supports regional customization of terminology
- Maintains consistency with other module language conventions
- Includes knowledge base-specific terminology

## Integration Points
- **Menu System**: Provides labels for navigation items
- **View Rendering**: Supplies text for forms and displays
- **Metadata System**: Referenced through VName in vardefs
- **Search System**: Provides labels for search interfaces
- **Subpanel System**: Titles for related record sections
- **Workflow System**: Labels for status and approval processes

## File Dependencies
- Used by: `modules/AOK_KnowledgeBase/Menu.php`
- Used by: `modules/AOK_KnowledgeBase/vardefs.php` (VName references)
- Used by: Various metadata files for view rendering
- Related: Other language files (e.g., es_ES.lang.php) for multilingual support
- Related: Global dropdown language definitions for status options 