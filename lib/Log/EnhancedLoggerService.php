<?php
/**
 * @fileoverview Enhanced Logger Service for SuiteCRM
 *
 * Provides advanced logging capabilities that build upon existing Monolog v1.27.1
 * infrastructure without breaking backward compatibility. Adds structured logging,
 * performance monitoring, enhanced rotation, and centralized log management while
 * preserving existing SuiteCRM logging patterns and formats.
 *
 * Key Features:
 * - Structured logging with context preservation
 * - Performance monitoring and timing analysis
 * - Enhanced log rotation with configurable policies
 * - Multiple output handlers (file, database, syslog)
 * - Integration with existing SugarLoggerHandler
 * - Backward compatibility with existing LoggerManager
 * - PSR-3 compliant interface extension
 * - Memory-efficient processing for high-volume logging
 *
 * Dependencies:
 * - Existing Monolog v1.27.1 infrastructure
 * - SuiteCRM LoggerManager and SugarLogger system
 * - PSR-3 logging interface compatibility
 * - PHP 7.4+ compatibility with existing SuiteCRM patterns
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

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\WebProcessor;
use Psr\Log\LoggerInterface;
use SuiteCRM\Utility\SuiteLogger;

/**
 * EnhancedLoggerService
 *
 * Advanced logging service that extends existing SuiteCRM logging infrastructure
 * with modern capabilities while maintaining full backward compatibility.
 */
class EnhancedLoggerService
{
    /** @var Logger $structuredLogger Primary structured logger instance */
    private Logger $structuredLogger;
    
    /** @var Logger $performanceLogger Performance monitoring logger instance */
    private Logger $performanceLogger;
    
    /** @var SuiteLogger $legacyLogger Existing PSR-3 compatible logger */
    private SuiteLogger $legacyLogger;
    
    /** @var array $configuration Enhanced logging configuration */
    private array $configuration;
    
    /** @var array $activeHandlers Registry of active handlers for management */
    private array $activeHandlers = [];
    
    /** @var array $performanceMetrics Performance monitoring data */
    private array $performanceMetrics = [];
    
    /** @var bool $isInitialized Initialization state flag */
    private bool $isInitialized = false;

    /**
     * Constructor - Initialize enhanced logging service
     *
     * Sets up enhanced logging infrastructure while preserving existing
     * SuiteCRM logging functionality. Reads configuration from global
     * sugar_config and sets up default handlers and processors.
     *
     * @param array $customConfig Optional custom configuration overrides
     */
    public function __construct(array $customConfig = [])
    {
        $this->loadConfiguration($customConfig);
        $this->legacyLogger = new SuiteLogger();
        $this->initializeLoggers();
        $this->isInitialized = true;
    }

    /**
     * Get structured logger instance for advanced logging scenarios
     *
     * Returns Monolog logger configured with structured formatting,
     * context processors, and multiple output handlers. Ideal for
     * API requests, user actions, and complex application events.
     *
     * @return Logger Configured structured logger instance
     */
    public function getStructuredLogger(): Logger
    {
        return $this->structuredLogger;
    }

    /**
     * Get performance logger for timing and resource monitoring
     *
     * Returns specialized logger for performance metrics, timing data,
     * memory usage tracking, and resource consumption analysis.
     *
     * @return Logger Performance monitoring logger instance
     */
    public function getPerformanceLogger(): Logger
    {
        return $this->performanceLogger;
    }

    /**
     * Get legacy logger for backward compatibility
     *
     * Returns existing SuiteLogger instance for compatibility with
     * existing code that expects PSR-3 interface without enhanced features.
     *
     * @return LoggerInterface Legacy PSR-3 compatible logger
     */
    public function getLegacyLogger(): LoggerInterface
    {
        return $this->legacyLogger;
    }

