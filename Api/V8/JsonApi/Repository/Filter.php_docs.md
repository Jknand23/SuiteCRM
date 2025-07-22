
/**
 * @fileoverview This file defines the `Filter` class for the SuiteCRM V8 API. This helper class is used to parse the `filter` query parameter and generate a SQL `WHERE` clause.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Filter

## Overview

The `Filter.php` file, located in `Api/V8/JsonApi/Repository/`, defines the `Filter` class. This helper class is used to parse the `filter` query parameter and generate a SQL `WHERE` clause.

## Constructor

The constructor takes a single argument:

-   `\DBManager $db`: An instance of the database manager.

## `parseWhere(\SugarBean $bean, array $params)`

This is the main method of the class. It takes a `SugarBean` object and an array of filter parameters, and it returns a `WHERE` clause string.

### Arguments:

-   `\SugarBean $bean`: The bean to be filtered.
-   `array $params`: An array of filter parameters.

### Returns:

A `WHERE` clause string.

### Throws:

-   `\InvalidArgumentException` when a field is not found or is not an array.
-   `\InvalidArgumentException` when an operator is invalid.

## Associated Components

-   `\DBManager`: The database manager class.
-   `\SugarBean`: The base class for all of SuiteCRM's data objects. 