
/**
 * @fileoverview This file provides the `CustomLoader` class, a utility designed to facilitate the loading of custom configurations and routes for the SuiteCRM API. It enables an upgrade-safe extension mechanism by allowing developers to place their own configuration and route files in a designated `custom` directory. These custom files are then merged with the base application's configurations, allowing for modification and extension of the API without altering core files.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# Custom Configuration and Route Loader

## Overview

The `CustomLoader` class, found in `Api/Core/Loader/`, is a crucial component for enabling the customization and extension of the SuiteCRM API. It provides a set of static methods to load and merge custom configuration arrays and route definitions from the `custom/` directory, which is the standard location for user-defined modifications in SuiteCRM.

### Key Responsibilities:

-   **Extensible Configuration**: It provides a mechanism to merge custom configuration arrays with the base API configuration. This allows administrators or developers to override default settings or add new ones without modifying the core code.
-   **Custom Route Loading**: It allows for the loading of custom route definition files, enabling the addition of new API endpoints.
-   **Upgrade-Safe Customizations**: By separating custom code into the `custom/` directory, this loader helps ensure that customizations are not overwritten during application upgrades.

## Core Methods

### `mergeCustomArray($array, $customFile)`

This method is the heart of the configuration loading process. It takes a base configuration array and the name of a file. It then looks for a file with the same name in the `custom/` path. If the custom file exists and returns an array, this method will recursively merge the custom array into the base array using the `arrayMerge` helper method.

### `loadCustomRoutes(App $app, $customRoutesFile)`

This method is used to load custom API routes. It looks for a specified route file (defaulting to `Config/routes.php`) within the `custom/` path. If the file exists, it is included, and it is expected to define new routes on the provided `\Slim\App` instance.

### `arrayMerge($arrays)`

This is a static helper method that performs a deep merge of multiple arrays. Unlike the standard `array_merge_recursive()`, it is designed to handle both associative and indexed arrays in a way that is more suitable for configuration merging, correctly overwriting values by key.

## Error Handling and Path Management

-   The class defines several constants (`ERR_*`) to represent different error states, such as a file not being found. The `getLastError()` method can be used to check the status of the most recent operation.
-   The path to the custom directory is managed by the `getCustomPath()` and `setCustomPath()` methods, with a default value of `custom/application/Ext/Api/V8/`.

## Associated Components

-   `\Slim\App`: The core Slim application instance, which is passed to `loadCustomRoutes` to have new routes registered on it. 