    /**
     * Log structured data with enhanced context and formatting
     *
     * Provides advanced logging with structured data, automatic context
     * enhancement, and multiple output formats while maintaining compatibility
     * with existing logging patterns.
     *
     * @param string $level PSR-3 log level (info, warning, error, etc.)
     * @param string $message Log message with interpolation support
     * @param array $context Context data for message interpolation and metadata
     * @param array $structured Additional structured data for analysis
     *
     * @return void
     *
     * @throws \InvalidArgumentException When invalid log level provided
     */
    public function logStructured(string $level, string $message, array $context = [], array $structured = []): void
    {
        // Enhanced context with application metadata
        $enhancedContext = $this->enhanceContext($context, $structured);
        
        // Log to structured logger with enhanced formatting
        $this->structuredLogger->log($level, $message, $enhancedContext);
        
        // Maintain backward compatibility by also logging to legacy system
        if ($this->configuration['legacy_compatibility']) {
            $this->legacyLogger->log($level, $message, $context);
        }
    }

    /**
     * Log performance metrics and timing data
     *
     * Specialized logging for performance monitoring including execution
     * timing, memory usage, database query counts, and resource consumption.
     *
     * @param string $operation Operation or process being measured
     * @param float $executionTime Execution time in milliseconds
     * @param array $metrics Additional performance metrics
     * @param array $context Optional context for the performance measurement
     *
     * @return void
     */
    public function logPerformance(string $operation, float $executionTime, array $metrics = [], array $context = []): void
    {
        $performanceData = [
            'operation' => $operation,
            'execution_time_ms' => round($executionTime, 3),
            'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
            'memory_peak_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
            'timestamp' => microtime(true),
            'metrics' => $metrics,
            'context' => $context
        ];

        // Store metrics for analysis
        $this->performanceMetrics[] = $performanceData;
        
        // Log to performance logger
        $this->performanceLogger->info('Performance metric recorded', $performanceData);
        
        // Log to legacy system if enabled
        if ($this->configuration['legacy_compatibility']) {
            $message = sprintf(
                'Performance: %s completed in %sms (Memory: %sMB)',
                $operation,
                $performanceData['execution_time_ms'],
                $performanceData['memory_usage_mb']
            );
            $this->legacyLogger->info($message, $context);
        }
    }

    /**
     * Start performance timing for an operation
     *
     * Begins timing measurement for performance monitoring. Returns
     * timing token that can be used to stop timing and log results.
     *
     * @param string $operation Operation identifier for timing
     * @param array $context Optional context for the timing operation
     *
     * @return string Timing token for stopping measurement
     */
    public function startTiming(string $operation, array $context = []): string
    {
        $timingId = uniqid('timing_', true);
        $this->performanceMetrics[$timingId] = [
            'operation' => $operation,
            'start_time' => microtime(true),
            'start_memory' => memory_get_usage(true),
            'context' => $context
        ];
        
        return $timingId;
    }

    /**
     * Stop performance timing and log results
     *
     * Completes timing measurement started with startTiming() and
     * automatically logs performance data with calculated metrics.
     *
     * @param string $timingId Timing token from startTiming()
     * @param array $additionalMetrics Optional additional metrics to include
     *
     * @return float Execution time in milliseconds
     */
    public function stopTiming(string $timingId, array $additionalMetrics = []): float
    {
        if (!isset($this->performanceMetrics[$timingId])) {
            throw new \InvalidArgumentException('Invalid timing ID: ' . $timingId);
        }

        $timingData = $this->performanceMetrics[$timingId];
        $executionTime = (microtime(true) - $timingData['start_time']) * 1000; // Convert to milliseconds
        $memoryUsed = memory_get_usage(true) - $timingData['start_memory'];
        
        $metrics = array_merge([
            'memory_delta_mb' => round($memoryUsed / 1024 / 1024, 2)
        ], $additionalMetrics);
        
        $this->logPerformance(
            $timingData['operation'],
            $executionTime,
            $metrics,
            $timingData['context']
        );
        
        // Clean up timing data
        unset($this->performanceMetrics[$timingId]);
        
        return $executionTime;
    }

