
/**
 * @fileoverview This file defines the `MetaService` for the SuiteCRM V8 API. This service is responsible for providing metadata about the API and the SuiteCRM instance, such as lists of available modules, field definitions, and the API's Swagger/OpenAPI schema.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Meta Service

## Overview

The `MetaService.php` file, located in `Api/V8/Service/`, defines the `MetaService` class. This service contains the business logic for retrieving metadata about the API. It is used by the `MetaController`.

## Constructor

The constructor takes the following arguments:

-   `BeanManager $beanManager`: An instance of the `BeanManager`.
-   `ModuleListProvider $moduleListProvider`: A helper for retrieving a list of modules.

## Methods

### `getModuleList(Request $request)`

This method retrieves a list of all available modules in the SuiteCRM instance.

### `getFieldList(Request $request, GetFieldListParams $fieldListParams)`

This method retrieves a list of all the fields for a given module. It also prunes the field definitions to only include a specific set of allowed fields, which are defined in the `allowedVardefFields` static property.

### `getSwaggerSchema()`

This method retrieves the Swagger/OpenAPI 2.0 schema for the API. The schema is a JSON file that provides a complete description of the API's endpoints, parameters, and responses.

### `checkIfUserHasModuleAccess($module)`

This is a private helper method that checks if the current user has access to a given module. It throws a `NotAllowedException` if the user does not have access.

### `buildFieldList($module)`

This is a private helper method that builds the list of fields for a given module.

### `pruneVardef($def)`

This is a private helper method that prunes the field definitions to only include the allowed fields.

## Associated Components

-   `Api\V8\Controller\MetaController`: The controller that uses this service.
-   `Api\V8\BeanDecorator\BeanManager`: A service used to interact with beans.
-   `Api\V8\Helper\ModuleListProvider`: A helper for retrieving a list of modules.
-   `Api\V8\Param\GetFieldListParams`: The parameter object that contains the validated request parameters.
-   `Slim\Http\Request`: The request object from the Slim framework.
-   `Api\V8\JsonApi\Response\*`: The classes used to build the JSON:API response.
-   `SuiteCRM\Exception\*`: The exception classes used by the service. 