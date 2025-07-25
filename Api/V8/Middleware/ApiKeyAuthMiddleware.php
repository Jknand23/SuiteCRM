<?php
/**
 * @fileoverview API Key Authentication Middleware for SuiteCRM V8 API
 *
 * Provides API key-based authentication for service-to-service communication,
 * complementing the existing OAuth2 authentication system. Validates API key
 * credentials and establishes user sessions for authenticated requests.
 *
 * Key Features:
 * - Secure API key validation with hashed storage
 * - Service-to-service authentication bypass for OAuth2 flows
 * - Integration with existing SuiteCRM session management
 * - Comprehensive logging for security monitoring and audit trails
 * - Scope-based access control for API endpoints
 *
 * Dependencies:
 * - Existing Slim 3 middleware infrastructure
 * - SuiteCRM database and session management
 * - ApiCommands.php for API key management functionality
 *
 * @package SuiteCRM\Api\V8\Middleware
 * @copyright Copyright (C) 2011 - 2024 SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 * @since 1.0.0
 */

namespace Api\V8\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;
use DBManagerFactory;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * ApiKeyAuthMiddleware
 *
 * Validates API key credentials for service-to-service authentication.
 * Provides an alternative authentication method to OAuth2 for automated
 * systems and internal services.
 */
class ApiKeyAuthMiddleware
{
    /** @var \DBManager $db Database manager instance */
    private $db;
    
    /** @var bool $enabled Whether API key authentication is enabled */
    private bool $enabled;
    
    /** @var array $exemptPaths Paths exempt from API key authentication */
    private array $exemptPaths;
    
    /**
     * ApiKeyAuthMiddleware constructor
     *
     * Initializes API key authentication configuration and database connection.
     */
    public function __construct()
    {
        global $sugar_config;
        
        $this->db = DBManagerFactory::getInstance();
        
        // Load API key authentication configuration
        $apiKeyConfig = $sugar_config['api_key_auth'] ?? [];
        
        $this->enabled = $apiKeyConfig['enabled'] ?? true;
        $this->exemptPaths = $apiKeyConfig['exempt_paths'] ?? [
            '/access_token', // OAuth2 token endpoint
            '/V8/docs',      // API documentation
            '/V8/meta'       // API metadata endpoints
        ];
        
        if ($this->enabled) {
            $GLOBALS['log']->info('API key authentication middleware initialized');
        } else {
            $GLOBALS['log']->info('API key authentication middleware disabled');
        }
    }
    
    /**
     * Middleware invocation handler
     *
     * Checks for API key authentication headers and validates credentials.
     * Sets up user session for valid API key requests.
     *
     * @param Request $request HTTP request object
     * @param Response $response HTTP response object
     * @param callable $next Next middleware in chain
     *
     * @return Response Response object
     *
     * @since 1.0.0
     */
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        // Skip API key authentication if disabled
        if (!$this->enabled) {
            return $next($request, $response);
        }
        
        // Skip for exempt paths
        $requestPath = $request->getUri()->getPath();
        if ($this->isPathExempt($requestPath)) {
            return $next($request, $response);
        }
        
        // Check for API key authentication header
        $authHeader = $request->getHeaderLine('Authorization');
        
        if (!$this->isApiKeyAuth($authHeader)) {
            // No API key authentication, continue to next middleware (OAuth2)
            return $next($request, $response);
        }
        
        // Extract and validate API key credentials
        $credentials = $this->extractApiKeyCredentials($authHeader);
        
        if (!$credentials) {
            return $this->createUnauthorizedResponse($response, 'Invalid API key format');
        }
        
        // Validate API key
        $apiKeyData = $this->validateApiKey($credentials['key'], $credentials['secret']);
        
        if (!$apiKeyData) {
            return $this->createUnauthorizedResponse($response, 'Invalid API key credentials');
        }
        
        // Set up authenticated session
        $this->setupAuthenticatedSession($apiKeyData);
        
        // Update last used timestamp
        $this->updateLastUsed($apiKeyData['id']);
        
