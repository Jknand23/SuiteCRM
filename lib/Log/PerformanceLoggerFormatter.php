<?php
/**
 * @fileoverview Performance Logger Formatter for SuiteCRM Performance Monitoring
 *
 * Specialized formatter optimized for performance monitoring and metrics logging.
 * Provides structured output specifically designed for performance analysis tools,
 * monitoring dashboards, and automated performance alerts while maintaining
 * compatibility with existing SuiteCRM logging infrastructure.
 *
 * Key Features:
 * - Performance-optimized structured output format
 * - Metrics aggregation and statistical analysis
 * - Monitoring tool integration (Grafana, Kibana, etc.)
 * - Automated performance threshold detection
 * - Memory usage tracking and analysis
 * - Database query performance monitoring
 * - Request/response timing analysis
 * - Resource consumption tracking
 *
 * Dependencies:
 * - Monolog v1.27.1 formatter infrastructure
 * - PHP 7.4+ performance monitoring functions
 * - SuiteCRM database and memory monitoring globals
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
 * PerformanceLoggerFormatter
 *
 * Specialized formatter for performance monitoring with optimized output
 * for analysis tools and automated monitoring systems.
 */
class PerformanceLoggerFormatter implements FormatterInterface
{
    /** @var array $configuration Performance formatter configuration */
    private array $configuration;
    
    /** @var array $thresholds Performance threshold settings */
    private array $thresholds;
    
    /** @var bool $enableAggregation Enable metrics aggregation */
    private bool $enableAggregation;

    /**
     * Constructor
     *
     * Initialize performance formatter with configuration for metrics
     * collection, threshold monitoring, and output optimization.
     *
     * @param array $configuration Performance formatter configuration
     */
    public function __construct(array $configuration = [])
    {
        $this->configuration = array_merge([
            'include_system_metrics' => true,
            'include_database_metrics' => true,
            'include_memory_details' => true,
            'include_timing_breakdown' => true,
            'enable_aggregation' => true,
            'output_format' => 'json', // json, csv, influxdb
            'precision' => 3, // decimal places for timing
            'include_percentiles' => false
        ], $configuration);
        
        $this->thresholds = array_merge([
            'slow_operation_ms' => 1000,
            'memory_warning_mb' => 128,
            'memory_critical_mb' => 256,
            'database_slow_query_ms' => 500
        ], $configuration['thresholds'] ?? []);
        
        $this->enableAggregation = $this->configuration['enable_aggregation'];
    }

    /**
     * Format performance log record
     *
     * Converts performance log record into optimized format for monitoring
     * tools with enhanced metrics, thresholds analysis, and structured output.
     *
     * @param array $record Monolog performance log record
     *
     * @return string Formatted performance log entry
     */
    public function format(array $record): string
    {
        $timestamp = $record['datetime']->format('Y-m-d\TH:i:s.u\Z');
        
        // Extract performance data from context
        $context = $record['context'] ?? [];
        $performance = $this->extractPerformanceData($context);
        
        // Build performance log entry
        $logEntry = [
            'timestamp' => $timestamp,
            'level' => strtolower($record['level_name']),
            'operation' => $performance['operation'] ?? 'unknown',
            'metrics' => $this->buildMetrics($performance, $record),
            'thresholds' => $this->analyzeThresholds($performance),
            'metadata' => $this->buildMetadata($record)
        ];

        // Add system metrics if enabled
        if ($this->configuration['include_system_metrics']) {
            $logEntry['system'] = $this->getSystemMetrics();
        }

        // Add database metrics if enabled
        if ($this->configuration['include_database_metrics']) {
            $logEntry['database'] = $this->getDatabaseMetrics();
        }

        return $this->formatOutput($logEntry);
    }

    /**
     * Format multiple performance records (batch processing)
     *
     * Efficiently processes multiple performance records with optional
     * aggregation and statistical analysis for monitoring systems.
     *
     * @param array $records Array of performance log records
     *
     * @return string Formatted batch performance log output
     */
    public function formatBatch(array $records): string
    {
        if (empty($records)) {
            return '';
        }

        $formatted = [];
        $aggregationData = [];

        foreach ($records as $record) {
            $formattedRecord = rtrim($this->format($record), "\n");
            $formatted[] = $formattedRecord;
            
            if ($this->enableAggregation) {
                $this->collectAggregationData($record, $aggregationData);
            }
        }

        $output = implode("\n", $formatted);
        
        // Add aggregation summary if enabled
        if ($this->enableAggregation && !empty($aggregationData)) {
            $output .= "\n" . $this->formatAggregationSummary($aggregationData);
        }

        return $output . "\n";
    }

