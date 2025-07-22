# enabledTabs.php_docs.md

/**
 * @fileoverview Default enabled module tabs configuration for SuiteCRM installation
 * @package SuiteCRM
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

This file defines the default set of module tabs that will be enabled and visible in the SuiteCRM interface after installation.

## UI Functionality

### Default Module Configuration

**Core CRM Modules**
- `Home`: Dashboard and main navigation
- `Accounts`: Customer account management
- `Contacts`: Contact relationship management
- `Opportunities`: Sales opportunity tracking
- `Leads`: Lead generation and management

**Sales and Quoting**
- `AOS_Quotes`: Advanced quotes management
- `AOS_Invoices`: Invoice generation and tracking
- `AOS_Contracts`: Contract management
- `AOS_Products`: Product catalog
- `AOS_Product_Categories`: Product categorization
- `AOS_PDF_Templates`: PDF template management

**Communication and Collaboration**
- `Calendar`: Event and meeting scheduling
- `Calls`: Phone call tracking
- `Meetings`: Meeting management
- `Tasks`: Task assignment and tracking
- `Emails`: Email integration
- `Notes`: Note-taking functionality

**Marketing and Campaigns**
- `Campaigns`: Marketing campaign management
- `Cases`: Customer support case tracking
- `Prospects`: Prospect management
- `ProspectLists`: Marketing list management

**Project Management**
- `Project`: Project tracking
- `AM_ProjectTemplates`: Project templates
- `AM_TaskTemplates`: Task templates

**Events and Locations**
- `FP_events`: Event management
- `FP_Event_Locations`: Event location tracking

**Mapping and Geographic**
- `jjwg_Maps`: Geographic mapping functionality
- `jjwg_Markers`: Map markers
- `jjwg_Areas`: Geographic areas
- `jjwg_Address_Cache`: Address geocoding cache

**Reporting and Knowledge**
- `AOR_Reports`: Advanced reporting
- `AOK_KnowledgeBase`: Knowledge base management
- `AOK_Knowledge_Base_Categories`: Knowledge categorization

**System and Templates**
- `Documents`: Document management
- `EmailTemplates`: Email template management

### Tab Visibility Control

**Installation Integration**
- Automatically enables essential CRM functionality
- Provides comprehensive out-of-box experience
- Ensures all core modules are immediately accessible

**User Experience**
- Balanced module selection for typical CRM workflows
- Includes both core and advanced functionality
- Supports complete sales, marketing, and service processes

## Internal API Integration

### Module Activation
- Used by installation process to activate default modules
- Integrates with tab management system
- Configures initial user interface layout

### Administrative Control
- Forms basis for administrator tab management
- Can be modified post-installation through Admin interface
- Supports customization based on organizational needs

## Configuration Management

**Extensibility**
- Additional modules can be added to array
- Supports custom module integration
- Maintains compatibility with SuiteCRM module framework

**Deployment Considerations**
- Provides sensible defaults for most organizations
- Includes advanced features like mapping and reporting
- Balances functionality with interface simplicity 