        // Continue to next middleware
        return $next($request, $response);
    }
    
    /**
     * Checks if the authorization header contains API key authentication
     *
     * @param string $authHeader Authorization header value
     *
     * @return bool True if API key authentication header is present
     *
     * @since 1.0.0
     */
    private function isApiKeyAuth(string $authHeader): bool
    {
        return strpos($authHeader, 'Api-Key ') === 0;
    }
    
    /**
     * Extracts API key credentials from authorization header
     *
     * Expected format: "Api-Key {key}:{secret}"
     *
     * @param string $authHeader Authorization header value
     *
     * @return array|null Array with 'key' and 'secret' or null if invalid
     *
     * @since 1.0.0
     */
    private function extractApiKeyCredentials(string $authHeader): ?array
    {
        // Remove "Api-Key " prefix
        $credentials = substr($authHeader, 8);
        
        // Split key and secret
        $parts = explode(':', $credentials, 2);
        
        if (count($parts) !== 2) {
            return null;
        }
        
        return [
            'key' => trim($parts[0]),
            'secret' => trim($parts[1])
        ];
    }
    
    /**
     * Validates API key credentials against database
     *
     * @param string $apiKey The API key to validate
     * @param string $apiSecret The API secret to validate
     *
     * @return array|null API key data if valid, null otherwise
     *
     * @since 1.0.0
     */
    private function validateApiKey(string $apiKey, string $apiSecret): ?array
    {
        try {
            // Hash the provided credentials
            $hashedKey = hash('sha256', $apiKey);
            $hashedSecret = hash('sha256', $apiSecret);
            
            // Query database for matching API key
            $sql = "SELECT id, name, scopes, expires_at, is_active 
                    FROM oauth2_api_keys 
                    WHERE api_key = ? AND api_secret = ? AND is_active = 1";
            
            $result = $this->db->fetchOne($sql, [$hashedKey, $hashedSecret]);
            
            if (!$result) {
                $GLOBALS['log']->warning('API key authentication failed: invalid credentials', [
                    'api_key_preview' => substr($apiKey, 0, 8) . '...',
                    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                return null;
            }
            
            // Check expiration
            if ($result['expires_at'] && strtotime($result['expires_at']) < time()) {
                $GLOBALS['log']->warning('API key authentication failed: expired key', [
                    'api_key_name' => $result['name'],
                    'expired_at' => $result['expires_at']
                ]);
                return null;
            }
            
            // Parse scopes
            $result['scopes'] = json_decode($result['scopes'], true) ?: ['api'];
            
            $GLOBALS['log']->info('API key authentication successful', [
                'api_key_name' => $result['name'],
                'scopes' => implode(', ', $result['scopes']),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            
            return $result;
        } catch (\Exception $e) {
            $GLOBALS['log']->error('API key validation error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Sets up authenticated session for API key request
     *
     * Creates a session that mimics OAuth2 authentication for compatibility
     * with existing API infrastructure.
     *
     * @param array $apiKeyData API key data from database
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function setupAuthenticatedSession(array $apiKeyData): void
    {
        global $current_user, $sugar_config;
        
        // Create a service user session for API key authentication
        $_SESSION['authenticated_user_id'] = 'api_key_' . $apiKeyData['id'];
        $_SESSION['is_valid_session'] = true;
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $_SESSION['user_id'] = 'api_key_' . $apiKeyData['id'];
        $_SESSION['type'] = 'api_key';
        $_SESSION['unique_key'] = $sugar_config['unique_key'];
        $_SESSION['api_key_name'] = $apiKeyData['name'];
        $_SESSION['api_key_scopes'] = $apiKeyData['scopes'];
        
        // Set current user to a service user representation
        $current_user = new \stdClass();
        $current_user->id = 'api_key_' . $apiKeyData['id'];
        $current_user->user_name = 'API Key: ' . $apiKeyData['name'];
        $current_user->is_admin = 1; // API keys have admin access by default
        $current_user->is_api_key = true;
        $current_user->api_key_scopes = $apiKeyData['scopes'];
    }
    
    /**
     * Updates the last used timestamp for an API key
     *
     * @param string $apiKeyId API key ID
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function updateLastUsed(string $apiKeyId): void
    {
        try {
            $sql = "UPDATE oauth2_api_keys SET last_used_at = NOW() WHERE id = ?";
            $this->db->query($sql, [$apiKeyId]);
        } catch (\Exception $e) {
            $GLOBALS['log']->warning('Failed to update API key last used timestamp: ' . $e->getMessage());
        }
    }
    
    /**
     * Creates an unauthorized response for invalid API key authentication
     *
     * @param Response $response HTTP response object
     * @param string $message Error message
     *
     * @return Response 401 Unauthorized response
     *
     * @since 1.0.0
     */
    private function createUnauthorizedResponse(Response $response, string $message): Response
    {
        $errorResponse = [
            'error' => 'unauthorized',
            'message' => $message,
            'authentication_required' => 'Api-Key {key}:{secret}'
        ];
        
        return $response
            ->withStatus(401, 'Unauthorized')
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('WWW-Authenticate', 'Api-Key')
            ->write(json_encode($errorResponse, JSON_PRETTY_PRINT));
    }
    
    /**
     * Checks if path is exempt from API key authentication
     *
     * @param string $requestPath Request path to check
     *
     * @return bool True if path is exempt
     *
     * @since 1.0.0
     */
    private function isPathExempt(string $requestPath): bool
    {
        foreach ($this->exemptPaths as $exemptPath) {
            if (strpos($requestPath, $exemptPath) !== false) {
                return true;
            }
        }
        
        return false;
    }
}
