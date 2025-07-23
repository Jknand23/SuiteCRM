<?php
/**
 * @fileoverview OAuth2 Authentication Provider for SuiteCRM
 * 
 * Extends the SuiteCRM authentication system to support OAuth2/SSO login alongside
 * traditional username/password authentication. Integrates with existing session
 * management, user loading, and authentication flow while providing OAuth2 token
 * handling and external provider authentication.
 * 
 * Key Features:
 * - Seamless integration with existing SuiteCRM authentication system
 * - OAuth2 provider support (Google, Microsoft, GitHub, custom)
 * - User account linking and creation for OAuth2 users
 * - Secure token management with encrypted storage
 * - Session compatibility with existing SuiteCRM flows
 * 
 * Dependencies:
 * - SugarAuthenticateUser base class for SuiteCRM integration
 * - OAuth2Service for OAuth2 flow coordination
 * - UserLinker for account management
 * - TokenManager for secure token storage
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

namespace SuiteCRM\Authentication;

require_once 'modules/Users/authentication/SugarAuthenticate/SugarAuthenticateUser.php';

use SuiteCRM\Authentication\OAuth2Service;
use SuiteCRM\Authentication\UserLinker;
use SuiteCRM\Authentication\TokenManager;
use SuiteCRM\Authentication\ProviderFactory;
use SuiteCRM\Authentication\SecurityValidator;

/**
 * OAuth2 Authentication Provider
 * 
 * Provides OAuth2/SSO authentication capabilities while maintaining full
 * compatibility with the existing SuiteCRM authentication system.
 */
class OAuth2AuthenticationProvider extends \SugarAuthenticateUser
{
    /** @var OAuth2Service $oauth2Service OAuth2 coordination service */
    private OAuth2Service $oauth2Service;
    
    /** @var UserLinker $userLinker User account linking service */
    private UserLinker $userLinker;
    
    /** @var TokenManager $tokenManager Token storage service */
    private TokenManager $tokenManager;
    
    /**
     * Constructor with dependency injection
     */
    public function __construct()
    {
        // Initialize all required dependencies
        $providerFactory = new ProviderFactory();
        $tokenManager = new TokenManager();
        $securityValidator = new SecurityValidator();
        $userLinker = new UserLinker();
        
        // Create OAuth2Service with proper dependency injection
        $this->oauth2Service = new OAuth2Service(
            $providerFactory,
            $tokenManager,
            $securityValidator,
            $userLinker
        );
        $this->userLinker = $userLinker;
        $this->tokenManager = $tokenManager;
    }
    
