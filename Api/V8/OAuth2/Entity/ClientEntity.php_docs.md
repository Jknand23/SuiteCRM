/**
 * @fileoverview This file defines the `ClientEntity` class for the SuiteCRM V8 API OAuth2 implementation. This entity represents an OAuth2 client using the League OAuth2 Server library.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API OAuth2 Client Entity

## Overview

The `ClientEntity.php` file, located in `Api/V8/OAuth2/Entity/`, defines the `ClientEntity` class. This entity represents an OAuth2 client and implements the `ClientEntityInterface` from the League OAuth2 Server library.

## Traits

This class uses the following traits from the League OAuth2 Server library:

-   `EntityTrait`: Provides basic entity functionality
-   `ClientTrait`: Provides standard client functionality

## Methods

### `setName($name)`

Sets the name of the OAuth2 client.

### `setRedirectUri($uri)`

Sets the redirect URI for the OAuth2 client.

### `setIsConfidential($confidential)`

Sets whether the client is confidential or not.

## Associated Components

-   `League\OAuth2\Server\Entities\ClientEntityInterface`: The interface that this class implements
-   `League\OAuth2\Server\Entities\Traits\*`: The traits used to provide OAuth2 functionality 