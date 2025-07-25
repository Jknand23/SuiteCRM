<?php
/**
 * @fileoverview Enhanced Input Validation Middleware for SuiteCRM V8 API
 *
 * Extends the existing parameter validation system with comprehensive input
 * sanitization, type validation, and security checks. Builds upon the existing
 * ParamsMiddleware patterns while adding enhanced validation capabilities
 * without breaking existing functionality.
 *
 * Key Features:
 * - Extended input validation beyond basic parameter checking
 * - Comprehensive input sanitization and type validation
 * - Security-focused validation rules (XSS, SQL injection prevention)
 * - Integration with existing parameter validation system
 * - Enhanced error reporting with detailed validation feedback
 * - Request size and complexity limits for security
 *
 * Dependencies:
 * - Existing Slim 3 middleware infrastructure
 * - Current ParamsMiddleware patterns and parameter classes
 * - SuiteCRM logging infrastructure for security monitoring
 * - Existing validation and security utilities
 *
 * @package SuiteCRM\Api\V8\Middleware
 * @copyright Copyright (C) 2011 - 2024 SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 * @since 1.0.0
 */

namespace Api\V8\Middleware;

use Api\V8\JsonApi\Response\ErrorResponse;
use Slim\Http\Request;
use Slim\Http\Response;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * EnhancedValidationMiddleware
 *
 * Provides comprehensive input validation, sanitization, and security checks
 * that enhance the existing parameter validation system without replacement.
 */
class EnhancedValidationMiddleware
{
    /** @var bool $enabled Whether enhanced validation is enabled */
    private bool $enabled;
    
    /** @var array $validationRules Enhanced validation rule configuration */
    private array $validationRules;
    
    /** @var array $securityLimits Request security limits */
    private array $securityLimits;
    
    /** @var array $exemptPaths Paths exempt from enhanced validation */
    private array $exemptPaths;
    
    /**
     * EnhancedValidationMiddleware constructor
     *
     * Initializes enhanced validation configuration with security-focused defaults.
     */
    public function __construct()
    {
        global $sugar_config;
        
        // Load enhanced validation configuration
        $validationConfig = $sugar_config['enhanced_validation'] ?? [];
        
        $this->enabled = $validationConfig['enabled'] ?? true;
        $this->exemptPaths = $validationConfig['exempt_paths'] ?? [
            '/access_token', // OAuth2 token endpoint has its own validation
            '/V8/docs'       // Documentation interface
        ];
        
        // Enhanced validation rules with security focus
        $this->validationRules = array_merge([
            'max_string_length' => 10000,           // Prevent extremely long inputs
            'max_array_depth' => 10,                // Prevent deeply nested arrays
            'max_array_items' => 1000,              // Prevent oversized arrays
            'allowed_html_tags' => '<p><br><strong><em><ul><ol><li>', // Basic safe HTML
            'forbidden_patterns' => [
                '/script\s*>/i',                    // Script tags
                '/javascript:/i',                   // JavaScript URLs
                '/vbscript:/i',                     // VBScript URLs
                '/on\w+\s*=/i',                     // Event handlers
                '/expression\s*\(/i',               // CSS expressions
            ],
            'sql_injection_patterns' => [
                '/union\s+select/i',
                '/drop\s+table/i',
                '/delete\s+from/i',
                '/insert\s+into/i',
                '/update\s+set/i',
                '/exec\s*\(/i',
                '/xp_\w+/i',
            ]
        ], $validationConfig['rules'] ?? []);
        
        // Security limits for request processing
        $this->securityLimits = array_merge([
            'max_request_size' => 10485760,         // 10MB max request size
            'max_file_upload_size' => 52428800,    // 50MB max file upload
            'max_request_parameters' => 500,       // Max number of parameters
            'request_timeout' => 30,               // Request processing timeout
        ], $validationConfig['limits'] ?? []);
        
        if ($this->enabled) {
            $GLOBALS['log']->info('Enhanced validation middleware initialized', [
                'validation_rules_count' => count($this->validationRules),
                'security_limits' => $this->securityLimits,
                'exempt_paths_count' => count($this->exemptPaths)
            ]);
        } else {
            $GLOBALS['log']->info('Enhanced validation middleware disabled');
        }
    }
    
    /**
     * Middleware invocation handler
     *
     * Performs comprehensive input validation and sanitization before processing.
     * Integrates with existing middleware pipeline without disrupting current flow.
     *
     * @param Request $request HTTP request object
     * @param Response $response HTTP response object
     * @param callable $next Next middleware in chain
     *
     * @return Response Response object or validation error response
     *
     * @since 1.0.0
     */
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        // Skip enhanced validation if disabled
        if (!$this->enabled) {
            return $next($request, $response);
        }
        
        // Skip for exempt paths
        $requestPath = $request->getUri()->getPath();
        if ($this->isPathExempt($requestPath)) {
            return $next($request, $response);
        }
        
        // Perform comprehensive validation
        $validationResult = $this->validateRequest($request);
        
