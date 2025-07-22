
/**
 * @fileoverview This file defines the `RelationshipController` for the SuiteCRM V8 API. This controller is responsible for managing the relationships between module records, providing endpoints to get, create, and delete relationships. It delegates the business logic for these operations to the `RelationshipService`.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Relationship Controller

## Overview

The `RelationshipController.php` file, located in `Api/V8/Controller/`, defines the `RelationshipController` class. This controller is responsible for handling all of the operations on the relationships between the records in SuiteCRM's modules.

## Constructor

The controller's constructor takes a single argument:

-   `RelationshipService $relationshipService`: An instance of the `RelationshipService`. This service is injected by the dependency injection container and contains the business logic for interacting with relationships.

## Methods

### `getRelationship(Request $request, Response $response, array $args, GetRelationshipParams $params)`

-   **Endpoint**: `GET /V8/module/{moduleName}/{id}/relationships/{linkFieldName}`
-   **Description**: Retrieves a list of records that are related to a given record through a specific relationship link.

### `createRelationship(Request $request, Response $response, array $args, CreateRelationshipParams $params)`

-   **Endpoint**: `POST /V8/module/{moduleName}/{id}/relationships`
-   **Description**: Creates a new relationship between two records.

### `createRelationshipByLink(Request $request, Response $response, array $args, CreateRelationshipByLinkParams $params)`

-   **Endpoint**: `POST /V8/module/{moduleName}/{id}/relationships/{linkFieldName}`
-   **Description**: Creates a new relationship between two records using a specific link field.

### `deleteRelationship(Request $request, Response $response, array $args, DeleteRelationshipParams $params)`

-   **Endpoint**: `DELETE /V8/module/{moduleName}/{id}/relationships/{linkFieldName}/{relatedBeanId}`
-   **Description**: Deletes a relationship between two records.

### Common Logic

All of the methods in this controller follow the same basic pattern:

1.  **Call Service**: They call the appropriate method on the `relationshipService` to perform the requested relationship operation.
2.  **Generate Response**: They use the `generateResponse()` method (inherited from `BaseController`) to create a successful HTTP response with the appropriate status code (`200 OK` for retrievals and deletions, `201 Created` for creations).
3.  **Error Handling**: They use a `try...catch` block to handle any exceptions that may occur, and they use the `generateErrorResponse()` method to create a standardized error response with a `400 Bad Request` status code.

## Associated Components

-   `Api\V8\Controller\BaseController`: The base class that this controller extends.
-   `Api\V8\Service\RelationshipService`: The service that contains the business logic for relationship operations.
-   `Api\V8\Param\*`: The parameter objects that contain the validated request parameters for each method.
-   `Slim\Http\Request`: The request object from the Slim framework.
-   `Slim\Http\Response`: The response object from the Slim framework. 