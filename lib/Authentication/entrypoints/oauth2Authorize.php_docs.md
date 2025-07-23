# oauth2Authorize.php Documentation

/**
 * @fileoverview OAuth2 Authorization Endpoint for SuiteCRM authentication system.
 * Initiates the OAuth2 authorization flow by redirecting users to their chosen
 * OAuth2 provider with secure state parameters for CSRF protection.
 * @package SuiteCRM.Authentication.EntryPoints
 * @copyright SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 */

## Overview

The `oauth2Authorize.php` file serves as the OAuth2 authorization entry point for SuiteCRM. It handles the initiation of OAuth2 authentication flows by validating provider requests, generating secure state parameters, and redirecting users to their chosen OAuth2 provider for authentication.

### Key Responsibilities
- OAuth2 provider validation and support verification
- Secure state parameter generation for CSRF protection
- Authorization URL construction with appropriate scopes
- Session management for OAuth2 flow state
- Comprehensive error handling and logging

## Entry Point Security

### SuiteCRM Entry Point Validation
**Primary security gate for OAuth2 authorization requests**
```php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
```
- Prevents direct file access without proper SuiteCRM initialization
- Ensures OAuth2 authorization occurs within SuiteCRM security context
- Dies immediately if accessed directly

### Input Validation and Sanitization
**Provider parameter validation**
- **Provider Parameter**: Validates `$_GET['provider']` parameter exists
- **Supported Providers**: Checks against whitelist of supported OAuth2 providers
- **Provider Security**: Prevents unauthorized or malicious provider requests

## OAuth2 Flow Initiation

### Provider Validation
**Supported OAuth2 providers verification**
```php
$supportedProviders = ['google', 'microsoft', 'github'];
if (!in_array(strtolower($providerName), $supportedProviders)) {
    // Error handling and redirect to login
}
```
- **Google OAuth2**: OpenID Connect with profile and email scopes
- **Microsoft OAuth2**: Azure AD OAuth2 with standard scopes
- **GitHub OAuth2**: GitHub OAuth2 with user email access
- **Provider Whitelist**: Security through explicit provider allowlist

### State Parameter Generation
**CSRF protection through cryptographic state parameters**
```php
$state = $securityValidator->generateState();
$_SESSION['oauth2_state'] = $state;
```
- **Cryptographic Security**: Uses `SecurityValidator` for secure state generation
- **Session Storage**: Stores state in session for callback validation
- **CSRF Protection**: Prevents cross-site request forgery attacks
- **Unique State**: Each authorization request gets unique state parameter

### Session Management
**OAuth2 flow state preservation**
```php
$_SESSION['oauth2_provider'] = $providerName;
$_SESSION['oauth2_state'] = $state;
$_SESSION['oauth2_redirect_uri'] = $redirectUri;
```
- **Provider Storage**: Preserves selected OAuth2 provider for callback
- **State Preservation**: Maintains CSRF protection state
- **Redirect URI**: Stores callback URI for authorization flow completion

## Provider Configuration

### Scope Definition
**OAuth2 scope configuration per provider**
```php
switch (strtolower($providerName)) {
    case 'google':
        $scopes = ['openid', 'email', 'profile'];
        break;
    case 'microsoft':
        $scopes = ['openid', 'email', 'profile'];
        break;
    case 'github':
        $scopes = ['user:email'];
        break;
}
```
- **Google Scopes**: OpenID Connect standard scopes for profile access
- **Microsoft Scopes**: Azure AD standard scopes for user information
- **GitHub Scopes**: Minimal scope for email access only
- **Scope Security**: Requests minimum necessary permissions

### Authorization URL Construction
**OAuth2 authorization URL generation**
```php
$authorizationUrl = $provider->getAuthorizationUrl([
    'scope' => $scopes,
    'state' => $state
]);
```
- **Provider Integration**: Uses `ProviderFactory` for OAuth2 provider instances
- **URL Generation**: Constructs proper authorization URLs with parameters
- **Parameter Inclusion**: Includes scopes and state for secure authorization
- **Redirect Preparation**: Prepares URL for user redirection

