# license.php Documentation

## @fileoverview
License display and acceptance interface that presents the SuiteCRM license agreement and handles user acceptance validation during the installation process.

## @package SuiteCRM Installation
## @copyright SalesAgility Ltd.
## @license AGPL-3.0

## Overview

This file provides a dedicated interface for displaying the complete SuiteCRM license text and managing user acceptance. It ensures legal compliance by requiring explicit user acknowledgment of the AGPL license terms before proceeding with installation.

## Core Functionality

### License Presentation
- **Full License Display**: Presents complete AGPL license text in readable format
- **Scrollable Interface**: Provides scrollable textarea for license review
- **Print Functionality**: Offers printable version for offline reference
- **Accessibility Features**: Ensures license is accessible to all users

### Acceptance Management
- **Explicit Consent**: Requires user to explicitly check acceptance checkbox
- **Validation Logic**: Prevents proceeding without valid license acceptance
- **Session Persistence**: Maintains acceptance state across installation steps
- **Legal Compliance**: Ensures proper license acknowledgment per AGPL requirements

### User Interface
- **Professional Layout**: Clean, professional license presentation
- **Interactive Elements**: Checkbox, links, and button controls
- **Progress Indication**: Shows current step in installation process
- **Navigation Controls**: Appropriate next/back navigation options

## UI Functionality

### License Display Interface
The license page creates a focused license reading experience:

#### HTML Structure
```php
$out =<<<EOQ
<!DOCTYPE HTML>
<html {$langHeader}>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title>{$mod_strings['LBL_WIZARD_TITLE']} {$mod_strings['LBL_LICENSE_ACCEPTANCE']}</title>
EOQ;
```

#### Visual Elements
- **Header Section**: Installation branding and progress indicators
- **License Textarea**: Large, scrollable license text display
- **Acceptance Controls**: Checkbox and acceptance link
- **Print Button**: Dedicated print functionality
- **Navigation Footer**: Installation step controls

### Interactive Components
- **Acceptance Checkbox**: Primary acceptance mechanism
- **Acceptance Link**: Alternative method to toggle acceptance
- **Print License Button**: Opens printable license version
- **JavaScript Integration**: Dynamic UI behavior and validation

## Session Management

### State Persistence
Manages license acceptance state through session variables:
```php
if (!isset($_SESSION['license_submitted']) || !$_SESSION['license_submitted']) {
    $_SESSION['setup_license_accept'] = false;
}
$checked = (isset($_SESSION['setup_license_accept']) && !empty($_SESSION['setup_license_accept'])) ? 'checked="on"' : '';
```

### Configuration Storage
- **Acceptance Tracking**: Records user license acceptance
- **Submission State**: Tracks whether license step has been submitted
- **Validation State**: Maintains validation results
- **Progress Tracking**: Records completion of license step

### Default Values
- License acceptance defaults to false (unchecked)
- Submission state defaults to false
- Progress indicators show current step

## JavaScript Integration

### Client-Side Functionality
- **Dynamic Validation**: Real-time validation of license acceptance
- **Toggle Functionality**: `toggleLicenseAccept()` for acceptance management
- **Button State Management**: `toggleNextButton()` for navigation control
- **Focus Management**: Automatic focus on acceptance checkbox

### User Experience Enhancement
- **Immediate Feedback**: Instant visual feedback for user actions
- **Keyboard Navigation**: Full keyboard accessibility
- **Print Integration**: Seamless print functionality
- **Responsive Behavior**: Adaptive behavior across devices

## Integration Points

### Installation Utilities
Integrates with `install_utils.php` for:
- **License Content Loading**: `getLicenseContents("LICENSE.txt")`
- **Language Processing**: Language header generation
- **Session Management**: Session variable handling
- **Installation Context**: Installation script validation

### Resource Management
- **CSS Styling**: Professional installation styling integration
- **JavaScript Libraries**: YUI framework for UI components
- **Font Integration**: Icon fonts for visual elements
- **Theme System**: SuiteP theme compatibility

## License File Handling

### License Content Loading
```php
require_once("install/install_utils.php");
$license_file = getLicenseContents("LICENSE.txt");
```

### File Management
- **License File Reading**: Reads LICENSE.txt from file system
- **Content Validation**: Ensures license file exists and is readable
- **Character Encoding**: Handles UTF-8 encoding properly
- **Error Handling**: Manages file reading errors gracefully

### Print Functionality
- **Print Version Generation**: Creates printer-friendly license version
- **Window Management**: Opens print version in new window
- **Content Formatting**: Optimizes license formatting for printing
- **Browser Compatibility**: Works across different browsers

## Security Considerations

