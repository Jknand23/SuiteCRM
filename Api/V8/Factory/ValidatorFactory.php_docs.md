
/**
 * @fileoverview This file defines the `ValidatorFactory` class for the SuiteCRM V8 API. This factory is responsible for creating validator closures that can be used to validate data.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Validator Factory

## Overview

The `ValidatorFactory.php` file, located in `Api/V8/Factory/`, defines the `ValidatorFactory` class. This class is a factory for creating validator closures.

## Constructor

The constructor takes a single argument:

-   `ValidatorInterface $validator`: An instance of the Symfony Validator.

## Methods

### `createClosure(array $constraints, $allowNull = false)`

This method takes an array of constraints and returns a closure that can be used to validate a value.

### `createClosureForIterator(array $constraints, $allowNull = false)`

This method takes an array of constraints and returns a closure that can be used to validate an array or iterator. It will validate each item in the collection against the given constraints.

## Example

```php
$validatorFactory = new ValidatorFactory($validator);

$isEmail = $validatorFactory->createClosure([
    new Email()
]);

if ($isEmail('test@example.com')) {
    // ...
}
```

## Associated Components

-   `Symfony\Component\Validator\Validator\ValidatorInterface`: The Symfony Validator.
-   `Symfony\Component\Validator\Constraint`: The base class for all constraints. 