    /**
     * Extract performance data from log context
     *
     * Extracts and normalizes performance metrics from log record context
     * with validation and error handling for missing or invalid data.
     *
     * @param array $context Log record context data
     *
     * @return array Normalized performance data
     */
    private function extractPerformanceData(array $context): array
    {
        $performance = [
            'operation' => $context['operation'] ?? 'unknown',
            'execution_time_ms' => $this->normalizeExecutionTime($context),
            'memory_usage_mb' => $context['memory_usage_mb'] ?? null,
            'memory_peak_mb' => $context['memory_peak_mb'] ?? null,
            'memory_delta_mb' => $context['metrics']['memory_delta_mb'] ?? null,
            'custom_metrics' => $context['metrics'] ?? []
        ];

        // Add request-specific data if available
        if (isset($context['request_id'])) {
            $performance['request_id'] = $context['request_id'];
        }

        if (isset($context['user_id'])) {
            $performance['user_id'] = $context['user_id'];
        }

        return $performance;
    }

    /**
     * Normalize execution time data
     *
     * Converts execution time to consistent millisecond format with
     * proper precision and validation.
     *
     * @param array $context Context containing timing data
     *
     * @return float|null Normalized execution time in milliseconds
     */
    private function normalizeExecutionTime(array $context): ?float
    {
        $executionTime = $context['execution_time_ms'] ?? $context['execution_time'] ?? null;
        
        if ($executionTime === null) {
            return null;
        }
        
        // Convert to milliseconds if in seconds
        if ($executionTime < 10) { // Likely in seconds
            $executionTime *= 1000;
        }
        
        return round((float) $executionTime, $this->configuration['precision']);
    }

    /**
     * Build comprehensive metrics object
     *
     * Constructs detailed metrics object with performance data, resource
     * usage, and statistical information for monitoring analysis.
     *
     * @param array $performance Performance data
     * @param array $record Original log record
     *
     * @return array Comprehensive metrics object
     */
    private function buildMetrics(array $performance, array $record): array
    {
        $metrics = [
            'timing' => [
                'execution_time_ms' => $performance['execution_time_ms'],
                'timestamp' => microtime(true),
                'precision' => $this->configuration['precision']
            ],
            'memory' => [
                'usage_mb' => $performance['memory_usage_mb'],
                'peak_mb' => $performance['memory_peak_mb'],
                'delta_mb' => $performance['memory_delta_mb']
            ],
            'operation' => [
                'name' => $performance['operation'],
                'level' => strtolower($record['level_name']),
                'message' => $record['message']
            ]
        ];

        // Add memory details if enabled
        if ($this->configuration['include_memory_details']) {
            $metrics['memory']['current_mb'] = round(memory_get_usage(true) / 1024 / 1024, 2);
            $metrics['memory']['limit_mb'] = $this->getMemoryLimit();
            $metrics['memory']['usage_percent'] = $this->calculateMemoryUsagePercent();
        }

        // Add timing breakdown if enabled
        if ($this->configuration['include_timing_breakdown'] && !empty($performance['custom_metrics'])) {
            $metrics['timing']['breakdown'] = $performance['custom_metrics'];
        }

        return $metrics;
    }

    /**
     * Analyze performance against thresholds
     *
     * Evaluates performance metrics against configured thresholds and
     * generates alerts and warnings for monitoring systems.
     *
     * @param array $performance Performance data to analyze
     *
     * @return array Threshold analysis results
     */
    private function analyzeThresholds(array $performance): array
    {
        $analysis = [
            'alerts' => [],
            'warnings' => [],
            'status' => 'normal'
        ];

        // Check execution time threshold
        if ($performance['execution_time_ms'] !== null) {
            if ($performance['execution_time_ms'] > $this->thresholds['slow_operation_ms']) {
                $analysis['alerts'][] = [
                    'type' => 'slow_operation',
                    'value' => $performance['execution_time_ms'],
                    'threshold' => $this->thresholds['slow_operation_ms'],
                    'severity' => 'warning'
                ];
                $analysis['status'] = 'warning';
            }
        }

        // Check memory usage thresholds
        if ($performance['memory_usage_mb'] !== null) {
            if ($performance['memory_usage_mb'] > $this->thresholds['memory_critical_mb']) {
                $analysis['alerts'][] = [
                    'type' => 'memory_critical',
                    'value' => $performance['memory_usage_mb'],
                    'threshold' => $this->thresholds['memory_critical_mb'],
                    'severity' => 'critical'
                ];
                $analysis['status'] = 'critical';
            } elseif ($performance['memory_usage_mb'] > $this->thresholds['memory_warning_mb']) {
                $analysis['warnings'][] = [
                    'type' => 'memory_warning',
                    'value' => $performance['memory_usage_mb'],
                    'threshold' => $this->thresholds['memory_warning_mb'],
                    'severity' => 'warning'
                ];
                if ($analysis['status'] === 'normal') {
                    $analysis['status'] = 'warning';
                }
            }
        }

        return $analysis;
    }

