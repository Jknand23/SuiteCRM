
/**
 * @fileoverview This file is responsible for defining and aggregating all of the services for the SuiteCRM V8 API's dependency injection container. It acts as a central manifest for service registration, defining some core services directly and including others from a dedicated `services/` subdirectory. This modular approach keeps the service definitions organized and maintainable.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Service Definitions

## Overview

The `services.php` file, located in `Api/V8/Config/`, is the primary file for configuring the dependency injection container for the V8 API. It returns a large array of service definitions that are then registered with the Slim container by the `ContainerLoader`.

The file uses `Api\Core\Loader\CustomLoader::mergeCustomArray()` to allow for the extension and modification of service definitions in an upgrade-safe manner.

## Core Service Definitions

This file defines a few key services directly:

-   `foundHandler`: This service overrides Slim's default route-found handler with a custom `SuiteInvocationStrategy`. This allows for customized logic to be executed when a matching route is found, likely to handle controller instantiation and method invocation in a way that is specific to SuiteCRM.

-   `Api\V8\BeanDecorator\BeanManager`: This service provides the `BeanManager` class, which is likely a key component for working with SuiteCRM's `SugarBean` objects. It is constructed with dependencies on the `DBManager` and a `beanAliases` service.

## Modular Service Includes

The majority of the service definitions are not in this file directly but are included from other files within the `Api/V8/Config/services/` subdirectory. This file uses `require` to include and merge arrays of service definitions from the following files:

-   `beanAliases.php`
-   `controllers.php`
-
-   `factories.php`
-   `globals.php`
-   `helpers.php`
-   `middlewares.php`
-   `params.php`
-   `services.php`
-   `validators.php`

This modular structure makes the service configuration easy to navigate and manage. Each file is responsible for a specific category of services (e.g., all controller services are defined in `controllers.php`).

## Associated Components

-   `Api\Core\Loader\ContainerLoader`: The class that loads this file and registers the services with the container.
-   `Api\Core\Loader\CustomLoader`: The class that allows for custom modifications to the service definitions.
-   `Api\V8\Controller\InvocationStrategy\SuiteInvocationStrategy`: The custom route invocation strategy.
-   `Api\V8\BeanDecorator\BeanManager`: A core service for managing `SugarBean` objects.
-   `Psr\Container\ContainerInterface`: The interface that the dependency injection container implements. 