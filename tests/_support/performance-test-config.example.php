<?php
/**
 * @fileoverview Performance Test Configuration Example
 * 
 * This file demonstrates how to configure performance testing thresholds
 * for different environments (development, staging, production testing).
 * Copy this file and customize values for your specific testing needs.
 * 
 * Usage:
 * 1. Copy this file to performance-test-config.php
 * 2. Customize thresholds for your environment
 * 3. Include in your test setup or CI/CD pipeline
 * 
 * @package SuiteCRM\Tests\Configuration
 * @copyright Copyright (C) 2011 - 2024 SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 * @since 1.0.0
 */

// Performance Test Configuration
return [
    
    // =============================================================================
    // DEVELOPMENT ENVIRONMENT CONFIGURATION
    // =============================================================================
    'development' => [
        'performance_thresholds' => [
            'max_avg_response_time' => 3000,      // 3 seconds (relaxed for dev)
            'max_response_time' => 8000,          // 8 seconds maximum
            'min_security_compliance' => 70,      // 70% minimum compliance
            'min_documentation_health' => 60,     // 60% minimum documentation health
        ],
        'load_testing' => [
            'concurrent_users' => 2,              // Light load for development
            'duration_seconds' => 10,             // Short duration
            'ramp_up_seconds' => 5,               // Quick ramp up
        ],
        'validation_thresholds' => [
            'max_validation_time' => 1000,       // 1 second for validation
            'max_payload_size' => 5120,           // 5KB max payload
        ]
    ],
    
    // =============================================================================
    // STAGING ENVIRONMENT CONFIGURATION
    // =============================================================================
    'staging' => [
        'performance_thresholds' => [
            'max_avg_response_time' => 2000,      // 2 seconds (production-like)
            'max_response_time' => 5000,          // 5 seconds maximum
            'min_security_compliance' => 85,      // 85% minimum compliance
            'min_documentation_health' => 80,     // 80% minimum documentation health
        ],
        'load_testing' => [
            'concurrent_users' => 10,             // Moderate load for staging
            'duration_seconds' => 30,             // Medium duration
            'ramp_up_seconds' => 10,              // Moderate ramp up
        ],
        'validation_thresholds' => [
            'max_validation_time' => 500,        // 500ms for validation
            'max_payload_size' => 2048,           // 2KB max payload
        ]
    ],
    
    // =============================================================================
    // PRODUCTION TESTING CONFIGURATION
    // =============================================================================
    'production' => [
        'performance_thresholds' => [
            'max_avg_response_time' => 1500,      // 1.5 seconds (strict)
            'max_response_time' => 3000,          // 3 seconds maximum
            'min_security_compliance' => 95,      // 95% minimum compliance
            'min_documentation_health' => 90,     // 90% minimum documentation health
        ],
        'load_testing' => [
            'concurrent_users' => 50,             // High load for production testing
            'duration_seconds' => 60,             // Extended duration
            'ramp_up_seconds' => 20,              // Gradual ramp up
        ],
        'validation_thresholds' => [
            'max_validation_time' => 200,        // 200ms for validation
            'max_payload_size' => 1024,           // 1KB max payload
        ]
    ],
    
    // =============================================================================
    // CI/CD PIPELINE CONFIGURATION
    // =============================================================================
    'ci_cd' => [
        'performance_thresholds' => [
            'max_avg_response_time' => 2500,      // 2.5 seconds (CI resources)
            'max_response_time' => 6000,          // 6 seconds maximum
            'min_security_compliance' => 80,      // 80% minimum compliance
            'min_documentation_health' => 75,     // 75% minimum documentation health
        ],
        'load_testing' => [
            'concurrent_users' => 5,              // Light load for CI
            'duration_seconds' => 15,             // Quick test
            'ramp_up_seconds' => 5,               // Fast ramp up
        ],
        'validation_thresholds' => [
            'max_validation_time' => 800,        // 800ms for validation
            'max_payload_size' => 3072,           // 3KB max payload
        ]
    ],
    
    // =============================================================================
    // DOCKER ENVIRONMENT CONFIGURATION
    // =============================================================================
    'docker' => [
        'performance_thresholds' => [
            'max_avg_response_time' => 3500,      // 3.5 seconds (container overhead)
            'max_response_time' => 7000,          // 7 seconds maximum
            'min_security_compliance' => 75,      // 75% minimum compliance
            'min_documentation_health' => 70,     // 70% minimum documentation health
        ],
        'load_testing' => [
            'concurrent_users' => 3,              // Conservative for containers
            'duration_seconds' => 20,             // Medium duration
            'ramp_up_seconds' => 8,               // Slower ramp up
        ],
        'validation_thresholds' => [
            'max_validation_time' => 1200,       // 1.2 seconds for validation
            'max_payload_size' => 4096,           // 4KB max payload
        ],
        'container_limits' => [
            'memory_limit' => '1G',               // Container memory limit
            'cpu_limit' => '1.0',                 // Container CPU limit
        ]
    ],
    
    // =============================================================================
    // CUSTOM ENVIRONMENT CONFIGURATION
    // =============================================================================
    'custom' => [
        'performance_thresholds' => [
            'max_avg_response_time' => 2000,      // Customize as needed
            'max_response_time' => 5000,          // Customize as needed
            'min_security_compliance' => 80,      // Customize as needed
            'min_documentation_health' => 75,     // Customize as needed
        ],
        'load_testing' => [
            'concurrent_users' => 10,             // Customize as needed
            'duration_seconds' => 30,             // Customize as needed
            'ramp_up_seconds' => 10,              // Customize as needed
        ],
        'validation_thresholds' => [
            'max_validation_time' => 500,        // Customize as needed
            'max_payload_size' => 2048,           // Customize as needed
        ]
    ],
    
    // =============================================================================
    // UTILITY FUNCTIONS
    // =============================================================================
    
    /**
     * Get configuration for specific environment
     * 
     * @param string $environment Environment name
     * @return array Environment configuration
     */
    'getEnvironmentConfig' => function($environment = 'development') {
        $config = include __FILE__;
        return $config[$environment] ?? $config['development'];
    },
    
    /**
     * Apply configuration to API helper
     * 
     * @param object $apiHelper API helper instance
     * @param string $environment Environment name
     */
    'applyToApiHelper' => function($apiHelper, $environment = 'development') {
        $config = include __FILE__;
        $envConfig = $config[$environment] ?? $config['development'];
        
        if (method_exists($apiHelper, 'configurePerformanceThresholds')) {
            $apiHelper->configurePerformanceThresholds($envConfig['performance_thresholds']);
        }
    },
    
    /**
     * Set environment variables for testing
     * 
     * @param string $environment Environment name
     */
    'setEnvironmentVariables' => function($environment = 'development') {
        $config = include __FILE__;
        $envConfig = $config[$environment] ?? $config['development'];
        
        foreach ($envConfig['performance_thresholds'] as $key => $value) {
            $envVar = 'PERFORMANCE_' . strtoupper($key);
            putenv("{$envVar}={$value}");
        }
        
        foreach ($envConfig['validation_thresholds'] ?? [] as $key => $value) {
            $envVar = 'VALIDATION_' . strtoupper($key);
            putenv("{$envVar}={$value}");
        }
    }
];

/**
 * Usage Examples:
 * 
 * // Load configuration for development environment
 * $config = include 'performance-test-config.php';
 * $devConfig = $config['development'];
 * 
 * // Apply configuration to API helper
 * $apiHelper = new Helper\api();
 * $config['applyToApiHelper']($apiHelper, 'staging');
 * 
 * // Set environment variables for CI/CD
 * $config['setEnvironmentVariables']('ci_cd');
 * 
 * // Custom configuration for specific test case
 * $customThresholds = [
 *     'max_avg_response_time' => 1000,
 *     'max_response_time' => 2000,
 *     'min_security_compliance' => 90
 * ];
 * $apiHelper->configurePerformanceThresholds($customThresholds);
 */ 