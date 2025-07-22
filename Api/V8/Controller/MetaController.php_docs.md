
/**
 * @fileoverview This file defines the `MetaController` for the SuiteCRM V8 API. This controller is responsible for handling requests for metadata about the API and the SuiteCRM instance, such as lists of available modules, field definitions, and the API's Swagger/OpenAPI schema. It delegates the business logic for these tasks to the `MetaService`.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Metadata Controller

## Overview

The `MetaController.php` file, located in `Api/V8/Controller/`, defines the `MetaController` class. This controller is responsible for providing metadata about the API to clients. This is a crucial part of the API, as it allows clients to dynamically discover the API's capabilities, such as the available modules and their fields.

## Constructor

The controller's constructor takes a single argument:

-   `MetaService $metaService`: An instance of the `MetaService`. This service is injected by the dependency injection container and contains the business logic for retrieving metadata.

## Methods

### `getModuleList(Request $request, Response $response, array $args)`

This method handles requests to the `/V8/meta/modules` endpoint. It calls the `getModuleList()` method on the `metaService` to retrieve a list of all available modules in the SuiteCRM instance.

### `getFieldList(Request $request, Response $response, array $args, GetFieldListParams $fieldListParams)`

This method handles requests to the `/V8/meta/fields/{moduleName}` endpoint. It calls the `getFieldList()` method on the `metaService` to retrieve a list of all the fields for a given module.

### `getSwaggerSchema(Request $request, Response $response)`

This method handles requests to the `/V8/meta/swagger.json` endpoint. It calls the `getSwaggerSchema()` method on the `metaService` to retrieve the Swagger/OpenAPI 2.0 schema for the API. This schema provides a complete description of the API's endpoints, parameters, and responses, and it can be used to generate client libraries and interactive documentation.

### Common Logic

All of the methods in this controller follow the same basic pattern:

1.  **Call Service**: They call the appropriate method on the `metaService` to retrieve the requested metadata.
2.  **Generate Response**: They use the `generateResponse()` method (inherited from `BaseController`) to create a successful HTTP response with a `200 OK` status code.
3.  **Error Handling**: They use a `try...catch` block to handle any exceptions that may occur, and they use the `generateErrorResponse()` method to create a standardized error response with a `400 Bad Request` status code.

## Associated Components

-   `Api\V8\Controller\BaseController`: The base class that this controller extends.
-   `Api\V8\Service\MetaService`: The service that contains the business logic for retrieving metadata.
-   `Api\V8\Param\GetFieldListParams`: The parameter object that contains the validated request parameters for the `getFieldList()` method.
-   `Slim\Http\Request`: The request object from the Slim framework.
-   `Slim\Http\Response`: The response object from the Slim framework. 