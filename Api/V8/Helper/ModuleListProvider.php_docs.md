
/**
 * @fileoverview This file defines the `ModuleListProvider` class for the SuiteCRM V8 API. This helper class is responsible for retrieving a list of all available modules, taking into account the current user's ACL access, and formatting the list with labels and access information.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Module List Provider

## Overview

The `ModuleListProvider.php` file, located in `Api/V8/Helper/`, defines the `ModuleListProvider` class. This helper class is responsible for retrieving a list of all available modules.

## `getModuleList()`

This is the main method of the class. It retrieves a list of all available modules and then performs a number of operations to format the list for the API response.

### Logic:

1.  **Get Module Access List**: It calls the `query_module_access_list()` function to get a list of all modules that the current user has access to.
2.  **Filter Module List**: It calls the `\ACLController::filterModuleList()` method to filter the list of modules based on the user's ACL access.
3.  **Remove Invisible Modules**: It calls the `removeInvisibleModules()` helper method to remove any modules that are marked as invisible.
4.  **Mark ACL Access**: It calls the `markACLAccess()` helper method to add the user's ACL access information to each module.
5.  **Add Module Labels**: It calls the `addModuleLabels()` helper method to add the module labels to each module.

### Returns:

An array of modules, where each module is an array with the following keys: `label`, `access`.

## Associated Components

-   `\ACLController`: The ACL controller class.
-   `\ACLAction`: The ACL action class.
-   `$app_list_strings`: The global application list strings.
-   `$modInvisList`: The global list of invisible modules. 