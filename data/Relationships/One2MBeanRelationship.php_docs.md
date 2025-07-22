# One2MBeanRelationship.php Documentation

/**
 * @fileoverview Field-based one-to-many relationship class that manages relationships through direct foreign key storage in bean tables with sophisticated query generation and workflow integration
 * @package SuiteCRM.data.Relationships
 * @copyright 2011-2018 SalesAgility Ltd, 2004-2013 SugarCRM Inc.
 * @license GNU Affero General Public License version 3
 */

## Overview
The `One2MBeanRelationship` class extends `One2MRelationship` to provide field-based one-to-many relationship management. Unlike table-based relationships, this class stores relationship data directly in foreign key fields of the related bean's table, offering better performance and simpler database schema while maintaining full relationship functionality including workflow integration, query generation, and subpanel support.

## Class Definition
- **Extends**: `One2MRelationship`
- **Namespace**: Global
- **Attributes**: `#[\AllowDynamicProperties]`
- **API**: Public API class
- **Type**: "one-to-many" (used for query construction)

## Dependencies
- `data/Relationships/One2MRelationship.php` - Base one-to-many relationship functionality
- `DBManagerFactory` - Database connection management
- `BeanFactory` - Bean creation and module resolution
- `SugarRelationship` - Core relationship functionality
- `LoggerManager` - Logging infrastructure

## Database Operations

### Field-Based Storage
The class manages relationships through direct foreign key fields rather than intermediate tables:

#### add() Method
- **Purpose**: Creates one-to-many relationship by updating foreign key field
- **Duplicate Prevention**: Checks existing relationship before adding
- **Constraint Enforcement**: Removes previous relationships from RHS before adding new one
- **Parameters**:
  - `$lhs` (SugarBean): Left-hand side bean (the "one" side)
  - `$rhs` (SugarBean): Right-hand side bean (the "many" side)
  - `$additionalFields` (array): Additional fields to update on RHS bean
- **Database Impact**: Updates foreign key field and role fields directly in RHS table

#### remove() Method
- **Purpose**: Removes relationship by clearing foreign key field
- **Validation**: Verifies relationship exists before removal
- **Parameters**:
  - `$lhs` (SugarBean): Left-hand side bean
  - `$rhs` (SugarBean): Right-hand side bean
  - `$save` (boolean): Whether to save RHS bean after removal
- **Database Impact**: Clears foreign key field in RHS table

#### Query Generation
Multiple specialized query methods for different use cases:
- **getQuery()**: Generates SELECT queries for relationship loading
- **getJoin()**: Creates JOIN clauses for relationship queries
- **getSubpanelQuery()**: Specialized queries for subpanel display

## Internal API Calls

### Bean Relationship Management
```php
$rhs->load_relationship($rhsLinkName);
$oldLink->getBeans(null);
$lhs->$lhsLinkName->load();
$lhs->$lhsLinkName->addBean($rhs);
```

### Workflow Integration
```php
$this->callBeforeAdd($lhs, $rhs);
$this->callAfterAdd($lhs, $rhs);
$this->callBeforeDelete($lhs, $rhs);
$this->callAfterDelete($lhs, $rhs);
```

### Bean Saving and Resaving
```php
SugarRelationship::addToResaveList($rhs);
SugarRelationship::resaveRelatedBeans();
$rhs->save();
```

### Database Querying
```php
DBManagerFactory::getInstance()->query($query);
$db->fetchByAssoc($result, false);
DBManagerFactory::getInstance()->limitQuery($query, $offset, $limit);
```

### Bean Factory Integration
```php
BeanFactory::getBean($this->getRHSModule());
```

## Key Features

### Field-Based Relationship Storage
- **Direct Foreign Keys**: Stores relationship as foreign key field in RHS bean table
- **No Join Tables**: Eliminates need for separate relationship tables
- **Simpler Schema**: Reduces database complexity and improves query performance
- **Role Field Support**: Handles relationship role columns for filtered relationships

### Advanced Constraint Management
- **Relationship Existence Check**: Prevents duplicate relationship creation
- **Previous Relationship Cleanup**: Automatically removes existing relationships before adding new ones
- **Save Loop Prevention**: Uses global flags to prevent infinite save loops
- **Bean Resaving**: Manages deferred bean saving for relationship updates

### Sophisticated Query Generation
- **Multiple Query Types**: Supports different query patterns for various use cases
- **Parameter Flexibility**: Extensive parameter support for query customization
- **Role Filtering**: Automatic inclusion of role-based filtering
- **Custom Field Support**: Handles custom field joins when needed
- **Limit and Offset**: Support for pagination through limit/offset parameters

