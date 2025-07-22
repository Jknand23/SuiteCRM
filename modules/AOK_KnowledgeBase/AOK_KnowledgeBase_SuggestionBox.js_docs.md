# AOK_KnowledgeBase_SuggestionBox.js Documentation

/**
 * @fileoverview JavaScript functionality for Knowledge Base article suggestion box with interactive tooltips and content transfer
 * @package modules/AOK_KnowledgeBase
 * @author SalesAgility (Andrew Mclaughlan)
 * @copyright SugarCRM Inc. & SalesAgility Ltd.
 * @license AGPL v3
 */

## Overview
The AOK_KnowledgeBase_SuggestionBox.js file provides interactive JavaScript functionality for suggesting relevant Knowledge Base articles based on user input. It implements real-time search suggestions, interactive tooltips using Qtip, and content transfer capabilities to enhance the user experience when working with knowledge base content.

## Core Functionality

### Real-Time Article Suggestions
Uses jQuery's `bindWithDelay` to provide intelligent search suggestions:
- **Trigger**: Keyup events on the `#name` field
- **Delay**: 1000ms to prevent excessive server requests
- **Target Endpoint**: `index.php?module=Cases&action=get_kb_articles`
- **Response**: Populates `#suggestion_box` with matching articles

### Interactive Article Tooltips
Implements Qtip-based tooltips for article preview:
- **Trigger**: Mouseover events on `.kb_article` elements
- **Content Source**: `index.php?module=Cases&action=get_kb_article`
- **Features**: Ajax-loaded content, positioning controls, fade effects
- **Update Mechanism**: Dynamic content updates for existing tooltips

### Content Transfer Animation
Provides animated content transfer functionality:
- **Source**: `#additional_info_p` element content
- **Target**: `#resolution` field
- **Animation**: jQuery UI transfer effect with 1000ms duration
- **Callback**: Delayed text insertion after animation completion

## External API Integration

### Cases Module Integration
The suggestion functionality integrates with the Cases module endpoints:
```javascript
// Article search endpoint
'index.php?module=Cases&action=get_kb_articles'

// Article content retrieval endpoint  
'index.php?module=Cases&action=get_kb_article'
```

### AJAX Communication
- **Search Requests**: POST requests with search parameters
- **Article Requests**: POST requests with article ID
- **Response Handling**: Dynamic HTML content injection
- **Error Handling**: Implicit through jQuery AJAX

## UI Functionality

### Search Interface
- **Real-time Search**: Responds to user typing with configurable delay
- **Results Display**: Populates suggestion box with relevant articles
- **User Experience**: Prevents overwhelming server with rapid requests

### Tooltip System
- **Position Configuration**: 
  - Tooltip: `middle right`
  - Target: `middle left`
  - Adjustments: No mouse following, scroll adjustment enabled
- **Show Behavior**: 1000ms delay, fade-in effect over 800ms
- **Hide Behavior**: Manual close via button, no auto-hide
- **Styling**: Uses Qtip default classes with shadow effects

### Animation Features
- **Transfer Effect**: Visual connection between source and destination
- **Text Movement**: Smooth content transfer with timing controls
- **User Feedback**: Clear visual indication of content movement

## JavaScript Dependencies

### jQuery Libraries
- **jQuery Core**: Base functionality for DOM manipulation and events
- **jQuery UI**: Transfer effect for content animation
- **bindWithDelay**: Custom plugin for delayed event handling
- **Qtip**: Tooltip library for interactive overlays

### Language Integration
```javascript
var title = SUGAR.language.get('Cases', 'LBL_TOOL_TIP_BOX_TITLE');
```
- Integrates with SuiteCRM's language system
- Provides localized tooltip titles
- Maintains consistency with module translations

## Performance Optimizations

### Request Throttling
- **Delay Mechanism**: 1000ms delay prevents excessive server requests
- **User Experience**: Balances responsiveness with server efficiency
- **Resource Management**: Reduces server load during typing

### Tooltip Management
- **Reuse Strategy**: Updates existing tooltips instead of creating new ones
- **Positioning Control**: Automatic screen boundary detection and adjustment
- **Memory Efficiency**: Avoids tooltip proliferation

### Screen Position Management
```javascript
setInterval(function(){
    $('.qtip').each(function(i,e){
        if(parseInt($(e).css('left'))<0) {
            $(e).animate({left: 0});
        }
    });
}, 300);
```
- **Boundary Checking**: Ensures tooltips remain visible on screen
- **Automatic Correction**: Animates tooltips back into visible area
- **Continuous Monitoring**: 300ms interval for position validation

## Integration Points
- **Cases Module**: Primary integration point for article suggestions
- **Knowledge Base Module**: Source of article content and data
- **SuiteCRM Language System**: Localized text and messages
- **jQuery UI Framework**: Animation and effect libraries
- **Qtip Library**: Advanced tooltip functionality

## User Experience Features
- **Contextual Suggestions**: Articles suggested based on current input
- **Non-intrusive Tooltips**: Hover-based article previews
- **Visual Feedback**: Animated content transfer with clear user guidance
- **Responsive Design**: Automatic positioning and screen boundary management
- **Accessibility**: Keyboard and mouse interaction support

## File Dependencies
- jQuery library and plugins (bindWithDelay, UI effects)
- Qtip library for tooltip functionality
- SuiteCRM language system integration
- Cases module endpoints for article data
- CSS classes for styling and animation effects 