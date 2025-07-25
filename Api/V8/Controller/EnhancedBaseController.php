<?php
/**
 * @fileoverview Enhanced Base Controller for SuiteCRM V8 API
 *
 * Extends the existing BaseController with enhanced response standardization,
 * improved error handling, and comprehensive logging. Maintains full backward
 * compatibility while adding enhanced features for consistent API responses
 * and better error tracking across all endpoints.
 *
 * Key Features:
 * - Standardized JSON:API response format across all endpoints
 * - Enhanced error response handling with correlation IDs
 * - Integration with enhanced error response and logging systems
 * - Backward compatibility with existing BaseController patterns
 * - Comprehensive response metadata and timing information
 * - Consistent response headers and content type handling
 *
 * Dependencies:
 * - Existing BaseController class for compatibility
 * - EnhancedErrorResponse for improved error handling
 * - Existing JSON:API specification compliance
 * - SuiteCRM logging infrastructure integration
 *
 * @package SuiteCRM\Api\V8\Controller
 * @copyright Copyright (C) 2011 - 2024 SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 * @since 1.0.0
 */

namespace Api\V8\Controller;

use Api\V8\JsonApi\Response\ErrorResponse;
use Api\V8\JsonApi\Response\EnhancedErrorResponse;
use Slim\Http\Response as HttpResponse;
use Exception;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * EnhancedBaseController
 *
 * Enhanced base controller that provides standardized response formatting,
 * improved error handling, and comprehensive logging while maintaining
 * full backward compatibility with existing BaseController patterns.
 */
#[\AllowDynamicProperties]
abstract class EnhancedBaseController extends BaseController
{
    /** @var array $responseMetadata Additional metadata for responses */
    protected array $responseMetadata = [];
    
    /** @var bool $enableEnhancedLogging Whether to enable enhanced logging features */
    protected bool $enableEnhancedLogging = true;
    
    /** @var string $requestId Current request correlation ID */
    protected string $requestId;
    
    /**
     * Enhanced response generation with standardized formatting
     *
     * Generates consistent JSON:API responses with enhanced metadata,
     * performance information, and standardized headers while maintaining
     * backward compatibility with existing response patterns.
     *
     * @param HttpResponse $httpResponse HTTP response object
     * @param mixed $response Response data
     * @param integer $status HTTP status code
     * @param array $metadata Additional response metadata
     *
     * @return HttpResponse Enhanced standardized response
     *
     * @since 1.0.0
     */
    public function generateEnhancedResponse(
        HttpResponse $httpResponse,
        $response,
        $status,
        array $metadata = []
    ): HttpResponse {
        // Get request ID from request attributes if available
        $this->requestId = $this->getRequestId($httpResponse);
        
        // Enhance response data with standardized structure
        $enhancedResponse = $this->standardizeResponseFormat($response, $status, $metadata);
        
        // Add response metadata
        $enhancedResponse = $this->addResponseMetadata($enhancedResponse, $status);
        
        // Generate response using parent method with enhancements
        $finalResponse = $this->generateResponse($httpResponse, $enhancedResponse, $status);
        
        // Add enhanced headers
        $finalResponse = $this->addEnhancedHeaders($finalResponse, $status);
        
        // Log response if enhanced logging is enabled
        if ($this->enableEnhancedLogging) {
            $this->logResponse($enhancedResponse, $status);
        }
        
        return $finalResponse;
    }
    
    /**
     * Enhanced error response generation with comprehensive error handling
     *
     * Creates detailed error responses with correlation IDs, enhanced logging,
     * and structured error information while maintaining JSON:API compliance.
     *
     * @param HttpResponse $httpResponse HTTP response object
     * @param Exception $exception Exception that caused the error
     * @param integer $status HTTP status code
     * @param array $additionalContext Additional error context
     * @param string $severity Error severity level
     *
     * @return HttpResponse Enhanced error response
     *
     * @since 1.0.0
     */
    public function generateEnhancedErrorResponse(
        HttpResponse $httpResponse,
        Exception $exception,
        $status,
        array $additionalContext = [],
        string $severity = 'medium'
    ): HttpResponse {
        // Get request ID
        $this->requestId = $this->getRequestId($httpResponse);
        
        // Create enhanced error response
        $errorResponse = new EnhancedErrorResponse(null, $this->requestId);
        $errorResponse->setStatus($status);
        $errorResponse->setTitle($this->getErrorTitle($status, $exception));
        $errorResponse->setDetail($exception->getMessage());
        
        // Set enhanced error context
        $errorResponse->setErrorContext($exception, $additionalContext, $severity);
        
        // Add custom metadata if available
        if (!empty($this->responseMetadata)) {
            $errorResponse->setMetadata($this->responseMetadata);
        }
        
        // Generate response using parent method
        $finalResponse = $this->generateResponse($httpResponse, $errorResponse, $status);
        
        // Add enhanced headers for error responses
        $finalResponse = $this->addEnhancedHeaders($finalResponse, $status, true);
        
        return $finalResponse;
    }
    
