<?php
/**
 * @fileoverview OAuth2 Authorization Endpoint for SuiteCRM
 * 
 * Entry point that initiates the OAuth2 authorization flow by redirecting users
 * to their chosen OAuth2 provider. Generates secure state parameters for CSRF
 * protection and constructs the appropriate authorization URL with required scopes.
 * 
 * Key Features:
 * - Secure OAuth2 authorization flow initiation
 * - CSRF protection with cryptographic state parameters
 * - Multi-provider support (Google, Microsoft, GitHub, custom)
 * - Comprehensive error handling and logging
 * - Redirect URL validation and security
 * 
 * Dependencies:
 * - OAuth2Service for provider coordination
 * - SecurityValidator for CSRF protection
 * - ProviderFactory for provider instantiation
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/entryPoint.php';

// Ensure session is properly configured for OAuth2 flow
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_httponly', '1');
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    ini_set('session.cookie_secure', '1');
}

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use SuiteCRM\Authentication\OAuth2Service;
use SuiteCRM\Authentication\SecurityValidator;
use SuiteCRM\Authentication\ProviderFactory;
use SuiteCRM\Authentication\TokenManager;
use SuiteCRM\Authentication\UserLinker;

try {
    // Get provider from URL parameter
    $providerName = $_GET['provider'] ?? '';
    
    if (empty($providerName)) {
        $GLOBALS['log']->error('OAuth2 authorization failed: no provider specified');
        header('Location: index.php?module=Users&action=Login&oauth_error=invalid_provider');
        exit;
    }
    
    // Validate provider is supported
    $supportedProviders = ['google', 'microsoft', 'github'];
    if (!in_array(strtolower($providerName), $supportedProviders)) {
        $GLOBALS['log']->error('OAuth2 authorization failed: unsupported provider', [
            'provider' => $providerName,
            'supported' => $supportedProviders
        ]);
        header('Location: index.php?module=Users&action=Login&oauth_error=unsupported_provider');
        exit;
    }
    
    // Initialize OAuth2 services with proper dependency injection
    $providerFactory = new ProviderFactory();
    $tokenManager = new TokenManager();
    $securityValidator = new SecurityValidator();
    $userLinker = new UserLinker();
    
    // Create OAuth2Service with all required dependencies
    $oauth2Service = new OAuth2Service(
        $providerFactory,
        $tokenManager,
        $securityValidator,
        $userLinker
    );
    
    // Generate secure state parameter for CSRF protection
    $state = $securityValidator->generateState();
    
    // Define redirect URI for OAuth2 callback
    $redirectUri = $GLOBALS['sugar_config']['site_url'] . '/index.php?entryPoint=oauth2Callback';
    
    // Store state and provider in session for callback validation
    $_SESSION['oauth2_state'] = $state;
    $_SESSION['oauth2_provider'] = $providerName;
    $_SESSION['oauth2_redirect_uri'] = $redirectUri;

    error_log("OAuth2 Authorize: Session ID: " . session_id());
    error_log("OAuth2 Authorize: Stored state: " . substr($state, 0, 8) . "...");
    error_log("OAuth2 Authorize: Provider: " . $providerName);
    error_log("OAuth2 Authorize: Session data: " . json_encode($_SESSION));
    
    // Get authorization URL from OAuth2 service
    $authorizationUrl = $oauth2Service->getAuthorizationUrl($providerName, $redirectUri);
    
    // Log authorization attempt
    $GLOBALS['log']->info('OAuth2 authorization initiated', [
        'provider' => $providerName,
        'state' => substr($state, 0, 8) . '...', // Log partial state for debugging
        'redirect_uri' => $redirectUri,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ]);
    
    // Redirect to OAuth2 provider for authorization
    header('Location: ' . $authorizationUrl);
    exit;
    
} catch (\Exception $e) {
    // Log the error with full context
    $GLOBALS['log']->error('OAuth2 authorization error: ' . $e->getMessage(), [
        'provider' => $providerName ?? 'unknown',
        'exception' => get_class($e),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ]);
    
    // Clean up session variables
    unset($_SESSION['oauth2_provider']);
    unset($_SESSION['oauth2_state']);
    unset($_SESSION['oauth2_redirect_uri']);
    
    // Redirect back to login with error message
    $errorMessage = 'OAuth2 authorization failed. Please try again or use traditional login.';
    header('Location: index.php?module=Users&action=Login&oauth_error=authorization_failed&error_message=' . urlencode($errorMessage));
    exit;
} 