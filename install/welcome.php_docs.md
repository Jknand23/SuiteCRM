# welcome.php Documentation

## @fileoverview
Initial installation wizard interface that provides language selection, license acceptance, and entry point to the SuiteCRM installation process.

## @package SuiteCRM Installation
## @copyright SalesAgility Ltd.
## @license AGPL-3.0

## Overview

This file serves as the first interactive page of the SuiteCRM installation wizard, providing users with language selection capabilities, license acceptance interface, and system branding. It establishes the foundation for the installation process by setting user preferences and validating legal acceptance requirements.

## Core Functionality

### Language Selection
- **Multi-Language Support**: Provides dropdown selection of available installation languages
- **Dynamic Language Loading**: Generates language options from supported language array
- **Session Persistence**: Stores selected language in session for installation continuity
- **Locale Detection**: Can detect browser language preferences for auto-selection

### License Acceptance Interface
- **License Display**: Presents the full SuiteCRM license text in readable format
- **Acceptance Validation**: Requires explicit user acceptance before proceeding
- **Legal Compliance**: Ensures proper license acknowledgment per AGPL requirements
- **Print Option**: Provides printable license version for offline reference

### Installation Branding
- **Professional Appearance**: Displays SuiteCRM branding and visual identity
- **Progress Indicators**: Shows installation step progression with visual cues
- **Responsive Design**: Adapts to different screen sizes and devices
- **External Links**: Provides access to documentation and support resources

## UI Functionality

### Page Structure
The welcome page generates complete HTML5 structure including:

#### HTML Document Setup
```php
$out = <<<EOQ
<!DOCTYPE HTML>
<html {$langHeader}>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
   <title>{$mod_strings['LBL_WIZARD_TITLE']} {$mod_strings['LBL_TITLE_WELCOME']} {$setup_sugar_version}</title>
EOQ;
```

#### Visual Elements
- **Header Section**: SuiteCRM logo and branding
- **Progress Bar**: Visual indication of installation progress
- **Language Selector**: Dropdown for language selection
- **License Area**: Formatted license text display
- **Acceptance Controls**: Checkbox and agreement interface

### JavaScript Integration
Includes comprehensive JavaScript libraries:
- **jQuery**: Modern JavaScript framework for interactions
- **YUI Libraries**: Legacy UI components for compatibility
- **ResponsiveSlides**: Responsive image handling
- **Custom Scripts**: Installation-specific JavaScript functionality

### CSS Styling
Multiple stylesheet integration:
- **install2.css**: Primary installation styling
- **responsiveslides.css**: Responsive image styling
- **themes.css**: SuiteP theme integration
- **fontello.css**: Icon font support

## Session Management

### Configuration Storage
Manages installation state through session variables:
```php
$_SESSION['setup_license_accept'] = get_boolean_from_request('setup_license_accept');
$_SESSION['license_submitted'] = true;
```

### State Persistence
- **License Acceptance**: Tracks user's license acceptance status
- **Language Selection**: Maintains chosen language across installation steps
- **Progress Tracking**: Records completion of welcome step
- **Validation State**: Preserves form validation results

### Default Values
Establishes default settings if page hasn't been submitted:
- License acceptance defaults to false
- Language defaults to browser preference or English
- Progress indicators show current step status

## Integration Points

### Installation Utilities
Integrates with `install_utils.php` for:
- **License Content Loading**: `getLicenseContents("LICENSE.txt")`
- **Language Processing**: Language header and selection utilities
- **Boolean Validation**: `get_boolean_from_request()` for form processing
- **Session Utilities**: Session variable management

### Language System
- **Language File Loading**: Loads appropriate mod_strings for selected language
- **Header Generation**: Creates proper HTML language headers
- **Character Set Management**: Ensures UTF-8 encoding throughout
- **RTL Support**: Supports right-to-left languages when applicable

### JavaScript Resource Loading
```php
include('jssource/JSGroupings.php');
foreach ($sugar_grp1_yui as $jsFile => $grp) {
    $jsSrc .= "\t<script src=\"$jsFile\"></script>\n";
}
```

## External Dependencies

### System Requirements
- **PHP Sessions**: Session handling for state management
- **File System Access**: License file reading and resource loading
- **Web Browser**: Modern browser with JavaScript and CSS support
- **Character Encoding**: UTF-8 support for international characters

