<?php
/**
 * @fileoverview Enhanced Error Response for SuiteCRM V8 API
 *
 * Extends the existing ErrorResponse class with comprehensive error handling,
 * enhanced logging, structured error data, and improved debugging capabilities.
 * Maintains full compatibility with existing JSON:API error format while
 * adding enhanced features for better error tracking and resolution.
 *
 * Key Features:
 * - Enhanced error structure with metadata and context
 * - Comprehensive error logging with structured data
 * - Request correlation IDs for error tracking
 * - Security-conscious error message filtering
 * - Integration with existing error handling patterns
 * - Backward compatibility with existing ErrorResponse
 *
 * Dependencies:
 * - Existing ErrorResponse class for compatibility
 * - SuiteCRM logging infrastructure
 * - JSON:API specification compliance
 * - Existing API configuration and debug settings
 *
 * @package SuiteCRM\Api\V8\JsonApi\Response
 * @copyright Copyright (C) 2011 - 2024 SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 * @since 1.0.0
 */

namespace Api\V8\JsonApi\Response;

use Api\Core\Config\ApiConfig;
use Exception;
use JsonSerializable;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * EnhancedErrorResponse
 *
 * Provides comprehensive error response handling with enhanced logging,
 * structured metadata, and improved debugging capabilities while maintaining
 * full compatibility with existing JSON:API error format.
 */
class EnhancedErrorResponse extends ErrorResponse implements JsonSerializable
{
    /** @var string $requestId Unique identifier for error correlation */
    private string $requestId;
    
    /** @var array $context Additional context data for error analysis */
    private array $context;
    
    /** @var array $metadata Enhanced metadata for error resolution */
    private array $metadata;
    
    /** @var string $severity Error severity level */
    private string $severity;
    
    /** @var bool $logged Whether error has been logged */
    private bool $logged;
    
    /** @var array $allowedSeverities Valid error severity levels */
    private array $allowedSeverities = ['low', 'medium', 'high', 'critical'];
    
    /**
     * EnhancedErrorResponse constructor
     *
     * Initializes enhanced error response with comprehensive error tracking
     * and structured metadata while maintaining parent compatibility.
     *
     * @param bool|null $debugExceptions Enable debug mode for detailed exceptions
     * @param string|null $requestId Optional request correlation ID
     */
    public function __construct($debugExceptions = null, ?string $requestId = null)
    {
        parent::__construct($debugExceptions);
        
        $this->requestId = $requestId ?? $this->generateRequestId();
        $this->context = [];
        $this->metadata = [];
        $this->severity = 'medium';
        $this->logged = false;
    }
    
    /**
     * Sets enhanced error context with automatic logging
     *
     * @param Exception $exception The exception that caused the error
     * @param array $additionalContext Additional context data
     * @param string $severity Error severity level
     *
     * @return self Fluent interface
     *
     * @since 1.0.0
     */
    public function setErrorContext(Exception $exception, array $additionalContext = [], string $severity = 'medium'): self
    {
        // Set parent exception data
        $this->setException($exception);
        
        // Set severity
        $this->setSeverity($severity);
        
        // Build comprehensive context
        $this->context = array_merge([
            'exception_class' => get_class($exception),
            'exception_code' => $exception->getCode(),
            'exception_file' => $exception->getFile(),
            'exception_line' => $exception->getLine(),
            'timestamp' => date('c'),
            'request_id' => $this->requestId,
            'php_version' => PHP_VERSION,
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true)
        ], $additionalContext);
        
        // Add request context if available
        $this->addRequestContext();
        
        // Log the error automatically
        $this->logError($exception);
        
