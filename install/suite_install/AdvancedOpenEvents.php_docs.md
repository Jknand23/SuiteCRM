# AdvancedOpenEvents.php_docs.md

/**
 * @fileoverview Advanced Open Events (AOE) installation module for event management and email template setup
 * @package SuiteCRM
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

This file provides installation functionality for the Advanced Open Events (AOE) module, which sets up event management capabilities and creates default email templates for event invitations.

## Database Operations

### Email Template Creation
- Creates new EmailTemplate bean using `BeanFactory::newBean('EmailTemplates')`
- Saves template configuration to database via `$emailTemp->save()`
- Establishes system-level email template for event invitations

### Template Configuration
**Default Event Invite Template Properties:**
- **Date Tracking**: Automatic `date_entered` and `date_modified` timestamps
- **Template Identity**: Name set to 'Event Invite Template'
- **Status**: Published status set to 'off' for manual activation
- **Template Type**: Marked as 'system' template for core functionality

## Internal API Calls

### Module Installation Process

**install_aoe() Function**
- **Purpose**: Main installation function for Advanced Open Events module
- **Dependencies**: 
  - `modules/Administration/Administration.php`
  - `modules/EmailTemplates/EmailTemplate.php`
- **Template Creation**: Creates default event invitation email template

### Email Template Integration
- **Bean Factory**: Uses `BeanFactory::newBean()` for EmailTemplate creation
- **System Integration**: Templates integrate with SuiteCRM email system
- **Variable Support**: Templates support dynamic variable substitution

## Email Template Configuration

### Template Content Structure

**Subject Line**
- Dynamic subject with event name variable: `"You have been invited to $fp_events_name"`
- Personalized for each event invitation

**Plain Text Body**
```
Dear $contact_name,
You have been invited to $fp_events_name on $fp_events_date_start to $fp_events_date_end
$fp_events_description
Yours Sincerely,
```

**HTML Body**
```html
<p>Dear $contact_name,</p>
<p>You have been invited to $fp_events_name on $fp_events_date_start to $fp_events_date_end</p>
<p>$fp_events_description</p>
<p>If you would like to accept this invititation please click accept.</p>
<p> $fp_events_link or $fp_events_link_declined</p>
<p>Yours Sincerely,</p>
```

### Dynamic Variables Supported

**Contact Information**
- `$contact_name`: Recipient's contact name

**Event Details**
- `$fp_events_name`: Event title/name
- `$fp_events_date_start`: Event start date and time
- `$fp_events_date_end`: Event end date and time
- `$fp_events_description`: Detailed event description

**Interactive Elements**
- `$fp_events_link`: Acceptance link for event invitation
- `$fp_events_link_declined`: Decline link for event invitation

## UI Functionality

### Email Template Management
- Template appears in EmailTemplates module for administrator management
- Can be customized and activated through admin interface
- Supports both HTML and plain text email clients

### Event Invitation Process
- Provides foundation for automated event invitation emails
- Supports RSVP functionality through interactive links
- Maintains professional email formatting and branding

## System Integration

### Event Module Integration
- Integrates with `FP_events` module for event management
- Supports contact-based invitation workflows
- Enables automated invitation distribution

### Email System Integration
- Leverages SuiteCRM's core email functionality
- Supports template customization and localization
- Maintains email delivery tracking and management

## Configuration Management

### Template Customization
- System administrators can modify template content post-installation
- Supports organization-specific branding and messaging
- Maintains template versioning and modification tracking

### Deployment Considerations
- Template starts in unpublished state for review before activation
- Supports multiple language implementations
- Maintains compatibility with SuiteCRM email infrastructure

## Usage Context

**Event Management Scenarios**
- Corporate meeting invitations
- Training session notifications
- Conference and seminar announcements
- Customer appointment confirmations

**Integration Points**
- Works with calendar module for scheduling
- Integrates with contact management for recipient lists
- Supports campaign management for bulk invitations 