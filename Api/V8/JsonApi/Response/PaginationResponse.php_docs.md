
/**
 * @fileoverview This file defines the `PaginationResponse` class for the SuiteCRM V8 API. This class is a simple value object that is used to represent the pagination links in a JSON:API response.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Pagination Response

## Overview

The `PaginationResponse.php` file, located in `Api/V8/JsonApi/Response/`, defines the `PaginationResponse` class. This class is a simple value object that is used to represent the pagination links in a JSON:API response.

## Methods

This class has a number of getter and setter methods for the `first`, `prev`, `next`, and `last` properties.

### `jsonSerialize()`

This method is called when the object is converted to JSON. It returns an array containing the `first`, `prev`, `next`, and `last` links.

## Associated Components

-   `Api\V8\JsonApi\Response\LinksResponse`: The class that this class extends. 