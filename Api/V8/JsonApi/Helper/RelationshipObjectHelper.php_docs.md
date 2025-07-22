
/**
 * @fileoverview This file defines the `RelationshipObjectHelper` class for the SuiteCRM V8 API. This helper class is used to create the "relationships" object in a JSON:API resource object.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Relationship Object Helper

## Overview

The `RelationshipObjectHelper.php` file, located in `Api/V8/JsonApi/Helper/`, defines the `RelationshipObjectHelper` class. This helper class is used to create the "relationships" object in a JSON:API resource object.

## Constructor

The constructor takes a single argument:

-   `VarDefHelper $varDefHelper`: A helper for working with `VarDefs`.

## `getRelationships(\SugarBean $bean, $uriPath)`

This is the main method of the class. It takes a `SugarBean` object and a URI path, and it returns a `RelationshipResponse` object.

### Arguments:

-   `\SugarBean $bean`: The bean to get the relationships from.
-   `$uriPath`: The URI path for the resource.

### Returns:

A `RelationshipResponse` object.

### Logic:

1.  **Get Relationships**: It uses the `VarDefHelper` to get all of the relationships for the bean.
2.  **Create Links**: It creates a "links" object for each relationship, with a "related" link that points to the relationship's endpoint.
3.  **Create RelationshipResponse**: It creates a new `RelationshipResponse` object with the links and returns it.

## Associated Components

-   `Api\V8\Helper\VarDefHelper`: A helper for working with `VarDefs`.
-   `Api\V8\JsonApi\Response\LinksResponse`: The class that is used to create the "links" object.
-   `Api\V8\JsonApi\Response\RelationshipResponse`: The class that is returned by the `getRelationships()` method.
-   `\SugarBean`: The base class for all of SuiteCRM's data objects. 