    /**
     * Authenticates user via OAuth2 provider
     * 
     * Called when a user completes OAuth2 authentication flow. Validates
     * the OAuth2 token, links or creates user account, and returns user ID
     * for session establishment.
     * 
     * @param string $providerName OAuth2 provider name (google, microsoft, etc)
     * @param string $accessToken OAuth2 access token from provider
     * @param array $userInfo User information from OAuth2 provider
     * 
     * @return string User ID for session loading or empty string on failure
     * 
     * @throws \RuntimeException When OAuth2 validation fails
     * @throws \InvalidArgumentException When provider or token is invalid
     * 
     * @since 1.0.0
     */
    public function authenticateWithOAuth2(string $providerName, string $accessToken, array $userInfo): string
    {
        try {
            // Validate OAuth2 token and user information
            if (empty($providerName) || empty($accessToken) || empty($userInfo)) {
                $GLOBALS['log']->warning('OAuth2 authentication failed: missing required parameters', [
                    'provider' => $providerName,
                    'has_token' => !empty($accessToken),
                    'has_user_info' => !empty($userInfo)
                ]);
                return '';
            }
            
            // Link or create user account based on OAuth2 information
            $userId = $this->userLinker->linkOrCreateUser($userInfo, $providerName);
            
            if (empty($userId)) {
                $GLOBALS['log']->error('OAuth2 authentication failed: user linking failed', [
                    'provider' => $providerName,
                    'provider_user_id' => $userInfo['id'] ?? 'unknown'
                ]);
                return '';
            }
            
            // Token storage is handled by OAuth2Service during the callback flow
            // No need to store tokens here as we only have the token string, not the AccessToken object
            
            $GLOBALS['log']->info('OAuth2 authentication successful', [
                'user_id' => $userId,
                'provider' => $providerName,
                'provider_user_id' => $userInfo['id']
            ]);
            
            return $userId;
            
        } catch (\Exception $e) {
            $GLOBALS['log']->error('OAuth2 authentication error: ' . $e->getMessage(), [
                'provider' => $providerName,
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \RuntimeException('OAuth2 authentication failed: ' . $e->getMessage(), 0, $e);
        }
    }
    
    /**
     * Override parent authenticateUser to handle OAuth2 authentication
     * 
     * Extends the base authentication method to support OAuth2 authentication
     * alongside traditional username/password authentication. Falls back to
     * parent implementation for traditional auth.
     * 
     * @param string $name Username or OAuth2 provider identifier
     * @param string $password Password or OAuth2 access token
     * @param bool $fallback Whether this is a fallback authentication attempt
     * 
     * @return string User ID for session loading or empty string on failure
     * 
     * @since 1.0.0
     */
    public function authenticateUser($name, $password, $fallback = false)
    {
        // Check if this is an OAuth2 authentication request
        if (isset($_SESSION['oauth2_authentication']) && $_SESSION['oauth2_authentication'] === true) {
            $providerName = $_SESSION['oauth2_provider'] ?? '';
            $userInfo = $_SESSION['oauth2_user_info'] ?? [];
            
            if (!empty($providerName) && !empty($userInfo)) {
                // Clean up session variables
                unset($_SESSION['oauth2_authentication']);
                unset($_SESSION['oauth2_provider']);
                unset($_SESSION['oauth2_user_info']);
                
                return $this->authenticateWithOAuth2($providerName, $password, $userInfo);
            }
        }
        
        // Fall back to traditional authentication
        return parent::authenticateUser($name, $password, $fallback);
    }
    
    /**
     * Load user on OAuth2 login
     * 
     * Handles user loading for OAuth2 authentication by setting up the session
     * with OAuth2-specific information and calling the parent method for standard
     * user loading.
     * 
     * @param string $name Username or OAuth2 identifier
     * @param string $password Password or OAuth2 token
     * @param bool $fallback Whether this is a fallback authentication
     * @param array $PARAMS Additional parameters from authentication flow
     * 
     * @return bool True if user loaded successfully, false otherwise
     * 
     * @since 1.0.0
     */
    public function loadUserOnLogin($name, $password, $fallback = false, $PARAMS = array())
    {
        // Handle OAuth2 authentication
        if (isset($PARAMS['oauth2_auth']) && $PARAMS['oauth2_auth'] === true) {
            $providerName = $PARAMS['provider'] ?? '';
            $userInfo = $PARAMS['user_info'] ?? [];
            
            if (empty($providerName) || empty($userInfo)) {
                $GLOBALS['log']->error('OAuth2 loadUserOnLogin failed: missing parameters');
                return false;
            }
            
            // Set session variables for authenticateUser method
            $_SESSION['oauth2_authentication'] = true;
            $_SESSION['oauth2_provider'] = $providerName;
            $_SESSION['oauth2_user_info'] = $userInfo;
            
            // Use OAuth2 user info for authentication
            $userId = $this->authenticateUser($name, $password, $fallback);
            
            if (empty($userId)) {
                return false;
            }
            
            // Load user on session using the authenticated user ID
            return $this->loadUserOnSession($userId);
        }
        
        // Fall back to parent implementation for traditional authentication
        return parent::loadUserOnLogin($name, $password, $fallback, $PARAMS);
    }
    
    /**
     * Check if user has valid OAuth2 session
     * 
     * Verifies if the current user session includes valid OAuth2 authentication
     * and that the OAuth2 tokens are still valid.
     * 
     * @return bool True if user has valid OAuth2 session, false otherwise
     * 
     * @since 1.0.0
     */
    public function hasValidOAuth2Session(): bool
    {
        if (empty($_SESSION['authenticated_user_id'])) {
            return false;
        }
        
        try {
            $userId = $_SESSION['authenticated_user_id'];
            $tokenData = $this->tokenManager->getTokenForUser($userId);
            
            if (empty($tokenData)) {
                return false;
            }
            
            // Check if token is expired
            $expiresAt = $tokenData['expires_at'] ?? 0;
            if ($expiresAt > 0 && time() > $expiresAt) {
                $GLOBALS['log']->info('OAuth2 token expired for user', ['user_id' => $userId]);
                return false;
            }
            
            return true;
            
        } catch (\Exception $e) {
            $GLOBALS['log']->error('Error checking OAuth2 session: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Logout OAuth2 user
     * 
     * Handles cleanup of OAuth2-specific session data and tokens when user
     * logs out. Cleans up stored tokens and session variables.
     * 
     * @param string $userId User ID to logout
     * 
     * @return bool True if logout successful, false otherwise
     * 
     * @since 1.0.0
     */
    public function logoutOAuth2User(string $userId): bool
    {
        try {
            // Get the provider name from session or try to determine it
            $providerName = $_SESSION['oauth2_provider'] ?? '';
            
            // If we have a provider name, try to delete the token
            if (!empty($providerName)) {
                $this->tokenManager->deleteToken($userId, $providerName);
            }
            
            // Clean up OAuth2-related session variables
            unset($_SESSION['oauth2_authentication']);
            unset($_SESSION['oauth2_provider']);
            unset($_SESSION['oauth2_user_info']);
            unset($_SESSION['oauth2_state']);
            
            $GLOBALS['log']->info('OAuth2 user logged out successfully', ['user_id' => $userId]);
            
            return true;
            
        } catch (\Exception $e) {
            $GLOBALS['log']->error('Error during OAuth2 logout: ' . $e->getMessage(), [
                'user_id' => $userId,
                'exception' => get_class($e)
            ]);
            return false;
        }
    }
    
    /**
     * Get OAuth2 provider for user
     * 
     * Retrieves the OAuth2 provider name for a given user if they are
     * authenticated via OAuth2.
     * 
     * @param string $userId User ID to check
     * 
     * @return string Provider name or empty string if not OAuth2 user
     * 
     * @since 1.0.0
     */
    public function getOAuth2Provider(string $userId): string
    {
        try {
            $tokenData = $this->tokenManager->getTokenForUser($userId);
            return $tokenData['provider'] ?? '';
            
        } catch (\Exception $e) {
            $GLOBALS['log']->debug('Error getting OAuth2 provider: ' . $e->getMessage());
            return '';
        }
    }
} 