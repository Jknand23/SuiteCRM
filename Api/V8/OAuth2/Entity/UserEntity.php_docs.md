/**
 * @fileoverview This file defines the `UserEntity` class for the SuiteCRM V8 API OAuth2 implementation. This entity represents an OAuth2 user using the League OAuth2 Server library.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API OAuth2 User Entity

## Overview

The `UserEntity.php` file, located in `Api/V8/OAuth2/Entity/`, defines the `UserEntity` class. This entity represents an OAuth2 user and implements the `UserEntityInterface` from the League OAuth2 Server library.

## Constructor

The constructor takes a single argument:

-   `$userId`: The identifier of the user.

## Methods

### `getIdentifier()`

Returns the user identifier.

## Associated Components

-   `League\OAuth2\Server\Entities\UserEntityInterface`: The interface that this class implements 