# AdvancedOpenSales.php_docs.md

/**
 * @fileoverview Advanced Open Sales (AOS) installation and upgrade module for sales functionality
 * @package SuiteCRM
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

This file provides installation and upgrade functionality for the Advanced Open Sales (AOS) module, which enables comprehensive sales management including quotes, invoices, contracts, and line items functionality.

## Database Operations

### Configuration Management
- Updates global `sugar_config` array with AOS settings
- Writes configuration changes to `config.php` using `write_array_to_file()`
- Maintains version tracking through `aos.version` configuration

### Database Schema Updates (Upgrade Function)
- Updates `aos_pdf_templates` table for module name changes
- Migrates template types from 'Quotes' to 'AOS_Quotes'
- Migrates template types from 'Invoices' to 'AOS_Invoices'

### File Management (Upgrade Function)
- Creates backup directory structure for deprecated files
- Moves outdated extension files to `custom/bak_aos/` directory
- Removes deprecated JavaScript files to prevent conflicts

## Internal API Calls

### Module Installation Process

**install_aos() Function**
- **Purpose**: Main installation function for AOS module
- **Dependencies**: Requires `modules/Administration/Administration.php`
- **Configuration**: Sets up all AOS-related configuration settings
- **Version Management**: Records AOS version for upgrade tracking

**upgrade_aos() Function**
- **Purpose**: Handles upgrades from previous AOS versions
- **Version Check**: Compares current version against installed version
- **Database Migration**: Updates table schemas for compatibility
- **File Cleanup**: Removes deprecated files and creates backups

### Configuration Settings Applied

**AOS Version Tracking**
- `aos.version`: Set to '5.3.3' for current installation
- Used for future upgrade compatibility checking

**Contract Configuration**
- `aos.contracts.renewalReminderPeriod`: Default 14 days for renewal notifications
- Supports automated contract renewal management

**Line Items Configuration**
- `aos.lineItems.totalTax`: Default false for tax calculation method
- `aos.lineItems.enableGroups`: Default true for line item grouping functionality

**Invoice Configuration**
- `aos.invoices.initialNumber`: Default '1' for invoice numbering sequence
- Supports customizable invoice numbering schemes

**Quote Configuration**
- `aos.quotes.initialNumber`: Default '1' for quote numbering sequence
- Enables sequential quote numbering with custom starting points

## System Integration

### Module Compatibility Management
- Handles migration from legacy module naming conventions
- Ensures PDF template compatibility across versions
- Maintains data integrity during upgrades

### File System Management
**Deprecated Files Handled:**
- `custom/Extension/modules/Accounts/Ext/Layoutdefs/Account.php`
- `custom/Extension/modules/Accounts/Ext/Vardefs/Account.php`
- `custom/Extension/modules/Contacts/Ext/Layoutdefs/Contact.php`
- `custom/Extension/modules/Contacts/Ext/Vardefs/Contact.php`
- `custom/Extension/modules/Opportunities/Ext/Layoutdefs/Opportunity.php`
- `custom/Extension/modules/Opportunities/Ext/Vardefs/Opportunity.php`
- `custom/Extension/modules/Project/Ext/Layoutdefs/Project.php`
- `custom/Extension/modules/Project/Ext/Vardefs/Project.php`
- `modules/AOS_Quotes/js/Quote.js`

### Backup Strategy
- Creates `custom/bak_aos/` directory structure
- Preserves original file hierarchy in backup location
- Uses `sugar_rename()` for safe file operations

## UI Functionality

### Sales Process Configuration
- Configures default settings for optimal sales workflow
- Enables line item grouping for complex product structures
- Sets up automated numbering for professional document management

### Template Management
- Ensures PDF templates are properly associated with correct modules
- Maintains template functionality across version upgrades
- Supports customizable document generation

## Configuration Management

### Default Settings Strategy
- Only sets configuration values if not already present
- Preserves existing custom configurations during installation
- Provides sensible defaults for new installations

### Version Management
- Tracks AOS version for upgrade compatibility
- Enables conditional upgrade logic based on version comparison
- Maintains upgrade history and configuration tracking

## Installation Integration

### SuiteCRM Integration
- Seamlessly integrates with core SuiteCRM installation process
- Maintains compatibility with existing CRM functionality
- Supports both new installations and upgrades from previous versions

### Error Handling
- Checks for file existence before attempting operations
- Creates necessary directory structures automatically
- Provides safe fallback for missing configuration values

## Usage Context

**Sales Management Features**
- Quote generation and management
- Invoice creation and tracking
- Contract management with renewal reminders
- Advanced line item handling with grouping support

**Deployment Scenarios**
- New SuiteCRM installations with sales functionality
- Upgrades from previous AOS versions
- Migration from legacy sales modules
- Configuration of custom numbering schemes and tax handling 