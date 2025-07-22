
/**
 * @fileoverview This file defines the `ModuleController` for the SuiteCRM V8 API. This is the central controller for all CRUD (Create, Read, Update, Delete) operations on module records. It acts as the primary entry point for interacting with the data in SuiteCRM modules, delegating the business logic for these operations to the `ModuleService`.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Module Controller

## Overview

The `ModuleController.php` file, located in `Api/V8/Controller/`, defines the `ModuleController` class. This controller is the workhorse of the V8 API, responsible for handling all of the CRUD (Create, Read, Update, Delete) operations on the records in SuiteCRM's modules.

## Constructor

The controller's constructor takes a single argument:

-   `ModuleService $moduleService`: An instance of the `ModuleService`. This service is injected by the dependency injection container and contains the business logic for interacting with module records.

## Methods

### `getModuleRecord(Request $request, Response $response, array $args, GetModuleParams $params)`

-   **Endpoint**: `GET /V8/module/{moduleName}/{id}`
-   **Description**: Retrieves a single record from a module.

### `getModuleRecords(Request $request, Response $response, array $args, GetModulesParams $params)`

-   **Endpoint**: `GET /V8/module/{moduleName}`
-   **Description**: Retrieves a list of records from a module. This method supports pagination, filtering, and sorting.

### `createModuleRecord(Request $request, Response $response, array $args, CreateModuleParams $params)`

-   **Endpoint**: `POST /V8/module`
-   **Description**: Creates a new record in a module.

### `updateModuleRecord(Request $request, Response $response, array $args, UpdateModuleParams $params)`

-   **Endpoint**: `PATCH /V8/module`
-   **Description**: Updates an existing record in a module.

### `deleteModuleRecord(Request $request, Response $response, array $args, DeleteModuleParams $params)`

-   **Endpoint**: `DELETE /V8/module/{moduleName}/{id}`
-   **Description**: Deletes a record from a module.

### Common Logic

All of the methods in this controller follow the same basic pattern:

1.  **Call Service**: They call the appropriate method on the `moduleService` to perform the requested CRUD operation.
2.  **Generate Response**: They use the `generateResponse()` method (inherited from `BaseController`) to create a successful HTTP response with the appropriate status code (`200 OK` for retrievals and deletions, `201 Created` for creations and updates).
3.  **Error Handling**: They use a `try...catch` block to handle any exceptions that may occur, and they use the `generateErrorResponse()` method to create a standardized error response with a `400 Bad Request` status code.

## Associated Components

-   `Api\V8\Controller\BaseController`: The base class that this controller extends.
-   `Api\V8\Service\ModuleService`: The service that contains the business logic for module operations.
-   `Api\V8\Param\*`: The parameter objects that contain the validated request parameters for each method.
-   `Slim\Http\Request`: The request object from the Slim framework.
-   `Slim\Http\Response`: The response object from the Slim framework. 