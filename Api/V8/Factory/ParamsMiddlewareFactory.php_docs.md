
/**
 * @fileoverview This file defines the `ParamsMiddlewareFactory` class for the SuiteCRM V8 API. This factory is responsible for creating parameter-handling middleware. It provides a generic way to create middleware that can be used to handle the parameters for any API endpoint.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Params Middleware Factory

## Overview

The `ParamsMiddlewareFactory.php` file, located in `Api/V8/Factory/`, defines the `ParamsMiddlewareFactory` class. This class is a factory for creating parameter-handling middleware.

## Constructor

The constructor takes a single argument:

-   `Container $container`: An instance of the dependency injection container.

## `bind($containerId)`

This method takes the container ID of a parameter object and returns a callable middleware. The middleware, in turn, creates a new `ParamsMiddleware` object, passing it the parameter object and the `BeanManager`.

### Arguments:

-   `$containerId`: The container ID of the parameter object.

### Returns:

A callable middleware.

## Example

```php
$app->get('/module/{moduleName}', 'Api\V8\Controller\ModuleController:getModuleRecords')
    ->add($paramsMiddlewareFactory->bind(Param\GetModulesParams::class));
```

In this example, the `bind()` method is used to create a middleware that will handle the parameters for the `getModuleRecords` endpoint. The middleware will use the `GetModulesParams` object to validate the parameters.

## Associated Components

-   `Api\V8\Middleware\ParamsMiddleware`: The middleware that is created by the factory.
-   `Psr\Container\ContainerInterface`: The dependency injection container.
-   `Api\V8\BeanDecorator\BeanManager`: A service that is passed to the `ParamsMiddleware`. 