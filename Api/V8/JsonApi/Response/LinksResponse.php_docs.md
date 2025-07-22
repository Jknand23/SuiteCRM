
/**
 * @fileoverview This file defines the `LinksResponse` class for the SuiteCRM V8 API. This class is a simple value object that is used to represent the "links" object in a JSON:API response.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Links Response

## Overview

The `LinksResponse.php` file, located in `Api/V8/JsonApi/Response/`, defines the `LinksResponse` class. This class is a simple value object that is used to represent the "links" object in a JSON:API response.

## Methods

This class has a number of getter and setter methods for the `self` and `related` properties.

### `jsonSerialize()`

This method is called when the object is converted to JSON. It returns an array containing the `self` and `related` links.

## Associated Components

None. 