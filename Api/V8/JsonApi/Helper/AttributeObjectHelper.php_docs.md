
/**
 * @fileoverview This file defines the `AttributeObjectHelper` class for the SuiteCRM V8 API. This helper class is used to create the "attributes" object in a JSON:API resource object.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Attribute Object Helper

## Overview

The `AttributeObjectHelper.php` file, located in `Api/V8/JsonApi/Helper/`, defines the `AttributeObjectHelper` class. This helper class is used to create the "attributes" object in a JSON:API resource object.

## Constructor

The constructor takes a single argument:

-   `BeanManager $beanManager`: An instance of the `BeanManager`.

## `getAttributes(\SugarBean $bean, $fields = null)`

This is the main method of the class. It takes a `SugarBean` object and an optional array of fields, and it returns an `AttributeResponse` object.

### Arguments:

-   `\SugarBean $bean`: The bean to get the attributes from.
-   `$fields`: An optional array of fields to include in the attributes object. If this is not provided, all fields will be included.

### Returns:

An `AttributeResponse` object.

### Logic:

1.  **Fix Up Formatting**: It calls the `fixUpFormatting()` method on the bean to ensure that the bean's data is properly formatted.
2.  **Filter Fields**: It filters the list of fields to only include fields that are not marked as sensitive and are visible to the API.
3.  **Format Dates**: It formats all date fields to the ISO 8601 format.
4.  **Create Attributes Object**: It creates an array of attributes, where the key is the name of the field and the value is the value of the field.
5.  **Remove ID**: It removes the `id` from the attributes object, as it is already included in the resource object's `id` field.
6.  **Create AttributeResponse**: It creates a new `AttributeResponse` object with the attributes and returns it.

## Associated Components

-   `Api\V8\BeanDecorator\BeanManager`: A service used to interact with beans.
-   `Api\V8\JsonApi\Response\AttributeResponse`: The class that is returned by the `getAttributes()` method.
-   `\SugarBean`: The base class for all of SuiteCRM's data objects. 