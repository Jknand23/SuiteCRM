
/**
 * @fileoverview This file defines the `SuiteInvocationStrategy` for the SuiteCRM V8 API. This is a custom invocation strategy for the Slim framework that modifies the way controller methods are called. It enhances the default strategy by adding the route arguments and a `params` attribute from the request as arguments to the controller method, providing the controller with all of its required data in a consistent and predictable manner.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Suite Invocation Strategy

## Overview

The `SuiteInvocationStrategy.php` file, located in `Api/V8/Controller/InvocationStrategy/`, defines the `SuiteInvocationStrategy` class. This class implements the `InvocationStrategyInterface` from the Slim framework, which allows it to customize the way that controller methods are called.

The purpose of this custom strategy is to provide the controller methods with a consistent set of arguments, regardless of the number of route parameters or whether the request has a `params` attribute.

## `__invoke(callable $callable, ServerRequestInterface $request, ResponseInterface $response, array $routeArguments)`

This is the main method of the class. It is called by the Slim framework when it is time to dispatch a request to a controller.

### Arguments:

-   `callable $callable`: The controller method to be called.
-   `ServerRequestInterface $request`: The HTTP request object.
-   `ResponseInterface $response`: The HTTP response object.
-   `array $routeArguments`: An array of arguments from the route's pattern.

### Logic:

1.  **Add Route Arguments to Request**: It first iterates over the `$routeArguments` and adds each one as an attribute to the `$request` object. This makes the route arguments available to any middleware that may be executed before the controller.

2.  **Call Controller Method**: It then calls the controller method (`$callable`), passing it the following arguments:
    -   The `$request` object.
    -   The `$response` object.
    -   The `$routeArguments` array.
    -   The `params` attribute from the request. The `params` attribute is set by the parameter handling middleware, and it contains an object with the validated request parameters. If the `params` attribute is not set, it passes `null`.

## Usage

This custom invocation strategy is registered with the dependency injection container as the `foundHandler` service in the `Api/V8/Config/services.php` file. This means that it will be used for all routes in the V8 API.

## Associated Components

-   `Slim\Interfaces\InvocationStrategyInterface`: The interface that this class implements.
-   `Psr\Http\Message\ServerRequestInterface`: The request object.
-   `Psr\Http\Message\ResponseInterface`: The response object.
-   The dependency injection container, which is used to register this class as the `foundHandler`. 