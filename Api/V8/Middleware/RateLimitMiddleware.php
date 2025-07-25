<?php
/**
 * @fileoverview Rate Limiting Middleware for SuiteCRM V8 API
 *
 * Provides configurable rate limiting for API endpoints to prevent abuse and ensure
 * fair usage. Integrates with existing Slim 3 middleware pipeline and builds upon
 * the rate limiting patterns established in the OAuth2 SecurityValidator.
 *
 * Key Features:
 * - Configurable rate limits per IP address and user
 * - Integration with existing SuiteCRM session management
 * - Different rate limits for authenticated vs unauthenticated requests
 * - Comprehensive logging for monitoring and security analysis
 * - Sliding window rate limiting for accurate request tracking
 *
 * Dependencies:
 * - Existing Slim 3 middleware infrastructure
 * - SuiteCRM session management for user identification
 * - SuiteCRM logging infrastructure for monitoring
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
 * RateLimitMiddleware
 *
 * Implements sliding window rate limiting for API endpoints with configurable
 * limits based on authentication status and request patterns.
 */
class RateLimitMiddleware
{
    /** @var int $defaultRequestLimit Default requests per window for unauthenticated users */
    private int $defaultRequestLimit;
    
    /** @var int $authenticatedRequestLimit Requests per window for authenticated users */
    private int $authenticatedRequestLimit;
    
    /** @var int $windowSizeSeconds Size of rate limiting window in seconds */
    private int $windowSizeSeconds;
    
    /** @var array $exemptPaths Paths exempt from rate limiting */
    private array $exemptPaths;
    
    /** @var bool $enabled Whether rate limiting is enabled */
    private bool $enabled;
    
    /**
     * RateLimitMiddleware constructor
     *
     * Initializes rate limiting configuration from SuiteCRM global configuration
     * with sensible defaults for API protection.
     */
    public function __construct()
    {
        global $sugar_config;
        
        // Load rate limiting configuration
        $rateLimitConfig = $sugar_config['api_rate_limit'] ?? [];
        
        $this->enabled = $rateLimitConfig['enabled'] ?? true;
        $this->defaultRequestLimit = $rateLimitConfig['default_limit'] ?? 100; // per hour
        $this->authenticatedRequestLimit = $rateLimitConfig['authenticated_limit'] ?? 1000; // per hour
        $this->windowSizeSeconds = $rateLimitConfig['window_seconds'] ?? 3600; // 1 hour
        
        // Paths exempt from rate limiting (critical endpoints)
        $this->exemptPaths = $rateLimitConfig['exempt_paths'] ?? [
            '/access_token', // OAuth2 token endpoint
            '/V8/logout'     // Logout endpoint
        ];
        
        // Log rate limiting configuration
        if ($this->enabled) {
            $GLOBALS['log']->info('Rate limiting middleware initialized', [
                'default_limit' => $this->defaultRequestLimit,
                'authenticated_limit' => $this->authenticatedRequestLimit,
                'window_seconds' => $this->windowSizeSeconds,
                'exempt_paths_count' => count($this->exemptPaths)
            ]);
        } else {
            $GLOBALS['log']->info('Rate limiting middleware disabled by configuration');
        }
    }
    
    /**
     * Middleware invocation handler
     *
     * Checks rate limits for incoming requests and returns 429 Too Many Requests
     * if limits are exceeded. Integrates with existing middleware pipeline.
     *
     * @param Request $request HTTP request object
     * @param Response $response HTTP response object
     * @param callable $next Next middleware in chain
     *
     * @return Response Response with rate limit headers or 429 status
     *
     * @since 1.0.0
     */
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        // Skip rate limiting if disabled
        if (!$this->enabled) {
            return $next($request, $response);
        }
        
        // Skip rate limiting for exempt paths
        $requestPath = $request->getUri()->getPath();
        if ($this->isPathExempt($requestPath)) {
            return $next($request, $response);
        }
        
        // Get client identifier and rate limit
        $clientId = $this->getClientIdentifier($request);
        $requestLimit = $this->getRequestLimit($request);
        
        // Check rate limit
        $requestCount = $this->getCurrentRequestCount($clientId);
        $isLimitExceeded = $requestCount >= $requestLimit;
        
        if ($isLimitExceeded) {
            return $this->handleRateLimitExceeded($response, $clientId, $requestCount, $requestLimit);
        }
        
        // Record request
        $this->recordRequest($clientId);
        
        // Add rate limit headers to response
        $response = $this->addRateLimitHeaders($response, $requestCount + 1, $requestLimit);
        
