
/**
 * @fileoverview This file defines the core configuration settings for the Slim framework, which powers the SuiteCRM API. It returns an array of settings that control the behavior of the Slim application, such as error detail display and middleware execution order. The configuration is designed to be extensible, using a `CustomLoader` to merge these default settings with any custom configurations that may be present in the SuiteCRM installation.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# Slim Framework Configuration

## Overview

The `slim.php` file, located in `Api/Core/Config/`, is responsible for providing the foundational configuration for the Slim micro-framework. This configuration is crucial for controlling the application's runtime behavior.

The file returns an array of settings, which includes:

-   `displayErrorDetails`: When set to `true`, the default error handler will display detailed information about exceptions, which is useful for debugging during development. This should typically be set to `false` in a production environment.
-   `determineRouteBeforeAppMiddleware`: When `true`, the router is invoked before any middleware, making the route information available within the middleware. This is useful for middleware that needs to act based on the specific route being accessed.
-   `addContentLengthHeader`: When `false`, the `Content-Length` header is not automatically added to the response. This can be useful in situations where the content length is not known in advance or when using chunked encoding.

## Extensibility

A key feature of this configuration file is its use of `Api\Core\Loader\CustomLoader::mergeCustomArray()`. This allows the default Slim settings to be overridden or extended by custom configuration files located elsewhere in the SuiteCRM structure. This provides a clean way to customize the API's behavior without modifying the core files. The `basename(__FILE__)` argument is used to identify the base configuration file being loaded.

## Associated Components

-   `\Slim\App`: The core class of the Slim Framework, which uses this configuration.
-   `Api\Core\Loader\CustomLoader`: A utility class for merging custom configurations. 