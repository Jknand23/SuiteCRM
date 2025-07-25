<?php
/**
 * @fileoverview Request Logging and Monitoring Middleware for SuiteCRM V8 API
 *
 * Provides comprehensive request logging, performance monitoring, and security
 * analysis for all API requests. Integrates with existing SuiteCRM logging
 * infrastructure while adding enhanced monitoring capabilities for API
 * performance and security tracking.
 *
 * Key Features:
 * - Comprehensive request and response logging
 * - Performance monitoring with timing and memory usage
 * - Security event tracking and anomaly detection
 * - Integration with existing SuiteCRM logging infrastructure
 * - Configurable logging levels and filtering
 * - Request correlation IDs for tracing
 * - Structured logging for analysis and monitoring
 *
 * Dependencies:
 * - Existing Slim 3 middleware infrastructure
 * - SuiteCRM logging infrastructure (LoggerManager, $GLOBALS['log'])
 * - Existing session management for user tracking
 *
 * @package SuiteCRM\Api\V8\Middleware
 * @copyright Copyright (C) 2011 - 2024 SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 * @since 1.0.0
 */

namespace Api\V8\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;
use SuiteCRM\Log\EnhancedLoggerService;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * RequestLoggingMiddleware
 *
 * Comprehensive request logging and monitoring middleware that provides
 * detailed insights into API usage, performance, and security events.
 */
class RequestLoggingMiddleware
{
    /** @var bool $enabled Whether request logging is enabled */
    private bool $enabled;
    
    /** @var string $logLevel Logging level for requests */
    private string $logLevel;
    
    /** @var array $securityEvents Security events to monitor */
    private array $securityEvents;
    
    /** @var array $performanceThresholds Performance monitoring thresholds */
    private array $performanceThresholds;
    
    /** @var array $exemptPaths Paths exempt from detailed logging */
    private array $exemptPaths;
    
    /** @var bool $logRequestBody Whether to log request body data */
    private bool $logRequestBody;
    
    /** @var bool $logResponseBody Whether to log response body data */
    private bool $logResponseBody;
    
    /** @var int $maxBodyLogSize Maximum size of body to log */
    private int $maxBodyLogSize;
    
    /** @var EnhancedLoggerService|null $enhancedLogger Enhanced logging service instance */
    private ?EnhancedLoggerService $enhancedLogger = null;
    
    /** @var bool $useEnhancedLogging Whether to use enhanced logging features */
    private bool $useEnhancedLogging;
    
