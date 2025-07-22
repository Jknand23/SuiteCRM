
/**
 * @fileoverview This file defines the `RelationshipService` for the SuiteCRM V8 API. This service is responsible for the business logic of managing the relationships between module records, providing methods to get, create, and delete relationships.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Relationship Service

## Overview

The `RelationshipService.php` file, located in `Api/V8/Service/`, defines the `RelationshipService` class. This service contains the business logic for managing the relationships between module records. It is used by the `RelationshipController`.

## Constructor

The constructor takes the following arguments:

-   `BeanManager $beanManager`: An instance of the `BeanManager`.
-   `AttributeObjectHelper $attributeHelper`: A helper for creating JSON:API attribute objects.
-   `PaginationObjectHelper $paginationHelper`: A helper for creating JSON:API pagination objects.

## Methods

### `getRelationship(GetRelationshipParams $params, Request $request)`

This method retrieves a list of records that are related to a given record through a specific relationship link. It supports pagination and filtering.

### `createRelationship(CreateRelationshipParams $params)`

This method creates a new relationship between two records. It determines the link field to use based on the two beans and then creates the relationship.

### `createRelationshipByLink(CreateRelationshipByLinkParams $params)`

This method creates a new relationship between two records using a specific link field.

### `deleteRelationship(DeleteRelationshipParams $params)`

This method deletes a relationship between two records.

## Associated Components

-   `Api\V8\Controller\RelationshipController`: The controller that uses this service.
-   `Api\V8\BeanDecorator\BeanManager`: A service used to interact with beans.
-   `Api\V8\JsonApi\Helper\*`: Helper classes for creating JSON:API objects.
-   `Api\V8\Param\*`: The parameter objects that contain the validated request parameters.
-   `Slim\Http\Request`: The request object from the Slim framework.
-   `Api\V8\JsonApi\Response\*`: The classes used to build the JSON:API response.
-   `SugarBean`: The base class for all of SuiteCRM's data objects.
-   `DomainException`: The exception class used by the service. 