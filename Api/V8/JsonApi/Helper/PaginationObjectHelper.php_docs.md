
/**
 * @fileoverview This file defines the `PaginationObjectHelper` class for the SuiteCRM V8 API. This helper class is used to create the pagination object in a JSON:API response.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Pagination Object Helper

## Overview

The `PaginationObjectHelper.php` file, located in `Api/V8/JsonApi/Helper/`, defines the `PaginationObjectHelper` class. This helper class is used to create the pagination object in a JSON:API response.

## Methods

### `getPaginationMeta($totalPages, $numOfRecords)`

This method creates the "meta" object for the pagination.

### `getPaginationLinks(Request $request, $totalPages, $number)`

This method creates the "links" object for the pagination. It creates the "first", "prev", "next", and "last" links for a paginated collection.

### `createPaginationLink(Request $request, $number)`

This is a private helper method that is used to create the pagination links.

## Associated Components

-   `Api\V8\JsonApi\Response\MetaResponse`: The class that is returned by the `getPaginationMeta()` method.
-   `Api\V8\JsonApi\Response\PaginationResponse`: The class that is returned by the `getPaginationLinks()` method.
-   `Slim\Http\Request`: The request object from the Slim framework. 