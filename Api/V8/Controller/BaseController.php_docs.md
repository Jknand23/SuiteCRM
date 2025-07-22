
/**
 * @fileoverview This file defines the `BaseController` for the SuiteCRM V8 API. This abstract class provides common functionality that is shared by all other controllers in the API, such as methods for generating standardized success and error responses. This promotes code reuse and ensures a consistent response format across all API endpoints.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Base Controller

## Overview

The `BaseController.php` file, located in `Api/V8/Controller/`, defines an abstract `BaseController` class that serves as the foundation for all other controllers in the V8 API. It provides a set of common methods and properties that are used to generate the API's HTTP responses, ensuring that all responses are consistent and adhere to the JSON:API specification.

## Core Methods

### `generateResponse(HttpResponse $httpResponse, $response, $status)`

This method is used to generate a successful HTTP response. It takes an `HttpResponse` object, the response data, and an HTTP status code as input. It then configures the response object with the following:

-   **Status Code**: Sets the HTTP status code for the response.
-   **Headers**: Sets the `Accept` and `Content-type` headers to `application/vnd.api+json`, which is the media type for JSON:API.
-   **Body**: Encodes the response data as a JSON string and writes it to the response body. The JSON is formatted with the `JSON_PRETTY_PRINT`, `JSON_UNESCAPED_UNICODE`, and `JSON_UNESCAPED_SLASHES` options for improved readability.

### `generateErrorResponse(HttpResponse $httpResponse, \Exception $exception, $status)`

This method is used to generate a standardized error response. It takes an `HttpResponse` object, an `Exception` object, and an HTTP status code as input. It then creates an `ErrorResponse` object and populates it with the details from the exception. Finally, it calls the `generateResponse()` method to create the HTTP response with the error data.

This method ensures that all error responses have a consistent format, which makes it easier for clients to handle errors.

## Constants

-   `MEDIA_TYPE`: This constant defines the JSON:API media type, `application/vnd.api+json`.

## Inheritance

All other controllers in the V8 API should extend this `BaseController` class. This allows them to inherit the `generateResponse()` and `generateErrorResponse()` methods, which simplifies the process of creating responses and ensures that all responses are consistent.

## Associated Components

-   `Slim\Http\Response`: The response object from the Slim framework.
-   `Api\V8\JsonApi\Response\ErrorResponse`: The class used to create the body of an error response. 