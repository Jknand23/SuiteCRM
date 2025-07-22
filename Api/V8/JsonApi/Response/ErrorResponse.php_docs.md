
/**
 * @fileoverview This file defines the `ErrorResponse` class for the SuiteCRM V8 API. This class is a simple value object that is used to represent an error response in the JSON:API format.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Error Response

## Overview

The `ErrorResponse.php` file, located in `Api/V8/JsonApi/Response/`, defines the `ErrorResponse` class. This class is a simple value object that is used to represent an error response in the JSON:API format.

## Constructor

The constructor takes an optional argument:

-   `$debugExceptions`: A boolean that determines whether or not to include the full exception details in the response. If this is not provided, the value of `ApiConfig::getDebugExceptions()` will be used.

## Methods

This class has a number of getter and setter methods for the `status`, `title`, `detail`, and `exception` properties.

### `jsonSerialize()`

This method is called when the object is converted to JSON. It returns an array containing the `status`, `title`, and `detail` of the error. If debug mode is enabled, it will also include the full exception details.

## Associated Components

-   `Api\Core\Config\ApiConfig`: The class that is used to get the debug exceptions setting. 