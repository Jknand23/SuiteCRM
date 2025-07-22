# Account.php Documentation

## @fileoverview
Core Account entity class that extends the Company template to provide complete customer relationship management functionality for business accounts, including contact management, address handling, email integration, and relationship tracking.

## @package SuiteCRM\Modules\Accounts
## @copyright SugarCRM Inc. & SalesAgility Ltd.
## @license AGPL-3.0

## Overview

The Account class serves as the primary entity for managing business accounts within SuiteCRM. It extends the Company template class and implements the EmailInterface, providing comprehensive functionality for storing and managing customer account information, relationships, and business data.

## Database Operations

### Core Table Structure
- **Primary Table**: `accounts` 
- **Module Directory**: `Accounts`
- **Object Name**: `Account`
- **Importable**: Yes
- **New Schema**: Enabled

### Field Management
- **Billing Address Fields**: Complete billing address with multi-line street support (street_2, street_3, street_4)
- **Shipping Address Fields**: Complete shipping address with multi-line street support
- **Contact Information**: Email addresses (email1, email2), phone numbers (office, fax, alternate)
- **Business Data**: Annual revenue, employees count, industry, rating, account type
- **Relationship Fields**: Parent account, assigned user, campaign tracking

### Database Query Methods
- **`clear_account_case_relationship()`**: Removes account associations from cases
- **`create_export_query()`**: Generates complex export queries with joins for email addresses and related entities
- **`getProductsServicesPurchasedQuery()`**: Retrieves purchased products/services through quotes relationship

## Internal API Calls

### Core Entity Methods
- **`__construct()`**: Initializes custom fields and field name mapping, handles email context logic
- **`get_summary_text()`**: Returns account name as summary text
- **`get_contacts()`**: Retrieves linked contact records through relationship
- **`fill_in_additional_detail_fields()`**: Populates parent account name and campaign information
- **`fill_in_additional_list_fields()`**: Inherits parent list field population

### Data Processing
- **`get_list_view_data()`**: Formats data for list view display including city/state concatenation
- **`build_generic_where_clause()`**: Constructs search queries for account name and phone numbers
- **`bean_implements()`**: Implements ACL interface for access control

### Field Mapping and Relationships
- **`field_name_map`**: Dynamic field definition mapping from vardefs
- **`additional_column_fields`**: Extended fields for form processing and relationships
- **`relationship_fields`**: Maps relationship IDs to relationship names

## External API Calls

### Email Integration
- **`get_unlinked_email_query()`**: Generates queries for unlinked email management
- **EmailInterface Implementation**: Provides email address management through SugarEmailAddress
- **Email Context Handling**: Manages parent/child relationships with email records

### Export and Import
- **Export Query Generation**: Complex join generation for data export with email addresses
- **Import Support**: Full import capability with field validation

## UI Functionality

### Display Methods
- **List View Formatting**: Customized display with encoded names and address formatting
- **Detail View Enhancement**: Parent account name resolution and campaign name display
- **Search Integration**: Generic search support across name and phone fields

### Address Management
- **Billing Address**: Complete address management with multi-line street support
- **Shipping Address**: Separate shipping address management
- **Address Display**: Formatted city/state display in list views

### Relationship Display
- **Parent Account**: Hierarchical account relationship display
- **Campaign Integration**: Campaign name resolution and display
- **User Assignment**: Assigned user information management

## Associated Tests

### Core Functionality Tests
- Account creation and modification
- Address field management
- Email integration testing
- Relationship management validation

### Database Operations Tests
- Export query generation testing
- Case relationship clearing validation
- Custom field integration testing

### UI Integration Tests
- List view data formatting
- Detail view field population
- Search functionality validation

## Key Features

### Multi-Address Support
- Separate billing and shipping addresses
- Multi-line street address support (up to 4 lines for each address type)
- International address format support

### Business Intelligence
- Annual revenue tracking
- Employee count management
- Industry classification
- Customer rating system
- Account type categorization

### Integration Points
- Campaign tracking integration
- Contact relationship management
- Case management integration
- Email system integration
- Custom field support through Company template

### Data Security
- ACL implementation for access control
- Field-level security through SugarBean
- Data validation and sanitization 