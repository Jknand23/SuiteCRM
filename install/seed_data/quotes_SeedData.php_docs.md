# quotes_SeedData.php Documentation

## @fileoverview
Demo data generator that creates sample quote records, product bundles, and associated relationships for the Quotes module during SuiteCRM installation.

## @package SuiteCRM Installation Seed Data
## @copyright SalesAgility Ltd.
## @license AGPL-3.0

## Overview

This file serves as a seed data generator for the Quotes module, creating realistic demo data that helps users understand the functionality and structure of the quoting system. It generates sample quotes with associated product bundles, line items, and relationships to accounts and contacts, providing a comprehensive demonstration of the CRM's sales functionality.

## Core Functionality

### Demo Data Generation
The file processes quote demo data from the global `$sugar_demodata` array to create:
- **Quote Records**: Complete quote instances with metadata
- **Product Bundles**: Grouped product collections within quotes
- **Line Items**: Individual products and services within bundles
- **Relationships**: Connections to accounts, contacts, and other entities

### Quote Creation Process
For each quote in the demo data array:
1. Creates new Quote bean instance with unique ID
2. Populates quote metadata (name, description, stage, dates)
3. Configures quote settings (type, calculations, line numbering)
4. Establishes team and ownership assignments
5. Links to random accounts and contacts for realistic relationships

## Database Operations

### Quote Record Creation
The file performs direct database operations:
- Creates quote records in `quotes` table
- Generates unique GUIDs for all new records
- Sets quote-specific configuration flags
- Establishes team and user assignments

### Relationship Management
- Links quotes to existing account records
- Associates quotes with contact records
- Creates product bundle relationships
- Establishes line item associations

### Data Integrity
- Ensures referential integrity between related records
- Validates required field completion
- Maintains consistent data relationships
- Preserves team-based security assignments

## Internal API Calls

### SuiteCRM Bean Integration
- `BeanFactory::newBean('Quotes')`: Creates Quote bean instances
- `BeanFactory::newBean('ProductBundleNote')`: Generates product bundle records
- `BeanFactory::newBean('Products')`: Creates associated product records
- `create_guid()`: Generates unique identifiers for new records

### Quote Configuration
- Sets `calc_grand_total = 1` for automatic total calculations
- Enables `show_line_nums = 1` for line number display
- Configures `quote_type = 'Quotes'` for proper categorization
- Applies current user's team assignments

### Demo Data Processing
The file reads from `$sugar_demodata['quotes_seed_data']['quotes']` array structure:
```php
$quote['name'] - Quote name/title
$quote['description'] - Quote description
$quote['quote_stage'] - Current quote stage
$quote['date_quote_expected_closed'] - Expected close date
$quote['purchase_order_num'] - PO number if applicable
$quote['original_po_date'] - Original PO date
$quote['payment_terms'] - Payment terms and conditions
```

## Integration Points

### Installation System
This seed data integrates with:
- **populateSeedData.php**: Called during demo data installation phase
- **Demo Data Framework**: Part of comprehensive demo data system
- **Account/Contact Modules**: Creates realistic business relationships
- **Product Catalog**: Utilizes existing product and service definitions

### Quote Module Integration
- Works with standard Quote bean structure and methods
- Utilizes quote workflow and calculation systems
- Supports quote stage progression and reporting
- Maintains consistency with quote management features

## Usage Context

### Installation Process
During installation with demo data selected:
1. System validates that demo data is requested
2. Loads quote demo data from configuration
3. Processes each quote definition in the array
4. Creates associated product bundles and line items
5. Establishes relationships with existing accounts/contacts

### Demo Environment Setup
- Provides realistic sales pipeline data for evaluation
- Demonstrates quote-to-cash functionality
- Shows product bundling and pricing capabilities
- Illustrates relationship management features

## Development Notes

### Demo Data Structure
- Quote data must follow established array structure
- Required fields include name, description, and expected close date
- Optional fields enhance realism but are not required for basic functionality
- Relationships rely on existing account and contact records

### Extensibility
- Additional quote fields can be added to demo data structure
- Product bundle creation can be expanded with more complex configurations
- Line item generation can include custom products and pricing
- Relationship creation can include additional entity types

### Data Quality
- Random account/contact assignment ensures relationship variety
- Team assignments maintain security model compliance
- Date handling supports various date formats and timezones
- Currency and pricing data follows system configuration

### Integration Dependencies
- Requires Quotes module to be properly installed and configured
- Depends on Account and Contact modules for relationship creation
- Utilizes Product module for line item generation
- Requires demo data framework to be enabled during installation 