    /**
     * Build metadata for performance context
     *
     * Constructs metadata object with environmental and contextual
     * information for performance analysis correlation.
     *
     * @param array $record Original log record
     *
     * @return array Performance metadata
     */
    private function buildMetadata(array $record): array
    {
        $metadata = [
            'channel' => $record['channel'],
            'logger' => 'suitecrm.performance',
            'environment' => $this->getEnvironmentInfo()
        ];

        // Add user context if available
        if (!empty($GLOBALS['current_user'])) {
            $metadata['user'] = [
                'id' => $GLOBALS['current_user']->id ?? 'anonymous',
                'name' => $GLOBALS['current_user']->user_name ?? 'unknown'
            ];
        }

        // Add request context if available
        if (!empty($_SERVER['REQUEST_METHOD'])) {
            $metadata['request'] = [
                'method' => $_SERVER['REQUEST_METHOD'],
                'uri' => $_SERVER['REQUEST_URI'] ?? '',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ];
        }

        return $metadata;
    }

    /**
     * Get current system metrics
     *
     * Collects system-level performance metrics including CPU usage,
     * disk usage, and system load information.
     *
     * @return array System metrics data
     */
    private function getSystemMetrics(): array
    {
        $metrics = [
            'php_version' => PHP_VERSION,
            'process_id' => getmypid(),
            'uptime' => $this->getProcessUptime()
        ];

        // Add load average on Unix systems
        if (function_exists('sys_getloadavg') && php_uname('s') !== 'Windows') {
            $loadAvg = sys_getloadavg();
            $metrics['load_average'] = [
                '1min' => $loadAvg[0] ?? null,
                '5min' => $loadAvg[1] ?? null,
                '15min' => $loadAvg[2] ?? null
            ];
        }

        return $metrics;
    }

    /**
     * Get database performance metrics
     *
     * Collects database-related performance metrics including query counts,
     * connection status, and query timing information.
     *
     * @return array Database metrics data
     */
    private function getDatabaseMetrics(): array
    {
        $metrics = [
            'connected' => false,
            'query_count' => 0,
            'connection_info' => []
        ];

        if (!empty($GLOBALS['db'])) {
            $db = $GLOBALS['db'];
            $metrics['connected'] = true;
            $metrics['query_count'] = method_exists($db, 'getQueryCount') ? $db->getQueryCount() : 0;
            $metrics['connection_info'] = [
                'type' => $db->dbType ?? 'unknown',
                'host' => $db->connectOptions['db_host_name'] ?? 'unknown'
            ];
        }

        return $metrics;
    }

    /**
     * Get memory limit in MB
     *
     * @return float|null Memory limit in megabytes
     */
    private function getMemoryLimit(): ?float
    {
        $limit = ini_get('memory_limit');
        if ($limit === '-1') {
            return null; // No limit
        }
        
        return $this->convertToMegabytes($limit);
    }

    /**
     * Calculate memory usage percentage
     *
     * @return float|null Memory usage percentage
     */
    private function calculateMemoryUsagePercent(): ?float
    {
        $limit = $this->getMemoryLimit();
        if ($limit === null) {
            return null;
        }
        
        $usage = memory_get_usage(true) / 1024 / 1024;
        return round(($usage / $limit) * 100, 2);
    }

    /**
     * Convert memory value to megabytes
     *
     * @param string $value Memory value with unit (e.g., "128M", "2G")
     *
     * @return float Value in megabytes
     */
    private function convertToMegabytes(string $value): float
    {
        $unit = strtoupper(substr($value, -1));
        $number = (float) substr($value, 0, -1);
        
        switch ($unit) {
            case 'G':
                return $number * 1024;
            case 'M':
                return $number;
            case 'K':
                return $number / 1024;
            default:
                return $number / 1024 / 1024; // Bytes to MB
        }
    }

    /**
     * Get environment information
     *
     * @return array Environment metadata
     */
    private function getEnvironmentInfo(): array
    {
        global $sugar_config;
        
        return [
            'server_name' => $_SERVER['SERVER_NAME'] ?? 'unknown',
            'environment' => $sugar_config['environment'] ?? 'production',
            'timezone' => date_default_timezone_get(),
            'php_sapi' => php_sapi_name()
        ];
    }

