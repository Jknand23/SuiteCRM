
/**
 * @fileoverview This file defines the `UserService` for the SuiteCRM V8 API. This service is responsible for retrieving information about the currently authenticated user and formatting it for the API response.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API User Service

## Overview

The `UserService.php` file, located in `Api/V8/Service/`, defines the `UserService` class. This service contains the business logic for retrieving information about the currently authenticated user. It is used by the `UserController`.

## Constructor

The constructor takes the following arguments:

-   `BeanManager $beanManager`: An instance of the `BeanManager`.
-   `AttributeObjectHelper $attributeHelper`: A helper for creating JSON:API attribute objects.
-   `RelationshipObjectHelper $relationshipHelper`: A helper for creating JSON:API relationship objects.

## `getCurrentUser(Request $request)`

This is the main method of the service. It is responsible for retrieving and formatting the data for the currently authenticated user.

### Arguments:

-   `Request $request`: The HTTP request object.

### Logic:

1.  **Get Access Token**: It retrieves the `oauth_access_token_id` attribute from the request, which is set by the OAuth2 middleware.
2.  **Find Token**: It uses the `beanManager` to find the corresponding access token in the database.
3.  **Get User**: It uses the `assigned_user_id` from the token to retrieve the user's record.
4.  **Format User Data**: It converts the user bean to an array and unsets the `user_hash` for security.
5.  **Generate Response**: It creates a `DocumentResponse` object with the formatted user data and returns it.

## Associated Components

-   `Api\V8\Controller\UserController`: The controller that uses this service.
-   `Api\V8\BeanDecorator\BeanManager`: A service used to interact with beans.
-   `Api\V8\JsonApi\Helper\*`: Helper classes for creating JSON:API objects.
-   `Slim\Http\Request`: The request object from the Slim framework.
-   `Api\V8\JsonApi\Response\*`: The classes used to build the JSON:API response.
-   `OAuth2Tokens`: The bean class for the OAuth2 tokens.
-   `User`: The bean class for users. 