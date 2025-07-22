/**
 * @fileoverview This file defines the `ClientRepository` class for the SuiteCRM V8 API OAuth2 implementation. This repository manages OAuth2 clients, including validation and retrieval.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# V8 API OAuth2 Client Repository

## Overview

The `ClientRepository.php` file, located in `Api/V8/OAuth2/Repository/`, defines the `ClientRepository` class. This repository manages OAuth2 clients and implements the `ClientRepositoryInterface` from the League OAuth2 Server library.

## Constructor

The constructor takes the following arguments:

-   `ClientEntity $clientEntity`: An instance of the client entity
-   `BeanManager $beanManager`: An instance of the `BeanManager`

## Methods

### `getClientEntity($clientIdentifier)`

Retrieves a client entity by its identifier, populating it with data from the database.

### `validateClient($clientIdentifier, $clientSecret, $grantType)`

Validates a client by checking if the provided credentials match and if the grant type is allowed for the client.

## Associated Components

-   `League\OAuth2\Server\Repositories\ClientRepositoryInterface`: The interface that this class implements
-   `Api\V8\BeanDecorator\BeanManager`: A service used to interact with beans
-   `Api\V8\OAuth2\Entity\ClientEntity`: The client entity class
-   `OAuth2Clients`: The bean class for OAuth2 clients 