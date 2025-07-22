
/**
 * @fileoverview This file defines the `AttributeResponse` class for the SuiteCRM V8 API. This class is a simple value object that is used to represent the "attributes" object in a JSON:API resource object.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 204-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Attribute Response

## Overview

The `AttributeResponse.php` file, located in `Api/V8/JsonApi/Response/`, defines the `AttributeResponse` class. This class is a simple value object that is used to represent the "attributes" object in a JSON:API resource object.

## Constructor

The constructor takes a single argument:

-   `$properties`: An array or `stdClass` object containing the properties of the attributes object.

### Throws:

`\InvalidArgumentException` when the attributes object includes forbidden keys.

## Associated Components

-   `Api\V8\JsonApi\Response\MetaResponse`: The class that this class extends. 