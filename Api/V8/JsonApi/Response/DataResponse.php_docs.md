
/**
 * @fileoverview This file defines the `DataResponse` class for the SuiteCRM V8 API. This class is a simple value object that is used to represent the "data" object in a JSON:API response.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Data Response

## Overview

The `DataResponse.php` file, located in `Api/V8/JsonApi/Response/`, defines the `DataResponse` class. This class is a simple value object that is used to represent the "data" object in a JSON:API response.

## Constructor

The constructor takes two arguments:

-   `$type`: The type of the resource.
-   `$id`: The ID of the resource.

## Methods

This class has a number of getter and setter methods for the `type`, `id`, `attributes`, `relationships`, and `links` properties.

### `jsonSerialize()`

This method is called when the object is converted to JSON. It returns an array containing the `type`, `id`, `attributes`, `relationships`, and `links` of the resource.

## Associated Components

-   `Api\V8\JsonApi\Response\AttributeResponse`: The class that is used to represent the "attributes" object.
-   `Api\V8\JsonApi\Response\RelationshipResponse`: The class that is used to represent the "relationships" object.
-   `Api\V8\JsonApi\Response\LinksResponse`: The class that is used to represent the "links" object. 