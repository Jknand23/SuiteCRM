
/**
 * @fileoverview This file defines the `LogoutController` for the SuiteCRM V8 API. This controller is responsible for handling user logout requests. It validates the user's access token and then uses the `LogoutService` to invalidate the token, effectively logging the user out.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Logout Controller

## Overview

The `LogoutController.php` file, located in `Api/V8/Controller/`, defines the `LogoutController` class. This controller is responsible for handling requests to the `/V8/logout` endpoint. It is an "invokable" controller, which means it has a single `__invoke()` method that is executed when the controller is called.

## Constructor

The controller's constructor takes two arguments:

-   `LogoutService $logoutService`: An instance of the `LogoutService`. This service is injected by the dependency injection container and contains the business logic for logging a user out.
-   `ResourceServer $resourceServer`: An instance of the `ResourceServer`. This is the OAuth2 resource server, and it is used to validate the access token that is sent with the logout request.

## `__invoke(Request $request, Response $response)`

This is the main method of the controller. It is responsible for handling the logout request.

### Arguments:

-   `Request $request`: The HTTP request object.
-   `Response $response`: The HTTP response object.

### Logic:

1.  **Validate Token**: It first calls the `validateAuthenticatedRequest()` method on the `resourceServer`. This method validates the access token that is sent with the request and returns a new request object with the token's details as attributes. It then gets the `oauth_access_token_id` attribute from the request.

2.  **Call Service**: It then calls the `logout()` method on the `logoutService`, passing the access token ID to it. The service is responsible for invalidating the access token, which effectively logs the user out.

3.  **Generate Response**: It takes the response from the service and uses the `generateResponse()` method (inherited from `BaseController`) to create a successful HTTP response with a `200 OK` status code.

4.  **Error Handling**: If an exception is thrown during the process, it is caught, and the `generateErrorResponse()` method is used to create a standardized error response with a `400 Bad Request` status code.

## Associated Components

-   `Api\V8\Controller\BaseController`: The base class that this controller extends.
-   `Api\V8\Service\LogoutService`: The service that contains the business logic for logging out.
-   `League\OAuth2\Server\ResourceServer`: The OAuth2 resource server.
-   `Slim\Http\Request`: The request object from the Slim framework.
-   `Slim\Http\Response`: The response object from the Slim framework. 