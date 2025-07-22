
/**
 * @fileoverview This file is responsible for configuring the OAuth2 server for the SuiteCRM V8 API. It defines the `AuthorizationServer` and `ResourceServer` services, which are the core components of the OAuth2 implementation. This file is critical for securing the API and controlling access to its resources.
 *
 * @package   SuiteCRM
 * @copyright Copyright (C) 2004-2024 SugarCRM Inc. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 */

# OAuth2 Server and Middleware Service Definitions

## Overview

The `middlewares.php` file, located in `Api/V8/Config/services/`, is a critical part of the API's security infrastructure. It is responsible for setting up and configuring the OAuth2 server, which is used to authenticate and authorize API requests. It defines the `AuthorizationServer` and `ResourceServer` from the `league/oauth2-server` library as services in the dependency injection container.

## Service Definitions

### `AuthorizationServer`

-   **Class**: `League\OAuth2\Server\AuthorizationServer`
-   **Description**: This service is the heart of the OAuth2 token issuance process. It is responsible for validating client credentials and user credentials, and for issuing access tokens and refresh tokens.

The service definition for the `AuthorizationServer` configures it with the following components:

-   **Repositories**: It is configured with repositories for clients (`ClientRepository`), access tokens (`AccessTokenRepository`), and scopes (`ScopeRepository`). These repositories are responsible for storing and retrieving the data needed for the OAuth2 process.
-   **Private Key**: It is configured with the path to the OAuth2 private key, which is used to sign the JWT access tokens.
-   **Encryption Key**: It is configured with an encryption key, which is used to encrypt the authorization codes.
-   **Grant Types**: It is configured with the grant types that the API supports. This implementation enables the following grants:
    -   **Client Credentials Grant**: For machine-to-machine authentication.
    -   **Password Grant**: For authenticating users with a username and password.
    -   **Refresh Token Grant**: For obtaining a new access token using a refresh token.

### `ResourceServer`

-   **Class**: `League\OAuth2\Server\ResourceServer`
-   **Description**: This service is responsible for protecting the API's resources. It is used as middleware on the API's routes to ensure that incoming requests have a valid access token.

The service definition for the `ResourceServer` configures it with the following components:

-   **AccessTokenRepository**: The same repository used by the `AuthorizationServer`, to validate the incoming access tokens.
-   **Public Key**: The public key that corresponds to the private key used to sign the tokens. The `ResourceServer` uses this to verify the signature of the JWT access tokens.

## Extensibility

As with the other service definition files, this file uses `Api\Core\Loader\CustomLoader::mergeCustomArray()` to allow for the modification of the OAuth2 server configuration in an upgrade-safe way.

## Associated Components

-   `league/oauth2-server`: The library that provides the OAuth2 server implementation.
-   `Api\V8\OAuth2\*`: The entity and repository classes for the OAuth2 implementation.
-   `ApiConfig`: The class that provides the paths to the OAuth2 keys.
-   `Api\Core\Loader\CustomLoader`: The loader for custom service definitions. 