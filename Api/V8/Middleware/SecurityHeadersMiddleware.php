<?php
/**
 * @fileoverview Security Headers Middleware for SuiteCRM V8 API
 *
 * Provides comprehensive security headers to protect against common web security
 * vulnerabilities including XSS, clickjacking, MIME sniffing, and other attacks.
 * Configurable security policy with sensible defaults for API protection.
 *
 * Key Features:
 * - X-Frame-Options for clickjacking protection
 * - Content-Security-Policy for XSS and injection prevention
 * - X-Content-Type-Options for MIME sniffing protection
 * - X-XSS-Protection for legacy browser XSS protection
 * - Referrer-Policy for referrer information control
 * - Configurable security policies based on environment
 *
 * Dependencies:
 * - Existing Slim 3 middleware infrastructure
 * - SuiteCRM configuration system for policy customization
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
 * SecurityHeadersMiddleware
 *
 * Adds comprehensive security headers to all API responses for enhanced
 * protection against common web security vulnerabilities.
 */
class SecurityHeadersMiddleware
{
    /** @var array $securityHeaders Security headers configuration */
    private array $securityHeaders;
    
    /** @var bool $enabled Whether security headers are enabled */
    private bool $enabled;
    
    /** @var array $exemptPaths Paths exempt from certain security headers */
    private array $exemptPaths;
    
    /**
     * SecurityHeadersMiddleware constructor
     *
     * Initializes security headers configuration with environment-appropriate defaults.
     */
    public function __construct()
    {
        global $sugar_config;
        
        // Load security headers configuration
        $securityConfig = $sugar_config['security_headers'] ?? [];
        
        $this->enabled = $securityConfig['enabled'] ?? true;
        $this->exemptPaths = $securityConfig['exempt_paths'] ?? [
            '/V8/docs' // Documentation interface may need relaxed CSP
        ];
        
        // Default security headers with configurable overrides
        $this->securityHeaders = array_merge([
            'X-Frame-Options' => 'DENY',
            'X-Content-Type-Options' => 'nosniff',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'X-Permitted-Cross-Domain-Policies' => 'none',
            'Content-Security-Policy' => $this->buildContentSecurityPolicy(),
            'Strict-Transport-Security' => $this->buildHSTSHeader(),
            'X-API-Security' => 'SuiteCRM-Enhanced'
        ], $securityConfig['headers'] ?? []);
        
        if ($this->enabled) {
            $GLOBALS['log']->info('Security headers middleware initialized', [
                'headers_count' => count($this->securityHeaders),
                'exempt_paths_count' => count($this->exemptPaths)
            ]);
        } else {
            $GLOBALS['log']->info('Security headers middleware disabled');
        }
    }
    
    /**
     * Middleware invocation handler
     *
     * Adds security headers to all API responses for enhanced protection.
     *
     * @param Request $request HTTP request object
     * @param Response $response HTTP response object
     * @param callable $next Next middleware in chain
     *
     * @return Response Response with security headers added
     *
     * @since 1.0.0
     */
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        // Process request through middleware chain first
        $response = $next($request, $response);
        
        // Skip security headers if disabled
        if (!$this->enabled) {
            return $response;
        }
        
        // Add security headers to response
        return $this->addSecurityHeaders($response, $request);
    }
    
    /**
     * Adds security headers to response
     *
     * @param Response $response HTTP response object
     * @param Request $request HTTP request object for context
     *
     * @return Response Response with security headers added
     *
     * @since 1.0.0
     */
    private function addSecurityHeaders(Response $response, Request $request): Response
    {
        $requestPath = $request->getUri()->getPath();
        
        foreach ($this->securityHeaders as $header => $value) {
            // Skip certain headers for exempt paths
            if ($this->shouldSkipHeaderForPath($header, $requestPath)) {
                continue;
            }
            
            // Only add HSTS header for HTTPS requests
            if ($header === 'Strict-Transport-Security' && !$this->isHttpsRequest($request)) {
                continue;
            }
            
            $response = $response->withHeader($header, $value);
        }
        
        return $response;
    }
    
    /**
     * Builds Content Security Policy header value
     *
     * @return string CSP header value
     *
     * @since 1.0.0
     */
    private function buildContentSecurityPolicy(): string
    {
        global $sugar_config;
        
        // Get CSP configuration or use secure defaults
        $cspConfig = $sugar_config['security_headers']['csp'] ?? [];
        
        $defaultPolicies = [
            'default-src' => "'self'",
            'script-src' => "'self' 'unsafe-inline' https://unpkg.com", // Allow Swagger UI
            'style-src' => "'self' 'unsafe-inline' https://unpkg.com",
            'img-src' => "'self' data: https:",
            'font-src' => "'self' https:",
            'connect-src' => "'self'",
            'frame-ancestors' => "'none'",
            'base-uri' => "'self'",
            'form-action' => "'self'",
            'upgrade-insecure-requests' => ''
        ];
        
        $policies = array_merge($defaultPolicies, $cspConfig);
        
        $cspParts = [];
        foreach ($policies as $directive => $value) {
            if ($value !== '') {
                $cspParts[] = "$directive $value";
            } else {
                $cspParts[] = $directive;
            }
        }
        
        return implode('; ', $cspParts);
    }
    
    /**
     * Builds Strict Transport Security header value
     *
     * @return string HSTS header value
     *
     * @since 1.0.0
     */
    private function buildHSTSHeader(): string
    {
        global $sugar_config;
        
        $hstsConfig = $sugar_config['security_headers']['hsts'] ?? [];
        
        $maxAge = $hstsConfig['max_age'] ?? 31536000; // 1 year default
        $includeSubdomains = $hstsConfig['include_subdomains'] ?? false;
        $preload = $hstsConfig['preload'] ?? false;
        
        $hsts = "max-age=$maxAge";
        
        if ($includeSubdomains) {
            $hsts .= '; includeSubDomains';
        }
        
        if ($preload) {
            $hsts .= '; preload';
        }
        
        return $hsts;
    }
    
    /**
     * Checks if header should be skipped for specific path
     *
     * @param string $header Header name
     * @param string $requestPath Request path
     *
     * @return bool True if header should be skipped
     *
     * @since 1.0.0
     */
    private function shouldSkipHeaderForPath(string $header, string $requestPath): bool
    {
        // Check if path is exempt
        foreach ($this->exemptPaths as $exemptPath) {
            if (strpos($requestPath, $exemptPath) !== false) {
                // Relax certain headers for exempt paths (like documentation)
                if (in_array($header, ['Content-Security-Policy', 'X-Frame-Options'])) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Checks if request is made over HTTPS
     *
     * @param Request $request HTTP request object
     *
     * @return bool True if HTTPS request
     *
     * @since 1.0.0
     */
    private function isHttpsRequest(Request $request): bool
    {
        $scheme = $request->getUri()->getScheme();
        
        // Check for HTTPS
        if ($scheme === 'https') {
            return true;
        }
        
        // Check for forwarded HTTPS (proxy/load balancer)
        $serverParams = $request->getServerParams();
        
        if (!empty($serverParams['HTTPS']) && $serverParams['HTTPS'] !== 'off') {
            return true;
        }
        
        if (!empty($serverParams['HTTP_X_FORWARDED_PROTO']) && $serverParams['HTTP_X_FORWARDED_PROTO'] === 'https') {
            return true;
        }
        
        if (!empty($serverParams['HTTP_X_FORWARDED_SSL']) && $serverParams['HTTP_X_FORWARDED_SSL'] === 'on') {
            return true;
        }
        
        return false;
    }
}
