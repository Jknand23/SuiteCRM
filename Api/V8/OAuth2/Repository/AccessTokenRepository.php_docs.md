/**
 * @fileoverview This file defines the `AccessTokenRepository` class for the SuiteCRM V8 API OAuth2 implementation. This repository manages OAuth2 access tokens, including creation, persistence, and validation.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API OAuth2 Access Token Repository

## Overview

The `AccessTokenRepository.php` file, located in `Api/V8/OAuth2/Repository/`, defines the `AccessTokenRepository` class. This repository manages OAuth2 access tokens and implements the `AccessTokenRepositoryInterface` from the League OAuth2 Server library.

## Constructor

The constructor takes the following arguments:

-   `AccessTokenEntity $accessTokenEntity`: An instance of the access token entity
-   `BeanManager $beanManager`: An instance of the `BeanManager`

## Methods

### `getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)`

Creates a new access token entity with the provided client, scopes, and user identifier.

### `persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)`

Persists a new access token to the database. It determines the user ID based on the grant type (password or client_credentials) and saves the token information.

### `revokeAccessToken($tokenId)`

Revokes an access token by marking it as deleted in the database.

### `isAccessTokenRevoked($tokenId)`

Checks if an access token is revoked by verifying if it exists, is marked as revoked, or has expired.

## Associated Components

-   `League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface`: The interface that this class implements
-   `Api\V8\BeanDecorator\BeanManager`: A service used to interact with beans
-   `Api\V8\OAuth2\Entity\AccessTokenEntity`: The access token entity class
-   `OAuth2Tokens`: The bean class for OAuth2 tokens
-   `OAuth2Clients`: The bean class for OAuth2 clients 