        if (!$validationResult['valid']) {
            return $this->createValidationErrorResponse(
                $response,
                $validationResult['errors'],
                $validationResult['security_violations']
            );
        }
        
        // Add validation metadata to request for downstream processing
        $request = $request->withAttribute('enhanced_validation', [
            'validated' => true,
            'sanitized_data' => $validationResult['sanitized_data'],
            'validation_timestamp' => time()
        ]);
        
        // Continue to next middleware
        return $next($request, $response);
    }
    
    /**
     * Performs comprehensive request validation
     *
     * Validates request size, parameter count, input sanitization, and security checks.
     *
     * @param Request $request HTTP request object
     *
     * @return array Validation result with validity status and details
     *
     * @since 1.0.0
     */
    private function validateRequest(Request $request): array
    {
        $errors = [];
        $securityViolations = [];
        $sanitizedData = [];
        
        // Check request size limits
        $requestSize = strlen($request->getBody()->getContents());
        $request->getBody()->rewind(); // Reset stream for further processing
        
        if ($requestSize > $this->securityLimits['max_request_size']) {
            $errors[] = 'Request size exceeds maximum allowed limit';
            $securityViolations[] = 'oversized_request';
        }
        
        // Get all request data for validation
        $allParameters = $this->getAllRequestParameters($request);
        
        // Check parameter count limits
        if (count($allParameters) > $this->securityLimits['max_request_parameters']) {
            $errors[] = 'Request contains too many parameters';
            $securityViolations[] = 'excessive_parameters';
        }
        
        // Validate and sanitize each parameter
        foreach ($allParameters as $key => $value) {
            $validationResult = $this->validateParameter($key, $value);
            
            if (!$validationResult['valid']) {
                $errors = array_merge($errors, $validationResult['errors']);
                $securityViolations = array_merge($securityViolations, $validationResult['security_violations']);
            }
            
            $sanitizedData[$key] = $validationResult['sanitized_value'];
        }
        
        // Log security violations
        if (!empty($securityViolations)) {
            $GLOBALS['log']->warning('Enhanced validation security violations detected', [
                'violations' => $securityViolations,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                'request_path' => $request->getUri()->getPath(),
                'parameter_count' => count($allParameters),
                'request_size' => $requestSize
            ]);
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'security_violations' => $securityViolations,
            'sanitized_data' => $sanitizedData
        ];
    }
    
    /**
     * Validates and sanitizes individual parameter
     *
     * @param string $key Parameter name
     * @param mixed $value Parameter value
     *
     * @return array Validation result for the parameter
     *
     * @since 1.0.0
     */
    private function validateParameter(string $key, $value): array
    {
        $errors = [];
        $securityViolations = [];
        $sanitizedValue = $value;
        
        // Handle different data types
        if (is_string($value)) {
            $result = $this->validateStringParameter($key, $value);
            $errors = array_merge($errors, $result['errors']);
            $securityViolations = array_merge($securityViolations, $result['security_violations']);
            $sanitizedValue = $result['sanitized_value'];
        } elseif (is_array($value)) {
            $result = $this->validateArrayParameter($key, $value);
            $errors = array_merge($errors, $result['errors']);
            $securityViolations = array_merge($securityViolations, $result['security_violations']);
            $sanitizedValue = $result['sanitized_value'];
        } elseif (is_numeric($value)) {
            $result = $this->validateNumericParameter($key, $value);
            $errors = array_merge($errors, $result['errors']);
            $sanitizedValue = $result['sanitized_value'];
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'security_violations' => $securityViolations,
            'sanitized_value' => $sanitizedValue
        ];
    }
    
    /**
     * Validates string parameters with comprehensive security checks
     *
     * @param string $key Parameter name
     * @param string $value String value to validate
     *
     * @return array String validation result
     *
     * @since 1.0.0
     */
    private function validateStringParameter(string $key, string $value): array
    {
        $errors = [];
        $securityViolations = [];
        
        // Check string length limits
        if (strlen($value) > $this->validationRules['max_string_length']) {
            $errors[] = "Parameter '{$key}' exceeds maximum string length";
            $securityViolations[] = 'oversized_string';
        }
        
        // Check for forbidden patterns (XSS, scripts, etc.)
        foreach ($this->validationRules['forbidden_patterns'] as $pattern) {
            if (preg_match($pattern, $value)) {
                $errors[] = "Parameter '{$key}' contains forbidden content";
                $securityViolations[] = 'forbidden_content';
                break;
            }
        }
        
        // Check for SQL injection patterns
        foreach ($this->validationRules['sql_injection_patterns'] as $pattern) {
            if (preg_match($pattern, $value)) {
                $errors[] = "Parameter '{$key}' contains potentially malicious SQL content";
                $securityViolations[] = 'sql_injection_attempt';
                break;
            }
        }
        
        // Sanitize the string
        $sanitizedValue = $this->sanitizeString($value);
        
        return [
            'errors' => $errors,
            'security_violations' => $securityViolations,
            'sanitized_value' => $sanitizedValue
        ];
    }
    
    /**
     * Validates array parameters with depth and size limits
     *
     * @param string $key Parameter name
     * @param array $value Array value to validate
     * @param int $depth Current nesting depth
     *
     * @return array Array validation result
     *
     * @since 1.0.0
     */
    private function validateArrayParameter(string $key, array $value, int $depth = 1): array
    {
        $errors = [];
        $securityViolations = [];
        $sanitizedValue = [];
        
        // Check array depth limits
        if ($depth > $this->validationRules['max_array_depth']) {
            $errors[] = "Parameter '{$key}' has excessive nesting depth";
            $securityViolations[] = 'excessive_array_depth';
            return [
                'errors' => $errors,
                'security_violations' => $securityViolations,
                'sanitized_value' => []
            ];
        }
        
        // Check array size limits
        if (count($value) > $this->validationRules['max_array_items']) {
            $errors[] = "Parameter '{$key}' contains too many items";
            $securityViolations[] = 'oversized_array';
        }
        
        // Recursively validate array items
        foreach ($value as $itemKey => $itemValue) {
            $fullKey = "{$key}[{$itemKey}]";
            
            if (is_array($itemValue)) {
                $result = $this->validateArrayParameter($fullKey, $itemValue, $depth + 1);
            } else {
                $result = $this->validateParameter($fullKey, $itemValue);
            }
            
            $errors = array_merge($errors, $result['errors']);
            $securityViolations = array_merge($securityViolations, $result['security_violations']);
            $sanitizedValue[$itemKey] = $result['sanitized_value'];
        }
        
        return [
            'errors' => $errors,
            'security_violations' => $securityViolations,
            'sanitized_value' => $sanitizedValue
        ];
    }
    
    /**
     * Validates numeric parameters
     *
     * @param string $key Parameter name
     * @param mixed $value Numeric value to validate
     *
     * @return array Numeric validation result
     *
     * @since 1.0.0
     */
    private function validateNumericParameter(string $key, $value): array
    {
        $errors = [];
        
        // Validate numeric range and format
        if (!is_numeric($value)) {
            $errors[] = "Parameter '{$key}' is not a valid number";
        }
        
        // Sanitize numeric value
        $sanitizedValue = is_float($value) ? (float)$value : (int)$value;
        
        return [
            'errors' => $errors,
            'sanitized_value' => $sanitizedValue
        ];
    }
    
    /**
     * Sanitizes string input for security
     *
     * @param string $value String to sanitize
     *
     * @return string Sanitized string
     *
     * @since 1.0.0
     */
    private function sanitizeString(string $value): string
    {
        // Remove null bytes
        $value = str_replace("\0", '', $value);
        
        // Trim whitespace
        $value = trim($value);
        
        // Strip or escape potentially dangerous HTML
        $value = strip_tags($value, $this->validationRules['allowed_html_tags']);
        
        // Encode special characters
        $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        return $value;
    }
    
    /**
     * Gets all request parameters from different sources
     *
     * @param Request $request HTTP request object
     *
     * @return array Combined request parameters
     *
     * @since 1.0.0
     */
    private function getAllRequestParameters(Request $request): array
    {
        $parameters = [];
        
        // Get route parameters
        $route = $request->getAttribute('route');
        if ($route) {
            $parameters = array_merge($parameters, $route->getArguments());
        }
        
        // Get query parameters
        $parameters = array_merge($parameters, $request->getQueryParams());
        
        // Get body parameters
        $parsedBody = $request->getParsedBody();
        if (is_array($parsedBody)) {
            $parameters = array_merge($parameters, $parsedBody);
        }
        
        return $parameters;
    }
    
    /**
     * Creates validation error response
     *
     * @param Response $response HTTP response object
     * @param array $errors Validation errors
     * @param array $securityViolations Security violations
     *
     * @return Response Error response with validation details
     *
     * @since 1.0.0
     */
    private function createValidationErrorResponse(
        Response $response,
        array $errors,
        array $securityViolations
    ): Response {
        // Create enhanced error response
        $errorResponse = new ErrorResponse();
        $errorResponse->setStatus(400);
        $errorResponse->setTitle('Input Validation Failed');
        $errorResponse->setDetail('The request contains invalid or potentially malicious input data.');
        
        // Enhanced error data with validation details
        $errorData = [
            'errors' => [[
                'status' => '400',
                'title' => 'Input Validation Failed',
                'detail' => 'The request contains invalid or potentially malicious input data.',
                'meta' => [
                    'validation_errors' => $errors,
                    'security_violations' => $securityViolations,
                    'timestamp' => date('c'),
                    'request_id' => uniqid('req_', true)
                ]
            ]]
        ];
        
        return $response
            ->withStatus(400)
            ->withHeader('Content-Type', 'application/vnd.api+json')
            ->write(json_encode($errorData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
    
    /**
     * Checks if path is exempt from enhanced validation
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
