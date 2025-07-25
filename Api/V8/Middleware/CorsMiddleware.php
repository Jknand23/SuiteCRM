<?php
/**
 * @fileoverview Enhanced CORS Middleware for SuiteCRM V8 API
 *
 * Provides configurable CORS (Cross-Origin Resource Sharing) handling that enhances
 * the basic wildcard implementation while maintaining security and compatibility.
 * Integrates with existing Slim 3 middleware pipeline without breaking current functionality.
 *
 * Key Features:
 * - Configurable allowed origins instead of wildcard (*)
 * - Proper OPTIONS preflight request handling
 * - Secure default configuration with environment-based overrides
 * - Integration with existing SuiteCRM configuration system
 * - Comprehensive logging for CORS-related security events
 *
 * Dependencies:
 * - Existing Slim 3 middleware infrastructure
 * - SuiteCRM configuration system ($sugar_config)
 * - SuiteCRM logging infrastructure
 *
 * @package SuiteCRM\Api\V8\Middleware
 * @copyright Copyright (C) 2011 - 2024 SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 * @since 1.0.0
 */

namespace Api\V8\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * CorsMiddleware
 *
 * Enhanced CORS handling middleware that provides secure, configurable
 * cross-origin resource sharing while maintaining compatibility with
 * existing API infrastructure.
 */
class CorsMiddleware
{
    /** @var array $allowedOrigins Configured allowed origins */
    private array $allowedOrigins;
    
    /** @var array $allowedMethods Allowed HTTP methods */
    private array $allowedMethods;
    
    /** @var array $allowedHeaders Allowed request headers */
    private array $allowedHeaders;
    
    /** @var bool $allowCredentials Whether to allow credentials */
    private bool $allowCredentials;
    
    /** @var int $maxAge Cache duration for preflight requests */
    private int $maxAge;
    
    /**
     * CorsMiddleware constructor
     *
     * Initializes CORS configuration from SuiteCRM global configuration
     * with secure defaults and environment-based overrides.
     */
    public function __construct()
    {
        global $sugar_config;
        
        // Load CORS configuration with secure defaults
        $corsConfig = $sugar_config['cors'] ?? [];
        
        // Default to site URL for security instead of wildcard
        $defaultOrigin = $sugar_config['site_url'] ?? 'http://localhost';
        
        $this->allowedOrigins = $corsConfig['allowed_origins'] ?? [$defaultOrigin];
        
        // Maintain existing allowed methods for compatibility
        $this->allowedMethods = $corsConfig['allowed_methods'] ?? [
            'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'
        ];
        
        // Maintain existing allowed headers for compatibility
        $this->allowedHeaders = $corsConfig['allowed_headers'] ?? [
            'Content-Type',
            'Authorization',
            'X-Requested-With',
            'Accept',
            'Origin',
            'Cache-Control',
            'X-File-Name'
        ];
        
        $this->allowCredentials = $corsConfig['allow_credentials'] ?? true;
        $this->maxAge = $corsConfig['max_age'] ?? 3600; // 1 hour
        
        // Log CORS configuration for security monitoring
        $GLOBALS['log']->info('CORS middleware initialized', [
            'allowed_origins_count' => count($this->allowedOrigins),
            'allowed_methods' => $this->allowedMethods,
            'allow_credentials' => $this->allowCredentials
        ]);
    }
    
    /**
     * Middleware invocation handler
     *
     * Processes CORS headers for all requests and handles OPTIONS preflight
     * requests appropriately. Integrates with existing Slim 3 middleware chain.
     *
     * @param Request $request HTTP request object
     * @param Response $response HTTP response object
     * @param callable $next Next middleware in chain
     *
     * @return Response Enhanced response with CORS headers
     *
     * @since 1.0.0
     */
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        // Get request origin
        $origin = $request->getHeaderLine('Origin');
        
        // Add CORS headers to response
        $response = $this->addCorsHeaders($response, $origin);
        
        // Handle preflight OPTIONS requests
        if ($request->getMethod() === 'OPTIONS') {
            return $this->handlePreflightRequest($request, $response, $origin);
        }
        
