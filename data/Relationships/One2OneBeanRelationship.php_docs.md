# One2OneBeanRelationship.php Documentation

/**
 * @fileoverview One-to-one bean relationship management class that extends One2MBeanRelationship to enforce exclusive bidirectional links using direct bean field storage
 * @package SuiteCRM.data.Relationships
 * @copyright 2011-2018 SalesAgility Ltd, 2004-2013 SugarCRM Inc.
 * @license GNU Affero General Public License version 3
 */

## Overview
The `One2OneBeanRelationship` class represents and manages one-to-one relationships between SugarBean entities using direct field-based storage. Unlike `One2OneRelationship` which uses an intermediate table, this class extends `One2MBeanRelationship` and stores the relationship through foreign key fields directly in the related tables.

## Class Definition
- **Extends**: `One2MBeanRelationship`
- **Namespace**: Global
- **API**: Public API class

## Dependencies
- `data/Relationships/One2MBeanRelationship.php` - One-to-many bean relationship implementation

## Database Operations

### Direct Field Storage
The class manages one-to-one relationships through direct foreign key fields in the database tables rather than intermediate relationship tables:

#### add() Method
- **Purpose**: Adds a one-to-one relationship between two SugarBean instances
- **Constraint Enforcement**: Removes existing left-hand side relationships before creating new relationship
- **Parameters**:
  - `$lhs` (SugarBean): Left-hand side bean
  - `$rhs` (SugarBean): Right-hand side bean
  - `$additionalFields` (array): Optional key-value pairs for additional relationship fields
- **Returns**: Boolean indicating success
- **Database Impact**: Updates foreign key fields directly in bean tables

#### SQL Join Generation
The `getJoin()` method creates SQL joins for relationship queries:
- **Direct Table Joins**: No intermediate relationship table required
- **Key Mapping**: Uses `lhs_key`/`rhs_key` for direct field relationships
- **Deleted Record Handling**: Filters out soft-deleted records
- **Alias Support**: Supports table aliases for complex queries

## Internal API Calls

### Relationship Loading and Cleanup
```php
$lhs->load_relationship($lhsLinkName);
$this->removeAll($lhs->$lhsLinkName);
```

### Parent Class Integration
- Calls `parent::__construct($def)` during initialization
- Leverages `parent::add($lhs, $rhs, $additionalFields)` after constraint cleanup
- Uses inherited One2M functionality for actual relationship creation

### Bean Link Management
The `updateLinks()` method manages in-memory bean references:
```php
$lhs->$lhsLinkName->beans = array($rhs->id => $rhs);
$rhs->$rhsLinkName->beans = array($lhs->id => $lhs);
```

## Key Features

### Field-Based Storage
- **Direct Foreign Keys**: Stores relationships as foreign key fields in tables
- **No Join Table**: Eliminates need for intermediate relationship tables
- **Simpler Queries**: Enables direct joins between related tables

### One-to-One Constraint Enforcement
- **Left Side Cleanup**: Automatically removes existing left-hand relationships
- **Right Side Inheritance**: Leverages parent class for right-hand side management
- **Bidirectional Links**: Maintains consistent bean references on both sides

### SQL Join Optimization
- **Performance**: Direct table joins are more efficient than join table relationships
- **Flexibility**: Supports various join types (INNER, LEFT, etc.)
- **Role Filtering**: Integrates role-based access control

## Methods

### Constructor
```php
public function __construct($def)
```
- Initializes one-to-one bean relationship with definition array
- Delegates to parent One2MBeanRelationship constructor

### add()
```php
public function add($lhs, $rhs, $additionalFields = array())
```
- **Constraint Logic**: Removes existing left-hand side relationships
- **Delegation**: Uses parent class for actual relationship creation
- **Field Updates**: Updates foreign key fields in database

### updateLinks()
```php
protected function updateLinks($lhs, $lhsLinkName, $rhs, $rhsLinkName)
```
- **Bean Management**: Maintains in-memory bean references
- **Bidirectional**: Updates both sides of the relationship
- **Single Bean**: Ensures only one bean per side (1:1 constraint)

### getJoin()
```php
public function getJoin($link, $params = array(), $return_array = false)
```
- **SQL Generation**: Creates JOIN statements for relationship queries
- **Parameter Support**: 
  - `$link`: Link object containing relationship context
  - `$params`: Query parameters (aliases, join types, deleted handling)
  - `$return_array`: Whether to return structured array or SQL string
- **Return Types**: SQL string or structured array with join details

## Architecture Notes

### Design Pattern
- **Field-Based Strategy**: Uses direct foreign key storage instead of join tables
- **Template Method**: Overrides specific methods while inheriting core functionality
- **Performance Optimization**: Eliminates intermediate table joins

### Database Schema
- **Foreign Key Fields**: Requires foreign key columns in related tables
- **Direct Relationships**: No separate relationship table needed
- **Index Optimization**: Foreign key fields should be indexed for performance

### Memory Management
- **Bean Caching**: Maintains loaded beans in memory through link objects
- **Lazy Loading**: Loads relationships only when accessed
- **Reference Consistency**: Ensures bidirectional bean references are synchronized

## Performance Considerations
- **Query Efficiency**: Direct joins are faster than join table relationships
- **Memory Usage**: Cached beans consume memory but improve access speed
- **Index Dependency**: Performance depends on proper foreign key indexing

## Usage Context
- **Simple Relationships**: Ideal for straightforward 1:1 relationships
- **Performance Critical**: Used when join performance is important
- **Direct Modeling**: When relationship maps directly to foreign key fields
- **Legacy Compatibility**: Compatible with traditional database relationship patterns 