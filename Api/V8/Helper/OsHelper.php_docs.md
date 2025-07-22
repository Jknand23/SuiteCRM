
/**
 * @fileoverview This file defines the `OsHelper` class for the SuiteCRM V8 API. This helper class is used to determine the current operating system.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API OS Helper

## Overview

The `OsHelper.php` file, located in `Api/V8/Helper/`, defines the `OsHelper` class. This helper class is used to determine the current operating system.

## `getOS()`

This is the main method of the class. It returns one of the three constants: `OS_WINDOWS`, `OS_LINUX`, or `OS_OSX`.

### Returns:

A string representing the current operating system.

### Throws:

`\RuntimeException` when the operating system cannot be determined.

## Associated Components

None. 