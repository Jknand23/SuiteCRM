
/**
 * @fileoverview This file defines the `BeanManager` class for the SuiteCRM V8 API. This class acts as a decorator for the legacy `BeanFactory` and `SugarBean` classes, providing a more modern and consistent interface for working with beans. It is a crucial part of the API, as it provides a safe and reliable way to interact with SuiteCRM's data objects.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Bean Manager

## Overview

The `BeanManager.php` file, located in `Api/V8/BeanDecorator/`, defines the `BeanManager` class. This class is a key part of the API's architecture, as it provides a safe and consistent interface for working with SuiteCRM's `SugarBean` objects. It acts as a decorator for the legacy `BeanFactory` and `SugarBean` classes, adding a layer of safety and convenience to the process of creating, retrieving, and manipulating beans.

## Constructor

The constructor takes the following arguments:

-   `\DBManager $db`: An instance of the database manager.
-   `array $beanAliases`: An array of bean aliases, which is used to map module names to bean classes.

## Methods

### `newBeanSafe($module)`

This method creates a new bean for a given module. It uses the `beanAliases` to resolve the module name to a bean class, and then it uses the `BeanFactory` to create the bean. It also checks to make sure that the created bean is a valid `SugarBean` object.

### `getBean($module, $id, $params, $deleted)`

This method retrieves an existing bean from the database. It is a simple wrapper around the `BeanFactory::getBean()` method.

### `getBeanSafe($module, $id, $params, $deleted)`

This method retrieves an existing bean from the database, but it also adds a layer of safety by checking to make sure that the bean exists and that the module name is valid.

### `getList($module)`

This method returns a new `BeanListRequest` object, which can be used to build a query for a list of beans.

### `createRelationshipSafe(\SugarBean $sourceBean, \SugarBean $relatedBean, $relationship)`

This method creates a new relationship between two beans. It uses the `load_relationship()` and `add()` methods on the source bean to create the relationship.

### `deleteRelationshipSafe(\SugarBean $sourceBean, \SugarBean $relatedBean, $relationship)`

This method deletes a relationship between two beans. It uses the `load_relationship()` and `delete()` methods on the source bean to delete the relationship.

### `getLinkedFieldName(\SugarBean $sourceBean, \SugarBean $relatedBean)`

This method retrieves the name of the link field that connects two beans.

### `getLinkedFieldBean(\SugarBean $sourceBean, $linkFieldName)`

This method retrieves the bean that is related to a given bean through a specific link field.

### `countRecords($module, $where)`

This method counts the number of records in a given module that match a given where clause.

### `getDefaultFields(\SugarBean $bean)`

This method retrieves the default fields for a given bean.

### `filterAcceptanceFields(\SugarBean $bean, array $fields)`

This method filters a given list of fields to only include the fields that are acceptable for a given bean.

## Associated Components

-   `\DBManager`: The database manager class.
-   `\SugarBean`: The base class for all of SuiteCRM's data objects.
-   `\BeanFactory`: The factory class for creating beans.
-   `Api\V8\BeanDecorator\BeanListRequest`: A class for building queries for lists of beans. 