        // Continue to next middleware for non-preflight requests
        return $next($request, $response);
    }
    
    /**
     * Adds appropriate CORS headers to response
     *
     * Determines if the request origin is allowed and adds appropriate
     * CORS headers. Logs security events for monitoring.
     *
     * @param Response $response HTTP response object
     * @param string $origin Request origin
     *
     * @return Response Response with CORS headers added
     *
     * @since 1.0.0
     */
    private function addCorsHeaders(Response $response, string $origin): Response
    {
        // Check if origin is allowed
        if ($this->isOriginAllowed($origin)) {
            $response = $response->withHeader('Access-Control-Allow-Origin', $origin);
            
            if ($this->allowCredentials) {
                $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');
            }
        } elseif (empty($origin)) {
            // For requests without Origin header (same-origin, Postman, etc.)
            // Allow the first configured origin or maintain backward compatibility
            $allowedOrigin = $this->allowedOrigins[0] ?? '*';
            $response = $response->withHeader('Access-Control-Allow-Origin', $allowedOrigin);
        } else {
            // Log potential security issue
            $GLOBALS['log']->warning('CORS request from disallowed origin', [
                'origin' => $origin,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);
        }
        
        // Add exposed headers for JSON:API compatibility
        $response = $response->withHeader('Access-Control-Expose-Headers', 'Content-Type, Authorization');
        
        return $response;
    }
    
    /**
     * Handles CORS preflight OPTIONS requests
     *
     * Processes preflight requests by validating the requested method and headers
     * and returning appropriate CORS preflight response.
     *
     * @param Request $request HTTP request object
     * @param Response $response HTTP response object
     * @param string $origin Request origin
     *
     * @return Response Preflight response with CORS headers
     *
     * @since 1.0.0
     */
    private function handlePreflightRequest(Request $request, Response $response, string $origin): Response
    {
        // Validate preflight request
        if (!$this->isOriginAllowed($origin)) {
            $GLOBALS['log']->warning('CORS preflight request from disallowed origin', [
                'origin' => $origin,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            return $response->withStatus(403);
        }
        
        // Get requested method and headers
        $requestedMethod = $request->getHeaderLine('Access-Control-Request-Method');
        $requestedHeaders = $request->getHeaderLine('Access-Control-Request-Headers');
        
        // Validate requested method
        if (!in_array($requestedMethod, $this->allowedMethods)) {
            $GLOBALS['log']->warning('CORS preflight request with disallowed method', [
                'origin' => $origin,
                'requested_method' => $requestedMethod,
                'allowed_methods' => $this->allowedMethods
            ]);
            return $response->withStatus(405);
        }
        
        // Add preflight response headers
        $response = $response
            ->withHeader('Access-Control-Allow-Methods', implode(', ', $this->allowedMethods))
            ->withHeader('Access-Control-Allow-Headers', implode(', ', $this->allowedHeaders))
            ->withHeader('Access-Control-Max-Age', (string)$this->maxAge);
        
        // Log successful preflight
        $GLOBALS['log']->debug('CORS preflight request processed', [
            'origin' => $origin,
            'requested_method' => $requestedMethod,
            'requested_headers' => $requestedHeaders
        ]);
        
        return $response->withStatus(204); // No Content for preflight
    }
    
    /**
     * Checks if an origin is allowed
     *
     * Validates the request origin against the configured allowed origins.
     * Supports exact matches and wildcard (*) for backward compatibility.
     *
     * @param string $origin Request origin to validate
     *
     * @return bool True if origin is allowed, false otherwise
     *
     * @since 1.0.0
     */
    private function isOriginAllowed(string $origin): bool
    {
        if (empty($origin)) {
            return false;
        }
        
        // Check for exact match
        if (in_array($origin, $this->allowedOrigins)) {
            return true;
        }
        
        // Check for wildcard (backward compatibility)
        if (in_array('*', $this->allowedOrigins)) {
            return true;
        }
        
        // Check for pattern matches (if needed in future)
        foreach ($this->allowedOrigins as $allowedOrigin) {
            if ($this->matchesOriginPattern($origin, $allowedOrigin)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Matches origin against pattern
     *
     * Supports simple pattern matching for origins. Currently supports
     * exact matches, with potential for wildcard subdomain matching.
     *
     * @param string $origin Request origin
     * @param string $pattern Allowed origin pattern
     *
     * @return bool True if origin matches pattern
     *
     * @since 1.0.0
     */
    private function matchesOriginPattern(string $origin, string $pattern): bool
    {
        // For now, only exact matches (can be extended for subdomain patterns)
        return $origin === $pattern;
    }
}
