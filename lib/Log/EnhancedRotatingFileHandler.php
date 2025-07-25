<?php
/**
 * @fileoverview Enhanced Rotating File Handler for SuiteCRM Logging
 *
 * Extends Monolog's RotatingFileHandler with enhanced rotation policies,
 * compression capabilities, and monitoring integration while maintaining
 * compatibility with existing SuiteCRM logging infrastructure. Provides
 * advanced log management features for production environments.
 *
 * Key Features:
 * - Enhanced log rotation policies (size, time, count-based)
 * - Automatic log compression and archiving
 * - Log retention policies with cleanup automation
 * - Performance monitoring for log operations
 * - Integration with existing SuiteCRM log management
 * - Configurable compression algorithms (gzip, bzip2)
 * - Log file integrity checking and validation
 * - Monitoring alerts for log storage issues
 *
 * Dependencies:
 * - Monolog v1.27.1 RotatingFileHandler
 * - PHP 7.4+ file system and compression functions
 * - SuiteCRM configuration system integration
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

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

/**
 * EnhancedRotatingFileHandler
 *
 * Advanced rotating file handler with enhanced rotation policies,
 * compression capabilities, and monitoring integration.
 */
class EnhancedRotatingFileHandler extends RotatingFileHandler
{
    /** @var array $enhancedConfig Enhanced handler configuration */
    private array $enhancedConfig;
    
    /** @var array $rotationStats Rotation operation statistics */
    private array $rotationStats;
    
    /** @var int $currentFileSize Current log file size in bytes */
    private int $currentFileSize = 0;
    
    /** @var string $compressionMethod Compression method for archived logs */
    private string $compressionMethod;
    
    /** @var int $retentionDays Number of days to retain log files */
    private int $retentionDays;
    
    /** @var bool $enableIntegrityChecks Enable log file integrity checks */
    private bool $enableIntegrityChecks;

    /**
     * Constructor
     *
     * Initialize enhanced rotating file handler with advanced configuration
     * options for rotation policies, compression, and monitoring.
     *
     * @param string $filename Log file path
     * @param int $maxFiles Maximum number of files to keep
     * @param int $level Minimum log level to handle
     * @param bool $bubble Whether to bubble messages up the stack
     * @param int|null $filePermission File permissions for log files
     * @param bool $useLocking Whether to use file locking
     * @param array $enhancedConfig Enhanced configuration options
     */
    public function __construct(
        string $filename,
        int $maxFiles = 0,
        int $level = Logger::DEBUG,
        bool $bubble = true,
        int $filePermission = null,
        bool $useLocking = false,
        array $enhancedConfig = []
    ) {
        parent::__construct($filename, $maxFiles, $level, $bubble, $filePermission, $useLocking);
        
        $this->enhancedConfig = array_merge([
            'max_file_size_mb' => 50,
            'compression_enabled' => true,
            'compression_method' => 'gzip', // gzip, bzip2, none
            'retention_days' => 30,
            'integrity_checks' => true,
            'performance_monitoring' => true,
            'rotation_strategy' => 'size_and_time', // size, time, size_and_time
            'cleanup_enabled' => true,
            'backup_to_archive' => false,
            'archive_path' => null
        ], $enhancedConfig);
        
        $this->compressionMethod = $this->enhancedConfig['compression_method'];
        $this->retentionDays = $this->enhancedConfig['retention_days'];
        $this->enableIntegrityChecks = $this->enhancedConfig['integrity_checks'];
        
        $this->rotationStats = [
            'total_rotations' => 0,
            'total_compressed' => 0,
            'total_cleaned' => 0,
            'last_rotation' => null,
            'average_file_size' => 0,
            'compression_ratio' => 0
        ];
        
        $this->initializeEnhancedFeatures();
    }

    /**
     * Write log record with enhanced rotation checking
     *
     * Overrides parent write method to add enhanced rotation logic,
     * performance monitoring, and integrity checking.
     *
     * @param array $record Log record to write
     *
     * @return void
     */
    protected function write(array $record): void
    {
        $startTime = microtime(true);
        
        // Check if rotation is needed before writing
        if ($this->shouldRotateBeforeWrite()) {
            $this->rotateFiles();
        }
        
        // Call parent write method
        parent::write($record);
        
        // Update file size tracking
        $this->updateFileSizeTracking();
        
        // Perform integrity check if enabled
        if ($this->enableIntegrityChecks && $this->shouldPerformIntegrityCheck()) {
            $this->performIntegrityCheck();
        }
        
        // Monitor performance if enabled
        if ($this->enhancedConfig['performance_monitoring']) {
            $this->monitorWritePerformance($startTime, $record);
        }
        
        // Clean up old files if needed
        if ($this->enhancedConfig['cleanup_enabled'] && $this->shouldCleanup()) {
            $this->cleanupOldFiles();
        }
    }

