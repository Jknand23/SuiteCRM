
/**
 * @fileoverview This file defines the `Sort` class for the SuiteCRM V8 API. This helper class is used to parse the `sort` query parameter and generate a SQL `ORDER BY` clause.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Sort

## Overview

The `Sort.php` file, located in `Api/V8/JsonApi/Repository/`, defines the `Sort` class. This helper class is used to parse the `sort` query parameter and generate a SQL `ORDER BY` clause.

## `parseOrderBy(\SugarBean $bean, $value)`

This is the main method of the class. It takes a `SugarBean` object and a sort string, and it returns an `ORDER BY` clause string.

### Arguments:

-   `\SugarBean $bean`: The bean to be sorted.
-   `$value`: The sort string.

### Returns:

An `ORDER BY` clause string.

### Throws:

`\InvalidArgumentException` when a sort field is not found in the bean.

## Associated Components

-   `\SugarBean`: The base class for all of SuiteCRM's data objects. 