## Error Handling

### Provider Validation Errors
**Invalid or unsupported provider handling**
- **Missing Provider**: Redirects to login with `invalid_provider` error
- **Unsupported Provider**: Redirects with `unsupported_provider` error
- **Provider Logging**: Logs provider validation failures with context

### Authorization Flow Errors
**OAuth2 authorization preparation errors**
- **Service Initialization**: Handles OAuth2 service creation failures
- **State Generation**: Manages state parameter generation errors
- **Provider Creation**: Handles OAuth2 provider instantiation failures

### Exception Management
**Comprehensive exception handling**
```php
try {
    // OAuth2 authorization logic
} catch (\Exception $e) {
    // Error logging and user redirection
    $GLOBALS['log']->error('OAuth2 authorization error: ' . $e->getMessage());
    header('Location: index.php?module=Users&action=Login&oauth_error=authorization_failed');
}
```
- **Exception Logging**: Detailed error logging with full context
- **Session Cleanup**: Removes OAuth2 session variables on errors
- **User Feedback**: Redirects to login with meaningful error messages
- **Security Logging**: Logs potential security issues and attacks

## Logging and Monitoring

### Security Event Logging
**Comprehensive security event tracking**
```php
$GLOBALS['log']->info('OAuth2 authorization initiated', [
    'provider' => $providerName,
    'state' => substr($state, 0, 8) . '...',
    'scopes' => $scopes,
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
]);
```
- **Authorization Tracking**: Logs all OAuth2 authorization attempts
- **Security Context**: Includes IP address and user agent information
- **State Tracking**: Logs partial state for debugging (security-conscious)
- **Provider Tracking**: Records which OAuth2 provider was requested

### Error Event Logging
**Detailed error logging for troubleshooting**
- **Error Context**: Full exception details including stack traces
- **User Context**: IP address and user agent for security analysis
- **Provider Context**: Which provider caused the error
- **Session Context**: Session state during error occurrence

## Security Considerations

### CSRF Protection
- **State Parameter**: Cryptographically secure state generation
- **Session Binding**: State tied to specific user session
- **Validation Required**: State must be validated in callback

### Session Security
- **Session Variables**: OAuth2 state stored securely in session
- **Variable Cleanup**: Session cleanup on errors or completion
- **Session Hijacking**: State parameter helps prevent session hijacking

### Provider Security
- **Whitelist Approach**: Only explicitly allowed providers supported
- **Scope Minimization**: Requests minimal necessary OAuth2 scopes
- **URL Validation**: Proper OAuth2 authorization URL construction

## Integration Points

### SuiteCRM Entry Point System
- **Entry Point Registry**: Registered as `oauth2Authorize` entry point
- **URL Pattern**: Accessible via `index.php?entryPoint=oauth2Authorize&provider=PROVIDER`
- **Authentication**: No authentication required (public entry point)

### OAuth2 Infrastructure
- **OAuth2Service**: Coordinates OAuth2 flow management
- **ProviderFactory**: Creates OAuth2 provider instances
- **SecurityValidator**: Provides CSRF protection and validation

### Error Handling System
- **Login Integration**: Redirects to login page with error parameters
- **Error Messages**: Provides meaningful error feedback to users
- **Logging Integration**: Uses SuiteCRM logging system for error tracking

## Usage Examples

### Google OAuth2 Authorization
```
https://suitecrm.example.com/index.php?entryPoint=oauth2Authorize&provider=google
```

### Microsoft OAuth2 Authorization
```
https://suitecrm.example.com/index.php?entryPoint=oauth2Authorize&provider=microsoft
```

### GitHub OAuth2 Authorization
```
https://suitecrm.example.com/index.php?entryPoint=oauth2Authorize&provider=github
``` 