    /**
     * Backward-compatible enhanced response method
     *
     * Maintains full compatibility with existing generateResponse calls while
     * adding enhanced features when possible.
     *
     * @param HttpResponse $httpResponse HTTP response object
     * @param mixed $response Response data
     * @param integer $status HTTP status code
     *
     * @return HttpResponse Enhanced response maintaining compatibility
     *
     * @since 1.0.0
     */
    public function generateResponse(
        HttpResponse $httpResponse,
        $response,
        $status
    ): HttpResponse {
        // Check if response is already an ErrorResponse instance
        if ($response instanceof ErrorResponse) {
            // Handle existing error responses
            return parent::generateResponse($httpResponse, $response, $status);
        }
        
        // For regular responses, add minimal enhancements if possible
        if (is_array($response) || is_object($response)) {
            return $this->generateEnhancedResponse($httpResponse, $response, $status);
        }
        
        // Fall back to parent implementation for simple responses
        return parent::generateResponse($httpResponse, $response, $status);
    }
    
    /**
     * Backward-compatible enhanced error response method
     *
     * @param HttpResponse $httpResponse HTTP response object
     * @param Exception $exception Exception that caused the error
     * @param integer $status HTTP status code
     *
     * @return HttpResponse Enhanced error response
     *
     * @since 1.0.0
     */
    public function generateErrorResponse(HttpResponse $httpResponse, Exception $exception, $status): HttpResponse
    {
        return $this->generateEnhancedErrorResponse($httpResponse, $exception, $status);
    }
    
    /**
     * Standardizes response format according to JSON:API specification
     *
     * @param mixed $response Original response data
     * @param integer $status HTTP status code
     * @param array $metadata Additional metadata
     *
     * @return array Standardized response format
     *
     * @since 1.0.0
     */
    private function standardizeResponseFormat($response, int $status, array $metadata): array
    {
        // If response is already in JSON:API format, preserve it
        if (is_array($response) && (isset($response['data']) || isset($response['errors']))) {
            return $this->enhanceExistingJsonApiResponse($response, $status, $metadata);
        }
        
        // Standardize simple responses to JSON:API format
        $standardized = [
            'data' => $response,
            'meta' => array_merge([
                'status' => $status,
                'timestamp' => date('c'),
                'api_version' => '8.0'
            ], $metadata)
        ];
        
        return $standardized;
    }
    
    /**
     * Enhances existing JSON:API responses with additional metadata
     *
     * @param array $response Existing JSON:API response
     * @param integer $status HTTP status code
     * @param array $metadata Additional metadata
     *
     * @return array Enhanced JSON:API response
     *
     * @since 1.0.0
     */
    private function enhanceExistingJsonApiResponse(array $response, int $status, array $metadata): array
    {
        // Ensure meta object exists
        if (!isset($response['meta'])) {
            $response['meta'] = [];
        }
        
        // Add standard metadata
        $response['meta'] = array_merge($response['meta'], [
            'status' => $status,
            'timestamp' => date('c'),
            'api_version' => '8.0'
        ], $metadata);
        
        return $response;
    }
    
