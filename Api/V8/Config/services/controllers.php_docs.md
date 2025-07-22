
/**
 * @fileoverview This file is responsible for defining all of the controller services for the SuiteCRM V8 API. It uses the dependency injection container to instantiate the controller classes and inject their required service dependencies. This approach promotes a clean separation of concerns and enhances the testability of the controllers.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Controller Service Definitions

## Overview

The `controllers.php` file, located in `Api/V8/Config/services/`, defines all of the controller classes as services for the V8 API. By defining the controllers as services, the application can leverage the dependency injection container to manage their creation and to automatically inject any required dependencies (which are themselves other services).

## Service Definitions

The file returns an array of service definitions, where each key is the fully qualified class name of a controller, and the value is a closure that creates an instance of that controller.

The closures receive the container instance (`Psr\Container\ContainerInterface`) as an argument, which they use to retrieve the services that the controller depends on.

For example, the service definition for the `ModuleController` is:

```php
Controller\ModuleController::class => function (Container $container) {
    return new Controller\ModuleController(
        $container->get(ModuleService::class)
    );
},
```

This definition specifies that when the `ModuleController` service is requested from the container, the container should execute this closure. The closure will then retrieve the `ModuleService` from the container and use it to construct a new `ModuleController` instance.

## Defined Controllers

This file defines the following controller services:

-   `ListViewSearchController`
-   `UserPreferencesController`
-   `UserController`
-   `MetaController`
-   `ListViewController`
-   `ModuleController`
-   `LogoutController`
-   `RelationshipController`

Each of these controllers is responsible for handling a specific area of the API's functionality, as seen in the `routes.php` file.

## Extensibility

Like the other service definition files, this file uses `Api\Core\Loader\CustomLoader::mergeCustomArray()` to allow for the addition of custom controller services or the modification of existing ones in an upgrade-safe way.

## Associated Components

-   `Api\V8\Controller\*`: The controller classes themselves.
-   `Api\V8\Service\*`: The service classes that are injected into the controllers.
-   `Psr\Container\ContainerInterface`: The dependency injection container.
-   `Api\Core\Loader\CustomLoader`: The loader for custom service definitions. 