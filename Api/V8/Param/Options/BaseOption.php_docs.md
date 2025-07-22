/**
 * @fileoverview This file defines the `BaseOption` abstract class for the SuiteCRM V8 API parameter system. This class provides the foundation for all parameter option classes that define validation rules for specific parameter types.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Base Parameter Option Class

## Overview

The `BaseOption.php` file, located in `Api/V8/Param/Options/`, defines the `BaseOption` abstract class. This class provides the foundation for all parameter option classes that define validation rules for specific parameter types.

## Constructor

The constructor takes the following arguments:

-   `ValidatorFactory $validatorFactory`: An instance of the validator factory
-   `BeanManager $beanManager`: An instance of the `BeanManager`

## Methods

### `add(OptionsResolver $resolver)` (Abstract)

This abstract method must be implemented by subclasses to add their specific parameter validation rules to the `OptionsResolver`.

### `getOptionName($class)`

This helper method extracts the option name from a class name by converting it to lowercase and removing namespaces.

## Associated Components

-   `Api\V8\Factory\ValidatorFactory`: Used for creating validators
-   `Api\V8\BeanDecorator\BeanManager`: Used for bean operations
-   `Symfony\Component\OptionsResolver\OptionsResolver`: Used for parameter validation and resolution 