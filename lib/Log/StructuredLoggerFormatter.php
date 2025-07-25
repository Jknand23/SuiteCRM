<?php
/**
 * @fileoverview Structured Logger Formatter for Enhanced SuiteCRM Logging
 *
 * Provides structured JSON logging format with enhanced context preservation
 * while maintaining compatibility with existing SuiteCRM logging patterns.
 * Formats log records with structured data, metadata, and application context
 * for better analysis and debugging capabilities.
 *
 * Key Features:
 * - Structured JSON output for machine-readable logs
 * - Enhanced context and metadata preservation
 * - Backward compatibility with existing log analysis tools
 * - Configurable output format and field inclusion
 * - Performance-optimized JSON encoding
 * - Support for nested context data and arrays
 * - User and application context integration
 * - Timestamp standardization with microsecond precision
 *
 * Dependencies:
 * - Monolog v1.27.1 formatter infrastructure
 * - PHP 7.4+ JSON encoding capabilities
 * - SuiteCRM application context globals
 *
 * @package SuiteCRM\Log
 * @copyright Copyright (C) 2011 - 2024 SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 * @since 1.0.0
 */

namespace SuiteCRM\Log;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

use Monolog\Formatter\FormatterInterface;

/**
 * StructuredLoggerFormatter
 *
 * Advanced formatter for structured logging output with enhanced context
 * preservation and machine-readable JSON format.
 */
class StructuredLoggerFormatter implements FormatterInterface
{
    /** @var array $configuration Formatter configuration options */
    private array $configuration;
    
    /** @var bool $includeStackTrace Include stack trace in error logs */
    private bool $includeStackTrace;
    
    /** @var int $maxDepth Maximum depth for nested data structures */
    private int $maxDepth;

    /**
     * Constructor
     *
     * Initialize structured formatter with configuration options for
     * output format, field inclusion, and processing behavior.
     *
     * @param array $configuration Formatter configuration options
     */
    public function __construct(array $configuration = [])
    {
        $this->configuration = array_merge([
            'include_context' => true,
            'include_extra' => true,
            'include_stack_trace' => true,
            'pretty_print' => false,
            'max_depth' => 10,
            'datetime_format' => 'c',
            'include_microseconds' => true
        ], $configuration);
        
        $this->includeStackTrace = $this->configuration['include_stack_trace'];
        $this->maxDepth = $this->configuration['max_depth'];
    }

    /**
     * Format a log record into structured JSON
     *
     * Converts Monolog record array into structured JSON format with
     * enhanced metadata, context preservation, and standardized fields
     * for better log analysis and debugging.
     *
     * @param array $record Monolog log record to format
     *
     * @return string Formatted JSON log entry
     */
    public function format(array $record): string
    {
        $formatted = [
            '@timestamp' => $this->formatDateTime($record['datetime']),
            '@version' => '1',
            'level' => strtolower($record['level_name']),
            'level_value' => $record['level'],
            'channel' => $record['channel'],
            'message' => $record['message'],
            'logger' => 'suitecrm.enhanced'
        ];

        // Add context data if available and configured
        if ($this->configuration['include_context'] && !empty($record['context'])) {
            $formatted['context'] = $this->sanitizeData($record['context'], $this->maxDepth);
        }

        // Add extra data if available and configured
        if ($this->configuration['include_extra'] && !empty($record['extra'])) {
            $formatted['extra'] = $this->sanitizeData($record['extra'], $this->maxDepth);
        }

        // Add application metadata
        $formatted['application'] = $this->getApplicationMetadata();

        // Add stack trace for error levels if configured
        if ($this->shouldIncludeStackTrace($record)) {
            $formatted['stack_trace'] = $this->getStackTrace();
        }

        // Add performance timing if available
        if (isset($record['context']['execution_time_ms'])) {
            $formatted['performance'] = [
                'execution_time_ms' => $record['context']['execution_time_ms'],
                'memory_usage_mb' => $record['extra']['memory_usage'] ?? null,
                'memory_peak_mb' => $record['extra']['memory_peak_usage'] ?? null
            ];
        }

        return $this->encodeJson($formatted) . "\n";
    }

    /**
     * Format multiple log records (batch formatting)
     *
     * Efficiently formats multiple log records in batch operations
     * for better performance with high-volume logging scenarios.
     *
     * @param array $records Array of Monolog log records
     *
     * @return string Concatenated formatted log entries
     */
    public function formatBatch(array $records): string
    {
        $formatted = [];
        foreach ($records as $record) {
            $formatted[] = rtrim($this->format($record), "\n");
        }
        return implode("\n", $formatted) . "\n";
    }

    /**
     * Format DateTime object with enhanced precision
     *
     * Formats log timestamp with configurable format and optional
     * microsecond precision for high-resolution timing analysis.
     *
     * @param \DateTime $dateTime DateTime object to format
     *
     * @return string Formatted timestamp string
     */
    private function formatDateTime(\DateTime $dateTime): string
    {
        $format = $this->configuration['datetime_format'];
        
        if ($this->configuration['include_microseconds']) {
            // Add microseconds to the timestamp for high-precision logging
            $microtime = $dateTime->format('u');
            $formatted = $dateTime->format('Y-m-d\TH:i:s') . '.' . $microtime . $dateTime->format('P');
            return $formatted;
        }
        
        return $dateTime->format($format);
    }

