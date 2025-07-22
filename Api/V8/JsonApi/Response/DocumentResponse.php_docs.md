
/**
 * @fileoverview This file defines the `DocumentResponse` class for the SuiteCRM V8 API. This class is a simple value object that is used to represent the top-level document in a JSON:API response.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Document Response

## Overview

The `DocumentResponse.php` file, located in `Api/V8/JsonApi/Response/`, defines the `DocumentResponse` class. This class is a simple value object that is used to represent the top-level document in a JSON:API response.

## Methods

This class has a number of getter and setter methods for the `data`, `meta`, and `links` properties.

### `jsonSerialize()`

This method is called when the object is converted to JSON. It returns an array containing the `data`, `meta`, and `links` of the document.

## Associated Components

-   `Api\V8\JsonApi\Response\DataResponse`: The class that is used to represent the "data" object.
-   `Api\V8\JsonApi\Response\MetaResponse`: The class that is used to represent the "meta" object.
-   `Api\V8\JsonApi\Response\LinksResponse`: The class that is used to represent the "links" object. 