    /**
     * Get current performance statistics
     *
     * Returns aggregated performance statistics for monitoring and
     * analysis including average execution times, memory usage patterns,
     * and operation frequency data.
     *
     * @return array Performance statistics and aggregated metrics
     */
    public function getPerformanceStats(): array
    {
        $stats = [
            'total_operations' => count($this->performanceMetrics),
            'current_memory_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
            'peak_memory_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
            'active_timings' => 0,
            'operations' => []
        ];

        foreach ($this->performanceMetrics as $id => $metric) {
            if (isset($metric['start_time'])) {
                $stats['active_timings']++;
            } elseif (isset($metric['operation'])) {
                $operation = $metric['operation'];
                if (!isset($stats['operations'][$operation])) {
                    $stats['operations'][$operation] = [
                        'count' => 0,
                        'total_time_ms' => 0,
                        'avg_time_ms' => 0,
                        'max_time_ms' => 0,
                        'min_time_ms' => PHP_FLOAT_MAX
                    ];
                }
                
                $stats['operations'][$operation]['count']++;
                $execTime = $metric['execution_time_ms'] ?? 0;
                $stats['operations'][$operation]['total_time_ms'] += $execTime;
                $stats['operations'][$operation]['max_time_ms'] = max(
                    $stats['operations'][$operation]['max_time_ms'],
                    $execTime
                );
                $stats['operations'][$operation]['min_time_ms'] = min(
                    $stats['operations'][$operation]['min_time_ms'],
                    $execTime
                );
                $stats['operations'][$operation]['avg_time_ms'] = round(
                    $stats['operations'][$operation]['total_time_ms'] / $stats['operations'][$operation]['count'],
                    3
                );
            }
        }

        return $stats;
    }

    /**
     * Load enhanced logging configuration
     *
     * Loads configuration from global sugar_config with sensible defaults
     * and custom overrides. Preserves existing logging configuration while
     * adding enhanced features.
     *
     * @param array $customConfig Custom configuration overrides
     *
     * @return void
     */
    private function loadConfiguration(array $customConfig = []): void
    {
        global $sugar_config;
        
        // Default enhanced logging configuration
        $defaultConfig = [
            'enhanced_logging' => [
                'enabled' => true,
                'legacy_compatibility' => true,
                'structured_logging' => [
                    'enabled' => true,
                    'file_path' => 'logs/enhanced.log',
                    'max_files' => 30,
                    'level' => 'info'
                ],
                'performance_logging' => [
                    'enabled' => true,
                    'file_path' => 'logs/performance.log',
                    'max_files' => 30,
                    'level' => 'info',
                    'slow_query_threshold' => 1000 // milliseconds
                ],
                'processors' => [
                    'memory_usage' => true,
                    'process_id' => true,
                    'web_context' => true,
                    'user_context' => true
                ],
                'handlers' => [
                    'file' => true,
                    'sugar_integration' => true,
                    'cli' => true
                ]
            ]
        ];
        
        // Merge with existing sugar_config
        $existingConfig = $sugar_config['enhanced_logging'] ?? [];
        $mergedConfig = array_replace_recursive($defaultConfig['enhanced_logging'], $existingConfig, $customConfig);
        
        $this->configuration = $mergedConfig;
    }

    /**
     * Initialize enhanced loggers with handlers and processors
     *
     * Sets up structured and performance loggers with appropriate handlers,
     * formatters, and processors based on configuration. Maintains integration
     * with existing SuiteCRM logging infrastructure.
     *
     * @return void
     *
     * @throws \Exception When logger initialization fails
     */
    private function initializeLoggers(): void
    {
        try {
            // Initialize structured logger
            $this->structuredLogger = new Logger('structured');
            $this->setupStructuredLoggerHandlers();
            $this->setupLoggerProcessors($this->structuredLogger);
            
            // Initialize performance logger
            $this->performanceLogger = new Logger('performance');
            $this->setupPerformanceLoggerHandlers();
            $this->setupLoggerProcessors($this->performanceLogger);
        } catch (\Exception $e) {
            // Fallback to legacy logging if enhanced setup fails
            if ($this->legacyLogger) {
                $this->legacyLogger->error('Enhanced logger initialization failed: ' . $e->getMessage());
            }
            throw $e;
        }
    }

