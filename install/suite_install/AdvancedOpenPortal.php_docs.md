# AdvancedOpenPortal.php_docs.md

/**
 * @fileoverview Advanced Open Portal (AOP) installation module for customer portal integration and case management
 * @package SuiteCRM
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

This file provides installation functionality for the Advanced Open Portal (AOP) module, which enables customer portal integration, case management workflows, and comprehensive email template setup for customer communications.

## Database Operations

### Email Template Creation
- Creates multiple EmailTemplate beans using `BeanFactory::newBean('EmailTemplates')`
- Saves template configurations to database via `$template->save()`
- Establishes comprehensive system-level email templates for portal functionality

### Configuration Management
- Updates global `sugar_config` array with AOP settings
- Disables portal functionality by default for security (`enable_portal = false`)
- Configures portal integration parameters and distribution settings

## Internal API Calls

### Module Installation Process

**install_aop() Function**
- **Purpose**: Main installation function for Advanced Open Portal module
- **Dependencies**: 
  - `EmailTemplates/EmailTemplate.php` for template management
- **Template Creation**: Creates comprehensive set of email templates for portal operations
- **Configuration**: Sets up portal integration parameters

**getTemplates() Function** (Referenced)
- **Purpose**: Provides template configuration data for email template creation
- **Returns**: Array of template configurations with all necessary email template properties
- **Integration**: Supplies data for automated template creation process

### Portal Configuration Settings

**Portal Integration Configuration**
- `aop.enable_portal`: Default false for security (manual activation required)
- `aop.joomla_url`: Empty string for Joomla integration URL
- `aop.distribution_user_id`: Empty string for case distribution user
- `aop.support_from_address`: Empty string for support email address
- `aop.support_from_name`: Empty string for support sender name
- `aop.distribution_method`: Set to 'roundRobin' for case distribution

## Email Template Configuration

### Template Creation System
- Iterates through template configurations from `getTemplates()`
- Creates EmailTemplate beans for each configuration
- Populates all template fields from configuration data
- Saves templates to database for immediate availability

### Template Categories (Based on configKey patterns)
**Case Management Templates**
- Case creation notifications
- Case update notifications  
- Case closure confirmations
- Case escalation alerts

**Portal Integration Templates**
- Customer welcome messages
- Password reset notifications
- Account activation emails
- Portal access instructions

**Support Workflow Templates**
- Support ticket acknowledgments
- Resolution notifications
- Feedback collection emails
- Customer satisfaction surveys

## System Architecture

### Portal Integration Framework
- **Joomla Integration**: Configurable URL for Joomla portal connections
- **User Distribution**: Configurable user ID for case assignment
- **Round Robin**: Automated case distribution algorithm
- **Email Integration**: Comprehensive email template system

### Case Management Workflow
- **Distribution Method**: Round robin assignment for balanced workload
- **Support Integration**: Configurable support team email addresses
- **Template Automation**: Automated email notifications for case lifecycle

## UI Functionality

### Portal Configuration Interface
- Provides foundation for portal activation and configuration
- Enables administrator setup of portal integration parameters
- Supports custom branding through template customization

### Case Management Interface
- Supports automated case distribution workflows
- Enables customer communication through template system
- Provides foundation for customer self-service portal

## Configuration Management

### Security-First Approach
- Portal disabled by default requiring manual activation
- Placeholder configuration values require administrator setup
- Comprehensive template system ready for immediate use upon activation

### Integration Flexibility
- **Joomla Support**: Configurable integration with Joomla CMS
- **Email Customization**: Extensive template system for communication
- **Distribution Control**: Flexible case assignment mechanisms

### Template Management
- **System Templates**: All templates marked as system-level
- **Customization Support**: Templates can be modified post-installation
- **Localization Ready**: Template structure supports multiple languages

## Performance Considerations

### Efficient Template Creation
- Batch template creation during installation
- Optimized template storage and retrieval
- Minimal overhead for portal functionality when disabled

### Scalable Architecture
- Round robin distribution scales with team size
- Template system supports high-volume customer communications
- Configuration flexibility enables optimization for different deployment sizes

## Integration Points

### CRM Module Integration
- **Cases Module**: Deep integration for case management workflows
- **Contacts Module**: Customer portal access and authentication
- **EmailTemplates Module**: Comprehensive template management
- **Users Module**: Support team and distribution configuration

### External System Integration
- **Joomla CMS**: Direct portal integration capabilities
- **Email Systems**: Template-based automated communications
- **Customer Portals**: Foundation for self-service customer interfaces

## Usage Context

**Customer Portal Scenarios**
- Self-service customer support portals
- Case creation and tracking interfaces
- Knowledge base integration
- Customer account management

**Support Workflow Scenarios**
- Automated case distribution among support teams
- Template-driven customer communications
- Case lifecycle management and tracking
- Customer satisfaction and feedback collection

## Installation Integration

### Setup Process
- Automatically installs comprehensive email template library
- Configures portal framework with security-conscious defaults
- Provides immediate template availability for portal activation

### Security Considerations
- Portal functionality disabled by default
- Requires explicit administrator configuration for activation
- Comprehensive template system ready for secure customer communications

## Template System Benefits

### Communication Automation
- Comprehensive set of templates for all customer interaction scenarios
- Automated email generation for case lifecycle events
- Professional, consistent customer communications

### Customization Support
- All templates can be customized for organizational branding
- Multiple language support through template localization
- Professional email formatting with HTML and plain text versions

## Configuration Requirements

### Administrator Setup Required
- Portal URL configuration for integration
- Support team email addresses and sender names
- Distribution user assignment for case management
- Portal activation through configuration settings

### Integration Dependencies
- Email system configuration for template delivery
- User account setup for case distribution
- Portal platform (Joomla) configuration if using external portal
- Case management workflow customization 