    /**
     * Enhanced file rotation with compression and archiving
     *
     * Performs file rotation with enhanced features including compression,
     * archiving, and performance monitoring while maintaining compatibility
     * with existing rotation logic.
     *
     * @return void
     */
    protected function rotateFiles(): void
    {
        $rotationStart = microtime(true);
        
        // Get current file size before rotation
        $preRotationSize = $this->getCurrentFileSize();
        
        // Perform standard rotation
        parent::rotateFiles();
        
        // Enhanced post-rotation processing
        $this->performPostRotationProcessing($preRotationSize);
        
        // Update rotation statistics
        $this->updateRotationStats($rotationStart, $preRotationSize);
    }

    /**
     * Check if rotation should occur before writing
     *
     * Enhanced rotation logic that considers multiple factors including
     * file size, time-based rotation, and custom rotation strategies.
     *
     * @return bool True if rotation should occur
     */
    private function shouldRotateBeforeWrite(): bool
    {
        $strategy = $this->enhancedConfig['rotation_strategy'];
        
        switch ($strategy) {
            case 'size':
                return $this->shouldRotateBySize();
            case 'time':
                return $this->shouldRotateByTime();
            case 'size_and_time':
                return $this->shouldRotateBySize() || $this->shouldRotateByTime();
            default:
                return parent::mustRotate($this->getUrl());
        }
    }

    /**
     * Check if rotation should occur based on file size
     *
     * @return bool True if file size exceeds configured maximum
     */
    private function shouldRotateBySize(): bool
    {
        $maxSizeBytes = $this->enhancedConfig['max_file_size_mb'] * 1024 * 1024;
        $currentSize = $this->getCurrentFileSize();
        
        return $currentSize >= $maxSizeBytes;
    }

    /**
     * Check if rotation should occur based on time
     *
     * @return bool True if rotation should occur based on time
     */
    private function shouldRotateByTime(): bool
    {
        $url = $this->getUrl();
        
        if (!file_exists($url)) {
            return false;
        }
        
        $fileModTime = filemtime($url);
        $currentTime = time();
        
        // Rotate daily by default
        return date('Y-m-d', $fileModTime) !== date('Y-m-d', $currentTime);
    }

    /**
     * Get current log file size
     *
     * @return int File size in bytes
     */
    private function getCurrentFileSize(): int
    {
        $url = $this->getUrl();
        
        if (file_exists($url)) {
            $size = filesize($url);
            $this->currentFileSize = $size ?: 0;
            return $this->currentFileSize;
        }
        
        return 0;
    }

    /**
     * Update file size tracking
     *
     * @return void
     */
    private function updateFileSizeTracking(): void
    {
        $this->getCurrentFileSize();
    }

    /**
     * Perform post-rotation processing
     *
     * Enhanced processing after rotation including compression, archiving,
     * and validation of rotated files.
     *
     * @param int $preRotationSize Size of file before rotation
     *
     * @return void
     */
    private function performPostRotationProcessing(int $preRotationSize): void
    {
        if ($this->enhancedConfig['compression_enabled']) {
            $this->compressRotatedFiles();
        }
        
        if ($this->enhancedConfig['backup_to_archive'] && $this->enhancedConfig['archive_path']) {
            $this->archiveRotatedFiles();
        }
        
        // Validate rotated files
        $this->validateRotatedFiles();
    }

    /**
     * Compress rotated log files
     *
     * Compresses rotated log files using configured compression method
     * to save disk space and improve storage efficiency.
     *
     * @return void
     */
    private function compressRotatedFiles(): void
    {
        $baseUrl = $this->getUrl();
        $pattern = $this->getRotatedFilePattern($baseUrl);
        $files = glob($pattern);
        
        foreach ($files as $file) {
            if ($this->shouldCompressFile($file)) {
                $compressed = $this->compressFile($file);
                if ($compressed) {
                    unlink($file); // Remove original after successful compression
                    $this->rotationStats['total_compressed']++;
                }
            }
        }
    }

    /**
     * Get pattern for rotated files
     *
     * @param string $baseUrl Base log file URL
     *
     * @return string Glob pattern for rotated files
     */
    private function getRotatedFilePattern(string $baseUrl): string
    {
        $pathInfo = pathinfo($baseUrl);
        $dir = $pathInfo['dirname'];
        $name = $pathInfo['filename'];
        $ext = $pathInfo['extension'] ?? '';
        
        $pattern = $dir . '/' . $name . '-*';
        if ($ext) {
            $pattern .= '.' . $ext;
        }
        
        return $pattern;
    }