    /**
     * Adds comprehensive response metadata
     *
     * @param array $response Response data
     * @param integer $status HTTP status code
     *
     * @return array Response with added metadata
     *
     * @since 1.0.0
     */
    private function addResponseMetadata(array $response, int $status): array
    {
        // Add request correlation ID if available
        if (!empty($this->requestId)) {
            $response['meta']['request_id'] = $this->requestId;
        }
        
        // Add performance metadata
        $response['meta']['performance'] = [
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true),
            'execution_time' => microtime(true) - ($_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true))
        ];
        
        // Add server metadata
        $response['meta']['server'] = [
            'php_version' => PHP_VERSION,
            'suite_version' => $GLOBALS['sugar_version'] ?? 'unknown'
        ];
        
        // Add user context if available
        if (isset($GLOBALS['current_user']) && !empty($GLOBALS['current_user']->id)) {
            $response['meta']['user'] = [
                'id' => $GLOBALS['current_user']->id,
                'authenticated' => true
            ];
        }
        
        // Add custom metadata from controller
        if (!empty($this->responseMetadata)) {
            $response['meta']['custom'] = $this->responseMetadata;
        }
        
        return $response;
    }
    
    /**
     * Adds enhanced headers to response
     *
     * @param HttpResponse $response HTTP response object
     * @param integer $status HTTP status code
     * @param bool $isError Whether this is an error response
     *
     * @return HttpResponse Response with enhanced headers
     *
     * @since 1.0.0
     */
    private function addEnhancedHeaders(HttpResponse $response, int $status, bool $isError = false): HttpResponse
    {
        // Add request correlation ID header
        if (!empty($this->requestId)) {
            $response = $response->withHeader('X-Request-ID', $this->requestId);
        }
        
        // Add API version header
        $response = $response->withHeader('X-API-Version', '8.0');
        
        // Add timestamp header
        $response = $response->withHeader('X-Response-Time', date('c'));
        
        // Add performance headers
        $response = $response->withHeader('X-Memory-Usage', number_format(memory_get_usage(true) / 1024 / 1024, 2) . 'MB');
        $response = $response->withHeader('X-Peak-Memory', number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB');
        
        // Add security headers for error responses
        if ($isError) {
            $response = $response->withHeader('X-Error-ID', $this->requestId);
            $response = $response->withHeader('X-Error-Timestamp', date('c'));
        }
        
        // Add caching headers based on status
        if ($status >= 200 && $status < 300) {
            $response = $response->withHeader('Cache-Control', 'no-cache, must-revalidate');
        } elseif ($status >= 400) {
            $response = $response->withHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        }
        
        return $response;
    }
    
    /**
     * Gets appropriate error title based on status and exception
     *
     * @param integer $status HTTP status code
     * @param Exception $exception Exception instance
     *
     * @return string Error title
     *
     * @since 1.0.0
     */
    private function getErrorTitle(int $status, Exception $exception): string
    {
        $titles = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            409 => 'Conflict',
            422 => 'Unprocessable Entity',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable'
        ];
        
        return $titles[$status] ?? 'API Error';
    }
    
    /**
     * Gets request correlation ID from various sources
     *
     * @param HttpResponse $httpResponse HTTP response object
     *
     * @return string Request correlation ID
     *
     * @since 1.0.0
     */
    private function getRequestId(HttpResponse $httpResponse): string
    {
        // Try to get from existing response headers
        $requestId = $httpResponse->getHeaderLine('X-Request-ID');
        
        // Try to get from request attributes (set by middleware)
        if (empty($requestId) && isset($GLOBALS['app'])) {
            $request = $GLOBALS['app']->getContainer()->get('request');
            if ($request && method_exists($request, 'getAttribute')) {
                $requestId = $request->getAttribute('request_id', '');
            }
        }
        
        // Generate new ID if none found
        if (empty($requestId)) {
            $requestId = uniqid('req_', true) . '_' . bin2hex(random_bytes(4));
        }
        
        return $requestId;
    }
    
    /**
     * Logs response information if enhanced logging is enabled
     *
     * @param array $response Response data
     * @param integer $status HTTP status code
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function logResponse(array $response, int $status): void
    {
        if (!$this->enableEnhancedLogging) {
            return;
        }
        
        $logLevel = $status >= 400 ? 'warning' : 'debug';
        $isError = $status >= 400;
        
        $logData = [
            'request_id' => $this->requestId,
            'status_code' => $status,
            'response_size' => strlen(json_encode($response)),
            'has_data' => isset($response['data']),
            'has_errors' => isset($response['errors']),
            'metadata_keys' => array_keys($response['meta'] ?? []),
            'is_error' => $isError,
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true)
        ];
        
        $message = $isError ? 'Enhanced error response generated' : 'Enhanced response generated';
        
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->{$logLevel}($message, $logData);
        }
    }
    
    /**
     * Sets custom response metadata
     *
     * @param array $metadata Metadata to add to responses
     *
     * @return self Fluent interface
     *
     * @since 1.0.0
     */
    protected function setResponseMetadata(array $metadata): self
    {
        $this->responseMetadata = array_merge($this->responseMetadata, $metadata);
        return $this;
    }
    
    /**
     * Adds single metadata entry
     *
     * @param string $key Metadata key
     * @param mixed $value Metadata value
     *
     * @return self Fluent interface
     *
     * @since 1.0.0
     */
    protected function addResponseMetadata(string $key, $value): self
    {
        $this->responseMetadata[$key] = $value;
        return $this;
    }
    
    /**
     * Enables or disables enhanced logging
     *
     * @param bool $enabled Whether to enable enhanced logging
     *
     * @return self Fluent interface
     *
     * @since 1.0.0
     */
    protected function setEnhancedLogging(bool $enabled): self
    {
        $this->enableEnhancedLogging = $enabled;
        return $this;
    }
}
