
/**
 * @fileoverview This file defines the `VarDefHelper` class for the SuiteCRM V8 API. This helper class is used to get all of the relationships for a given bean.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API VarDef Helper

## Overview

The `VarDefHelper.php` file, located in `Api/V8/Helper/`, defines the `VarDefHelper` class. This helper class is used to get all of the relationships for a given bean.

## `getAllRelationships(\SugarBean $bean)`

This is the main method of the class. It gets all of the relationships for a given bean.

### Arguments:

-   `\SugarBean $bean`: The bean to get the relationships for.

### Returns:

An array of relationships, where the key is the name of the relationship and the value is the name of the related module.

## Associated Components

-   `\SugarBean`: The base class for all of SuiteCRM's data objects. 