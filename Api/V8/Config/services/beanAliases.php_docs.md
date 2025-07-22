
/**
 * @fileoverview This file defines the `beanAliases` service for the SuiteCRM V8 API. This service provides a crucial mapping between the PHP class names of `SugarBean` objects and the user-friendly module names used throughout the API. This abstraction allows for a cleaner and more consistent API interface.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# Bean Aliases Service

## Overview

The `beanAliases.php` file, located in `Api/V8/Config/services/`, is responsible for defining the `beanAliases` service. This service is a key part of the API's architecture, as it provides a bidirectional mapping between the internal PHP class names of SuiteCRM's data objects (Beans) and the external module names that are exposed through the API.

## Service Definition

The file returns an array containing a single service definition:

-   `beanAliases`: This service is defined as a closure that returns an array of aliases. The keys of the array are the fully qualified class names of the `SugarBean` objects (e.g., `Account::class`), and the values are the corresponding module names (e.g., `'Accounts'`).

This mapping is essential for translating the module names used in API requests (e.g., in a URL like `/V8/module/Accounts`) into the actual PHP classes that need to be instantiated to handle the request.

## Extensibility

The service uses `Api\Core\Loader\CustomLoader::mergeCustomArray()` to load the aliases. This means that the default set of aliases can be extended or modified by creating a `beanAliases.php` file in the appropriate custom directory. This is useful for adding aliases for custom modules or for changing the aliases of existing modules.

## Example

An example of an alias definition is:

`Account::class => 'Accounts'`

This maps the `Account` class to the `Accounts` module name.

## Associated Components

-   `Api\V8\BeanDecorator\BeanManager`: This service is a primary consumer of the `beanAliases` service. It uses the aliases to instantiate the correct bean objects based on the module names provided in API requests.
-   `Api\Core\Loader\CustomLoader`: This class is used to allow for custom modifications to the alias list. 