### SuiteCRM Components
- **Language System**: Internationalization framework
- **JavaScript Framework**: YUI and jQuery libraries
- **Theme System**: SuiteP theme integration
- **Utility Functions**: Installation helper functions

## User Interface Features

### Responsive Design
- **Mobile Support**: Responsive viewport meta tag for mobile devices
- **Adaptive Layout**: Scales appropriately across different screen sizes
- **Touch-Friendly**: Optimized for touch interfaces on tablets and phones
- **Cross-Browser**: Compatible with major web browsers

### Accessibility
- **Keyboard Navigation**: Full keyboard accessibility for form elements
- **Screen Reader Support**: Proper HTML semantics for assistive technologies
- **High Contrast**: Compatible with high contrast display modes
- **Alternative Text**: Image alt text for screen readers

### Professional Appearance
- **Brand Consistency**: Maintains SuiteCRM visual identity
- **Clean Layout**: Professional, uncluttered interface design
- **Visual Hierarchy**: Clear information organization and flow
- **Progress Feedback**: Visual indicators of installation progress

## Security Considerations

### Input Validation
- **Entry Point Validation**: Enforces proper script access via `sugarEntry` check
- **Installation Context**: Validates `$install_script` variable
- **Session Security**: Secure session variable handling
- **XSS Prevention**: Proper output escaping in HTML generation

### License Compliance
- **Legal Requirements**: Ensures AGPL license compliance
- **Acceptance Tracking**: Maintains record of user license acceptance
- **License Display**: Complete license text presentation
- **Legal Links**: Provides access to complete license terms

## Error Handling

### Validation
- **Required Acceptance**: Validates license acceptance before proceeding
- **Language Selection**: Ensures valid language choice
- **Form Submission**: Validates form data integrity
- **Session State**: Handles session initialization errors

### User Feedback
- **Clear Messages**: Provides clear error messages for validation failures
- **Visual Indicators**: Uses visual cues to indicate required actions
- **Help Text**: Includes explanatory text for user guidance
- **Recovery Options**: Allows users to correct errors and retry

## Performance Optimization

### Resource Loading
- **CSS Optimization**: Efficient stylesheet loading and organization
- **JavaScript Bundling**: Groups related JavaScript files for efficiency
- **Image Optimization**: Optimized images for faster loading
- **Cache Headers**: Appropriate caching for static resources

### Page Generation
- **Template Efficiency**: Efficient HTML generation using heredoc syntax
- **Session Optimization**: Minimal session data storage
- **Memory Management**: Efficient variable usage and cleanup
- **Database Independence**: No database access required for this page

## Development Notes

### Template Structure
The page uses heredoc syntax for HTML generation:
- Clean separation of PHP logic and HTML markup
- Efficient string concatenation and output
- Maintainable template structure
- Easy customization and branding

### Customization Points
- **Branding**: Logo and styling can be customized
- **Language Support**: Additional languages can be added
- **Layout Modification**: Template structure allows easy modification
- **Feature Extensions**: Can be extended with additional welcome features

### Browser Compatibility
- **Modern Standards**: Uses HTML5 and modern CSS
- **Legacy Support**: Includes compatibility layers for older browsers
- **Progressive Enhancement**: Graceful degradation for limited capabilities
- **Mobile Optimization**: Responsive design for mobile devices

## Integration Workflow

### Installation Process Flow
1. **Page Load**: User accesses installation wizard
2. **Language Selection**: User chooses preferred language
3. **License Display**: System presents license for review
4. **Acceptance Required**: User must explicitly accept license
5. **Validation**: System validates acceptance and language selection
6. **Progress**: Advances to next installation step

### Session Flow
- **Initialization**: Sets up session variables with defaults
- **User Input**: Processes language and license acceptance
- **Validation**: Validates required inputs
- **Storage**: Stores configuration in session
- **Navigation**: Enables progression to next step

## Maintenance Considerations

### Updates and Modifications
- **License Updates**: License file can be updated without code changes
- **Language Additions**: New languages added through configuration
- **Styling Updates**: CSS modifications for appearance changes
- **Feature Enhancements**: Modular structure supports feature additions

### Testing Requirements
- **Cross-Browser Testing**: Verify functionality across browsers
- **Mobile Testing**: Ensure responsive behavior on mobile devices
- **Accessibility Testing**: Validate screen reader and keyboard access
- **Performance Testing**: Monitor page load times and resource usage 