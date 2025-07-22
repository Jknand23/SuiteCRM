# AOK_Knowledge_Base_CategoriesDashlet.meta.php Documentation

/**
 * @fileoverview Metadata configuration for Knowledge Base Categories dashlet defining properties and registration
 * @package modules/AOK_Knowledge_Base_Categories/Dashlets
 * @copyright SugarCRM Inc. & SalesAgility Ltd.
 * @license AGPL v3
 */

## Overview
The AOK_Knowledge_Base_CategoriesDashlet.meta.php file defines the metadata configuration for the Knowledge Base Categories dashlet. It registers the dashlet with SuiteCRM's dashboard system and provides essential properties for dashlet management and display.

## Dashlet Registration

### Global Registration
The file registers the dashlet in the global `$dashletMeta` array:
```php
$dashletMeta['AOK_Knowledge_Base_CategoriesDashlet']
```

This enables the dashlet to appear in the available dashlets list for user selection.

## Dashlet Configuration Properties

### Module Association
- **module**: `'AOK_Knowledge_Base_Categories'` - Links dashlet to specific module
- Enables data access and integration with module functionality
- Ensures proper context for bean operations and data display

### Display Properties
- **title**: `translate('LBL_HOMEPAGE_TITLE', 'AOK_Knowledge_Base_Categories')` - Default dashlet title
- **description**: `'A customizable view into AOK_Knowledge_Base_Categories'` - User-facing description
- **icon**: `'icon_AOK_Knowledge_Base_Categories_32.gif'` - Visual identifier for dashlet selection

### Category Classification
- **category**: `'Module Views'` - Groups dashlet in selection interface
- Helps users find module-specific dashlets among available options
- Maintains organization in dashlet library

## UI Integration

### Dashboard Selection Interface
- Appears in "Add Dashlets" interface under "Module Views" category
- Displays with module-specific icon and descriptive title
- Provides user-friendly description for dashlet functionality

### Language Integration
- Uses module language strings for consistent localization
- Supports translation through SuiteCRM's language system
- Maintains terminology consistency with module interface

## Internal API Integration
- **Global Registration**: Integrates with SuiteCRM's dashlet discovery system
- **Language System**: Uses translate() function for localized text
- **Icon System**: References standard module icon resources
- **Category System**: Participates in dashlet organization framework

## Integration Points
- **Dashboard Framework**: Registers dashlet for user selection
- **Module System**: Links to Knowledge Base Categories module
- **Language System**: Provides localized text through translation
- **Icon System**: Visual representation in selection interface
- **User Interface**: Categorization for organized dashlet selection

## User Experience Features
- Clear, descriptive title for easy identification
- Helpful description explaining dashlet functionality
- Logical categorization in "Module Views" section
- Visual icon for quick recognition
- Localized text supporting multiple languages

## File Dependencies
- Uses: Global `$app_strings` array (imported but not directly used)
- Related: `modules/AOK_Knowledge_Base_Categories/language/en_us.lang.php` (for title translation)
- Related: Module icon file: `icon_AOK_Knowledge_Base_Categories_32.gif`
- Integrates: Dashboard system's dashlet registration framework 