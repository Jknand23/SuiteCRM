/**
 * @fileoverview This file defines the `UserRepository` class for the SuiteCRM V8 API OAuth2 implementation. This repository manages OAuth2 user authentication for the password grant type.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API OAuth2 User Repository

## Overview

The `UserRepository.php` file, located in `Api/V8/OAuth2/Repository/`, defines the `UserRepository` class. This repository manages OAuth2 user authentication and implements the `UserRepositoryInterface` from the League OAuth2 Server library.

## Constructor

The constructor takes a single argument:

-   `BeanManager $beanManager`: An instance of the `BeanManager`

## Methods

### `getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)`

Validates user credentials and returns a user entity if authentication is successful. This method:

1. Retrieves the user by username
2. Validates the provided password against the stored hash
3. Returns a `UserEntity` with the user's ID

### Throws:

-   `\InvalidArgumentException` if the user does not exist or the password is invalid

## Associated Components

-   `League\OAuth2\Server\Repositories\UserRepositoryInterface`: The interface that this class implements
-   `Api\V8\BeanDecorator\BeanManager`: A service used to interact with beans
-   `Api\V8\OAuth2\Entity\UserEntity`: The user entity class
-   `User`: The bean class for users 