    /**
     * Setup handlers for structured logger
     *
     * Configures file handlers, rotation policies, and integration handlers
     * for structured logging output.
     *
     * @return void
     */
    private function setupStructuredLoggerHandlers(): void
    {
        if ($this->configuration['structured_logging']['enabled']) {
            // Enhanced rotating file handler
            $structuredHandler = new EnhancedRotatingFileHandler(
                $this->configuration['structured_logging']['file_path'],
                $this->configuration['structured_logging']['max_files'],
                Logger::toMonologLevel($this->configuration['structured_logging']['level'])
            );
            
            $structuredHandler->setFormatter(new StructuredLoggerFormatter());
            $this->structuredLogger->pushHandler($structuredHandler);
            $this->activeHandlers['structured_file'] = $structuredHandler;
        }
        
        // Integration with existing SugarLogger system
        if ($this->configuration['handlers']['sugar_integration']) {
            $sugarHandler = new SugarLoggerHandler();
            $this->structuredLogger->pushHandler($sugarHandler);
            $this->activeHandlers['sugar_integration'] = $sugarHandler;
        }
        
        // CLI handler for command-line operations
        if ($this->configuration['handlers']['cli'] && php_sapi_name() === 'cli') {
            $cliHandler = new CliLoggerHandler();
            $this->structuredLogger->pushHandler($cliHandler);
            $this->activeHandlers['cli'] = $cliHandler;
        }
    }

    /**
     * Setup handlers for performance logger
     *
     * Configures specialized handlers for performance monitoring and metrics
     * collection with appropriate formatting and rotation.
     *
     * @return void
     */
    private function setupPerformanceLoggerHandlers(): void
    {
        if ($this->configuration['performance_logging']['enabled']) {
            // Performance-specific rotating file handler
            $performanceHandler = new EnhancedRotatingFileHandler(
                $this->configuration['performance_logging']['file_path'],
                $this->configuration['performance_logging']['max_files'],
                Logger::toMonologLevel($this->configuration['performance_logging']['level'])
            );
            
            $performanceHandler->setFormatter(new PerformanceLoggerFormatter());
            $this->performanceLogger->pushHandler($performanceHandler);
            $this->activeHandlers['performance_file'] = $performanceHandler;
        }
    }

    /**
     * Setup processors for enhanced context
     *
     * Adds processors for memory usage, process ID, web context, and user
     * context based on configuration settings.
     *
     * @param Logger $logger Logger instance to enhance with processors
     *
     * @return void
     */
    private function setupLoggerProcessors(Logger $logger): void
    {
        if ($this->configuration['processors']['memory_usage']) {
            $logger->pushProcessor(new MemoryUsageProcessor());
        }
        
        if ($this->configuration['processors']['process_id']) {
            $logger->pushProcessor(new ProcessIdProcessor());
        }
        
        if ($this->configuration['processors']['web_context'] && !empty($_SERVER)) {
            $logger->pushProcessor(new WebProcessor());
        }
        
        if ($this->configuration['processors']['user_context']) {
            $logger->pushProcessor(function ($record) {
                if (!empty($GLOBALS['current_user']->id)) {
                    $record['extra']['user_id'] = $GLOBALS['current_user']->id;
                    $record['extra']['user_name'] = $GLOBALS['current_user']->user_name ?? 'unknown';
                }
                return $record;
            });
        }
    }

    /**
     * Enhance context data with application metadata
     *
     * Adds SuiteCRM-specific context including user information, session data,
     * and application state to log context for better debugging and analysis.
     *
     * @param array $context Original context data
     * @param array $structured Additional structured data
     *
     * @return array Enhanced context with metadata
     */
    private function enhanceContext(array $context, array $structured = []): array
    {
        $enhanced = array_merge($context, $structured);
        
        // Add application context
        $enhanced['app_context'] = [
            'timestamp' => date('c'),
            'request_id' => $_SERVER['HTTP_X_REQUEST_ID'] ?? uniqid('req_', true),
            'session_id' => session_id() ?: 'no_session',
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];
        
        // Add SuiteCRM specific context
        if (!empty($GLOBALS['current_user'])) {
            $enhanced['suitecrm_context'] = [
                'user_id' => $GLOBALS['current_user']->id ?? 'anonymous',
                'user_name' => $GLOBALS['current_user']->user_name ?? 'unknown',
                'is_admin' => $GLOBALS['current_user']->is_admin ?? false
            ];
        }
        
        return $enhanced;
    }
}
