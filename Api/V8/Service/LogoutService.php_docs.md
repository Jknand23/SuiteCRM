
/**
 * @fileoverview This file defines the `LogoutService` for the SuiteCRM V8 API. This service is responsible for the business logic of logging a user out, which it accomplishes by finding the user's access token in the database and marking it as deleted.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API Logout Service

## Overview

The `LogoutService.php` file, located in `Api/V8/Service/`, defines the `LogoutService` class. This service contains the business logic for logging a user out of the API. It is used by the `LogoutController`.

## Constructor

The constructor takes a single argument:

-   `BeanManager $beanManager`: An instance of the `BeanManager`. This service is injected by the dependency injection container and is used to interact with SuiteCRM's beans.

## `logout($accessToken)`

This is the main method of the service. It is responsible for logging the user out.

### Arguments:

-   `$accessToken`: The access token of the user to be logged out.

### Logic:

1.  **Find Token**: It uses the `beanManager` to create a new `OAuth2Tokens` bean and then retrieves the token from the database that matches the given access token.
2.  **Handle Not Found**: If the token is not found, it throws an `InvalidArgumentException`.
3.  **Delete Token**: If the token is found, it calls the `mark_deleted()` method on the token bean to mark it as deleted. This effectively invalidates the token and logs the user out.
4.  **Generate Response**: It creates a `DocumentResponse` with a meta object containing a success message and returns it.

## Associated Components

-   `Api\V8\Controller\LogoutController`: The controller that uses this service.
-   `Api\V8\BeanDecorator\BeanManager`: A service used to interact with beans.
-   `Api\V8\JsonApi\Response\*`: The classes used to build the JSON:API response.
-   `OAuth2Tokens`: The bean class for the OAuth2 tokens. 