    /**
     * Get application metadata for context enhancement
     *
     * Collects SuiteCRM-specific application metadata including
     * environment information, version details, and runtime context.
     *
     * @return array Application metadata array
     */
    private function getApplicationMetadata(): array
    {
        global $sugar_config, $sugar_version, $sugar_flavor;
        
        $metadata = [
            'environment' => $sugar_config['site_url'] ?? 'unknown',
            'version' => $sugar_version ?? 'unknown',
            'flavor' => $sugar_flavor ?? 'unknown',
            'php_version' => PHP_VERSION,
            'server_name' => $_SERVER['SERVER_NAME'] ?? 'unknown',
            'process_id' => getmypid(),
            'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'N/A'
        ];

        // Add database information if available
        if (!empty($GLOBALS['db'])) {
            $metadata['database'] = [
                'type' => $GLOBALS['db']->dbType ?? 'unknown',
                'host' => $GLOBALS['db']->connectOptions['db_host_name'] ?? 'unknown'
            ];
        }

        // Add current user context if available
        if (!empty($GLOBALS['current_user'])) {
            $metadata['user'] = [
                'id' => $GLOBALS['current_user']->id ?? 'anonymous',
                'name' => $GLOBALS['current_user']->user_name ?? 'unknown',
                'is_admin' => $GLOBALS['current_user']->is_admin ?? false,
                'department' => $GLOBALS['current_user']->department ?? null
            ];
        }

        return $metadata;
    }

    /**
     * Determine if stack trace should be included
     *
     * Evaluates log level and configuration to determine whether
     * to include stack trace information for debugging purposes.
     *
     * @param array $record Monolog log record
     *
     * @return bool True if stack trace should be included
     */
    private function shouldIncludeStackTrace(array $record): bool
    {
        if (!$this->includeStackTrace) {
            return false;
        }
        
        // Include stack trace for error levels and above
        $errorLevels = [400, 500, 550, 600]; // ERROR, CRITICAL, ALERT, EMERGENCY
        return in_array($record['level'], $errorLevels, true);
    }

    /**
     * Get current stack trace for debugging
     *
     * Captures current execution stack trace with filtering to
     * remove logger-specific frames and provide relevant context.
     *
     * @return array Filtered stack trace array
     */
    private function getStackTrace(): array
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20);
        $filtered = [];
        
        foreach ($trace as $frame) {
            // Skip logger-specific frames
            if (isset($frame['class']) &&
                (strpos($frame['class'], 'Logger') !== false ||
                 strpos($frame['class'], 'Monolog') !== false)) {
                continue;
            }
            
            $filtered[] = [
                'file' => $frame['file'] ?? 'unknown',
                'line' => $frame['line'] ?? 0,
                'function' => $frame['function'] ?? 'unknown',
                'class' => $frame['class'] ?? null
            ];
        }
        
        return array_slice($filtered, 0, 10); // Limit to 10 frames
    }

    /**
     * Sanitize data for JSON encoding
     *
     * Recursively sanitizes data structures to prevent JSON encoding
     * issues with circular references, excessive depth, and incompatible types.
     *
     * @param mixed $data Data to sanitize
     * @param int $maxDepth Maximum recursion depth
     * @param int $currentDepth Current recursion depth
     *
     * @return mixed Sanitized data safe for JSON encoding
     */
    private function sanitizeData($data, int $maxDepth, int $currentDepth = 0)
    {
        if ($currentDepth >= $maxDepth) {
            return '[MAX_DEPTH_REACHED]';
        }
        
        if (is_resource($data)) {
            return '[RESOURCE]';
        }
        
        if (is_object($data)) {
            if (method_exists($data, '__toString')) {
                return (string) $data;
            }
            
            if (method_exists($data, 'toArray')) {
                return $this->sanitizeData($data->toArray(), $maxDepth, $currentDepth + 1);
            }
            
            // Convert object to array, but limit depth
            $array = [];
            $reflection = new \ReflectionObject($data);
            foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
                try {
                    $array[$property->getName()] = $this->sanitizeData(
                        $property->getValue($data),
                        $maxDepth,
                        $currentDepth + 1
                    );
                } catch (\Exception $e) {
                    $array[$property->getName()] = '[ERROR_ACCESSING_PROPERTY]';
                }
            }
            return $array;
        }
        
        if (is_array($data)) {
            $sanitized = [];
            foreach ($data as $key => $value) {
                $sanitized[$key] = $this->sanitizeData($value, $maxDepth, $currentDepth + 1);
            }
            return $sanitized;
        }
        
        // Handle scalar types
        if (is_string($data)) {
            // Limit string length to prevent excessive log size
            if (strlen($data) > 1000) {
                return substr($data, 0, 1000) . '[TRUNCATED]';
            }
            return $data;
        }
        
        return $data;
    }

    /**
     * Encode data as JSON with error handling
     *
     * Safely encodes data as JSON with proper error handling and
     * fallback for encoding failures.
     *
     * @param array $data Data to encode as JSON
     *
     * @return string JSON encoded string
     */
    private function encodeJson(array $data): string
    {
        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        
        if ($this->configuration['pretty_print']) {
            $flags |= JSON_PRETTY_PRINT;
        }
        
        $json = json_encode($data, $flags);
        
        if ($json === false) {
            // Fallback for encoding errors
            $error = json_last_error_msg();
            return json_encode([
                '@timestamp' => date('c'),
                'level' => 'error',
                'message' => 'JSON encoding failed: ' . $error,
                'original_data' => '[ENCODING_FAILED]',
                'logger' => 'suitecrm.enhanced'
            ], JSON_UNESCAPED_SLASHES);
        }
        
        return $json;
    }
}
