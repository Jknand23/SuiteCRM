/**
 * @fileoverview This file defines the `RefreshTokenEntity` class for the SuiteCRM V8 API OAuth2 implementation. This entity represents an OAuth2 refresh token using the League OAuth2 Server library.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API OAuth2 Refresh Token Entity

## Overview

The `RefreshTokenEntity.php` file, located in `Api/V8/OAuth2/Entity/`, defines the `RefreshTokenEntity` class. This entity represents an OAuth2 refresh token and implements the `RefreshTokenEntityInterface` from the League OAuth2 Server library.

## Traits

This class uses the following traits from the League OAuth2 Server library:

-   `RefreshTokenTrait`: Provides standard refresh token functionality
-   `EntityTrait`: Provides basic entity functionality

## Associated Components

-   `League\OAuth2\Server\Entities\RefreshTokenEntityInterface`: The interface that this class implements
-   `League\OAuth2\Server\Entities\Traits\*`: The traits used to provide OAuth2 functionality 