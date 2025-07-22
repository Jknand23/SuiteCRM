/**
 * @fileoverview This file defines the `AccessTokenEntity` class for the SuiteCRM V8 API OAuth2 implementation. This entity represents an OAuth2 access token using the League OAuth2 Server library.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API OAuth2 Access Token Entity

## Overview

The `AccessTokenEntity.php` file, located in `Api/V8/OAuth2/Entity/`, defines the `AccessTokenEntity` class. This entity represents an OAuth2 access token and implements the `AccessTokenEntityInterface` from the League OAuth2 Server library.

## Traits

This class uses the following traits from the League OAuth2 Server library:

-   `AccessTokenTrait`: Provides standard access token functionality
-   `TokenEntityTrait`: Provides common token entity methods
-   `EntityTrait`: Provides basic entity functionality

## Associated Components

-   `League\OAuth2\Server\Entities\AccessTokenEntityInterface`: The interface that this class implements
-   `League\OAuth2\Server\Entities\Traits\*`: The traits used to provide OAuth2 functionality 