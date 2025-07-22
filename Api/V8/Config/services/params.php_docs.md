
/**
 * @fileoverview This file defines the services for all of the parameter objects used in the SuiteCRM V8 API. Each parameter object is responsible for validating the request parameters for a specific API endpoint. This approach encapsulates the validation logic for each endpoint, making the controllers cleaner and the validation logic easier to manage and test.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Parameter Service Definitions

## Overview

The `params.php` file, located in `Api/V8/Config/services/`, is responsible for defining all of the parameter objects as services in the dependency injection container. These parameter objects are a key part of the API's architecture, as they provide a structured and validated way to handle the data that is sent in API requests.

## Service Definitions

The file returns an array of service definitions, where each key is the fully qualified class name of a parameter object, and the value is a closure that creates an instance of that object.

Each parameter object service is defined with the same two dependencies:

-   `Api\V8\Factory\ValidatorFactory`: This factory is used to create the validator objects that the parameter object uses to validate the request data.
-   `Api\V8\BeanDecorator\BeanManager`: This manager is used to interact with the SuiteCRM beans, which is often necessary for validation (e.g., to check if a record exists).

For example, the service definition for the `GetModulesParams` object is:

```php
Param\GetModulesParams::class => function (Container $container) {
    return new Param\GetModulesParams(
        $container->get(ValidatorFactory::class),
        $container->get(BeanManager::class)
    );
},
```

## Defined Parameter Objects

This file defines a parameter object for nearly every endpoint in the API that accepts parameters. This includes parameters for:

-   Getting module records (`GetModuleParams`, `GetModulesParams`)
-   Creating, updating, and deleting module records (`CreateModuleParams`, `UpdateModuleParams`, `DeleteModuleParams`)
-   Getting and manipulating relationships (`GetRelationshipParams`, `CreateRelationshipParams`, `CreateRelationshipByLinkParams`, `DeleteRelationshipParams`)
-   Getting metadata (`ListViewSearchParams`, `ListViewColumnsParams`, `GetFieldListParams`)
-   Getting user preferences (`GetUserPreferencesParams`)

## Usage

These parameter object services are used by the `ParamsMiddlewareFactory`. When a route is configured with a parameter object, the factory creates a middleware that will, in turn, retrieve the corresponding parameter object from the container and use it to validate the request.

## Extensibility

As with the other service definition files, this file uses `Api\Core\Loader\CustomLoader::mergeCustomArray()` to allow for the addition of custom parameter services or the modification of existing ones in an upgrade-safe way.

## Associated Components

-   `Api\V8\Param\*`: The parameter classes themselves.
-   `Api\V8\Factory\ParamsMiddlewareFactory`: The factory that uses these services to create parameter-handling middleware.
-   `Api\V8\Factory\ValidatorFactory`: The factory that is injected into the parameter objects.
-   `Api\V8\BeanDecorator\BeanManager`: The bean manager that is injected into the parameter objects.
-   `Psr\Container\ContainerInterface`: The dependency injection container.
-   `Api\Core\Loader\CustomLoader`: The loader for custom service definitions. 