### Workflow and Hook Integration
- **Full Workflow Support**: Integrates with SuiteCRM workflow system
- **Before/After Hooks**: Provides hook points for custom business logic
- **Session Control**: Respects workflow disable settings
- **Bidirectional Hooks**: Calls hooks for both sides of relationship

## Methods

### add()
```php
public function add($lhs, $rhs, $additionalFields = array())
```
- **Duplicate Check**: Uses `relationship_exists()` to prevent duplicates
- **Previous Cleanup**: Removes existing relationships from RHS side
- **Field Updates**: Calls `updateFields()` to set foreign key and role fields
- **Link Updates**: Calls `updateLinks()` to maintain in-memory bean references
- **Workflow Integration**: Executes before/after add hooks
- **Bean Resaving**: Manages deferred save operations

### remove()
```php
public function remove($lhs, $rhs, $save = true)
```
- **Existence Validation**: Checks if relationship currently exists
- **Field Clearing**: Clears foreign key field in RHS bean
- **Optional Saving**: Controlled by `$save` parameter
- **Workflow Integration**: Executes before/after delete hooks

### updateFields()
```php
protected function updateFields($lhs, $rhs, $additionalFields)
```
- **Foreign Key Update**: Sets RHS foreign key field to LHS ID
- **Additional Fields**: Updates any additional relationship fields
- **Role Field Handling**: Sets relationship role column values

### updateLinks()
```php
protected function updateLinks($lhs, $lhsLinkName, $rhs, $rhsLinkName)
```
- **LHS Link Update**: Adds RHS bean to LHS link collection
- **RHS Link Update**: Sets single LHS bean reference in RHS link
- **Memory Management**: Maintains correct in-memory bean references

### load()
```php
public function load($link, $params = array())
```
- **Side-Specific Logic**: Different loading strategies for LHS vs RHS
- **RHS Loading**: Direct field access for single-bean relationships
- **LHS Loading**: Database query for multi-bean relationships
- **Parameter Support**: Flexible parameter handling for various load scenarios

### getQuery()
```php
public function getQuery($link, $params = array())
```
- **Query Construction**: Builds SELECT queries for relationship loading
- **Parameter Flexibility**: Supports various query customization options
- **Role Filtering**: Includes relationship role column filtering
- **Custom Field Support**: Handles custom field joins
- **Limit/Offset Support**: Pagination support for large result sets

### getJoin()
```php
public function getJoin($link, $params = array(), $return_array = false)
```
- **JOIN Generation**: Creates JOIN clauses for relationship queries
- **Table Aliasing**: Supports table aliases for complex queries
- **Role Integration**: Includes role-based WHERE clauses
- **Flexible Return**: Can return SQL string or structured array

### getSubpanelQuery()
```php
public function getSubpanelQuery($link, $params = array(), $return_array = false)
```
- **Subpanel Optimization**: Specialized query generation for subpanel display
- **Activity Handling**: Special logic for activity-related relationships
- **Alias Management**: Sophisticated table alias handling
- **Return Flexibility**: Supports both SQL string and array return formats

### relationship_exists()
```php
public function relationship_exists($lhs, $rhs)
```
- **Existence Check**: Determines if relationship already exists between two beans
- **Fetched Row Comparison**: Uses original field values to detect changes
- **Duplicate Prevention**: Prevents unnecessary relationship operations

## Architecture Notes

### Design Pattern
- **Field-Based Strategy**: Uses direct foreign key storage instead of join tables
- **Template Method**: Overrides parent methods while maintaining interface compatibility
- **Strategy Pattern**: Different behaviors for LHS vs RHS operations

### Performance Optimizations
- **Direct Field Access**: Eliminates need for join table queries
- **Lazy Loading**: Loads relationship data only when needed
- **Query Optimization**: Generates efficient queries with proper indexing support
- **Bean Caching**: Maintains loaded beans in memory to avoid repeated database access

### Database Schema Requirements
- **Foreign Key Fields**: Requires foreign key columns in RHS tables
- **Role Fields**: Optional role columns for filtered relationships
- **Index Requirements**: Foreign key fields should be indexed for performance

### Memory and Save Management
- **Bean Resaving**: Sophisticated system for managing bean save operations
- **Save Loop Prevention**: Prevents infinite loops during relationship updates
- **Memory Consistency**: Maintains consistent in-memory and database state

## Usage Context
- **Parent-Child Records**: Account to Contacts, Case to Case Updates
- **Ownership Relationships**: User to assigned records
- **Classification Systems**: Category to items, team to members
- **Hierarchical Data**: Department to employees, project to tasks
- **Simple Associations**: Any direct foreign key relationship pattern 