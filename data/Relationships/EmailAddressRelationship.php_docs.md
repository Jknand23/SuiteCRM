# EmailAddressRelationship.php Documentation

/**
 * @fileoverview Specialized many-to-many relationship class for managing email address associations with SugarBean entities, including primary address handling and module-specific filtering
 * @package SuiteCRM.data.Relationships
 * @copyright 2011-2018 SalesAgility Ltd, 2004-2013 SugarCRM Inc.
 * @license GNU Affero General Public License version 3
 */

## Overview
The `EmailAddressRelationship` class extends `M2MRelationship` to provide specialized functionality for managing email address relationships in SuiteCRM. It handles the unique requirements of email address associations, including primary address designation, module-specific filtering, and unidirectional link management.

## Class Definition
- **Extends**: `M2MRelationship`
- **Namespace**: Global
- **Attributes**: `#[\AllowDynamicProperties]`
- **API**: Public API class

## Dependencies
- `data/Relationships/M2MRelationship.php` - Base many-to-many relationship implementation

## Database Operations

### Email Address Table Management
The class manages relationships through the email addresses relationship table with specialized handling:

#### add() Method
- **Purpose**: Adds email address relationship with unidirectional link management
- **Special Behavior**: Only manages left-hand side links (entities to email addresses)
- **Parameters**:
  - `$lhs` (SugarBean): Entity bean (Contact, Account, etc.)
  - `$rhs` (SugarBean): EmailAddress bean
  - `$additionalFields` (array): Additional relationship fields (primary_address, etc.)
- **Returns**: Boolean indicating success
- **Database Impact**: Inserts row into email addresses relationship table

#### remove() Method
- **Purpose**: Removes email address relationship
- **Validation**: Ensures both LHS and RHS are valid SugarBean instances
- **Workflow Integration**: Executes before/after delete hooks when workflows enabled
- **Self-Referencing Support**: Handles self-referencing relationships
- **Database Impact**: Removes specific relationship row

#### Primary Address Handling
Special handling for primary email address designation:
- **Role Column**: Uses `primary_address` role column for primary designation
- **Module Filtering**: Adds `bean_module` filtering for primary addresses
- **Uniqueness**: Ensures only one primary email per module/entity combination

## Internal API Calls

### Relationship Loading and Validation
```php
$lhs->load_relationship($lhsLinkName);
$lhs->$lhsLinkName->beansAreLoaded();
$lhs->$lhsLinkName->addBean($rhs);
```

### Workflow Hook Integration
```php
$this->callBeforeAdd($lhs, $rhs, $lhsLinkName);
$this->callAfterAdd($lhs, $rhs, $lhsLinkName);
$this->callBeforeDelete($lhs, $rhs, $lhsLinkName);
$this->callAfterDelete($lhs, $rhs, $lhsLinkName);
```

### Data Manipulation
```php
$dataToInsert = $this->getRowToInsert($lhs, $rhs, $additionalFields);
$this->addRow($dataToInsert);
$this->removeRow($dataToRemove);
```

### Self-Referencing Support
```php
$this->addSelfReferencing($lhs, $rhs, $additionalFields);
$this->removeSelfReferencing($lhs, $rhs);
```

## Key Features

### Unidirectional Link Management
- **Left Side Only**: Only manages links from entities to email addresses
- **Performance Optimization**: Reduces overhead by not maintaining bidirectional links
- **Simplified Logic**: Email addresses don't need back-references to all entities

### Primary Address Support
- **Designation**: Supports marking email addresses as primary for entities
- **Module-Specific**: Primary designation is per-module (Contact, Account, etc.)
- **Query Filtering**: Automatically filters for module-specific primary addresses

### Workflow Integration
- **Hook Points**: Provides before/after add/delete hook integration
- **Session Control**: Respects `$_SESSION['disable_workflow']` setting
- **Custom Logic**: Allows custom business logic through workflow hooks

### Self-Referencing Relationships
- **Symmetric Support**: Handles relationships where entities relate to themselves
- **Bidirectional Cleanup**: Ensures proper cleanup for symmetric relationships

## Methods

### add()
```php
public function add($lhs, $rhs, $additionalFields = array())
```
- **Link Loading**: Ensures left-hand side relationship is loaded
- **Bean Management**: Updates in-memory bean collections if loaded
- **Workflow Hooks**: Executes before/after add callbacks
- **Data Insertion**: Creates relationship record in database
- **Self-Referencing**: Handles symmetric relationship addition

### remove()
```php
public function remove($lhs, $rhs)
```
- **Validation**: Verifies both parameters are SugarBean instances
- **Workflow Hooks**: Executes before/after delete callbacks (when enabled)
- **Data Removal**: Removes specific relationship record
- **Self-Referencing**: Handles symmetric relationship removal

### getRoleWhere()
```php
protected function getRoleWhere($table = "", $ignore_role_filter = false)
```
- **Parent Functionality**: Inherits base role filtering from parent class
- **Primary Address Logic**: Adds special filtering for primary_address roles
- **Module Filtering**: Includes bean_module filtering for primary addresses
- **Table Aliasing**: Supports table aliases in query construction

## Architecture Notes

### Design Pattern
- **Specialization**: Inherits M2M functionality while adding email-specific behavior
- **Asymmetric Relationships**: Optimized for unidirectional email address links
- **Hook Integration**: Seamlessly integrates with SuiteCRM workflow system

### Database Schema
- **Relationship Table**: Uses intermediate table for M2M relationship storage
- **Role Columns**: Supports role-based relationship filtering
- **Module Tracking**: Tracks source module for email address relationships

### Performance Considerations
- **Unidirectional Links**: Reduces memory usage by not loading reverse relationships
- **Conditional Loading**: Only loads relationship data when explicitly requested
- **Primary Address Optimization**: Efficient querying for primary email addresses

### Email Address Specifics
- **Multiple Addresses**: Entities can have multiple email addresses
- **Primary Designation**: One primary email per entity type
- **Module Separation**: Email relationships are module-aware
- **Validation**: Built-in validation for proper email address relationships

## Usage Context
- **Contact Management**: Primary use for Contact email addresses
- **Account Management**: Business email address relationships
- **Lead Processing**: Email capture and relationship management
- **Email Marketing**: Managing email lists and campaigns
- **Communication**: Enabling email functionality throughout CRM 