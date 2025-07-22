
/**
 * @fileoverview This file defines the core business logic services for the SuiteCRM V8 API. These services encapsulate the application's business logic, separating it from the controllers and making it reusable and easier to test. This file is a cornerstone of the API's service-oriented architecture.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Business Logic Service Definitions

## Overview

The `services.php` file, located in `Api/V8/Config/services/`, is responsible for defining the core business logic services for the V8 API. These services are the workhorses of the application, containing the logic for interacting with the database, manipulating data, and performing the other tasks that are required to fulfill the API's requests.

By separating the business logic into services, the API follows the "fat model, skinny controller" paradigm. The controllers are responsible for handling the HTTP request and response, while the services are responsible for the business logic. This makes the application more modular, easier to maintain, and easier to test.

## Service Definitions

The file returns an array of service definitions, where each key is the fully qualified class name of a service, and the value is a closure that creates an instance of that service.

Each service is defined with the dependencies it needs to perform its work. These dependencies are other services that are retrieved from the container.

For example, the service definition for the `ModuleService` is:

```php
Service\ModuleService::class => function (Container $container) {
    return new Service\ModuleService(
        $container->get(BeanManager::class),
        $container->get(AttributeObjectHelper::class),
        $container->get(RelationshipObjectHelper::class),
        $container->get(PaginationObjectHelper::class)
    );
},
```

This shows that the `ModuleService` depends on the `BeanManager` and several JSON:API helpers to do its work.

## Defined Services

This file defines the following services:

-   `ListViewSearchService`: Handles the logic for retrieving list view search definitions.
-   `UserPreferencesService`: Manages user preferences.
-   `UserService`: Handles user-related operations.
-   `MetaService`: Provides metadata about the API and the SuiteCRM instance.
-   `ListViewService`: Handles the logic for retrieving list view data.
-   `ModuleService`: The main service for performing CRUD (Create, Read, Update, Delete) operations on module records.
-   `LogoutService`: Handles the user logout process.
-   `RelationshipService`: Manages the relationships between module records.

## Extensibility

As with the other service definition files, this file uses `Api\Core\Loader\CustomLoader::mergeCustomArray()` to allow for the addition of custom business logic services or the modification of existing ones in an upgrade-safe way.

## Associated Components

-   `Api\V8\Service\*`: The service classes themselves.
-   `Api\V8\Controller\*`: The controller classes that use these services.
-   `Api\V8\BeanDecorator\BeanManager`: A key dependency for many of the services.
-   `Api\V8\JsonApi\Helper\*`: Helper services that are used by the business logic services.
-   `Psr\Container\ContainerInterface`: The dependency injection container.
-   `Api\Core\Loader\CustomLoader`: The loader for custom service definitions. 