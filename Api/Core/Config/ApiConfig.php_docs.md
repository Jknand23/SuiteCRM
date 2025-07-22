
/**
 * @fileoverview This file centralizes the configuration for the SuiteCRM API by providing a static interface to access key configuration file paths. It defines the locations for Slim framework settings, dependency injection container definitions, and route configurations. This class also manages a debug flag for exceptions and defines constants for OAuth2 cryptographic keys, making it a critical component for the API's initialization and runtime management.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# API Configuration Registry

## Overview

The `ApiConfig` class, located in `Api/Core/Config/`, serves as a centralized registry for the API's configuration. It uses static properties and methods to provide easy access to the paths of various configuration files and other settings. This approach decouples the application's bootstrap process from the specific locations of its configuration files, making the system more modular and easier to manage.

### Key Responsibilities:

-   **Slim Settings**: It provides the path to the Slim framework's configuration file via the `getSlimSettings()` method. This file contains settings that control the core behavior of the Slim application.

-   **Container Services**: The `getContainers()` method returns the paths to files that define services for the dependency injection container. This allows for the modular definition of services used throughout the API.

-   **Route Definitions**: The `getRoutes()` method provides the paths to the route definition files. This separates the API's endpoint definitions from the core application logic, improving organization.

-   **OAuth2 Keys**: It defines constants, `OAUTH2_PRIVATE_KEY` and `OAUTH2_PUBLIC_KEY`, which specify the locations of the cryptographic keys used for the OAuth2 authentication mechanism.

-   **Exception Debugging**: It includes a static property, `$debugExceptions`, and a corresponding getter, `getDebugExceptions()`, to control whether detailed exception information should be displayed. This is a useful flag for development and debugging.

## Static Properties

-   `$slimSettings`: An array containing the path(s) to the Slim framework configuration file(s).
-   `$containers`: An array containing the path(s) to the service container definition file(s).
-   `$routes`: An array containing the path(s) to the route definition file(s).
-   `$debugExceptions`: A boolean flag to enable or disable detailed exception output.

## Constants

-   `OAUTH2_PRIVATE_KEY`: The path to the OAuth2 private key file.
-   `OAUTH2_PUBLIC_KEY`: The path to the OAuth2 public key file. 