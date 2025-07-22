# One2OneRelationship.php Documentation

/**
 * @fileoverview One-to-one relationship management class that extends M2MRelationship to enforce exclusive bidirectional links between SugarBean entities
 * @package SuiteCRM.data.Relationships
 * @copyright 2011-2018 SalesAgility Ltd, 2004-2013 SugarCRM Inc.
 * @license GNU Affero General Public License version 3
 */

## Overview
The `One2OneRelationship` class represents and manages one-to-one relationships between SugarBean entities in SuiteCRM. It extends the `M2MRelationship` class but enforces strict 1:1 cardinality by removing any existing relationships before establishing new ones.

## Class Definition
- **Extends**: `M2MRelationship`
- **Namespace**: Global
- **Attributes**: `#[\AllowDynamicProperties]`

## Dependencies
- `data/Relationships/SugarRelationship.php` - Base relationship functionality
- `data/Relationships/One2MRelationship.php` - One-to-many relationship implementation

## Database Operations

### Relationship Management
The class manages one-to-one relationships through database operations inherited from its parent class, with additional constraint enforcement:

#### add() Method
- **Purpose**: Adds a one-to-one relationship between two SugarBean instances
- **Constraint Enforcement**: Automatically removes existing relationships on both sides before creating new relationship
- **Parameters**:
  - `$lhs` (SugarBean): Left-hand side bean
  - `$rhs` (SugarBean): Right-hand side bean  
  - `$additionalFields` (array): Optional key-value pairs for additional relationship fields
- **Returns**: Boolean indicating success (false if relationship already exists)

#### Database Integrity
- Uses `checkExisting()` to prevent duplicate relationships
- Calls `getRowToInsert()` to prepare relationship data
- Leverages parent `M2MRelationship` for actual database insertion

## Internal API Calls

### Relationship Loading and Removal
```php
$lhs->load_relationship($lhsLinkName);
$this->removeAll($lhs->$lhsLinkName);
$rhs->load_relationship($rhsLinkName);  
$this->removeAll($rhs->$rhsLinkName);
```

### Parent Class Integration
- Calls `parent::__construct($def)` during initialization
- Leverages `parent::add($lhs, $rhs, $additionalFields)` for actual relationship creation

### Link Management
- Accesses `$this->lhsLink` and `$this->rhsLink` properties for relationship links
- Uses bean's dynamic relationship loading via `load_relationship()`

## Key Features

### One-to-One Constraint Enforcement
- **Exclusive Relationships**: Automatically removes existing relationships before creating new ones
- **Bidirectional Cleanup**: Cleans both left-hand and right-hand side relationships
- **Data Integrity**: Prevents multiple relationships that would violate 1:1 constraint

### Inheritance Benefits
- **M2M Foundation**: Inherits robust many-to-many relationship infrastructure
- **Specialized Behavior**: Adds 1:1 specific logic while reusing proven relationship management
- **Consistent Interface**: Maintains same API as other relationship types

## Methods

### Constructor
```php
public function __construct($def)
```
- Initializes one-to-one relationship with definition array
- Delegates to parent M2MRelationship constructor

### add()
```php
public function add($lhs, $rhs, $additionalFields = array())
```
- **Core Logic**: Enforces 1:1 constraint by removing existing relationships
- **Duplicate Prevention**: Returns false if relationship already exists
- **Success Path**: Creates new relationship after cleanup

## Architecture Notes

### Design Pattern
- **Specialization**: Inherits from M2MRelationship but adds specific 1:1 behavior
- **Template Method**: Overrides add() while reusing parent infrastructure
- **Constraint Enforcement**: Implements business rules for one-to-one relationships

### Performance Considerations
- **Cleanup Overhead**: Requires removal of existing relationships before addition
- **Database Queries**: Multiple queries needed for constraint enforcement
- **Relationship Loading**: Lazy loading of relationships as needed

## Usage Context
- **CRM Relationships**: Used for exclusive entity relationships (e.g., primary contact, main account)
- **Data Modeling**: Enforces business rules requiring unique associations
- **Relationship Integrity**: Maintains database consistency for 1:1 constraints 