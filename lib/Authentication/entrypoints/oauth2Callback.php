<?php
/**
 * @fileoverview OAuth2 Callback Endpoint for SuiteCRM
 * 
 * Entry point that handles OAuth2 provider callbacks after user authorization.
 * Processes the authorization code, exchanges it for access tokens, retrieves
 * user information, and establishes a SuiteCRM session for authenticated users.
 * 
 * Key Features:
 * - Secure OAuth2 callback processing
 * - CSRF state parameter validation
 * - Token exchange and user information retrieval
 * - User account linking or creation
 * - SuiteCRM session establishment
 * - Comprehensive error handling and logging
 * 
 * Dependencies:
 * - OAuth2Service for callback coordination
 * - OAuth2AuthenticationProvider for user authentication
 * - SecurityValidator for state validation
 * - UserLinker for account management
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
use SuiteCRM\Authentication\OAuth2AuthenticationProvider;
use SuiteCRM\Authentication\SecurityValidator;
use SuiteCRM\Authentication\ProviderFactory;
use SuiteCRM\Authentication\TokenManager;
use SuiteCRM\Authentication\UserLinker;

try {
    // Log the start of callback processing
    error_log("OAuth2 Callback: Starting callback processing");
    error_log("OAuth2 Callback: Session ID: " . session_id());
    error_log("OAuth2 Callback: Code present: " . (!empty($_GET['code']) ? 'Yes' : 'No'));
    error_log("OAuth2 Callback: State present: " . (!empty($_GET['state']) ? 'Yes' : 'No'));
    error_log("OAuth2 Callback: Full session data: " . json_encode($_SESSION));
    
    // Check for error parameter from OAuth2 provider
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        $errorDescription = $_GET['error_description'] ?? 'Unknown OAuth2 error';
        
        $GLOBALS['log']->warning('OAuth2 provider returned error', [
            'error' => $error,
            'description' => $errorDescription,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
        
        // Clean up session
        unset($_SESSION['oauth2_provider']);
        unset($_SESSION['oauth2_state']);
        unset($_SESSION['oauth2_redirect_uri']);
        
        // Redirect to login with error
        $redirectUrl = 'index.php?module=Users&action=Login&oauth_error=' . urlencode($error) . 
                       '&error_message=' . urlencode($errorDescription);
        header('Location: ' . $redirectUrl);
        exit;
    }
    
    // Get authorization code and state from callback
    $code = $_GET['code'] ?? '';
    $state = $_GET['state'] ?? '';
    
    if (empty($code)) {
        $GLOBALS['log']->error('OAuth2 callback failed: missing authorization code');
        header('Location: index.php?module=Users&action=Login&oauth_error=missing_code');
        exit;
    }
    
    if (empty($state)) {
        $GLOBALS['log']->error('OAuth2 callback failed: missing state parameter');
        header('Location: index.php?module=Users&action=Login&oauth_error=missing_state');
        exit;
    }
    
    // Retrieve session information
    $sessionProvider = $_SESSION['oauth2_provider'] ?? '';
    $sessionState = $_SESSION['oauth2_state'] ?? '';
    $redirectUri = $_SESSION['oauth2_redirect_uri'] ?? '';
    
    error_log("OAuth2 Callback: Session data - Provider: $sessionProvider, State: " . substr($sessionState, 0, 8) . "..., Redirect: $redirectUri");
    
    if (empty($sessionProvider) || empty($sessionState) || empty($redirectUri)) {
        $GLOBALS['log']->error('OAuth2 callback failed: missing session data', [
            'has_provider' => !empty($sessionProvider),
            'has_state' => !empty($sessionState),
            'has_redirect' => !empty($redirectUri)
        ]);
        header('Location: index.php?module=Users&action=Login&oauth_error=session_error');
        exit;
    }
    
    // Initialize services with proper dependency injection
    $providerFactory = new ProviderFactory();
    $tokenManager = new TokenManager();
    $securityValidator = new SecurityValidator();
    $userLinker = new UserLinker();
    
    error_log("OAuth2 Callback: Services initialized");
    
    // Create OAuth2Service with all required dependencies
    $oauth2Service = new OAuth2Service(
        $providerFactory,
        $tokenManager,
        $securityValidator,
        $userLinker
    );
    $authProvider = new OAuth2AuthenticationProvider();
    
    // Process OAuth2 callback (handleCallback will validate state internally)
    $callbackResult = $oauth2Service->handleCallback($code, $state, $redirectUri);
    
    error_log("OAuth2 Callback: handleCallback returned: " . json_encode($callbackResult));
    
    if (empty($callbackResult) || empty($callbackResult['user_id'])) {
        $GLOBALS['log']->error('OAuth2 callback processing failed');
        header('Location: index.php?module=Users&action=Login&oauth_error=callback_failed');
        exit;
    }
    
    $userId = $callbackResult['user_id'];
    $userInfo = $callbackResult['user_info'] ?? [];
    $accessToken = $callbackResult['access_token'] ?? '';
    
    // Create OAuth2 authentication parameters
    $authParams = [
        'oauth2_auth' => true,
        'provider' => $sessionProvider,
        'user_info' => $userInfo,
        'access_token' => $accessToken
    ];
    
    // Use OAuth2 authentication provider to load user
    $authSuccess = $authProvider->loadUserOnLogin($userInfo['email'] ?? '', $accessToken, false, $authParams);
    
    error_log("OAuth2 Callback: loadUserOnLogin returned: " . ($authSuccess ? 'true' : 'false'));
    
    if (!$authSuccess) {
        $GLOBALS['log']->error('OAuth2 user loading failed', [
            'provider' => $sessionProvider,
            'user_id' => $userId,
            'email' => $userInfo['email'] ?? 'unknown'
        ]);
        header('Location: index.php?module=Users&action=Login&oauth_error=user_load_failed');
        exit;
    }
    
    // Clean up OAuth2 session variables
    unset($_SESSION['oauth2_provider']);
    unset($_SESSION['oauth2_state']);
    unset($_SESSION['oauth2_redirect_uri']);
    
    // Log successful OAuth2 authentication
    $GLOBALS['log']->info('OAuth2 authentication completed successfully', [
        'provider' => $sessionProvider,
        'user_id' => $userId,
        'email' => $userInfo['email'] ?? 'unknown',
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ]);
    
    // Redirect to appropriate destination
    $redirectUrl = 'index.php';
    
    // Check for saved redirect destination
    if (!empty($_SESSION['oauth2_original_destination'])) {
        $redirectUrl = $_SESSION['oauth2_original_destination'];
        unset($_SESSION['oauth2_original_destination']);
    } else {
        // Use default module from configuration
        if (!empty($GLOBALS['sugar_config']['default_module']) && !empty($GLOBALS['sugar_config']['default_action'])) {
            $redirectUrl = 'index.php?module=' . $GLOBALS['sugar_config']['default_module'] . 
                          '&action=' . $GLOBALS['sugar_config']['default_action'];
        }
    }
    
    // Redirect to destination
    header('Location: ' . $redirectUrl);
    exit;
    
} catch (\Exception $e) {
    // Log the error with full context
    error_log("OAuth2 Callback Exception: " . $e->getMessage());
    error_log("OAuth2 Callback Exception Trace: " . $e->getTraceAsString());
    
    $GLOBALS['log']->error('OAuth2 callback error: ' . $e->getMessage(), [
        'provider' => $_SESSION['oauth2_provider'] ?? 'unknown',
        'exception' => get_class($e),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'code' => $_GET['code'] ?? 'missing',
        'state' => isset($_GET['state']) ? substr($_GET['state'], 0, 8) . '...' : 'missing'
    ]);
    
    // Clean up session variables
    unset($_SESSION['oauth2_provider']);
    unset($_SESSION['oauth2_state']);
    unset($_SESSION['oauth2_redirect_uri']);
    unset($_SESSION['oauth2_original_destination']);
    
    // Redirect back to login with error message
    $errorMessage = 'OAuth2 authentication failed. Please try again or use traditional login.';
    header('Location: index.php?module=Users&action=Login&oauth_error=callback_error&error_message=' . urlencode($errorMessage));
    exit;
} 