### Input Validation
- **Entry Point Validation**: Ensures proper script access
- **Installation Context**: Validates installation script context
- **Session Security**: Secure session variable handling
- **XSS Prevention**: Proper output escaping in HTML generation

### Legal Compliance
- **AGPL Requirements**: Ensures compliance with AGPL license terms
- **Acceptance Documentation**: Maintains record of user acceptance
- **License Integrity**: Ensures license text integrity
- **Legal Notices**: Displays required legal notices

## User Interface Features

### Professional Presentation
- **Clean Layout**: Uncluttered, professional license display
- **Brand Consistency**: Maintains SuiteCRM visual identity
- **Typography**: Readable font choices and sizing
- **Visual Hierarchy**: Clear information organization

### Accessibility Features
- **Screen Reader Support**: Proper semantic HTML structure
- **Keyboard Navigation**: Full keyboard accessibility
- **High Contrast**: Compatible with high contrast modes
- **Alternative Text**: Appropriate alt text for images

### Responsive Design
- **Mobile Compatibility**: Works effectively on mobile devices
- **Adaptive Layout**: Scales appropriately across screen sizes
- **Touch-Friendly**: Optimized for touch interfaces
- **Cross-Browser**: Compatible with major browsers

## Error Handling

### Validation Errors
- **Acceptance Validation**: Ensures license acceptance before proceeding
- **File Access Errors**: Handles license file reading failures
- **Session Errors**: Manages session initialization problems
- **JavaScript Errors**: Graceful degradation for disabled JavaScript

### User Feedback
- **Clear Messages**: Provides clear validation error messages
- **Visual Indicators**: Uses visual cues for required actions
- **Help Integration**: Links to help and documentation
- **Recovery Options**: Allows users to correct errors

## Performance Optimization

### Resource Loading
- **CSS Optimization**: Efficient stylesheet loading
- **JavaScript Loading**: Optimized script loading and execution
- **Image Optimization**: Optimized images for faster loading
- **Cache Management**: Appropriate caching for static resources

### Page Generation
- **Template Efficiency**: Efficient HTML generation
- **Session Optimization**: Minimal session data storage
- **Memory Management**: Efficient variable usage
- **File I/O Optimization**: Optimized license file reading

## Development Support

### Customization Points
- **License Content**: License file can be updated independently
- **Styling Customization**: CSS customization for branding
- **Layout Modification**: Template structure supports modification
- **Functionality Extension**: Can be extended with additional features

### Testing Features
- **Validation Testing**: Comprehensive acceptance validation testing
- **Cross-Browser Testing**: Testing across different browsers
- **Mobile Testing**: Mobile device compatibility testing
- **Accessibility Testing**: Screen reader and keyboard testing

## Dependencies

### System Requirements
- **File System Access**: Read access to LICENSE.txt file
- **PHP Sessions**: Session handling for state management
- **Web Browser**: Modern browser with JavaScript support
- **Character Encoding**: UTF-8 support for license text

### SuiteCRM Components
- **Installation Utilities**: Integration with installation helper functions
- **Language System**: Internationalization framework
- **JavaScript Framework**: YUI and jQuery libraries
- **Theme System**: SuiteP theme integration

## Legal Compliance

### AGPL License Requirements
- **Complete License Display**: Full license text presentation
- **User Acceptance**: Explicit user acceptance requirement
- **Documentation**: Maintains record of acceptance
- **Legal Notices**: Proper display of required notices

### Compliance Features
- **Acceptance Tracking**: Records acceptance for audit purposes
- **License Integrity**: Ensures license text hasn't been modified
- **Print Capability**: Allows users to retain license copy
- **Clear Presentation**: Ensures license is clearly readable

## Integration Workflow

### Installation Process
1. **Page Load**: User accesses license page
2. **License Display**: System presents full license text
3. **User Review**: User reviews license terms
4. **Acceptance Action**: User checks acceptance checkbox
5. **Validation**: System validates acceptance
6. **Progress**: Advances to next installation step

### Session Flow
- **Initialization**: Sets up default acceptance state
- **User Interaction**: Processes acceptance actions
- **Validation**: Validates acceptance requirement
- **Storage**: Stores acceptance in session
- **Navigation**: Enables progression when accepted

## Maintenance Considerations

### License Updates
- **File-Based Updates**: License updates through file replacement
- **Version Control**: Maintains license version information
- **Change Documentation**: Documents license change history
- **Compliance Tracking**: Ensures ongoing compliance

### Code Maintenance
- **Template Updates**: Easy template modification and updates
- **Styling Updates**: CSS modifications for appearance changes
- **Functionality Enhancements**: Modular structure supports additions
- **Security Updates**: Regular security review and updates 