
/**
 * @fileoverview This file contains the `RouteLoader` class, which is responsible for dynamically loading all of the route definition files for the SuiteCRM API. It plays a crucial role in the application's bootstrap process by centralizing the route loading mechanism, which keeps the main application file clean and promotes a modular and organized structure for defining API endpoints.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# API Route Loader

## Overview

The `RouteLoader` class, located in `Api/Core/Loader/`, is a key component of the API's startup process. Its primary responsibility is to load the route definitions from their respective files and apply them to the Slim application instance.

### `configureRoutes(App $app)`

This is the main public method of the class. It orchestrates the route loading process as follows:

1.  **Retrieve Route File Paths**: It calls `Api\Core\Config\ApiConfig::getRoutes()` to get an array of paths to the route definition files. This decouples the loader from the specific locations of the route files, which are managed in the `ApiConfig` class.

2.  **Iterate and Load**: It iterates through the array of route file paths.

3.  **File Existence Check**: For each path, it uses `Api\Core\Resolver\ConfigResolver::isFileExist()` to verify that the route file actually exists before attempting to include it. This prevents errors that would occur if a configured route file were missing.

4.  **Include Route File**: If the file exists, it is included using `require`. The route definition files are expected to contain code that defines routes on the `$app` instance that is passed into the `configureRoutes` method.

## Decoupled Architecture

By using `ApiConfig` to get the route file paths and `ConfigResolver` to check for their existence, the `RouteLoader` demonstrates a decoupled and flexible architecture. This makes it easy to add, remove, or modify the locations of route files without having to change the loader's code.

## Associated Components

-   `\Slim\App`: The core Slim application instance to which the routes are applied.
-   `Api\Core\Config\ApiConfig`: The class that provides the paths to the route definition files.
-   `Api\Core\Resolver\ConfigResolver`: A utility class used to check for the existence of configuration files. 