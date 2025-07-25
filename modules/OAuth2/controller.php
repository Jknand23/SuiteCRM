<?php
/**
 * @fileoverview OAuth2 Controller
 *
 * Handles OAuth2 authorization flows including provider redirection, callback
 * processing, and token management. Integrates with SuiteCRM's MVC framework
 * to provide OAuth2 authentication endpoints.
 *
 * Key Features:
 * - Provider authorization initiation
 * - OAuth2 callback handling
 * - State validation for CSRF protection
 * - Token storage and user linking
 * - Error handling with user-friendly messages
 *
 * Endpoints:
 * - index.php?module=OAuth2&action=authorize&provider={provider}
 * - index.php?module=OAuth2&action=callback&provider={provider}
 *
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/MVC/Controller/SugarController.php';
require_once 'lib/Authentication/OAuth2Service.php';
require_once 'lib/Authentication/TokenManager.php';
require_once 'lib/Authentication/UserLinker.php';

use SuiteCRM\Authentication\OAuth2Service;
use SuiteCRM\Authentication\TokenManager;
use SuiteCRM\Authentication\UserLinker;

class OAuth2Controller extends SugarController
{
    /** @var OAuth2Service $oauth2Service OAuth2 service instance */
    private OAuth2Service $oauth2Service;
    
    /** @var TokenManager $tokenManager Token management service */
    private TokenManager $tokenManager;
    
    /** @var UserLinker $userLinker User linking service */
    private UserLinker $userLinker;
    
    /**
     * Controller constructor initializes services
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->oauth2Service = new OAuth2Service();
        $this->tokenManager = new TokenManager();
        $this->userLinker = new UserLinker();
    }
    
    /**
     * Pre-process hook to validate common requirements
     *
     * @return void
     */
    public function preProcess()
    {
        parent::preProcess();
        
        // Ensure we have a provider parameter
        if (empty($_REQUEST['provider'])) {
            $this->handleError('Missing OAuth2 provider parameter');
        }
    }
    
    /**
     * Initiates OAuth2 authorization flow
     *
     * Redirects user to OAuth2 provider for authentication
     *
     * @return void
     */
    public function action_authorize()
    {
        try {
            $provider = $_REQUEST['provider'];
            
            // Get authorization URL from OAuth2 service
            $authUrl = $this->oauth2Service->getAuthorizationUrl($provider);
            
            // Store state in session for CSRF protection
            $_SESSION['oauth2_state'] = $this->oauth2Service->getState();
            $_SESSION['oauth2_provider'] = $provider;
            
            // Log authorization attempt
            $GLOBALS['log']->info('OAuth2 authorization initiated', [
                'provider' => $provider,
                'user_id' => $GLOBALS['current_user']->id ?? 'anonymous'
            ]);
            
            // Redirect to provider
            header('Location: ' . $authUrl);
            exit;
        } catch (\Exception $e) {
            $this->handleError('Failed to initiate OAuth2 authorization: ' . $e->getMessage());
        }
    }
    
    /**
     * Handles OAuth2 callback from provider
     *
     * Processes authorization code, exchanges for tokens, and links user
     *
     * @return void
     */
    public function action_callback()
    {
        try {
            $provider = $_REQUEST['provider'];
            
            // Validate state parameter
            if (!$this->validateState()) {
                throw new \Exception('Invalid state parameter - possible CSRF attack');
            }
            
            // Check for errors from provider
            if (!empty($_REQUEST['error'])) {
                throw new \Exception('Provider error: ' . $_REQUEST['error_description'] ?? $_REQUEST['error']);
            }
            
            // Ensure we have an authorization code
            if (empty($_REQUEST['code'])) {
                throw new \Exception('Missing authorization code');
            }
            
            // Exchange authorization code for access token
            $accessToken = $this->oauth2Service->getAccessToken(
                $provider,
                $_REQUEST['code']
            );
            
            // Get provider user information
            $providerUser = $this->oauth2Service->getProviderUser($provider, $accessToken);
            
            // Store token securely
            $tokenId = $this->tokenManager->storeToken(
                $accessToken,
                $provider,
                $providerUser->getId(),
                $GLOBALS['current_user']->id ?? null
            );
            
            // Handle user linking/creation
            $this->handleUserLinking($provider, $providerUser, $tokenId);
            
            // Clean up session
            unset($_SESSION['oauth2_state']);
            unset($_SESSION['oauth2_provider']);
            
            // Redirect to success page or dashboard
            $this->redirectToSuccess();
        } catch (\Exception $e) {
            $this->handleError('OAuth2 callback failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Displays OAuth2 account management interface
     *
     * @return void
     */
    public function action_manage()
    {
        if (empty($GLOBALS['current_user']->id)) {
            $this->handleError('You must be logged in to manage OAuth2 accounts');
        }
        
        // Get linked accounts for current user
        $linkedAccounts = $this->userLinker->getLinkedAccounts($GLOBALS['current_user']->id);
        
        // Pass to view
        $this->view = 'manage';
        $this->view_object_map['linkedAccounts'] = $linkedAccounts;
    }
    
    /**
     * Unlinks an OAuth2 account
     *
     * @return void
     */
    public function action_unlink()
    {
        try {
            if (empty($GLOBALS['current_user']->id)) {
                throw new \Exception('You must be logged in to unlink accounts');
            }
            
            $provider = $_REQUEST['provider'] ?? '';
            if (empty($provider)) {
                throw new \Exception('Missing provider parameter');
            }
            
            // Delete the token
            $result = $this->tokenManager->deleteToken(
                $GLOBALS['current_user']->id,
                $provider
            );
            
            if ($result) {
                SugarApplication::appendSuccessMessage(
                    "Successfully unlinked {$provider} account"
                );
            } else {
                throw new \Exception('Failed to unlink account');
            }
            
            // Redirect back to management page
            SugarApplication::redirect('index.php?module=OAuth2&action=manage');
        } catch (\Exception $e) {
            $this->handleError($e->getMessage());
        }
    }
    
    /**
     * Validates OAuth2 state parameter for CSRF protection
     *
     * @return bool True if state is valid
     */
    private function validateState(): bool
    {
        $sessionState = $_SESSION['oauth2_state'] ?? '';
        $requestState = $_REQUEST['state'] ?? '';
        
        if (empty($sessionState) || empty($requestState)) {
            return false;
        }
        
        return hash_equals($sessionState, $requestState);
    }
    
    /**
     * Handles user linking after successful OAuth2 authentication
     *
     * @param string $provider Provider name
     * @param object $providerUser Provider user object
     * @param string $tokenId Token record ID
     *
     * @return void
     */
    private function handleUserLinking(string $provider, $providerUser, string $tokenId): void
    {
        // If user is already logged in, link the account
        if (!empty($GLOBALS['current_user']->id)) {
            $this->userLinker->linkProviderAccount(
                $GLOBALS['current_user']->id,
                $provider,
                $providerUser
            );
            return;
        }
        
        // Try to find existing user by email
        $email = $providerUser->getEmail();
        if (!empty($email)) {
            $existingUser = $this->userLinker->findUserByEmail($email);
            
            if ($existingUser) {
                // Log the user in
                $this->performLogin($existingUser);
                
                // Link the OAuth2 account
                $this->userLinker->linkProviderAccount(
                    $existingUser->id,
                    $provider,
                    $providerUser
                );
                return;
            }
        }
        
        // Create new user if auto-provisioning is enabled
        if ($this->isAutoProvisioningEnabled()) {
            $newUser = $this->userLinker->createUserFromProvider($provider, $providerUser);
            $this->performLogin($newUser);
        } else {
            // Store provider info in session for manual account creation
            $_SESSION['oauth2_pending'] = [
                'provider' => $provider,
                'provider_user_id' => $providerUser->getId(),
                'email' => $providerUser->getEmail(),
                'name' => $providerUser->getName(),
                'token_id' => $tokenId
            ];
            
            // Redirect to registration page
            SugarApplication::redirect('index.php?module=Users&action=RegisterWithOAuth2');
        }
    }
    
    /**
     * Performs user login
     *
     * @param \User $user User object to log in
     *
     * @return void
     */
    private function performLogin(\User $user): void
    {
        global $current_user;
        
        $current_user = $user;
        $_SESSION['authenticated_user_id'] = $user->id;
        
        // Update last login
        $user->updateLastLogin();
        
        // Log successful login
        $GLOBALS['log']->info('OAuth2 login successful', [
            'user_id' => $user->id,
            'provider' => $_SESSION['oauth2_provider'] ?? 'unknown'
        ]);
    }
    
    /**
     * Checks if auto-provisioning is enabled
     *
     * @return bool True if enabled
     */
    private function isAutoProvisioningEnabled(): bool
    {
        global $sugar_config;
        return !empty($sugar_config['oauth2']['auto_create_users']);
    }
    
    /**
     * Redirects to success page after OAuth2 flow
     *
     * @return void
     */
    private function redirectToSuccess(): void
    {
        // Check for return URL in session
        $returnUrl = $_SESSION['oauth2_return_url'] ?? 'index.php';
        unset($_SESSION['oauth2_return_url']);
        
        SugarApplication::redirect($returnUrl);
    }
    
    /**
     * Handles errors with user-friendly messages
     *
     * @param string $message Error message
     *
     * @return void
     */
    private function handleError(string $message): void
    {
        $GLOBALS['log']->error('OAuth2 Controller Error: ' . $message);
        
        SugarApplication::appendErrorMessage($message);
        
        // Redirect to appropriate page
        if (!empty($GLOBALS['current_user']->id)) {
            SugarApplication::redirect('index.php?module=Users&action=EditView&record=' . $GLOBALS['current_user']->id);
        } else {
            SugarApplication::redirect('index.php?module=Users&action=Login');
        }
        
        exit;
    }
}
