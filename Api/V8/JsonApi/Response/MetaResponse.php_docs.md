
/**
 * @fileoverview This file defines the `MetaResponse` class for the SuiteCRM V8 API. This class is a simple value object that is used to represent the "meta" object in a JSON:API response.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Meta Response

## Overview

The `MetaResponse.php` file, located in `Api/V8/JsonApi/Response/`, defines the `MetaResponse` class. This class is a simple value object that is used to represent the "meta" object in a JSON:API response.

## Constructor

The constructor takes a single argument:

-   `$properties`: An array or `stdClass` object containing the properties of the meta object.

### Throws:

`\InvalidArgumentException` when the properties are not an array or `stdClass` object.

## Magic Methods

This class uses the `__get()` and `__set()` magic methods to allow you to set and get any property on the object.

## `jsonSerialize()`

This method is called when the object is converted to JSON. It returns the properties of the meta object.

## Associated Components

None. 