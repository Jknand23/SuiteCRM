# Quick Note Capture Feature Documentation

## Overview

The Quick Note Capture feature provides a floating action button (FAB) interface for rapidly creating notes from any record detail view in SuiteCRM. This feature dramatically improves user productivity by eliminating the need to navigate away from the current record to create notes.

## Key Features

- **Floating Action Button (FAB)**: Persistent UI element on all detail views
- **Quick Note Modal**: Streamlined interface for rapid note entry
- **Auto-linking**: Automatic parent record association based on context
- **Keyboard Shortcuts**: Ctrl+Shift+N for instant access
- **Smart Context Detection**: Identifies current module and record for proper linking
- **Auto-save with Drafts**: Draft protection and recovery every 30 seconds
- **Conflict Resolution**: Handles simultaneous edits gracefully
- **User Preferences**: Customizable FAB visibility per user

## Technical Architecture

### Frontend Components

1. **JavaScript FAB Component** (`custom/include/javascript/quick-note-fab.js`)
   - SUGAR namespace integration
   - Context detection from URL parameters
   - Auto-save functionality with localStorage
   - User preference checking via AJAX
   - Keyboard shortcut handling

2. **Modal Template** (`custom/modules/Notes/tpls/QuickNoteModal.tpl`)
   - Smarty template with form fields
   - Auto-save indicators
   - Draft restoration UI
   - Inline validation

3. **CSS Styling** (`custom/themes/SuiteP/css/quick-note-fab.css`)
   - Theme-compliant styling
   - Responsive design
   - Accessibility features
   - Animation effects

### Backend Components

1. **QuickNoteCreate Class** (`custom/modules/Notes/QuickNoteCreate.php`)
   - Extends PopupQuickCreate
   - ACL permission validation
   - Auto-population of parent fields
   - Error handling with proper responses

2. **Notes Controller** (`custom/modules/Notes/controller.php`)
   - AJAX endpoints for note creation
   - Modal template loading
   - Conflict detection and resolution
   - User preference management

3. **User Preferences**
   - Custom field definition (`custom/Extension/modules/Users/Ext/Vardefs/quick_note_fab_preference.php`)
   - Preference view (`custom/modules/Users/views/view.preferences.php`)
   - Menu integration (`custom/modules/Users/Menu.php`)

## Implementation Details

### File Structure
```
custom/
├── modules/
│   ├── Notes/
│   │   ├── controller.php           # AJAX controller
│   │   ├── QuickNoteCreate.php      # Note creation logic
│   │   └── tpls/
│   │       └── QuickNoteModal.tpl   # Modal template
│   └── Users/
│       ├── Menu.php                  # Custom menu items
│       ├── views/
│       │   └── view.preferences.php  # Preference management
│       └── tpls/
│           └── preferences.tpl       # Preference UI
├── include/
│   └── javascript/
│       └── quick-note-fab.js        # Frontend JavaScript
├── themes/
│   └── SuiteP/
│       └── css/
│           └── quick-note-fab.css    # Styling
└── Extension/
    └── modules/
        └── Users/
            └── Ext/
                ├── Vardefs/
                │   └── quick_note_fab_preference.php
                └── Language/
                    └── en_us.quick_note_fab.php
```

### Auto-save Implementation

The auto-save feature uses the following approach:

1. **Timer-based Saving**: Saves every 30 seconds automatically
2. **Input-based Saving**: Debounced save on field changes (5-second delay)
3. **LocalStorage**: Drafts stored with unique keys per parent record
4. **Conflict Detection**: Checks for notes created since draft started
5. **Draft Recovery**: Automatically restores drafts when modal reopens

### Security Considerations

1. **ACL Validation**: All operations check user permissions
2. **CSRF Protection**: Uses SuiteCRM's built-in CSRF tokens
3. **Input Validation**: Server-side validation of all inputs
4. **XSS Prevention**: Proper output encoding in templates

## User Guide

### Enabling/Disabling the Feature

1. Navigate to your User Profile
2. Click "Quick Note Preferences" in the menu
3. Toggle "Enable Quick Note Button"
4. Save preferences

### Creating a Quick Note

1. **Using the FAB**:
   - Click the floating note button in the bottom-right corner
   - Fill in the subject and description
   - Click "Save Note"

2. **Using Keyboard Shortcut**:
   - Press Ctrl+Shift+N from any detail view
   - Complete the form and save

### Auto-save and Drafts

- Notes are automatically saved as drafts every 30 seconds
- If you close the modal, your draft is preserved
- When reopening, you'll see a "Draft restored" message
- Click "Clear Draft" to start fresh

### Conflict Resolution

If another user creates a note while you're editing:
- You'll see a conflict warning when saving
- Review the recently created notes
- Choose to continue saving or cancel

## Configuration

### User Preferences

Users can control FAB visibility through:
- User Profile → Quick Note Preferences
- Default: Enabled for all users

### Module Restrictions

The FAB only appears on:
- Detail views (not list views or edit views)
- Records with valid IDs
- Modules where user has Notes creation permission

## Troubleshooting

### FAB Not Appearing

1. Check user preferences are enabled
2. Verify you're on a Detail View
3. Confirm Notes module permissions
4. Clear browser cache

### Auto-save Not Working

1. Check browser localStorage is enabled
2. Verify JavaScript console for errors
3. Ensure sufficient localStorage space

### Permission Errors

1. Verify Notes module create permissions
2. Check parent module view permissions
3. Review ACL roles and settings

## Performance Optimization

- Parent record names cached for 15 minutes
- Lazy loading of modal content
- Debounced auto-save to reduce server calls
- Efficient DOM manipulation

## Future Enhancements

1. **Rich Text Editor**: Integration with TinyMCE
2. **File Attachments**: Quick file upload support
3. **Templates**: Pre-defined note templates
4. **Voice Notes**: Speech-to-text capability
5. **Mobile Optimization**: Touch-friendly interface

## Database Changes

After installation, run a Quick Repair and Rebuild to:
- Create the `quick_note_fab_enabled` field in users table
- Update field definitions
- Clear caches

## Browser Compatibility

- Chrome/Edge: Full support
- Firefox: Full support
- Safari: Full support
- IE11: Basic support (no auto-save) 