    /**
     * RequestLoggingMiddleware constructor
     *
     * Initializes request logging configuration with comprehensive monitoring.
     */
    public function __construct()
    {
        global $sugar_config;
        
        // Load request logging configuration
        $loggingConfig = $sugar_config['request_logging'] ?? [];
        
        $this->enabled = $loggingConfig['enabled'] ?? true;
        $this->logLevel = $loggingConfig['log_level'] ?? 'info';
        $this->logRequestBody = $loggingConfig['log_request_body'] ?? false;
        $this->logResponseBody = $loggingConfig['log_response_body'] ?? false;
        $this->maxBodyLogSize = $loggingConfig['max_body_log_size'] ?? 4096; // 4KB default
        
        // Paths exempt from detailed logging (to reduce noise)
        $this->exemptPaths = $loggingConfig['exempt_paths'] ?? [
            '/V8/docs',      // Documentation interface
            '/access_token', // OAuth2 token endpoint (has its own logging)
        ];
        
        // Security events to monitor
        $this->securityEvents = array_merge([
            'authentication_failure',
            'authorization_failure',
            'rate_limit_exceeded',
            'validation_failure',
            'suspicious_patterns',
            'error_threshold_exceeded'
        ], $loggingConfig['security_events'] ?? []);
        
        // Performance monitoring thresholds
        $this->performanceThresholds = array_merge([
            'response_time_warning' => 1000,   // 1 second
            'response_time_critical' => 5000,  // 5 seconds
            'memory_usage_warning' => 50485760, // 50MB
            'memory_usage_critical' => 104857600, // 100MB
            'error_rate_warning' => 5,          // 5% error rate
            'error_rate_critical' => 10         // 10% error rate
        ], $loggingConfig['performance_thresholds'] ?? []);
        
        // Initialize enhanced logging if configured
        $this->useEnhancedLogging = $loggingConfig['use_enhanced_logging'] ?? true;
        
        if ($this->useEnhancedLogging) {
            try {
                $this->enhancedLogger = new EnhancedLoggerService();
            } catch (\Exception $e) {
                // Fallback to legacy logging if enhanced logger fails
                $this->useEnhancedLogging = false;
                $GLOBALS['log']->warning('Enhanced logging initialization failed, falling back to legacy logging', [
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        if ($this->enabled) {
            $initData = [
                'log_level' => $this->logLevel,
                'log_request_body' => $this->logRequestBody,
                'log_response_body' => $this->logResponseBody,
                'max_body_log_size' => $this->maxBodyLogSize,
                'exempt_paths_count' => count($this->exemptPaths),
                'security_events_count' => count($this->securityEvents),
                'performance_monitoring' => true,
                'enhanced_logging' => $this->useEnhancedLogging
            ];
            
            if ($this->useEnhancedLogging && $this->enhancedLogger) {
                $this->enhancedLogger->logStructured('info', 'Request logging middleware initialized', $initData, [
                    'component' => 'api_middleware',
                    'middleware_type' => 'request_logging'
                ]);
            } else {
                $GLOBALS['log']->info('Request logging middleware initialized', $initData);
            }
        } else {
            if ($this->useEnhancedLogging && $this->enhancedLogger) {
                $this->enhancedLogger->logStructured('info', 'Request logging middleware disabled', [], [
                    'component' => 'api_middleware',
                    'middleware_type' => 'request_logging'
                ]);
            } else {
                $GLOBALS['log']->info('Request logging middleware disabled');
            }
        }
    }
    
    /**
     * Middleware invocation handler
     *
     * Logs comprehensive request and response data with performance monitoring.
     *
     * @param Request $request HTTP request object
     * @param Response $response HTTP response object
     * @param callable $next Next middleware in chain
     *
     * @return Response Response object with logged metrics
     *
     * @since 1.0.0
     */
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        // Skip request logging if disabled
        if (!$this->enabled) {
            return $next($request, $response);
        }
        
        // Generate unique request ID for correlation
        $requestId = $this->generateRequestId();
        $request = $request->withAttribute('request_id', $requestId);
        
        // Start enhanced performance monitoring if available
        $timingId = null;
        if ($this->useEnhancedLogging && $this->enhancedLogger) {
            $timingId = $this->enhancedLogger->startTiming('api_request', [
                'method' => $request->getMethod(),
                'path' => $request->getUri()->getPath(),
                'request_id' => $requestId
            ]);
        }
        
        // Record request start time and memory for legacy monitoring
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);
        
        // Log incoming request
        $this->logIncomingRequest($request, $requestId);
        
        // Process request through middleware chain
        $response = $next($request, $response);
        
        // Calculate performance metrics
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        $responseTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        $memoryUsage = $endMemory - $startMemory;
        $peakMemory = memory_get_peak_usage(true);
        
        // Stop enhanced performance monitoring and log
        if ($timingId && $this->useEnhancedLogging && $this->enhancedLogger) {
            $this->enhancedLogger->stopTiming($timingId, [
                'status_code' => $response->getStatusCode(),
                'response_size' => $response->getHeaderLine('Content-Length') ?: strlen((string)$response->getBody()),
                'is_error' => $response->getStatusCode() >= 400
            ]);
        }
        
        // Log outgoing response with metrics
        $this->logOutgoingResponse($request, $response, $requestId, [
            'response_time_ms' => $responseTime,
            'memory_usage_bytes' => $memoryUsage,
            'peak_memory_bytes' => $peakMemory,
            'start_time' => $startTime,
            'end_time' => $endTime
        ]);
        
        // Monitor performance thresholds
        $this->monitorPerformance($requestId, $responseTime, $memoryUsage, $peakMemory);
        
        // Add logging headers to response for debugging
        $response = $this->addLoggingHeaders($response, $requestId, $responseTime);
        
        return $response;
    }
    
    /**
     * Get enhanced logger service instance
     *
     * Provides access to the enhanced logger service for other components
     * that need advanced logging capabilities.
     *
     * @return EnhancedLoggerService|null Enhanced logger service or null if not available
     *
     * @since 1.0.0
     */
    public function getEnhancedLogger(): ?EnhancedLoggerService
    {
        return $this->useEnhancedLogging ? $this->enhancedLogger : null;
    }
    
    /**
     * Check if enhanced logging is enabled and available
     *
     * @return bool True if enhanced logging is enabled and functional
     *
     * @since 1.0.0
     */
    public function isEnhancedLoggingEnabled(): bool
    {
        return $this->useEnhancedLogging && $this->enhancedLogger !== null;
    }
    
    /**
     * Logs incoming request details
     *
     * @param Request $request HTTP request object
     * @param string $requestId Request correlation ID
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function logIncomingRequest(Request $request, string $requestId): void
    {
        $requestPath = $request->getUri()->getPath();
        
        // Skip detailed logging for exempt paths
        if ($this->isPathExempt($requestPath)) {
            return;
        }
        
        $logData = [
            'request_id' => $requestId,
            'method' => $request->getMethod(),
            'uri' => (string)$request->getUri(),
            'path' => $requestPath,
            'query_params' => $request->getQueryParams(),
            'headers' => $this->filterSensitiveHeaders($request->getHeaders()),
            'ip_address' => $this->getClientIpAddress($request),
            'user_agent' => $request->getHeaderLine('User-Agent'),
            'content_type' => $request->getHeaderLine('Content-Type'),
            'content_length' => $request->getHeaderLine('Content-Length'),
            'timestamp' => date('c'),
            'user_context' => $this->getUserContext()
        ];
        
        // Add request body if configured and safe to log
        if ($this->logRequestBody && $this->shouldLogBody($request)) {
            $logData['request_body'] = $this->getLoggableBody($request->getBody()->getContents());
            $request->getBody()->rewind(); // Reset stream for further processing
        }
        
        // Use enhanced logging if available, otherwise fallback to legacy
        if ($this->useEnhancedLogging && $this->enhancedLogger) {
            $this->enhancedLogger->logStructured($this->logLevel, 'API Request Received', $logData, [
                'component' => 'api_middleware',
                'event_type' => 'request_received',
                'request_flow' => 'incoming'
            ]);
        } else {
            $GLOBALS['log']->{$this->logLevel}('API Request Received', $logData);
        }
    }
    
    /**
     * Logs outgoing response details with performance metrics
     *
     * @param Request $request HTTP request object
     * @param Response $response HTTP response object
     * @param string $requestId Request correlation ID
     * @param array $metrics Performance metrics
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function logOutgoingResponse(
        Request $request,
        Response $response,
        string $requestId,
        array $metrics
    ): void {
        $requestPath = $request->getUri()->getPath();
        
        // Skip detailed logging for exempt paths
        if ($this->isPathExempt($requestPath)) {
            return;
        }
        
        $statusCode = $response->getStatusCode();
        $isError = $statusCode >= 400;
        $logLevel = $isError ? 'warning' : $this->logLevel;
        
        $logData = [
            'request_id' => $requestId,
            'method' => $request->getMethod(),
            'path' => $requestPath,
            'status_code' => $statusCode,
            'status_reason' => $response->getReasonPhrase(),
            'response_headers' => $this->filterSensitiveHeaders($response->getHeaders()),
            'response_time_ms' => $metrics['response_time_ms'],
            'memory_usage_bytes' => $metrics['memory_usage_bytes'],
            'peak_memory_bytes' => $metrics['peak_memory_bytes'],
            'timestamp' => date('c'),
            'is_error' => $isError,
            'user_context' => $this->getUserContext()
        ];
        
        // Add response body if configured and safe to log
        if ($this->logResponseBody && $this->shouldLogResponseBody($response)) {
            $responseBody = (string)$response->getBody();
            $logData['response_body'] = $this->getLoggableBody($responseBody);
        }
        
        // Add error context for failed requests
        if ($isError) {
            $logData['error_context'] = [
                'client_ip' => $this->getClientIpAddress($request),
                'user_agent' => $request->getHeaderLine('User-Agent'),
                'referer' => $request->getHeaderLine('Referer')
            ];
        }
        
        // Use enhanced logging if available, otherwise fallback to legacy
        if ($this->useEnhancedLogging && $this->enhancedLogger) {
            $this->enhancedLogger->logStructured($logLevel, 'API Response Sent', $logData, [
                'component' => 'api_middleware',
                'event_type' => 'response_sent',
                'request_flow' => 'outgoing',
                'response_category' => $isError ? 'error' : 'success'
            ]);
        } else {
            $GLOBALS['log']->{$logLevel}('API Response Sent', $logData);
        }
        
        // Log security events for suspicious activity
        if ($isError) {
            $this->checkSecurityEvents($request, $response, $requestId);
        }
    }
    
    /**
     * Monitors performance thresholds and logs warnings
     *
     * @param string $requestId Request correlation ID
     * @param float $responseTime Response time in milliseconds
     * @param int $memoryUsage Memory usage in bytes
     * @param int $peakMemory Peak memory usage in bytes
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function monitorPerformance(
        string $requestId,
        float $responseTime,
        int $memoryUsage,
        int $peakMemory
    ): void {
        $warnings = [];
        
        // Check response time thresholds
        if ($responseTime > $this->performanceThresholds['response_time_critical']) {
            $warnings[] = 'critical_response_time';
            $alertData = [
                'request_id' => $requestId,
                'response_time_ms' => $responseTime,
                'threshold_ms' => $this->performanceThresholds['response_time_critical']
            ];
            
            if ($this->useEnhancedLogging && $this->enhancedLogger) {
                $this->enhancedLogger->logStructured('error', 'Critical API response time detected', $alertData, [
                    'component' => 'api_middleware',
                    'event_type' => 'performance_alert',
                    'alert_type' => 'critical_response_time',
                    'severity' => 'critical'
                ]);
            } else {
                $GLOBALS['log']->error('Critical API response time detected', $alertData);
            }
        } elseif ($responseTime > $this->performanceThresholds['response_time_warning']) {
            $warnings[] = 'slow_response_time';
            $alertData = [
                'request_id' => $requestId,
                'response_time_ms' => $responseTime,
                'threshold_ms' => $this->performanceThresholds['response_time_warning']
            ];
            
            if ($this->useEnhancedLogging && $this->enhancedLogger) {
                $this->enhancedLogger->logStructured('warning', 'Slow API response time detected', $alertData, [
                    'component' => 'api_middleware',
                    'event_type' => 'performance_alert',
                    'alert_type' => 'slow_response_time',
                    'severity' => 'warning'
                ]);
            } else {
                $GLOBALS['log']->warning('Slow API response time detected', $alertData);
            }
        }
        
        // Check memory usage thresholds
        if ($peakMemory > $this->performanceThresholds['memory_usage_critical']) {
            $warnings[] = 'critical_memory_usage';
            $alertData = [
                'request_id' => $requestId,
                'peak_memory_bytes' => $peakMemory,
                'threshold_bytes' => $this->performanceThresholds['memory_usage_critical']
            ];
            
            if ($this->useEnhancedLogging && $this->enhancedLogger) {
                $this->enhancedLogger->logStructured('error', 'Critical API memory usage detected', $alertData, [
                    'component' => 'api_middleware',
                    'event_type' => 'performance_alert',
                    'alert_type' => 'critical_memory_usage',
                    'severity' => 'critical'
                ]);
            } else {
                $GLOBALS['log']->error('Critical API memory usage detected', $alertData);
            }
        } elseif ($peakMemory > $this->performanceThresholds['memory_usage_warning']) {
            $warnings[] = 'high_memory_usage';
            $alertData = [
                'request_id' => $requestId,
                'peak_memory_bytes' => $peakMemory,
                'threshold_bytes' => $this->performanceThresholds['memory_usage_warning']
            ];
            
            if ($this->useEnhancedLogging && $this->enhancedLogger) {
                $this->enhancedLogger->logStructured('warning', 'High API memory usage detected', $alertData, [
                    'component' => 'api_middleware',
                    'event_type' => 'performance_alert',
                    'alert_type' => 'high_memory_usage',
                    'severity' => 'warning'
                ]);
            } else {
                $GLOBALS['log']->warning('High API memory usage detected', $alertData);
            }
        }
        
        // Log performance summary if any warnings
        if (!empty($warnings)) {
            $GLOBALS['log']->warning('API performance warnings detected', [
                'request_id' => $requestId,
                'warnings' => $warnings,
                'metrics' => [
                    'response_time_ms' => $responseTime,
                    'memory_usage_bytes' => $memoryUsage,
                    'peak_memory_bytes' => $peakMemory
                ]
            ]);
        }
    }
    
    /**
     * Checks for security events and logs them
     *
     * @param Request $request HTTP request object
     * @param Response $response HTTP response object
     * @param string $requestId Request correlation ID
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function checkSecurityEvents(Request $request, Response $response, string $requestId): void
    {
        $statusCode = $response->getStatusCode();
        $securityEvents = [];
        
        // Authentication failures
        if ($statusCode === 401) {
            $securityEvents[] = 'authentication_failure';
        }
        
        // Authorization failures
        if ($statusCode === 403) {
            $securityEvents[] = 'authorization_failure';
        }
        
        // Rate limiting
        if ($statusCode === 429) {
            $securityEvents[] = 'rate_limit_exceeded';
        }
        
        // Validation failures
        if ($statusCode === 400) {
            $securityEvents[] = 'validation_failure';
        }
        
        // Log security events
        if (!empty($securityEvents)) {
            $GLOBALS['log']->warning('API security events detected', [
                'request_id' => $requestId,
                'security_events' => $securityEvents,
                'status_code' => $statusCode,
                'method' => $request->getMethod(),
                'path' => $request->getUri()->getPath(),
                'ip_address' => $this->getClientIpAddress($request),
                'user_agent' => $request->getHeaderLine('User-Agent'),
                'user_context' => $this->getUserContext()
            ]);
        }
    }
    
    /**
     * Gets client IP address considering proxy headers
     *
     * @param Request $request HTTP request object
     *
     * @return string Client IP address
     *
     * @since 1.0.0
     */
    private function getClientIpAddress(Request $request): string
    {
        $serverParams = $request->getServerParams();
        
        // Check proxy headers in order of preference
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
     * Gets current user context for logging
     *
     * @return array User context data
     *
     * @since 1.0.0
     */
    private function getUserContext(): array
    {
        $context = [
            'authenticated' => false,
            'user_id' => null,
            'user_name' => null
        ];
        
        // Check for authenticated user
        if (isset($GLOBALS['current_user']) && !empty($GLOBALS['current_user']->id)) {
            $context = [
                'authenticated' => true,
                'user_id' => $GLOBALS['current_user']->id,
                'user_name' => $GLOBALS['current_user']->user_name ?? 'unknown'
            ];
        } elseif (!empty($_SESSION['authenticated_user_id'])) {
            $context = [
                'authenticated' => true,
                'user_id' => $_SESSION['authenticated_user_id'],
                'user_name' => $_SESSION['authenticated_user_name'] ?? 'unknown'
            ];
        }
        
        return $context;
    }
    
    /**
     * Filters sensitive headers from logging
     *
     * @param array $headers Headers array
     *
     * @return array Filtered headers
     *
     * @since 1.0.0
     */
    private function filterSensitiveHeaders(array $headers): array
    {
        $sensitiveHeaders = [
            'authorization',
            'cookie',
            'x-api-key',
            'x-auth-token'
        ];
        
        $filtered = [];
        foreach ($headers as $name => $values) {
            if (in_array(strtolower($name), $sensitiveHeaders)) {
                $filtered[$name] = ['[FILTERED]'];
            } else {
                $filtered[$name] = $values;
            }
        }
        
        return $filtered;
    }
    
    /**
     * Determines if request body should be logged
     *
     * @param Request $request HTTP request object
     *
     * @return bool True if body should be logged
     *
     * @since 1.0.0
     */
    private function shouldLogBody(Request $request): bool
    {
        $contentType = $request->getHeaderLine('Content-Type');
        $contentLength = (int)$request->getHeaderLine('Content-Length');
        
        // Don't log large payloads
        if ($contentLength > $this->maxBodyLogSize) {
            return false;
        }
        
        // Don't log binary content
        if (strpos($contentType, 'multipart/form-data') !== false ||
            strpos($contentType, 'application/octet-stream') !== false ||
            strpos($contentType, 'image/') !== false ||
            strpos($contentType, 'video/') !== false ||
            strpos($contentType, 'audio/') !== false) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Determines if response body should be logged
     *
     * @param Response $response HTTP response object
     *
     * @return bool True if body should be logged
     *
     * @since 1.0.0
     */
    private function shouldLogResponseBody(Response $response): bool
    {
        $contentType = $response->getHeaderLine('Content-Type');
        $bodySize = $response->getBody()->getSize();
        
        // Don't log large responses
        if ($bodySize && $bodySize > $this->maxBodyLogSize) {
            return false;
        }
        
        // Only log JSON responses
        return strpos($contentType, 'application/json') !== false ||
               strpos($contentType, 'application/vnd.api+json') !== false;
    }
    
    /**
     * Gets loggable body content with size limits
     *
     * @param string $body Body content
     *
     * @return string Truncated body content
     *
     * @since 1.0.0
     */
    private function getLoggableBody(string $body): string
    {
        if (strlen($body) > $this->maxBodyLogSize) {
            return substr($body, 0, $this->maxBodyLogSize) . '... [TRUNCATED]';
        }
        
        return $body;
    }
    
    /**
     * Adds logging headers to response for debugging
     *
     * @param Response $response HTTP response object
     * @param string $requestId Request correlation ID
     * @param float $responseTime Response time in milliseconds
     *
     * @return Response Response with logging headers
     *
     * @since 1.0.0
     */
    private function addLoggingHeaders(Response $response, string $requestId, float $responseTime): Response
    {
        return $response
            ->withHeader('X-Request-ID', $requestId)
            ->withHeader('X-Response-Time', number_format($responseTime, 2) . 'ms')
            ->withHeader('X-Memory-Peak', number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB');
    }
    
    /**
     * Generates unique request correlation ID
     *
     * @return string Request ID
     *
     * @since 1.0.0
     */
    private function generateRequestId(): string
    {
        return uniqid('req_', true) . '_' . bin2hex(random_bytes(4));
    }
    
    /**
     * Checks if path is exempt from detailed logging
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