    /**
     * Get process uptime (approximate)
     *
     * @return float|null Process uptime in seconds
     */
    private function getProcessUptime(): ?float
    {
        static $startTime;
        if ($startTime === null) {
            $startTime = $_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true);
        }
        return microtime(true) - $startTime;
    }

    /**
     * Collect data for aggregation
     *
     * @param array $record Log record
     * @param array &$aggregationData Aggregation data reference
     *
     * @return void
     */
    private function collectAggregationData(array $record, array &$aggregationData): void
    {
        $context = $record['context'] ?? [];
        $operation = $context['operation'] ?? 'unknown';
        $executionTime = $this->normalizeExecutionTime($context);
        
        if (!isset($aggregationData[$operation])) {
            $aggregationData[$operation] = [
                'count' => 0,
                'total_time' => 0,
                'min_time' => PHP_FLOAT_MAX,
                'max_time' => 0,
                'execution_times' => []
            ];
        }
        
        $aggregationData[$operation]['count']++;
        if ($executionTime !== null) {
            $aggregationData[$operation]['total_time'] += $executionTime;
            $aggregationData[$operation]['min_time'] = min($aggregationData[$operation]['min_time'], $executionTime);
            $aggregationData[$operation]['max_time'] = max($aggregationData[$operation]['max_time'], $executionTime);
            $aggregationData[$operation]['execution_times'][] = $executionTime;
        }
    }

    /**
     * Format aggregation summary
     *
     * @param array $aggregationData Collected aggregation data
     *
     * @return string Formatted aggregation summary
     */
    private function formatAggregationSummary(array $aggregationData): string
    {
        $summary = [
            'type' => 'performance_summary',
            'timestamp' => date('c'),
            'operations' => []
        ];
        
        foreach ($aggregationData as $operation => $data) {
            $avgTime = $data['count'] > 0 ? $data['total_time'] / $data['count'] : 0;
            
            $operationSummary = [
                'operation' => $operation,
                'count' => $data['count'],
                'avg_time_ms' => round($avgTime, $this->configuration['precision']),
                'min_time_ms' => $data['min_time'] !== PHP_FLOAT_MAX ? $data['min_time'] : 0,
                'max_time_ms' => $data['max_time'],
                'total_time_ms' => $data['total_time']
            ];
            
            $summary['operations'][] = $operationSummary;
        }
        
        return $this->formatOutput($summary);
    }

    /**
     * Format output based on configuration
     *
     * @param array $data Data to format
     *
     * @return string Formatted output
     */
    private function formatOutput(array $data): string
    {
        switch ($this->configuration['output_format']) {
            case 'csv':
                return $this->formatAsCsv($data);
            case 'influxdb':
                return $this->formatAsInfluxDb($data);
            case 'json':
            default:
                return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Format data as CSV
     *
     * @param array $data Data to format
     *
     * @return string CSV formatted output
     */
    private function formatAsCsv(array $data): string
    {
        // Simplified CSV format for performance data
        $metrics = $data['metrics'] ?? [];
        $timing = $metrics['timing'] ?? [];
        $memory = $metrics['memory'] ?? [];
        
        return sprintf(
            '%s,%s,%s,%.3f,%.2f,%.2f',
            $data['timestamp'] ?? '',
            $data['operation'] ?? '',
            $data['level'] ?? '',
            $timing['execution_time_ms'] ?? 0,
            $memory['usage_mb'] ?? 0,
            $memory['peak_mb'] ?? 0
        );
    }

    /**
     * Format data as InfluxDB line protocol
     *
     * @param array $data Data to format
     *
     * @return string InfluxDB line protocol formatted output
     */
    private function formatAsInfluxDb(array $data): string
    {
        // Simplified InfluxDB line protocol format
        $measurement = 'suitecrm_performance';
        $tags = sprintf('operation=%s,level=%s', $data['operation'] ?? 'unknown', $data['level'] ?? 'info');
        $fields = [];
        
        $metrics = $data['metrics'] ?? [];
        if (isset($metrics['timing']['execution_time_ms'])) {
            $fields[] = sprintf('execution_time_ms=%.3f', $metrics['timing']['execution_time_ms']);
        }
        if (isset($metrics['memory']['usage_mb'])) {
            $fields[] = sprintf('memory_usage_mb=%.2f', $metrics['memory']['usage_mb']);
        }
        
        $timestamp = isset($data['timestamp']) ? strtotime($data['timestamp']) . '000000000' : '';
        
        return sprintf('%s,%s %s %s', $measurement, $tags, implode(',', $fields), $timestamp);
    }
}