    /**
     * Check if file should be compressed
     *
     * @param string $file File path to check
     *
     * @return bool True if file should be compressed
     */
    private function shouldCompressFile(string $file): bool
    {
        // Don't compress already compressed files
        $compressedExtensions = ['.gz', '.bz2', '.zip'];
        foreach ($compressedExtensions as $ext) {
            if ($this->stringEndsWith($file, $ext)) {
                return false;
            }
        }
        
        // Don't compress very small files
        $minCompressionSize = 1024; // 1KB
        return filesize($file) > $minCompressionSize;
    }

    /**
     * Compress individual file
     *
     * @param string $file File path to compress
     *
     * @return bool True if compression successful
     */
    private function compressFile(string $file): bool
    {
        try {
            switch ($this->compressionMethod) {
                case 'gzip':
                    return $this->compressWithGzip($file);
                case 'bzip2':
                    return $this->compressWithBzip2($file);
                default:
                    return false;
            }
        } catch (\Exception $e) {
            // Log compression error but don't fail the operation
            error_log("Enhanced log compression failed for {$file}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Compress file with gzip
     *
     * @param string $file File path to compress
     *
     * @return bool True if compression successful
     */
    private function compressWithGzip(string $file): bool
    {
        $compressedFile = $file . '.gz';
        $data = file_get_contents($file);
        
        if ($data === false) {
            return false;
        }
        
        $compressed = gzencode($data, 9);
        if ($compressed === false) {
            return false;
        }
        
        $result = file_put_contents($compressedFile, $compressed);
        return $result !== false;
    }

    /**
     * Compress file with bzip2
     *
     * @param string $file File path to compress
     *
     * @return bool True if compression successful
     */
    private function compressWithBzip2(string $file): bool
    {
        if (!function_exists('bzcompress')) {
            return false;
        }
        
        $compressedFile = $file . '.bz2';
        $data = file_get_contents($file);
        
        if ($data === false) {
            return false;
        }
        
        $compressed = bzcompress($data, 9);
        if ($compressed === false) {
            return false;
        }
        
        $result = file_put_contents($compressedFile, $compressed);
        return $result !== false;
    }

    /**
     * Archive rotated files to separate location
     *
     * @return void
     */
    private function archiveRotatedFiles(): void
    {
        $archivePath = $this->enhancedConfig['archive_path'];
        if (!is_dir($archivePath)) {
            mkdir($archivePath, 0755, true);
        }
        
        $baseUrl = $this->getUrl();
        $pattern = $this->getRotatedFilePattern($baseUrl);
        $files = glob($pattern);
        
        foreach ($files as $file) {
            $archiveFile = $archivePath . '/' . basename($file);
            if (copy($file, $archiveFile)) {
                unlink($file);
            }
        }
    }

    /**
     * Validate rotated files for integrity
     *
     * @return void
     */
    private function validateRotatedFiles(): void
    {
        if (!$this->enableIntegrityChecks) {
            return;
        }
        
        $baseUrl = $this->getUrl();
        $pattern = $this->getRotatedFilePattern($baseUrl);
        $files = glob($pattern);
        
        foreach ($files as $file) {
            if (!$this->validateFileIntegrity($file)) {
                error_log("Enhanced log file integrity check failed: {$file}");
            }
        }
    }

    /**
     * Validate file integrity
     *
     * @param string $file File to validate
     *
     * @return bool True if file is valid
     */
    private function validateFileIntegrity(string $file): bool
    {
        // Basic validation - file exists and is readable
        if (!file_exists($file) || !is_readable($file)) {
            return false;
        }
        
        // Check file size is reasonable
        $size = filesize($file);
        if ($size === false || $size < 0) {
            return false;
        }
        
        // For compressed files, try to read headers
        if ($this->stringEndsWith($file, '.gz')) {
            return $this->validateGzipFile($file);
        }
        
        if ($this->stringEndsWith($file, '.bz2')) {
            return $this->validateBzip2File($file);
        }
        
        return true;
    }

    /**
     * Validate gzip file integrity
     *
     * @param string $file Gzip file to validate
     *
     * @return bool True if file is valid gzip
     */
    private function validateGzipFile(string $file): bool
    {
        $handle = @gzopen($file, 'r');
        if ($handle === false) {
            return false;
        }
        
        // Try to read first chunk
        $chunk = @gzread($handle, 1024);
        @gzclose($handle);
        
        return $chunk !== false;
    }

    /**
     * Validate bzip2 file integrity
     *
     * @param string $file Bzip2 file to validate
     *
     * @return bool True if file is valid bzip2
     */
    private function validateBzip2File(string $file): bool
    {
        if (!function_exists('bzopen')) {
            return true; // Can't validate, assume valid
        }
        
        $handle = @bzopen($file, 'r');
        if ($handle === false) {
            return false;
        }
        
        // Try to read first chunk
        $chunk = @bzread($handle, 1024);
        @bzclose($handle);
        
        return $chunk !== false;
    }

    /**
     * Check if integrity check should be performed
     *
     * @return bool True if integrity check should be performed
     */
    private function shouldPerformIntegrityCheck(): bool
    {
        // Perform integrity check periodically, not on every write
        static $lastCheck = 0;
        $now = time();
        
        if ($now - $lastCheck > 3600) { // Check every hour
            $lastCheck = $now;
            return true;
        }
        
        return false;
    }

    /**
     * Perform integrity check on current log file
     *
     * @return void
     */
    private function performIntegrityCheck(): void
    {
        $file = $this->getUrl();
        if (!$this->validateFileIntegrity($file)) {
            error_log("Enhanced log file integrity check failed: {$file}");
        }
    }

    /**
     * Monitor write performance
     *
     * @param float $startTime Start time of write operation
     * @param array $record Log record that was written
     *
     * @return void
     */
    private function monitorWritePerformance(float $startTime, array $record): void
    {
        $duration = microtime(true) - $startTime;
        
        // Log slow write operations
        if ($duration > 0.1) { // 100ms threshold
            error_log(sprintf(
                "Enhanced log slow write operation: %.3fs for %d bytes",
                $duration,
                strlen($record['formatted'] ?? '')
            ));
        }
    }

    /**
     * Check if cleanup should be performed
     *
     * @return bool True if cleanup should be performed
     */
    private function shouldCleanup(): bool
    {
        // Perform cleanup periodically
        static $lastCleanup = 0;
        $now = time();
        
        if ($now - $lastCleanup > 86400) { // Once per day
            $lastCleanup = $now;
            return true;
        }
        
        return false;
    }

    /**
     * Clean up old log files based on retention policy
     *
     * @return void
     */
    private function cleanupOldFiles(): void
    {
        $cutoffTime = time() - ($this->retentionDays * 86400);
        $baseUrl = $this->getUrl();
        $pattern = $this->getRotatedFilePattern($baseUrl);
        $files = glob($pattern);
        
        // Also check for compressed files
        $compressedPatterns = [
            $pattern . '.gz',
            $pattern . '.bz2'
        ];
        
        foreach ($compressedPatterns as $compressedPattern) {
            $compressedFiles = glob($compressedPattern);
            $files = array_merge($files, $compressedFiles);
        }
        
        foreach ($files as $file) {
            $fileTime = filemtime($file);
            if ($fileTime && $fileTime < $cutoffTime) {
                if (unlink($file)) {
                    $this->rotationStats['total_cleaned']++;
                }
            }
        }
    }

    /**
     * Update rotation statistics
     *
     * @param float $rotationStart Start time of rotation
     * @param int $preRotationSize File size before rotation
     *
     * @return void
     */
    private function updateRotationStats(float $rotationStart, int $preRotationSize): void
    {
        $rotationDuration = microtime(true) - $rotationStart;
        
        $this->rotationStats['total_rotations']++;
        $this->rotationStats['last_rotation'] = date('c');
        
        // Update average file size
        if ($this->rotationStats['total_rotations'] > 0) {
            $currentAvg = $this->rotationStats['average_file_size'];
            $newAvg = (($currentAvg * ($this->rotationStats['total_rotations'] - 1)) + $preRotationSize) / $this->rotationStats['total_rotations'];
            $this->rotationStats['average_file_size'] = $newAvg;
        }
        
        // Log rotation performance if slow
        if ($rotationDuration > 1.0) { // 1 second threshold
            error_log(sprintf(
                "Enhanced log slow rotation: %.3fs for %d bytes",
                $rotationDuration,
                $preRotationSize
            ));
        }
    }

    /**
     * Get rotation statistics
     *
     * @return array Current rotation statistics
     */
    public function getRotationStats(): array
    {
        return $this->rotationStats;
    }

    /**
     * Initialize enhanced features
     *
     * @return void
     */
    private function initializeEnhancedFeatures(): void
    {
        // Ensure log directory exists and is writable
        $logDir = dirname($this->getUrl());
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        // Initialize archive directory if needed
        if ($this->enhancedConfig['backup_to_archive'] && $this->enhancedConfig['archive_path']) {
            $archivePath = $this->enhancedConfig['archive_path'];
            if (!is_dir($archivePath)) {
                mkdir($archivePath, 0755, true);
            }
        }
    }

    /**
     * PHP 7.4 compatible string ends with function
     *
     * Replacement for PHP 8.0+ str_ends_with() function to maintain
     * compatibility with PHP 7.4 Docker environment.
     *
     * @param string $haystack The string to search in
     * @param string $needle The substring to search for
     *
     * @return bool True if haystack ends with needle
     */
    private function stringEndsWith(string $haystack, string $needle): bool
    {
        $length = strlen($needle);
        if ($length === 0) {
            return true;
        }
        return substr($haystack, -$length) === $needle;
    }
}
