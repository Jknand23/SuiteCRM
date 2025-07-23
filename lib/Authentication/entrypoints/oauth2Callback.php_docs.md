# oauth2Callback.php Documentation

/**
 * @fileoverview OAuth2 Callback Endpoint for SuiteCRM authentication system.
 * Handles OAuth2 provider callbacks after user authorization, processes authorization
 * codes, exchanges them for access tokens, and establishes SuiteCRM user sessions.
 * @package SuiteCRM.Authentication.EntryPoints
 * @copyright SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `oauth2Callback.php` file serves as the OAuth2 callback entry point for SuiteCRM. It handles the completion of OAuth2 authentication flows by processing authorization codes from OAuth2 providers, exchanging them for access tokens, retrieving user information, and establishing authenticated SuiteCRM sessions.

### Key Responsibilities
- OAuth2 provider callback processing and validation
- Authorization code to access token exchange
- CSRF state parameter validation for security
- User account linking or creation for OAuth2 users
- SuiteCRM session establishment for authenticated users
- Comprehensive error handling and security logging

## Entry Point Security

### SuiteCRM Entry Point Validation
**Primary security gate for OAuth2 callback processing**
```php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
```
- Prevents direct file access without proper SuiteCRM initialization
- Ensures OAuth2 callback processing occurs within SuiteCRM security context
- Dies immediately if accessed directly

### OAuth2 Provider Error Handling
**OAuth2 provider error response processing**
```php
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    $errorDescription = $_GET['error_description'] ?? 'Unknown OAuth2 error';
    // Error logging and redirect to login
}
```
- **Provider Errors**: Handles OAuth2 provider error responses
- **Error Descriptions**: Processes detailed error descriptions from providers
- **Security Logging**: Logs provider errors for security monitoring
- **User Feedback**: Redirects to login with meaningful error messages

## OAuth2 Callback Processing

### Parameter Validation
**OAuth2 callback parameter validation and security checks**
```php
$code = $_GET['code'] ?? '';
$state = $_GET['state'] ?? '';

if (empty($code) || empty($state)) {
    // Error handling for missing parameters
}
```
- **Authorization Code**: Validates OAuth2 authorization code presence
- **State Parameter**: Ensures CSRF protection state parameter exists
- **Missing Parameters**: Handles missing required callback parameters
- **Security Validation**: Prevents callback processing without proper parameters

### Session Data Retrieval
**OAuth2 flow session data validation**
```php
$sessionProvider = $_SESSION['oauth2_provider'] ?? '';
$sessionState = $_SESSION['oauth2_state'] ?? '';
$redirectUri = $_SESSION['oauth2_redirect_uri'] ?? '';
```
- **Provider Validation**: Retrieves and validates stored OAuth2 provider
- **State Validation**: Accesses stored CSRF protection state
- **Redirect URI**: Retrieves callback URI from session
- **Session Integrity**: Ensures OAuth2 flow session data integrity

### CSRF Protection Validation
**State parameter validation for CSRF attack prevention**
```php
if (!$securityValidator->validateState($state, $sessionState)) {
    $GLOBALS['log']->error('OAuth2 callback failed: invalid state parameter (possible CSRF attack)');
    // Security logging and session cleanup
}
```
- **State Comparison**: Validates callback state against session state
- **CSRF Prevention**: Prevents cross-site request forgery attacks
- **Security Logging**: Logs potential CSRF attack attempts
- **Session Cleanup**: Removes OAuth2 session data on validation failure

## Token Exchange and User Processing

### OAuth2 Callback Coordination
**OAuth2Service callback processing**
```php
$callbackResult = $oauth2Service->handleCallback($code, $state, $redirectUri);
$userId = $callbackResult['user_id'];
$userInfo = $callbackResult['user_info'] ?? [];
$accessToken = $callbackResult['access_token'] ?? '';
```
- **Service Coordination**: Uses OAuth2Service for callback processing
- **Token Exchange**: Handles authorization code to access token exchange
- **User Information**: Retrieves user information from OAuth2 provider
- **Result Processing**: Processes OAuth2 callback results and data

### User Authentication and Session Establishment
**OAuth2AuthenticationProvider integration**
```php
$authParams = [
    'oauth2_auth' => true,
    'provider' => $sessionProvider,
    'user_info' => $userInfo,
    'access_token' => $accessToken
];

$authSuccess = $authProvider->loadUserOnLogin($userInfo['email'] ?? '', $accessToken, false, $authParams);
```
- **Authentication Parameters**: Prepares OAuth2 authentication parameters
- **User Loading**: Uses OAuth2AuthenticationProvider for user loading
- **Session Establishment**: Creates SuiteCRM session for authenticated user
- **Account Linking**: Links or creates user account based on OAuth2 data

## Session Management and Cleanup

### OAuth2 Session Cleanup
**OAuth2 flow session variable cleanup**
```php
unset($_SESSION['oauth2_provider']);
unset($_SESSION['oauth2_state']);
unset($_SESSION['oauth2_redirect_uri']);
```
- **Variable Cleanup**: Removes OAuth2-specific session variables
- **Security Cleanup**: Prevents session variable pollution
- **Flow Completion**: Marks OAuth2 authentication flow as complete
- **Memory Management**: Frees session memory from OAuth2 data

### Redirect Destination Handling
**Post-authentication redirect logic**
```php
$redirectUrl = 'index.php';
if (!empty($_SESSION['oauth2_original_destination'])) {
    $redirectUrl = $_SESSION['oauth2_original_destination'];
    unset($_SESSION['oauth2_original_destination']);
}
```
- **Destination Restoration**: Redirects to originally requested page
- **Default Redirect**: Uses configured default module/action
- **Session Cleanup**: Removes saved destination after use
- **User Experience**: Maintains user workflow after authentication

## Error Handling and Security

### Callback Validation Errors
**OAuth2 callback validation error handling**
- **Missing Code**: Handles missing authorization code parameters
- **Missing State**: Handles missing CSRF protection state
- **Session Errors**: Handles missing or corrupted session data
- **State Mismatch**: Handles CSRF protection validation failures

### Authentication Process Errors
**OAuth2 authentication process error handling**
- **Callback Processing**: Handles OAuth2Service callback processing failures
- **User Loading**: Handles OAuth2AuthenticationProvider user loading failures
- **Token Exchange**: Handles authorization code to access token exchange failures
- **Provider Communication**: Handles OAuth2 provider communication errors

### Exception Management and Logging
**Comprehensive exception handling and security logging**
```php
try {
    // OAuth2 callback processing logic
} catch (\Exception $e) {
    $GLOBALS['log']->error('OAuth2 callback error: ' . $e->getMessage(), [
        'provider' => $_SESSION['oauth2_provider'] ?? 'unknown',
        'exception' => get_class($e),
        'trace' => $e->getTraceAsString(),
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ]);
}
```
- **Exception Logging**: Detailed error logging with full context
- **Security Context**: Includes IP address and user agent for security analysis
- **Stack Traces**: Full exception stack traces for debugging
- **Session Cleanup**: Removes OAuth2 session variables on errors

## Security Monitoring and Logging

### Authentication Event Logging
**Successful OAuth2 authentication logging**
```php
$GLOBALS['log']->info('OAuth2 authentication completed successfully', [
    'provider' => $sessionProvider,
    'user_id' => $userId,
    'email' => $userInfo['email'] ?? 'unknown',
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
]);
```
- **Success Tracking**: Logs all successful OAuth2 authentications
- **User Context**: Records user ID and email for audit trails
- **Provider Tracking**: Records which OAuth2 provider was used
- **Security Context**: Includes IP address for security monitoring

### Security Event Logging
**Security-related event logging**
- **CSRF Attempts**: Logs potential CSRF attack attempts
- **Invalid States**: Logs invalid state parameter submissions
- **Missing Data**: Logs missing callback parameters or session data
- **Provider Errors**: Logs OAuth2 provider error responses

### Error Event Logging
**Comprehensive error event logging**
- **Callback Failures**: Detailed logging of callback processing failures
- **Authentication Failures**: Logging of user authentication failures
- **System Errors**: Logging of system-level errors during processing
- **Security Issues**: Logging of potential security vulnerabilities

## Integration Points

### SuiteCRM Entry Point System
- **Entry Point Registry**: Registered as `oauth2Callback` entry point
- **URL Pattern**: Used as OAuth2 provider callback URL
- **Authentication**: No authentication required (public callback endpoint)

### OAuth2 Infrastructure
- **OAuth2Service**: Coordinates OAuth2 callback processing
- **OAuth2AuthenticationProvider**: Handles user authentication and session establishment
- **SecurityValidator**: Provides CSRF protection validation

### Session Management System
- **Session Integration**: Full integration with SuiteCRM session management
- **Authentication System**: Integration with existing authentication flows
- **User Loading**: Compatible with existing user loading mechanisms

## Error Response Handling

### Provider Error Responses
**OAuth2 provider error response handling**
- **access_denied**: User denied authorization request
- **invalid_request**: Malformed authorization request
- **unauthorized_client**: Client not authorized for request
- **unsupported_response_type**: Provider doesn't support response type

### Application Error Responses
**SuiteCRM application error handling**
- **missing_code**: Authorization code missing from callback
- **missing_state**: CSRF protection state missing
- **session_error**: OAuth2 session data missing or corrupt
- **invalid_state**: CSRF protection validation failure
- **callback_failed**: OAuth2 callback processing failure
- **user_load_failed**: User authentication or loading failure

## Usage Examples

### Google OAuth2 Callback
```
https://suitecrm.example.com/index.php?entryPoint=oauth2Callback&code=AUTH_CODE&state=STATE_VALUE
```

### Microsoft OAuth2 Callback
```
https://suitecrm.example.com/index.php?entryPoint=oauth2Callback&code=AUTH_CODE&state=STATE_VALUE
```

### Error Callback Response
```
https://suitecrm.example.com/index.php?entryPoint=oauth2Callback&error=access_denied&error_description=User%20denied%20access
``` 