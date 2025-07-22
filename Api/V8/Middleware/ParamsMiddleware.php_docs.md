/**
 * @fileoverview This file defines the `ParamsMiddleware` class for the SuiteCRM V8 API. This middleware is responsible for parameter validation and processing, setting up the current user global, and handling any exceptions that occur during parameter processing.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Params Middleware

## Overview

The `ParamsMiddleware.php` file, located in `Api/V8/Middleware/`, defines the `ParamsMiddleware` class. This middleware is responsible for parameter validation and processing for the API.

## Constructor

The constructor takes the following arguments:

-   `BaseParam $params`: A parameter object that will be used to validate and process the request parameters.
-   `BeanManager $beanManager`: An instance of the `BeanManager`.

## `__invoke(Request $request, Response $httpResponse, callable $next)`

This method is called when the middleware is executed. It performs the following operations:

1.  **Set Current User Global**: It calls the `setCurrentUserGlobal()` method to set up the current user global.
2.  **Get Parameters**: It calls the `getParameters()` method to extract the parameters from the request.
3.  **Configure Parameters**: It calls the `configure()` method on the parameter object to validate and process the parameters.
4.  **Add Parameters to Request**: It adds the parameter object to the request as an attribute.
5.  **Handle Exceptions**: If any exception occurs during this process, it creates an `ErrorResponse` object and returns it.

### `setCurrentUserGlobal(Request $request)`

This method is responsible for setting up the current user global. It does this by finding the user's access token in the database and then using the `assigned_user_id` from the token to retrieve the user's record.

### `getParameters(Request $request)`

This method extracts the parameters from the request. It merges the route parameters, query parameters, and parsed body into a single array.

## Associated Components

-   `Api\V8\Param\BaseParam`: The base class for all parameter objects.
-   `Api\V8\BeanDecorator\BeanManager`: A service used to interact with beans.
-   `Api\V8\JsonApi\Response\ErrorResponse`: The class used to create error responses.
-   `Slim\Http\Request`: The request object from the Slim framework.
-   `Slim\Http\Response`: The response object from the Slim framework.
-   `LoggerManager`: The logger manager class. 