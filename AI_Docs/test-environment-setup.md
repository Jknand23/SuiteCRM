# Test Environment Setup Guide

## Overview

This guide provides the complete configuration template for setting up the enhanced testing infrastructure in Docker with PHP 7.4. The `.env.test` file is required for Feature 4, Step 3 completion.

## Setup Instructions

1. **Copy the template below** to create `.env.test` in your project root
2. **Customize values** for your Docker environment
3. **Verify OAuth2 credentials** are properly configured
4. **Run enhanced test commands** to validate setup

## .env.test Template

```bash
# SuiteCRM Test Environment Configuration
# This file provides configuration for enhanced testing infrastructure including
# OAuth2, API security, performance testing, and coverage validation

# =============================================================================
# DATABASE CONFIGURATION FOR TESTING
# =============================================================================
TEST_DATABASE_HOST=localhost
TEST_DATABASE_NAME=suitecrm_tests
TEST_DATABASE_USER=suitecrm_tests
TEST_DATABASE_PASSWORD=suitecrm_tests
TEST_DATABASE_DRIVER=mysql

# =============================================================================
# SUITECRM INSTANCE CONFIGURATION
# =============================================================================
TEST_INSTANCE_URL=http://localhost
TEST_INSTANCE_ADMIN_USER=admin
TEST_INSTANCE_ADMIN_PASSWORD=admin1
TEST_INSTANCE_CLIENT_ID=suitecrm_client
TEST_INSTANCE_CLIENT_SECRET=secret

# =============================================================================
# OAUTH2 TESTING CONFIGURATION
# =============================================================================
# Google OAuth2 Provider for Testing
OAUTH2_GOOGLE_CLIENT_ID=test_google_client_id_123
OAUTH2_GOOGLE_CLIENT_SECRET=test_google_client_secret_456
OAUTH2_GOOGLE_REDIRECT_URI=http://localhost/auth/oauth/callback/google

# OAuth2 Test Environment Settings
OAUTH2_TEST_MODE=true
OAUTH2_SKIP_SSL_VERIFICATION=true
OAUTH2_TEST_STATE_PREFIX=test_state_
OAUTH2_TEST_CODE_PREFIX=test_auth_code_

# =============================================================================
# API SECURITY TESTING CONFIGURATION
# =============================================================================
# API Key Authentication Testing
API_TEST_KEY=test_api_key_12345
API_TEST_SECRET=test_api_secret_67890
API_TEST_SCOPE=read,write

# Rate Limiting Test Configuration
RATE_LIMIT_TEST_ENABLED=true
RATE_LIMIT_TEST_REQUESTS_PER_MINUTE=100
RATE_LIMIT_TEST_BURST_LIMIT=20

# Security Headers Testing
SECURITY_HEADERS_TEST_ENABLED=true
SECURITY_HEADERS_STRICT_MODE=false

# Enhanced Validation Testing
VALIDATION_TEST_ENABLED=true
VALIDATION_TEST_MAX_STRING_LENGTH=1000
VALIDATION_TEST_MAX_PAYLOAD_SIZE=10240

# =============================================================================
# PERFORMANCE TESTING CONFIGURATION
# =============================================================================
# Response Time Thresholds (milliseconds)
PERFORMANCE_MAX_AVG_RESPONSE_TIME=2000
PERFORMANCE_MAX_RESPONSE_TIME=5000
PERFORMANCE_MAX_SLOW_QUERY_TIME=1000

# Memory Usage Thresholds (bytes)
PERFORMANCE_MAX_MEMORY_USAGE=134217728  # 128MB
PERFORMANCE_MAX_PEAK_MEMORY=268435456   # 256MB

# Security Compliance Thresholds (percentage)
SECURITY_MIN_COMPLIANCE_PERCENTAGE=80
SECURITY_MIN_HEADERS_PRESENT=5

# Documentation Health Thresholds (percentage)
DOCUMENTATION_MIN_HEALTH_PERCENTAGE=75
DOCUMENTATION_MIN_ENDPOINT_COVERAGE=10

# Load Testing Configuration
LOAD_TEST_ENABLED=false
LOAD_TEST_CONCURRENT_USERS=5
LOAD_TEST_DURATION_SECONDS=30
LOAD_TEST_RAMP_UP_SECONDS=10

# =============================================================================
# TESTING FRAMEWORK CONFIGURATION
# =============================================================================
# Codeception Configuration
CODECEPTION_DEBUG=false
CODECEPTION_VERBOSE=false
CODECEPTION_FAIL_FAST=false

# Test Data Configuration
TEST_DATA_CLEANUP_ENABLED=true
TEST_DATA_ISOLATION=true
TEST_DATA_SEED=12345

# Coverage Configuration
COVERAGE_ENABLED=true
COVERAGE_OUTPUT_FORMAT=html
COVERAGE_THRESHOLD_PERCENTAGE=75
COVERAGE_INCLUDE_NEW_COMPONENTS=true

# =============================================================================
# ENHANCED LOGGING CONFIGURATION
# =============================================================================
# Test Logging Levels
LOG_LEVEL_TESTS=info
LOG_LEVEL_PERFORMANCE=debug
LOG_LEVEL_SECURITY=warning

# Log Output Configuration
LOG_TEST_OUTPUT_ENABLED=true
LOG_TEST_OUTPUT_PATH=tests/_output/logs
LOG_TEST_RETENTION_DAYS=7

# =============================================================================
# NOTIFICATION TESTING CONFIGURATION
# =============================================================================
# Server-Sent Events Testing
SSE_TEST_ENABLED=true
SSE_TEST_ENDPOINT=/api/v8/notifications/stream
SSE_TEST_TIMEOUT_SECONDS=30
SSE_TEST_HEARTBEAT_INTERVAL=10

# Email Notification Testing
EMAIL_TEST_ENABLED=false
EMAIL_TEST_SMTP_HOST=localhost
EMAIL_TEST_SMTP_PORT=1025
EMAIL_TEST_FROM_ADDRESS=test@suitecrm.local

# =============================================================================
# BROWSER TESTING CONFIGURATION
# =============================================================================
# Selenium/WebDriver Configuration
SELENIUM_HOST=selenium
SELENIUM_PORT=4444
SELENIUM_BROWSER=chrome
SELENIUM_HEADLESS=true

# Browser Testing Timeouts
BROWSER_TIMEOUT_SECONDS=30
BROWSER_WAIT_TIMEOUT_SECONDS=10
BROWSER_PAGE_LOAD_TIMEOUT_SECONDS=30

# =============================================================================
# DOCKER TESTING CONFIGURATION
# =============================================================================
# Docker-specific test settings for PHP 7.4 compatibility
DOCKER_PHP_VERSION=7.4
DOCKER_MYSQL_VERSION=5.7
DOCKER_APACHE_VERSION=2.4

# Container Resource Limits
DOCKER_MEMORY_LIMIT=1G
DOCKER_CPU_LIMIT=1.0

# =============================================================================
# SECURITY TESTING CONFIGURATION
# =============================================================================
# XSS Testing Payloads (safe test patterns)
XSS_TEST_PATTERNS='<script>console.log("test")</script>,<img src="x" onerror="console.log(\'test\')">'

# SQL Injection Test Patterns (safe test patterns)
SQL_INJECTION_TEST_PATTERNS="'; DROP TABLE test; --,UNION SELECT 1,2,3"

# CSRF Testing
CSRF_TEST_ENABLED=true
CSRF_TEST_TOKEN_LENGTH=32

# =============================================================================
# CACHE TESTING CONFIGURATION
# =============================================================================
# Cache Testing Settings
CACHE_TEST_ENABLED=true
CACHE_TEST_DRIVER=array
CACHE_TEST_TTL=3600

# Session Testing
SESSION_TEST_DRIVER=array
SESSION_TEST_LIFETIME=7200

# =============================================================================
# DEBUGGING AND DEVELOPMENT
# =============================================================================
# Test Debugging
TEST_DEBUG_ENABLED=false
TEST_DEBUG_BREAKPOINTS=false
TEST_DEBUG_SQL_QUERIES=false

# Profiling
TEST_PROFILING_ENABLED=false
TEST_PROFILING_OUTPUT_PATH=tests/_output/profiling

# Error Reporting
TEST_ERROR_REPORTING=E_ALL
TEST_DISPLAY_ERRORS=true
TEST_LOG_ERRORS=true

# =============================================================================
# FEATURE FLAGS FOR TESTING
# =============================================================================
# Enhanced Component Testing Flags
TEST_OAUTH2_INTEGRATION=true
TEST_API_SECURITY_MIDDLEWARE=true
TEST_ENHANCED_VALIDATION=true
TEST_REQUEST_LOGGING=true
TEST_PERFORMANCE_MONITORING=true
TEST_DOCUMENTATION_GENERATION=true

# Legacy Component Testing Flags
TEST_EXISTING_FUNCTIONALITY=true
TEST_BACKWARD_COMPATIBILITY=true
TEST_REGRESSION_TESTING=true

# Experimental Features
TEST_EXPERIMENTAL_FEATURES=false
TEST_FUTURE_COMPATIBILITY=false
```

## Validation Commands

After creating the `.env.test` file, validate your setup:

```bash
# Test OAuth2 configuration
robo tests:configureEnhancedSecurityTests

# Validate OAuth2 test config  
robo tests:validateOAuth2TestConfig

# Generate enhanced test data
robo tests:generateEnhancedTestData

# Run enhanced coverage analysis
robo tests:runEnhancedCoverageAnalysis
```

## Docker Integration

The configuration is optimized for Docker with PHP 7.4:

- Database connections use Docker container names
- File paths are container-relative
- Resource limits appropriate for container environment
- Security settings compatible with Docker networking

## Security Considerations

- All test credentials are non-production values
- OAuth2 test mode prevents actual external API calls
- SQL injection and XSS test patterns are safe for testing
- API keys are clearly marked as test-only

## Troubleshooting

- **Permission Issues**: Ensure Docker container has write access to test output directories
- **Database Connection**: Verify Docker database container is running and accessible
- **OAuth2 Errors**: Check that test mode is enabled to prevent external API calls
- **Performance Issues**: Adjust memory limits and timeouts for your container resources 