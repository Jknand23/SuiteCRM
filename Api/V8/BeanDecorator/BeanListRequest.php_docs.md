
/**
 * @fileoverview This file defines the `BeanListRequest` class for the SuiteCRM V8 API. This class uses the builder pattern to provide a fluent interface for building queries for lists of beans. This approach makes the code for building queries more readable and maintainable.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Bean List Request

## Overview

The `BeanListRequest.php` file, located in `Api/V8/BeanDecorator/`, defines the `BeanListRequest` class. This class uses the builder pattern to provide a fluent interface for building queries for lists of beans.

## Constructor

The constructor takes a single argument:

-   `\SugarBean $bean`: The bean to be queried.

## Methods

This class has a number of methods that can be used to build a query. Each of these methods returns the `BeanListRequest` object, so they can be chained together.

-   `orderBy($orderBy)`: Sets the `order by` clause for the query.
-   `where($where)`: Sets the `where` clause for the query.
-   `offset($offset)`: Sets the `offset` for the query.
-   `limit($limit)`: Sets the `limit` for the query.
-   `max($max)`: Sets the `max` number of records to return.
-   `deleted($deleted)`: Sets whether or not to include deleted records in the query.
-   `singleSelect($singleSelect)`: Sets whether or not to use a single select query.
-   `fields(array $fields)`: Sets the fields to be returned by the query.

### `fetch()`

This method executes the query and returns a `BeanListResponse` object.

## Example

```php
$beanListRequest = new BeanListRequest($bean);

$beanListResponse = $beanListRequest
    ->where("name = 'test'")
    ->orderBy('date_entered DESC')
    ->limit(10)
    ->fetch();
```

## Associated Components

-   `\SugarBean`: The base class for all of SuiteCRM's data objects.
-   `Api\V8\BeanDecorator\BeanListResponse`: The class that is returned by the `fetch()` method. 