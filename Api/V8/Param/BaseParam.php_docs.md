/**
 * @fileoverview This file defines the `BaseParam` abstract class for the SuiteCRM V8 API. This class provides the foundation for all parameter validation classes, using the Symfony OptionsResolver component to validate and configure request parameters.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Base Parameter Class

## Overview

The `BaseParam.php` file, located in `Api/V8/Param/`, defines the `BaseParam` abstract class. This class provides the foundation for all parameter validation classes in the V8 API, using the Symfony OptionsResolver component to validate and configure request parameters.

## Constructor

The constructor takes the following arguments:

-   `ValidatorFactory $validatorFactory`: An instance of the validator factory
-   `BeanManager $beanManager`: An instance of the `BeanManager`

## Methods

### `configure(array $arguments)`

This is the main method that configures and validates the parameters. It:

1. Creates a new `OptionsResolver`
2. Calls `setDefined()` to allow for dynamic definition of options
3. Calls `configureParameters()` to set up the parameter validation rules
4. Resolves the arguments against the configured options
5. Stores the validated parameters

### `setDefined(OptionsResolver $resolver, array $arguments)`

This method can be overridden by subclasses to dynamically define options based on the incoming arguments.

### `configureParameters(OptionsResolver $resolver)` (Abstract)

This abstract method must be implemented by subclasses to define the specific parameter validation rules for each endpoint.

### `setOptions(OptionsResolver $optionResolver, array $options)`

This helper method configures parameter options by instantiating option classes and adding them to the resolver.

### `jsonSerialize()`

Implements the `JsonSerializable` interface to return the validated parameters.

## Associated Components

-   `Api\V8\Factory\ValidatorFactory`: Used for creating validators
-   `Api\V8\BeanDecorator\BeanManager`: Used for bean operations
-   `Api\V8\Param\Options\BaseOption`: Base class for parameter options
-   `Symfony\Component\OptionsResolver\OptionsResolver`: Used for parameter validation and resolution 