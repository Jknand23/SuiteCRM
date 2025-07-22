
/**
 * @fileoverview This file defines the `BeanListResponse` class for the SuiteCRM V8 API. This class is a simple value object that is used to hold the results of a bean list query.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Bean List Response

## Overview

The `BeanListResponse.php` file, located in `Api/V8/BeanDecorator/`, defines the `BeanListResponse` class. This class is a simple value object that is used to hold the results of a bean list query.

## Constructor

The constructor takes a single argument:

-   `array $result`: An array containing the results of a bean list query. This array should have two keys: `list`, which is an array of `SugarBean` objects, and `row_count`, which is the total number of rows that matched the query.

## Methods

### `getBeans()`

This method returns the array of `SugarBean` objects.

### `getRowCount()`

This method returns the total number of rows that matched the query.

## Associated Components

-   `\SugarBean`: The base class for all of SuiteCRM's data objects.
-   `Api\V8\BeanDecorator\BeanListRequest`: The class that creates `BeanListResponse` objects. 