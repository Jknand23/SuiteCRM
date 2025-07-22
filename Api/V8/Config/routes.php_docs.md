
/**
 * @fileoverview This file defines the routes for the SuiteCRM V8 API. It establishes all of the available endpoints, linking them to their corresponding controller actions and applying the necessary middleware for authentication and parameter handling. This file is the primary entry point for all V8 API requests and provides a clear map of the API's functionality.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Route Definitions

## Overview

The `routes.php` file, located in `Api/V8/Config/`, is responsible for defining all of the HTTP routes for the V8 API. It uses the Slim framework's routing capabilities to map URI patterns and HTTP methods to specific controller classes and methods. This file is included by the `RouteLoader` during the application's bootstrap process.

## Route Groups and Middleware

The routes are organized into two main groups:

1.  **Top-Level Group**: This group contains the OAuth2 `access_token` endpoint. It applies the `AuthorizationServerMiddleware` to handle the token issuance process.

2.  **`/V8` Group**: This is the main group for all V8 API endpoints. It is protected by the `ResourceServerMiddleware`, which ensures that all requests to these routes have a valid OAuth2 access token.

## Endpoints

The V8 API provides a comprehensive set of endpoints for interacting with SuiteCRM modules and data. Here is a summary of the available routes:

### Authentication

-   `POST /access_token`: Obtains an OAuth2 access token.
-   `POST /V8/logout`: Logs the user out.

### Module and Record Operations (CRUD)

-   `GET /V8/module/{moduleName}`: Retrieves a list of records for a given module.
-   `GET /V8/module/{moduleName}/{id}`: Retrieves a single record from a module.
-   `POST /V8/module`: Creates a new record in a module.
-   `PATCH /V8/module`: Updates an existing record in a module.
-   `DELETE /V8/module/{moduleName}/{id}`: Deletes a record from a module.

### Relationship Management

-   `GET /V8/module/{moduleName}/{id}/relationships/{linkFieldName}`: Retrieves the records related to a given record through a specific relationship link.
-   `POST /V8/module/{moduleName}/{id}/relationships`: Creates a new relationship between two records.
-   `POST /V8/module/{moduleName}/{id}/relationships/{linkFieldName}`: Creates a new relationship by link field.
-   `DELETE /V8/module/{moduleName}/{id}/relationships/{linkFieldName}/{relatedBeanId}`: Deletes a relationship between two records.

### Metadata and Configuration

-   `GET /V8/search-defs/module/{moduleName}`: Retrieves the search definitions for a module's list view.
-   `GET /V8/listview/columns/{moduleName}`: Retrieves the column definitions for a module's list view.
-   `GET /V8/current-user`: Retrieves information about the currently authenticated user.
-   `GET /V8/meta/modules`: Retrieves a list of all available modules.
-   `GET /V8/meta/fields/{moduleName}`: Retrieves the field definitions for a given module.
-   `GET /V8/user-preferences/{id}`: Retrieves the user preferences for a given user.
-   `GET /V8/meta/swagger.json`: Retrieves the Swagger/OpenAPI schema for the API.

### Customization

-   The file includes a `/V8/custom` route group that uses the `CustomLoader` to load any custom-defined routes, allowing for an easy and upgrade-safe way to extend the API.

## Parameter Handling

Many of the routes use a custom `ParamsMiddlewareFactory`. This middleware is responsible for binding incoming request parameters to specific parameter objects (e.g., `Param\GetModulesParams`). This provides a structured and validated way to handle request data.

## Associated Components

-   `Api\V8\Controller\*`: The controller classes that handle the logic for each route.
-   `Api\V8\Factory\ParamsMiddlewareFactory`: The factory for creating the parameter-binding middleware.
-   `Api\V8\Param\*`: The parameter objects that define the expected request parameters for each route.
-   `League\OAuth2\Server\Middleware\*`: The middleware classes for handling OAuth2 authentication.
-   `Api\Core\Loader\CustomLoader`: The loader for custom routes. 