        // Continue to next middleware
        return $next($request, $response);
    }
    
    /**
     * Gets client identifier for rate limiting
     *
     * Creates a unique identifier for the client based on IP address and
     * user ID (if authenticated) for accurate rate limiting.
     *
     * @param Request $request HTTP request object
     *
     * @return string Client identifier for rate limiting
     *
     * @since 1.0.0
     */
    private function getClientIdentifier(Request $request): string
    {
        $ipAddress = $this->getClientIpAddress($request);
        
        // Include user ID for authenticated requests
        $userId = $_SESSION['authenticated_user_id'] ?? null;
        if ($userId) {
            return "user_{$userId}_{$ipAddress}";
        }
        
        return "ip_{$ipAddress}";
    }
    
    /**
     * Gets client IP address from request
     *
     * Extracts client IP address considering proxy headers for accurate identification.
     *
     * @param Request $request HTTP request object
     *
     * @return string Client IP address
     *
     * @since 1.0.0
     */
    private function getClientIpAddress(Request $request): string
    {
        // Check for IP address in proxy headers
        $serverParams = $request->getServerParams();
        
        $ipHeaders = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'
        ];
        
        foreach ($ipHeaders as $header) {
            if (!empty($serverParams[$header])) {
                $ip = trim(explode(',', $serverParams[$header])[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $serverParams['REMOTE_ADDR'] ?? 'unknown';
    }
    
    /**
     * Gets applicable request limit for client
     *
     * Returns different rate limits based on authentication status.
     *
     * @param Request $request HTTP request object
     *
     * @return int Request limit for this client
     *
     * @since 1.0.0
     */
    private function getRequestLimit(Request $request): int
    {
        // Higher limits for authenticated users
        if (!empty($_SESSION['authenticated_user_id'])) {
            return $this->authenticatedRequestLimit;
        }
        
        return $this->defaultRequestLimit;
    }
    
    /**
     * Gets current request count for client
     *
     * Retrieves current request count from session storage using sliding window.
     *
     * @param string $clientId Client identifier
     *
     * @return int Current request count in window
     *
     * @since 1.0.0
     */
    private function getCurrentRequestCount(string $clientId): int
    {
        $sessionKey = "rate_limit_{$clientId}";
        $requestData = $_SESSION[$sessionKey] ?? [];
        
        $currentTime = time();
        $windowStart = $currentTime - $this->windowSizeSeconds;
        
        // Filter requests within current window
        $recentRequests = array_filter($requestData, function ($timestamp) use ($windowStart) {
            return $timestamp > $windowStart;
        });
        
        return count($recentRequests);
    }
    
    /**
     * Records a request for rate limiting
     *
     * Adds current request timestamp to session storage for tracking.
     *
     * @param string $clientId Client identifier
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function recordRequest(string $clientId): void
    {
        $sessionKey = "rate_limit_{$clientId}";
        $requestData = $_SESSION[$sessionKey] ?? [];
        
        $currentTime = time();
        $windowStart = $currentTime - $this->windowSizeSeconds;
        
        // Add current request
        $requestData[] = $currentTime;
        
        // Clean old requests outside window
        $requestData = array_filter($requestData, function ($timestamp) use ($windowStart) {
            return $timestamp > $windowStart;
        });
        
        // Store updated data
        $_SESSION[$sessionKey] = array_values($requestData);
    }
    
    /**
     * Handles rate limit exceeded scenario
     *
     * Returns 429 Too Many Requests response with appropriate headers and logging.
     *
     * @param Response $response HTTP response object
     * @param string $clientId Client identifier
     * @param int $requestCount Current request count
     * @param int $requestLimit Request limit
     *
     * @return Response 429 response with rate limit information
     *
     * @since 1.0.0
     */
    private function handleRateLimitExceeded(
        Response $response,
        string $clientId,
        int $requestCount,
        int $requestLimit
    ): Response {
        // Log rate limit violation
        $GLOBALS['log']->warning('API rate limit exceeded', [
            'client_id' => $clientId,
            'request_count' => $requestCount,
            'request_limit' => $requestLimit,
            'window_seconds' => $this->windowSizeSeconds,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
        
        // Calculate reset time
        $resetTime = time() + $this->windowSizeSeconds;
        
        // Create error response
        $errorResponse = [
            'error' => 'rate_limit_exceeded',
            'message' => 'API rate limit exceeded. Please try again later.',
            'limit' => $requestLimit,
            'window_seconds' => $this->windowSizeSeconds,
            'reset_time' => $resetTime
        ];
        
        return $response
            ->withStatus(429, 'Too Many Requests')
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('X-RateLimit-Limit', (string)$requestLimit)
            ->withHeader('X-RateLimit-Remaining', '0')
            ->withHeader('X-RateLimit-Reset', (string)$resetTime)
            ->withHeader('Retry-After', (string)$this->windowSizeSeconds)
            ->write(json_encode($errorResponse, JSON_PRETTY_PRINT));
    }
    
    /**
     * Adds rate limit headers to response
     *
     * Adds informational headers about rate limiting status.
     *
     * @param Response $response HTTP response object
     * @param int $requestCount Current request count
     * @param int $requestLimit Request limit
     *
     * @return Response Response with rate limit headers
     *
     * @since 1.0.0
     */
    private function addRateLimitHeaders(Response $response, int $requestCount, int $requestLimit): Response
    {
        $remaining = max(0, $requestLimit - $requestCount);
        $resetTime = time() + $this->windowSizeSeconds;
        
        return $response
            ->withHeader('X-RateLimit-Limit', (string)$requestLimit)
            ->withHeader('X-RateLimit-Remaining', (string)$remaining)
            ->withHeader('X-RateLimit-Reset', (string)$resetTime);
    }
    
    /**
     * Checks if path is exempt from rate limiting
     *
     * Determines if the current request path should be exempt from rate limiting.
     *
     * @param string $requestPath Request path to check
     *
     * @return bool True if path is exempt from rate limiting
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