        return $this;
    }
    
    /**
     * Sets error severity level
     *
     * @param string $severity Severity level (low, medium, high, critical)
     *
     * @return self Fluent interface
     *
     * @since 1.0.0
     */
    public function setSeverity(string $severity): self
    {
        if (in_array($severity, $this->allowedSeverities)) {
            $this->severity = $severity;
        } else {
            $this->severity = 'medium';
            $GLOBALS['log']->warning('Invalid error severity provided, defaulting to medium', [
                'provided_severity' => $severity,
                'allowed_severities' => $this->allowedSeverities
            ]);
        }
        
        return $this;
    }
    
    /**
     * Gets error severity level
     *
     * @return string Error severity level
     *
     * @since 1.0.0
     */
    public function getSeverity(): string
    {
        return $this->severity;
    }
    
    /**
     * Sets additional metadata for error analysis
     *
     * @param array $metadata Metadata array
     *
     * @return self Fluent interface
     *
     * @since 1.0.0
     */
    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;
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
    public function addMetadata(string $key, $value): self
    {
        $this->metadata[$key] = $value;
        return $this;
    }
    
    /**
     * Gets all metadata
     *
     * @return array Metadata array
     *
     * @since 1.0.0
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
    
    /**
     * Gets request correlation ID
     *
     * @return string Request ID
     *
     * @since 1.0.0
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }
    
    /**
     * Gets error context data
     *
     * @return array Context data
     *
     * @since 1.0.0
     */
    public function getContext(): array
    {
        return $this->context;
    }
    
    /**
     * Logs error with comprehensive context
     *
     * @param Exception $exception The exception to log
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function logError(Exception $exception): void
    {
        if ($this->logged) {
            return; // Prevent duplicate logging
        }
        
        $logLevel = $this->getLogLevelForSeverity($this->severity);
        $logMessage = $this->buildLogMessage($exception);
        $logContext = $this->buildLogContext($exception);
        
        // Log based on severity
        switch ($logLevel) {
            case 'critical':
                $GLOBALS['log']->fatal($logMessage, $logContext);
                break;
            case 'error':
                $GLOBALS['log']->error($logMessage, $logContext);
                break;
            case 'warning':
                $GLOBALS['log']->warning($logMessage, $logContext);
                break;
            default:
                $GLOBALS['log']->info($logMessage, $logContext);
        }
        
        $this->logged = true;
    }
    
    /**
     * Builds structured log message
     *
     * @param Exception $exception The exception
     *
     * @return string Log message
     *
     * @since 1.0.0
     */
    private function buildLogMessage(Exception $exception): string
    {
        return sprintf(
            'Enhanced API Error [%s]: %s',
            $this->requestId,
            $exception->getMessage()
        );
    }
    
    /**
     * Builds comprehensive log context
     *
     * @param Exception $exception The exception
     *
     * @return array Log context
     *
     * @since 1.0.0
     */
    private function buildLogContext(Exception $exception): array
    {
        return [
            'request_id' => $this->requestId,
            'severity' => $this->severity,
            'status' => $this->getStatus(),
            'title' => $this->getTitle(),
            'detail' => $this->getDetail(),
            'exception' => [
                'class' => get_class($exception),
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace_summary' => $this->getTraceSmary($exception)
            ],
            'context' => $this->context,
            'metadata' => $this->metadata,
            'timestamp' => date('c')
        ];
    }
    
    /**
     * Gets abbreviated stack trace for logging
     *
     * @param Exception $exception The exception
     *
     * @return array Abbreviated trace
     *
     * @since 1.0.0
     */
    private function getTraceSmary(Exception $exception): array
    {
        $trace = $exception->getTrace();
        $summary = [];
        
        // Limit trace to first 5 frames for log readability
        $frames = array_slice($trace, 0, 5);
        
        foreach ($frames as $frame) {
            $summary[] = sprintf(
                '%s:%d %s%s%s()',
                $frame['file'] ?? '[internal]',
                $frame['line'] ?? 0,
                $frame['class'] ?? '',
                $frame['type'] ?? '',
                $frame['function'] ?? '[unknown]'
            );
        }
        
        return $summary;
    }
    
    /**
     * Maps error severity to log level
     *
     * @param string $severity Error severity
     *
     * @return string Log level
     *
     * @since 1.0.0
     */
    private function getLogLevelForSeverity(string $severity): string
    {
        $mapping = [
            'low' => 'info',
            'medium' => 'warning',
            'high' => 'error',
            'critical' => 'critical'
        ];
        
        return $mapping[$severity] ?? 'warning';
    }
    
    /**
     * Adds request context data
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function addRequestContext(): void
    {
        // Add HTTP request context if available
        if (isset($_SERVER)) {
            $this->context['request'] = [
                'method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
                'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'unknown',
                'content_length' => $_SERVER['CONTENT_LENGTH'] ?? 0
            ];
        }
        
        // Add authenticated user context if available
        if (isset($GLOBALS['current_user']) && !empty($GLOBALS['current_user']->id)) {
            $this->context['user'] = [
                'id' => $GLOBALS['current_user']->id,
                'user_name' => $GLOBALS['current_user']->user_name ?? 'unknown'
            ];
        }
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
     * Filters sensitive data from error messages
     *
     * @param string $message Original message
     *
     * @return string Filtered message
     *
     * @since 1.0.0
     */
    private function filterSensitiveData(string $message): string
    {
        // Common patterns for sensitive data
        $patterns = [
            '/password["\']?\s*[:=]\s*["\']?[^"\';\s]+/i',
            '/token["\']?\s*[:=]\s*["\']?[^"\';\s]+/i',
            '/key["\']?\s*[:=]\s*["\']?[^"\';\s]+/i',
            '/secret["\']?\s*[:=]\s*["\']?[^"\';\s]+/i',
        ];
        
        $replacements = [
            'password: [FILTERED]',
            'token: [FILTERED]',
            'key: [FILTERED]',
            'secret: [FILTERED]',
        ];
        
        return preg_replace($patterns, $replacements, $message);
    }
    
    /**
     * Enhanced JSON serialization with comprehensive error data
     *
     * @return array JSON-serializable error data
     *
     * @since 1.0.0
     */
    public function jsonSerialize(): array
    {
        // Get base error data from parent
        $errorData = parent::jsonSerialize();
        
        // Enhance with additional metadata
        if (isset($errorData['errors'])) {
            $errorData['errors']['id'] = $this->requestId;
            $errorData['errors']['meta'] = array_merge($errorData['errors']['meta'] ?? [], [
                'severity' => $this->severity,
                'request_id' => $this->requestId,
                'timestamp' => $this->context['timestamp'] ?? date('c'),
                'correlation_id' => $this->requestId
            ]);
            
            // Add custom metadata if present
            if (!empty($this->metadata)) {
                $errorData['errors']['meta']['additional'] = $this->metadata;
            }
            
            // Add limited context in debug mode
            if ($this->debugExceptions && !empty($this->context)) {
                $errorData['errors']['meta']['context'] = $this->filterDebugContext($this->context);
            }
        }
        
        return $errorData;
    }
    
    /**
     * Filters context data for debug output
     *
     * @param array $context Original context
     *
     * @return array Filtered context safe for client output
     *
     * @since 1.0.0
     */
    private function filterDebugContext(array $context): array
    {
        // Remove sensitive data from debug context
        $filtered = $context;
        
        // Remove potentially sensitive server information
        if (isset($filtered['request'])) {
            unset($filtered['request']['user_agent']); // May contain sensitive info
        }
        
        // Remove internal file paths in production
        if (!$this->debugExceptions) {
            unset($filtered['exception_file']);
            unset($filtered['exception_line']);
        }
        
        return $filtered;
    }
}
