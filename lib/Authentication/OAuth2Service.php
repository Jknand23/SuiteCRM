<?php
/**
 * @fileoverview OAuth2 External Provider Authentication Service
 * 
 * Main service class for handling OAuth2 authentication with external providers
 * (Google, Microsoft, etc.). Supplements existing SuiteCRM authentication by
 * providing secure integration with corporate identity providers while maintaining
 * full backward compatibility with traditional username/password authentication.
 * 
 * Key Features:
 * - OAuth2 authorization flow management with state validation
 * - Support for multiple OAuth2 providers (Google, Microsoft, custom)
 * - Secure token storage and management with encryption
 * - User account linking between OAuth2 providers and SuiteCRM users
 * - Comprehensive error handling and audit logging
 * - CSRF protection via state parameter validation
 * 
 * Dependencies:
 * - League\OAuth2\Client for OAuth2 client implementation
 * - SuiteCRM User and BeanFactory for user management
 * - SuiteCRM session management for authentication state
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

namespace SuiteCRM\Authentication;

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Token\AccessToken;
use SuiteCRM\Authentication\ProviderFactory;
use SuiteCRM\Authentication\TokenManager;
use SuiteCRM\Authentication\SecurityValidator;
use SuiteCRM\Authentication\UserLinker;

class OAuth2Service
{
    /** @var ProviderFactory $providerFactory OAuth2 provider factory instance */
    private ProviderFactory $providerFactory;
    
    /** @var TokenManager $tokenManager Token storage and management instance */
    private TokenManager $tokenManager;
    
    /** @var SecurityValidator $securityValidator Security validation instance */
    private SecurityValidator $securityValidator;
    
    /** @var UserLinker $userLinker User account linking instance */
    private UserLinker $userLinker;
    
    /** @var array $config OAuth2 configuration settings */
    private array $config;
    
    /**
     * Constructor with dependency injection
     * 
     * @param ProviderFactory $providerFactory OAuth2 provider factory
     * @param TokenManager $tokenManager Token management service
     * @param SecurityValidator $securityValidator Security validation service
     * @param UserLinker $userLinker User account linking service
     */
    public function __construct(
        ProviderFactory $providerFactory,
        TokenManager $tokenManager,
        SecurityValidator $securityValidator,
        UserLinker $userLinker
    ) {
        $this->providerFactory = $providerFactory;
        $this->tokenManager = $tokenManager;
        $this->securityValidator = $securityValidator;
        $this->userLinker = $userLinker;
        $this->config = $this->loadConfiguration();
    }
    
    /**
     * Initiates OAuth2 authorization flow for specified provider
     * 
     * Generates authorization URL with state parameter for CSRF protection,
     * stores state in session, and returns URL for user redirection.
     * 
     * @param string $providerName Name of OAuth2 provider (google, microsoft, etc.)
     * @param string $redirectUri URI to redirect after authorization
     * 
     * @return string Authorization URL for user redirection
     * 
     * @throws \InvalidArgumentException When provider is not supported
     * @throws \RuntimeException When configuration is invalid
     * 
     * @since 1.0.0
     */
    public function getAuthorizationUrl(string $providerName, string $redirectUri): string
    {
        $provider = $this->providerFactory->createProvider($providerName, $redirectUri);
        
        // Generate state parameter for CSRF protection
        $state = $this->securityValidator->generateState();
        $_SESSION['oauth2_state'] = $state;
        $_SESSION['oauth2_provider'] = $providerName;
        
        $authorizationUrl = $provider->getAuthorizationUrl([
            'state' => $state,
            'scope' => $this->getProviderScopes($providerName)
        ]);
        
        $this->logAuthEvent('authorization_started', [
            'provider' => $providerName,
            'redirect_uri' => $redirectUri,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
        
        return $authorizationUrl;
    }
    
    /**
     * Handles OAuth2 callback and completes authentication flow
     * 
     * Validates authorization code and state parameter, exchanges code for
     * access token, retrieves user information, and creates or links user account.
     * 
     * @param string $code Authorization code from OAuth2 provider
     * @param string $state State parameter for CSRF validation
     * @param string $redirectUri Original redirect URI used in authorization
     * 
     * @return array Authentication result with user ID and success status
     * 
     * @throws \InvalidArgumentException When state validation fails
     * @throws \RuntimeException When token exchange fails
     * @throws \Exception When user creation or linking fails
     * 
     * @since 1.0.0
     */
    public function handleCallback(string $code, string $state, string $redirectUri): array
    {
        // Get provider name from session first
        $providerName = $_SESSION['oauth2_provider'] ?? null;
        if (!$providerName) {
            throw new \RuntimeException('OAuth2 provider not found in session');
        }
        
        // Validate state parameter to prevent CSRF attacks
        // The validateState method will compare against session state internally
        error_log("OAuth2Service::handleCallback - About to validate state");
        error_log("OAuth2Service::handleCallback - Session state: " . ($_SESSION['oauth2_state'] ?? 'NOT SET'));
        error_log("OAuth2Service::handleCallback - Provided state: " . substr($state, 0, 8) . "...");
        
        if (!$this->securityValidator->validateState($state)) {
            $this->logAuthEvent('callback_failed', [
                'reason' => 'invalid_state',
                'provided_state' => substr($state, 0, 8) . '...',
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            throw new \InvalidArgumentException('Invalid state parameter - possible CSRF attack');
        }
        
        $provider = $this->providerFactory->createProvider($providerName, $redirectUri);
        
        try {
            // Exchange authorization code for access token
            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $code
            ]);
            
            // Get user information from OAuth2 provider
            $resourceOwner = $provider->getResourceOwner($accessToken);
            $userInfo = $resourceOwner->toArray();
            
            error_log("OAuth2Service::handleCallback - User info: " . json_encode($userInfo));
            
            // Extract user ID based on provider
            $providerId = null;
            switch ($providerName) {
                case 'google':
                    $providerId = $userInfo['sub'] ?? $userInfo['id'] ?? null;
                    break;
                case 'microsoft':
                    $providerId = $userInfo['id'] ?? null;
                    break;
                case 'github':
                    $providerId = $userInfo['id'] ?? null;
                    break;
                default:
                    $providerId = $userInfo['id'] ?? $userInfo['sub'] ?? null;
            }
            
            if (!$providerId) {
                throw new \RuntimeException('Unable to extract user ID from provider response');
            }
            
            // Store token securely
            $this->tokenManager->storeToken($accessToken, $providerName, $providerId);
            
            // Link or create user account
            $userId = $this->userLinker->linkOrCreateUser($userInfo, $providerName);
            
            // Set up SuiteCRM session
            $this->establishSession($userId);
            
            $this->logAuthEvent('authentication_success', [
                'provider' => $providerName,
                'user_id' => $userId,
                'provider_user_id' => $providerId,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            
            // Clean up session variables
            unset($_SESSION['oauth2_state'], $_SESSION['oauth2_provider']);
            
            return [
                'success' => true,
                'user_id' => $userId,
                'provider' => $providerName,
                'user_info' => $userInfo,
                'access_token' => $accessToken->getToken(),
                'message' => 'OAuth2 authentication successful'
            ];
            
        } catch (\Exception $e) {
            $this->logAuthEvent('authentication_failed', [
                'provider' => $providerName,
                'error' => $e->getMessage(),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            throw $e;
        }
    }
    
    /**
     * Checks if OAuth2 provider is linked to current user
     * 
     * @param string $userId SuiteCRM user ID
     * @param string $providerName OAuth2 provider name
     * 
     * @return bool True if provider is linked to user
     * 
     * @since 1.0.0
     */
    public function isProviderLinked(string $userId, string $providerName): bool
    {
        return $this->userLinker->isProviderLinked($userId, $providerName);
    }
    
    /**
     * Links existing OAuth2 provider to current user account
     * 
     * @param string $userId SuiteCRM user ID
     * @param string $providerName OAuth2 provider name
     * @param array $userInfo OAuth2 user information
     * 
     * @return bool True if linking successful
     * 
     * @since 1.0.0
     */
    public function linkProviderToUser(string $userId, string $providerName, array $userInfo): bool
    {
        return $this->userLinker->linkExistingUser($userId, $providerName, $userInfo);
    }
    
    /**
     * Removes OAuth2 provider link from user account
     * 
     * @param string $userId SuiteCRM user ID
     * @param string $providerName OAuth2 provider name
     * 
     * @return bool True if unlinking successful
     * 
     * @since 1.0.0
     */
    public function unlinkProvider(string $userId, string $providerName): bool
    {
        return $this->userLinker->unlinkProvider($userId, $providerName);
    }
    
    /**
     * Gets OAuth2 scopes for specified provider
     * 
     * @param string $providerName OAuth2 provider name
     * 
     * @return array Array of OAuth2 scopes
     * 
     * @since 1.0.0
     */
    protected function getProviderScopes(string $providerName): array
    {
        $scopes = [
            'google' => ['openid', 'email', 'profile'],
            'microsoft' => ['openid', 'email', 'profile'],
            'github' => ['user:email'],
        ];
        
        return $scopes[$providerName] ?? ['openid', 'email', 'profile'];
    }
    
    /**
     * Establishes SuiteCRM session for authenticated user
     * 
     * @param string $userId SuiteCRM user ID
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    protected function establishSession(string $userId): void
    {
        global $current_user, $sugar_config;
        
        $_SESSION['authenticated_user_id'] = $userId;
        $_SESSION['is_valid_session'] = true;
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $_SESSION['user_id'] = $userId;
        $_SESSION['type'] = 'user';
        $_SESSION['unique_key'] = $sugar_config['unique_key'];
        
        // Load user object
        $current_user = \BeanFactory::newBean('Users');
        $current_user->retrieve($userId);
        $current_user->loadPreferences();
        
        // Call after_login logic hook
        $current_user->call_custom_logic('after_login');
    }
    
    /**
     * Loads OAuth2 configuration from SuiteCRM config system
     * 
     * @return array OAuth2 configuration array
     * 
     * @since 1.0.0
     */
    protected function loadConfiguration(): array
    {
        global $sugar_config;
        
        return $sugar_config['oauth2_external_providers'] ?? [
            'google' => [
                'enabled' => false,
                'client_id' => '',
                'client_secret' => '',
                'scopes' => ['openid', 'email', 'profile']
            ]
        ];
    }
    
    /**
     * Logs OAuth2 authentication events for security monitoring
     * 
     * @param string $eventType Type of authentication event
     * @param array $context Additional context information
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    protected function logAuthEvent(string $eventType, array $context): void
    {
        $GLOBALS['log']->info("OAuth2 Authentication Event: {$eventType}", $context);
    }
} 