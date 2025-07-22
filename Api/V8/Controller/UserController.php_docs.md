
/**
 * @fileoverview This file defines the `UserController` for the SuiteCRM V8 API. This controller is responsible for handling requests related to user data, such as retrieving information about the currently authenticated user. It delegates the business logic for these tasks to the `UserService`.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API User Controller

## Overview

The `UserController.php` file, located in `Api/V8/Controller/`, defines the `UserController` class. This controller is responsible for handling API requests that are related to user data.

## Constructor

The controller's constructor takes a single argument:

-   `UserService $userService`: An instance of the `UserService`. This service is injected by the dependency injection container and contains the business logic for handling user data.

## `getCurrentUser(Request $request, Response $response, array $args)`

This is the main method of the controller. It is responsible for handling requests to the `/V8/current-user` endpoint.

### Arguments:

-   `Request $request`: The HTTP request object.
-   `Response $response`: The HTTP response object.
-   `array $args`: An array of route parameters.

### Logic:

1.  **Call Service**: It calls the `getCurrentUser()` method on the `userService`, passing the `request` object to it. The service is responsible for retrieving the data for the currently authenticated user.

2.  **Generate Response**: It takes the JSON response from the service and uses the `generateResponse()` method (inherited from `BaseController`) to create a successful HTTP response with a `200 OK` status code.

3.  **Error Handling**: If an exception is thrown during the process, it is caught, and the `generateErrorResponse()` method is used to create a standardized error response with a `400 Bad Request` status code.

## Associated Components

-   `Api\V8\Controller\BaseController`: The base class that this controller extends.
-   `Api\V8\Service\UserService`: The service that contains the business logic for user operations.
-   `Slim\Http\Request`: The request object from the Slim framework.
-   `Slim\Http\Response`: The response object from the Slim framework. 