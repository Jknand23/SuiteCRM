# en_us.lang.php Documentation

/**
 * @fileoverview English language strings for Knowledge Base Categories module providing localized UI labels and messages
 * @package modules/AOK_Knowledge_Base_Categories/language
 * @copyright SugarCRM Inc. & SalesAgility Ltd.
 * @license AGPL v3
 */

## Overview
The en_us.lang.php file contains English language strings for the Knowledge Base Categories module. It provides all user-interface labels, messages, and text used throughout the module's various views and components.

## Language String Categories

### Standard Record Fields
- `LBL_ASSIGNED_TO_ID` - "Assigned User Id"
- `LBL_ASSIGNED_TO_NAME` - "Assigned to"
- `LBL_ID` - "ID"
- `LBL_DATE_ENTERED` - "Date Created"
- `LBL_DATE_MODIFIED` - "Date Modified"
- `LBL_DESCRIPTION` - "Description"
- `LBL_NAME` - "Name"
- `LBL_DELETED` - "Deleted"

### Audit Trail Fields
- `LBL_MODIFIED` - "Modified By"
- `LBL_MODIFIED_ID` - "Modified By Id"
- `LBL_MODIFIED_NAME` - "Modified By Name"
- `LBL_CREATED` - "Created By"
- `LBL_CREATED_ID` - "Created By Id"
- `LBL_CREATED_USER` - "Created by User"
- `LBL_MODIFIED_USER` - "Modified by User"

### Module-Specific Labels
- `LBL_MODULE_NAME` - "KB Categories"
- `LBL_MODULE_TITLE` - "KB Categories"
- `LBL_LIST_NAME` - "Name" (for list view)
- `LBL_HOMEPAGE_TITLE` - "My KB Categories"

### Form and View Labels
- `LBL_LIST_FORM_TITLE` - "KB Categories List"
- `LBL_NEW_FORM_TITLE` - "New KB Categories"
- `LBL_SEARCH_FORM_TITLE` - "Search KB Categories"

### Action Labels
- `LBL_EDIT_BUTTON` - "Edit"
- `LBL_REMOVE` - "Remove"
- `LNK_NEW_RECORD` - "Create KB Categories"
- `LNK_LIST` - "View KB Categories"
- `LNK_IMPORT_AOK_KB_CATEGORIES` - "Import KB Categories"

### Subpanel Labels
- `LBL_HISTORY_SUBPANEL_TITLE` - "View History"
- `LBL_ACTIVITIES_SUBPANEL_TITLE` - "Activities"
- `LBL_AOK_KB_CATEGORIES_SUBPANEL_TITLE` - "KB Categories"

### Relationship Labels
- `LBL_AOK_KB_TITLE` - "Knowledge Base" (for relationship with knowledge base articles)

## UI Functionality
- Provides localized text for all user interface elements
- Supports form labels in create, edit, and detail views
- Enables list view column headers and search form labels
- Provides navigation menu item labels
- Supports subpanel titles and relationship displays

## Internal API Integration
- Integrates with SuiteCRM's language system through `$mod_strings` array
- Used by Menu.php for navigation labels
- Referenced in metadata files for view rendering
- Supports vardefs.php through VName references

## Localization Features
- Follows SuiteCRM's standard language file structure
- Enables translation to other languages through parallel language files
- Supports regional customization of terminology
- Maintains consistency with other module language conventions

## Integration Points
- **Menu System**: Provides labels for navigation items
- **View Rendering**: Supplies text for forms and displays
- **Metadata System**: Referenced through VName in vardefs
- **Search System**: Provides labels for search interfaces
- **Subpanel System**: Titles for related record sections

## File Dependencies
- Used by: `modules/AOK_Knowledge_Base_Categories/Menu.php`
- Used by: `modules/AOK_Knowledge_Base_Categories/vardefs.php` (VName references)
- Used by: Various metadata files for view rendering
- Related: Other language files (e.g., es_ES.lang.php) for multilingual support 