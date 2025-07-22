/**
 * @fileoverview This file defines the `RefreshTokenRepository` class for the SuiteCRM V8 API OAuth2 implementation. This repository manages OAuth2 refresh tokens, including creation, persistence, and validation.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API OAuth2 Refresh Token Repository

## Overview

The `RefreshTokenRepository.php` file, located in `Api/V8/OAuth2/Repository/`, defines the `RefreshTokenRepository` class. This repository manages OAuth2 refresh tokens and implements the `RefreshTokenRepositoryInterface` from the League OAuth2 Server library.

## Constructor

The constructor takes a single argument:

-   `BeanManager $beanManager`: An instance of the `BeanManager`

## Methods

### `getNewRefreshToken()`

Creates a new refresh token entity.

### `persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)`

Persists a new refresh token to the database by updating the corresponding access token record.

### `revokeRefreshToken($tokenId)`

Revokes a refresh token by marking it as deleted in the database.

### `isRefreshTokenRevoked($tokenId)`

Checks if a refresh token is revoked by verifying if it exists or has expired.

## Associated Components

-   `League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface`: The interface that this class implements
-   `Api\V8\BeanDecorator\BeanManager`: A service used to interact with beans
-   `Api\V8\OAuth2\Entity\RefreshTokenEntity`: The refresh token entity class
-   `OAuth2Tokens`: The bean class for OAuth2 tokens 