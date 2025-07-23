# OAuth2AuthenticationProvider.php Documentation

/**
 * @fileoverview OAuth2 Authentication Provider extending SuiteCRM's authentication system
 * to support OAuth2/SSO login alongside traditional username/password authentication.
 * Provides seamless integration with existing session management, user loading, and 
 * authentication flow while adding OAuth2 token handling and external provider support.
 * @package SuiteCRM.Authentication
 * @copyright SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `OAuth2AuthenticationProvider` class extends SuiteCRM's existing `SugarAuthenticateUser` base class to provide OAuth2/SSO authentication capabilities. This provider maintains full compatibility with the existing authentication system while adding support for external OAuth2 providers like Google, Microsoft, and GitHub.

### Key Responsibilities
- OAuth2 authentication flow coordination
- User account linking and creation for OAuth2 users
- Secure token management with encrypted storage
- Session establishment and management for OAuth2 users
- Integration with existing SuiteCRM authentication patterns
- Logout handling and cleanup for OAuth2 sessions

## Database Operations

### OAuth2 Token Storage
- **Table**: `oauth2_user_providers` - Maps users to OAuth2 provider accounts
- **Token Storage**: Encrypted token storage using AES-256-GCM encryption
- **Token Retrieval**: Secure token lookup and validation for authenticated users
- **Token Cleanup**: Automatic token removal on user logout

### User Account Linking
- **User Creation**: Automatic user account creation for new OAuth2 users
- **Account Linking**: Links existing SuiteCRM users to OAuth2 provider accounts
- **User Lookup**: Retrieves user information based on OAuth2 provider data
- **Session Establishment**: Creates SuiteCRM session for OAuth2-authenticated users

## Internal API Calls

### SuiteCRM Authentication Integration
- **Base Class**: Extends `SugarAuthenticateUser` for compatibility
- **authenticateUser()**: Override to handle OAuth2 authentication requests
- **loadUserOnLogin()**: Enhanced user loading with OAuth2 parameters
- **loadUserOnSession()**: Standard session loading using authenticated user ID

### OAuth2 Service Integration
- **OAuth2Service**: Coordinates OAuth2 flow and provider communication
- **UserLinker**: Handles user account creation and linking operations
- **TokenManager**: Manages encrypted token storage and retrieval
- **SecurityValidator**: Provides CSRF protection and security validation

### Session Management
- **Session Variables**: OAuth2-specific session data management
- **Authentication State**: Tracks OAuth2 authentication status
- **Provider Information**: Stores OAuth2 provider details in session
- **Cleanup Operations**: Removes OAuth2 session data on logout

## OAuth2 Authentication Methods

### authenticateWithOAuth2()
**Primary OAuth2 authentication method**
```php
public function authenticateWithOAuth2(
    string $providerName,
    string $accessToken,
    array $userInfo
): string
```
- **Parameter Validation**: Validates OAuth2 provider name, token, and user info
- **User Linking**: Creates or links user account based on OAuth2 data
- **Token Storage**: Securely stores OAuth2 access token with encryption
- **Audit Logging**: Comprehensive logging of authentication events
- **Error Handling**: Robust error handling with detailed logging

### authenticateUser()
**Enhanced authentication method with OAuth2 support**
```php
public function authenticateUser(
    $name,
    $password,
    $fallback = false
)
```
- **OAuth2 Detection**: Checks for OAuth2 authentication requests
- **Session Handling**: Processes OAuth2 session variables
- **Fallback Support**: Falls back to traditional authentication when needed
- **Parameter Processing**: Handles both traditional and OAuth2 parameters

### loadUserOnLogin()
**User loading with OAuth2 parameter support**
```php
public function loadUserOnLogin(
    $name,
    $password,
    $fallback = false,
    $PARAMS = array()
)
```
- **OAuth2 Parameters**: Processes OAuth2 authentication parameters
- **Session Setup**: Establishes OAuth2 session variables
- **User Authentication**: Calls authentication methods with OAuth2 data
- **Session Loading**: Loads user session using authenticated user ID

## Session Management Methods

### hasValidOAuth2Session()
**OAuth2 session validation**
```php
public function hasValidOAuth2Session(): bool
```
- **Session Check**: Verifies authenticated user session exists
- **Token Validation**: Checks OAuth2 token validity and expiration
- **Expiration Handling**: Handles expired token scenarios
- **Error Logging**: Logs validation errors and token issues

### logoutOAuth2User()
**OAuth2 logout and cleanup**
```php
public function logoutOAuth2User(string $userId): bool
```
- **Token Removal**: Removes stored OAuth2 tokens from database
- **Session Cleanup**: Clears OAuth2-related session variables
- **Audit Logging**: Logs logout events for security monitoring
- **Error Handling**: Handles logout errors gracefully

### getOAuth2Provider()
**OAuth2 provider identification**
```php
public function getOAuth2Provider(string $userId): string
```
- **Provider Lookup**: Retrieves OAuth2 provider name for user
- **Token Data**: Accesses stored token information
- **Error Handling**: Returns empty string on errors
- **Debug Logging**: Logs retrieval attempts and errors

## Error Handling and Logging

### Exception Management
- **RuntimeException**: OAuth2 validation failures and processing errors
- **InvalidArgumentException**: Invalid provider or token parameters
- **Database Exceptions**: Token storage and retrieval errors
- **Session Exceptions**: Session management and state errors

### Comprehensive Logging
- **Authentication Events**: All OAuth2 authentication attempts and results
- **Security Events**: CSRF validation, token validation, and security issues
- **Error Events**: Detailed error logging with context and stack traces
- **Audit Events**: User account linking, token storage, and logout events

### Security Considerations
- **CSRF Protection**: State parameter validation for OAuth2 flows
- **Token Security**: AES-256-GCM encryption for token storage
- **Session Security**: Secure session variable management
- **Audit Trail**: Comprehensive logging for security monitoring

## Integration Points

### Existing Authentication System
- **Compatibility**: Full compatibility with existing authentication flows
- **Fallback**: Seamless fallback to traditional authentication
- **Session Management**: Integration with existing session handling
- **User Loading**: Compatibility with existing user loading mechanisms

### OAuth2 Infrastructure
- **Provider Support**: Google, Microsoft, GitHub, and custom providers
- **Token Management**: Secure token storage and lifecycle management
- **Security Validation**: CSRF protection and security validation
- **User Management**: Account linking and user creation services

## Usage Examples

### OAuth2 Authentication Flow
```php
// Initialize provider
$authProvider = new OAuth2AuthenticationProvider();

// OAuth2 authentication
$authParams = [
    'oauth2_auth' => true,
    'provider' => 'google',
    'user_info' => $oauthUserInfo,
    'access_token' => $accessToken
];

// Load user with OAuth2 parameters
$success = $authProvider->loadUserOnLogin(
    $userEmail,
    $accessToken,
    false,
    $authParams
);
```

### Session Validation
```php
// Check OAuth2 session validity
$authProvider = new OAuth2AuthenticationProvider();
$hasValidSession = $authProvider->hasValidOAuth2Session();

// Get OAuth2 provider for user
$provider = $authProvider->getOAuth2Provider($userId);
```

### Logout Handling
```php
// OAuth2 logout
$authProvider = new OAuth2AuthenticationProvider();
$logoutSuccess = $authProvider->logoutOAuth2User($userId);
``` 