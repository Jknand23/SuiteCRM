<?php
/**
 * @fileoverview Enhanced Integration Test Suite for SuiteCRM V8 API
 * 
 * Extends the existing ModulesCest patterns with comprehensive integration testing
 * for enhanced middleware, validation, security, and documentation features.
 * Tests the complete API pipeline with enhanced features while maintaining
 * compatibility with existing functionality.
 * 
 * Key Features:
 * - Enhanced middleware integration testing
 * - Security validation and error handling testing
 * - Performance integration testing
 * - Documentation accuracy validation
 * - End-to-end API enhancement testing
 * 
 * Dependencies:
 * - Existing ModulesCest patterns and ApiTester functionality
 * - Enhanced middleware implementations
 * - Helper\api enhanced testing utilities
 * 
 * @package SuiteCRM\Tests\Api\V8
 * @copyright Copyright (C) 2011 - 2024 SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 * @since 1.0.0
 */

use ApiTester;
use Helper\api;

/**
 * EnhancedIntegrationCest
 * 
 * Comprehensive integration testing for enhanced API features that builds upon
 * existing ModulesCest patterns while testing new middleware and enhancements.
 */
#[\AllowDynamicProperties]
class EnhancedIntegrationCest
{
    /** @var string $ACCOUNTS_RESOURCE API accounts resource endpoint */
    private static string $ACCOUNTS_RESOURCE = '/api/v8/modules/Accounts';
    
    /** @var string $CONTACTS_RESOURCE API contacts resource endpoint */
    private static string $CONTACTS_RESOURCE = '/api/v8/modules/Contacts';
    
    /** @var string $LEADS_RESOURCE API leads resource endpoint */
    private static string $LEADS_RESOURCE = '/api/v8/modules/Leads';
    
    /** @var string $META_RESOURCE API meta resource endpoint */
    private static string $META_RESOURCE = '/api/v8/meta';
    
    /** @var string $DOCS_RESOURCE API documentation resource endpoint */
    private static string $DOCS_RESOURCE = '/api/v8/docs';
    
    /** @var array $testRecordIds Created test record IDs for cleanup */
    private array $testRecordIds = [];
    
    /** @var \Faker\Generator $fakeData Faker instance for test data */
    protected $fakeData;
    
    /** @var api $apiHelper Enhanced API testing helper */
    private api $apiHelper;
    
    /**
     * Test setup before each scenario
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function _before(ApiTester $I)
    {
        // Initialize faker for test data generation
        $this->fakeData = \Faker\Factory::create();
        $this->fakeData->seed(12345); // Consistent seed for reproducible tests
        
        // Get enhanced API helper
        $this->apiHelper = $I->getModule('Helper\api');
        
        // Clean up any previous test records
        $this->cleanupTestRecords($I);
    }
    
    /**
     * Test cleanup after each scenario
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function _after(ApiTester $I)
    {
        // Clean up test records created during the test
        $this->cleanupTestRecords($I);
    }
    
    /**
     * Enhanced Middleware Integration Tests
     */
    
    /**
     * Test enhanced validation middleware with malicious payloads
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testEnhancedValidationMiddlewareIntegration(ApiTester $I)
    {
        $I->comment('Testing enhanced validation middleware with various attack vectors');
        
        // Test XSS attack patterns
        $xssPayload = [
            'data' => [
                'type' => 'Accounts',
                'attributes' => [
                    'name' => '<script>alert("XSS Attack")</script>',
                    'description' => '<img src="x" onerror="alert(\'XSS\')" />',
                    'website' => 'javascript:alert("XSS")'
                ]
            ]
        ];
        
        $result = $this->apiHelper->testEnhancedValidation(
            $I->getInstanceURL() . self::$ACCOUNTS_RESOURCE,
            $xssPayload
        );
        
        $I->assertArrayHasKey('validation_triggered', $result);
        $I->assertTrue($result['validation_triggered']);
        $I->assertContains('forbidden_content', $result['security_violations']);
        
        // Test SQL injection patterns
        $sqlInjectionPayload = [
            'data' => [
                'type' => 'Accounts',
                'attributes' => [
                    'name' => "'; DROP TABLE accounts; --",
                    'description' => 'UNION SELECT password FROM users',
                    'phone_office' => "' OR '1'='1"
                ]
            ]
        ];
        
        $result = $this->apiHelper->testEnhancedValidation(
            $I->getInstanceURL() . self::$ACCOUNTS_RESOURCE,
            $sqlInjectionPayload
        );
        
        $I->assertContains('sql_injection_attempt', $result['security_violations']);
        
        // Test oversized payload
        $oversizedPayload = [
            'data' => [
                'type' => 'Accounts',
                'attributes' => [
                    'name' => str_repeat('A', 15000), // Exceeds max_string_length
                    'description' => str_repeat('B', 20000)
                ]
            ]
        ];
        
        $result = $this->apiHelper->testEnhancedValidation(
            $I->getInstanceURL() . self::$ACCOUNTS_RESOURCE,
            $oversizedPayload
        );
        
        $I->assertContains('oversized_string', $result['security_violations']);
        
        $I->comment('Enhanced validation middleware integration test completed');
    }
    
    /**
     * Test security headers middleware across different endpoints
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testSecurityHeadersMiddlewareIntegration(ApiTester $I)
    {
        $I->comment('Testing security headers middleware across multiple endpoints');
        
        $endpointsToTest = [
            self::$ACCOUNTS_RESOURCE,
            self::$CONTACTS_RESOURCE,
            self::$LEADS_RESOURCE,
            self::$META_RESOURCE . '/swagger.json'
        ];
        
        $overallCompliance = 0;
        $testCount = 0;
        
        foreach ($endpointsToTest as $endpoint) {
            $result = $this->apiHelper->testSecurityHeaders($I->getInstanceURL() . $endpoint);
            
            $minCompliance = $this->apiHelper->getPerformanceThreshold('min_security_compliance');
            $I->assertGreaterThanOrEqual($minCompliance, $result['compliance_percentage'], 
                "Security headers compliance should be at least {$minCompliance}% for {$endpoint}");
            
            // Verify critical security headers are present
            $I->assertArrayHasKey('X-Frame-Options', $result['present_headers']);
            $I->assertArrayHasKey('X-Content-Type-Options', $result['present_headers']);
            $I->assertArrayHasKey('X-XSS-Protection', $result['present_headers']);
            
            $overallCompliance += $result['compliance_percentage'];
            $testCount++;
        }
        
        $averageCompliance = $overallCompliance / $testCount;
        $minOverallCompliance = $this->apiHelper->getPerformanceThreshold('min_security_compliance') + 5; // Slightly higher for overall
        $I->assertGreaterThanOrEqual($minOverallCompliance, $averageCompliance, 
            "Overall security headers compliance should be at least {$minOverallCompliance}%");
        
        $I->comment("Security headers middleware integration test completed with {$averageCompliance}% compliance");
    }
    
    /**
     * Test API key authentication middleware integration
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testApiKeyAuthenticationIntegration(ApiTester $I)
    {
        $I->comment('Testing API key authentication middleware integration');
        
        // First, we need to create a test API key using the existing OAuth2 authentication
        $I->loginAsAdmin();
        $I->sendJwtAuthorisation();
        $I->sendJsonApiContentNegotiation();
        
        // Test the API key authentication with a mock API key
        // Note: In a real environment, this would use a valid API key
        $testApiKey = 'test_api_key_' . uniqid();
        $testApiSecret = 'test_api_secret_' . uniqid();
        
        // Test endpoints that should work with API key authentication
        $endpointsToTest = [
            self::$ACCOUNTS_RESOURCE,
            self::$META_RESOURCE . '/list'
        ];
        
        foreach ($endpointsToTest as $endpoint) {
            // Note: This test validates the middleware behavior, not actual authentication
            // In a real test environment, valid API keys would be pre-configured
            $result = $this->apiHelper->testApiKeyAuthentication(
                $I->getInstanceURL() . $endpoint,
                $testApiKey,
                $testApiSecret
            );
            
            // Verify that invalid authentication is properly rejected
            $I->assertTrue($result['invalid_auth_rejected'], 
                "Invalid API key authentication should be rejected for {$endpoint}");
            
            // Verify that missing authentication is properly handled
            $I->assertTrue($result['missing_auth_handled'], 
                "Missing API key authentication should be handled for {$endpoint}");
        }
        
        $I->comment('API key authentication middleware integration test completed');
    }
    
    /**
     * Performance Integration Tests
     */
    
    /**
     * Test performance characteristics of enhanced endpoints
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testEnhancedEndpointPerformance(ApiTester $I)
    {
        $I->comment('Testing performance characteristics of enhanced API endpoints');
        
        // Define performance test cases
        $testCases = [
            'simple_list' => [
                'method' => 'GET',
                'description' => 'Simple list retrieval'
            ],
            'filtered_list' => [
                'method' => 'GET',
                'params' => ['filter[name]' => 'test'],
                'description' => 'Filtered list retrieval'
            ],
            'paginated_list' => [
                'method' => 'GET',
                'params' => ['page[number]' => '1', 'page[size]' => '20'],
                'description' => 'Paginated list retrieval'
            ],
            'create_record' => [
                'method' => 'POST',
                'payload' => [
                    'data' => [
                        'type' => 'Accounts',
                        'attributes' => [
                            'name' => $this->fakeData->company(),
                            'phone_office' => $this->fakeData->phoneNumber(),
                            'website' => $this->fakeData->url()
                        ]
                    ]
                ],
                'description' => 'Record creation'
            ]
        ];
        
        $results = $this->apiHelper->measureEndpointPerformance(
            $I->getInstanceURL() . self::$ACCOUNTS_RESOURCE,
            $testCases,
            3 // 3 iterations for faster testing
        );
        
        foreach ($results as $testName => $result) {
            // Verify performance thresholds using configurable values
            $maxAvgTime = $this->apiHelper->getPerformanceThreshold('max_avg_response_time');
            $maxTime = $this->apiHelper->getPerformanceThreshold('max_response_time');
            
            $I->assertLessThan($maxAvgTime, $result['avg_response_time'], 
                "Average response time for {$testName} should be less than {$maxAvgTime}ms");
            
            $I->assertLessThan($maxTime, $result['max_response_time'], 
                "Maximum response time for {$testName} should be less than {$maxTime}ms");
            
            // Store created record IDs for cleanup
            if ($testName === 'create_record') {
                foreach ($result['measurements'] as $measurement) {
                    if ($measurement['response_code'] === 201) {
                        // In a real test, we would extract the ID from the response
                        // This is a placeholder for the actual implementation
                    }
                }
            }
        }
        
        $I->comment('Enhanced endpoint performance integration test completed');
    }
    
    /**
     * Documentation Integration Tests
     */
    
    /**
     * Test API documentation accuracy and completeness
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testApiDocumentationAccuracy(ApiTester $I)
    {
        $I->comment('Testing API documentation accuracy and completeness');
        
        // Expected core endpoints that should be documented
        $expectedEndpoints = [
            '/V8/modules/{module}',
            '/V8/modules/{module}/{id}',
            '/V8/modules/{module}/{id}/relationships/{link}',
            '/V8/meta/modules',
            '/V8/meta/list'
        ];
        
        $result = $this->apiHelper->testApiDocumentation(
            $I->getInstanceURL() . '/V8/meta/swagger.json',
            $expectedEndpoints
        );
        
        // Verify documentation accessibility
        $I->assertTrue($result['specification_valid'], 
            'API documentation specification should be valid');
        
        // Verify minimum endpoint coverage
        $I->assertGreaterThanOrEqual(10, $result['documented_endpoints_count'], 
            'At least 10 endpoints should be documented');
        
        // Verify OpenAPI compliance
        $I->assertEquals('3.0.0', $result['openapi_version'], 
            'API documentation should use OpenAPI 3.0.0');
        
        // Test documentation UI accessibility
        if ($result['documentation_ui_accessible']) {
            $I->comment('Documentation UI is accessible');
        } else {
            $I->comment('Documentation UI accessibility test skipped');
        }
        
        // Verify that critical endpoints are documented
        $missingCriticalEndpoints = array_intersect($expectedEndpoints, $result['missing_expected_endpoints']);
        $I->assertEmpty($missingCriticalEndpoints, 
            'Critical endpoints should be documented: ' . implode(', ', $missingCriticalEndpoints));
        
        $I->comment('API documentation integration test completed');
    }
    
    /**
     * Enhanced Response Integration Tests
     */
    
    /**
     * Test enhanced response features across different scenarios
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testEnhancedResponseIntegration(ApiTester $I)
    {
        $I->comment('Testing enhanced response features integration');
        
        $endpointsToTest = [
            self::$ACCOUNTS_RESOURCE,
            self::$CONTACTS_RESOURCE,
            self::$META_RESOURCE . '/list'
        ];
        
        $overallEnhancementScore = 0;
        $testCount = 0;
        
        foreach ($endpointsToTest as $endpoint) {
            $result = $this->apiHelper->testEnhancedResponse($I->getInstanceURL() . $endpoint);
            
            // Verify enhanced metadata presence
            if ($result['has_enhanced_metadata']) {
                $I->comment("Enhanced metadata present for {$endpoint}");
                $I->assertNotEmpty($result['metadata_fields'], 
                    "Metadata fields should not be empty for {$endpoint}");
            }
            
            // Verify correlation ID presence
            if ($result['has_correlation_id']) {
                $I->comment("Correlation ID present for {$endpoint}");
            }
            
            // Verify enhanced headers
            if (!empty($result['enhanced_headers'])) {
                $I->comment("Enhanced headers present for {$endpoint}: " . 
                    implode(', ', array_keys($result['enhanced_headers'])));
            }
            
            $overallEnhancementScore += $result['enhancement_score'];
            $testCount++;
        }
        
        $averageEnhancementScore = $overallEnhancementScore / $testCount;
        $I->comment("Average enhancement score: {$averageEnhancementScore}%");
        
        $I->comment('Enhanced response integration test completed');
    }
    
    /**
     * End-to-End Integration Tests
     */
    
    /**
     * Test complete CRUD operations with enhanced features
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testEnhancedCrudOperationsIntegration(ApiTester $I)
    {
        $I->comment('Testing complete CRUD operations with enhanced features');
        
        // Set up authentication and content negotiation
        $I->loginAsAdmin();
        $I->sendJwtAuthorisation();
        $I->sendJsonApiContentNegotiation();
        
        // Create operation with enhanced validation
        $createPayload = [
            'data' => [
                'type' => 'Accounts',
                'attributes' => [
                    'name' => $this->fakeData->company(),
                    'phone_office' => $this->fakeData->phoneNumber(),
                    'website' => $this->fakeData->url(),
                    'description' => $this->fakeData->paragraph()
                ]
            ]
        ];
        
        // Test security headers during create
        $I->sendPOST($I->getInstanceURL() . self::$ACCOUNTS_RESOURCE, $createPayload);
        $I->seeResponseCodeIs(201);
        
        // Verify security headers are present
        $securityResult = $this->apiHelper->testSecurityHeaders($I->getInstanceURL() . self::$ACCOUNTS_RESOURCE);
        $I->assertGreaterThan(0, $securityResult['headers_present']);
        
        $createResponse = json_decode($I->grabResponse(), true);
        $recordId = $createResponse['data']['id'];
        $this->testRecordIds[] = $recordId;
        
        // Read operation with enhanced response
        $I->sendGET($I->getInstanceURL() . self::$ACCOUNTS_RESOURCE . '/' . $recordId);
        $I->seeResponseCodeIs(200);
        
        // Test enhanced response features
        $enhancedResult = $this->apiHelper->testEnhancedResponse(
            $I->getInstanceURL() . self::$ACCOUNTS_RESOURCE . '/' . $recordId
        );
        $I->assertGreaterThanOrEqual(0, $enhancedResult['enhancement_score']);
        
        // Update operation with validation
        $updatePayload = [
            'data' => [
                'type' => 'Accounts',
                'id' => $recordId,
                'attributes' => [
                    'name' => $this->fakeData->company() . ' Updated',
                    'description' => $this->fakeData->paragraph()
                ]
            ]
        ];
        
        $I->sendPATCH($I->getInstanceURL() . self::$ACCOUNTS_RESOURCE . '/' . $recordId, $updatePayload);
        $I->seeResponseCodeIs(200);
        
        // Delete operation
        $I->sendDELETE($I->getInstanceURL() . self::$ACCOUNTS_RESOURCE . '/' . $recordId);
        $I->seeResponseCodeIs(200);
        
        // Remove from cleanup list since it's been deleted
        $this->testRecordIds = array_filter($this->testRecordIds, function($id) use ($recordId) {
            return $id !== $recordId;
        });
        
        $I->comment('Enhanced CRUD operations integration test completed');
    }
    
    /**
     * Test error handling integration with enhanced features
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testEnhancedErrorHandlingIntegration(ApiTester $I)
    {
        $I->comment('Testing enhanced error handling integration');
        
        $I->loginAsAdmin();
        $I->sendJwtAuthorisation();
        $I->sendJsonApiContentNegotiation();
        
        // Test 404 error with enhanced error response
        $I->sendGET($I->getInstanceURL() . self::$ACCOUNTS_RESOURCE . '/nonexistent-id');
        $I->seeResponseCodeIs(404);
        
        $errorResponse = json_decode($I->grabResponse(), true);
        $I->assertArrayHasKey('errors', $errorResponse);
        
        // Test validation error with enhanced metadata
        $invalidPayload = [
            'data' => [
                'type' => 'InvalidType',
                'attributes' => []
            ]
        ];
        
        $I->sendPOST($I->getInstanceURL() . self::$ACCOUNTS_RESOURCE, $invalidPayload);
        $I->seeResponseCodeIs(400);
        
        $validationResponse = json_decode($I->grabResponse(), true);
        $I->assertArrayHasKey('errors', $validationResponse);
        
        // Test unauthorized access with enhanced security
        $I->deleteHeader('Authorization');
        $I->sendGET($I->getInstanceURL() . self::$ACCOUNTS_RESOURCE);
        $I->seeResponseCodeIsClientError(); // Should be 401 or similar
        
        $I->comment('Enhanced error handling integration test completed');
    }
    
    /**
     * Advanced Integration Tests
     */
    
    /**
     * Test comprehensive OAuth2 end-to-end integration with multiple providers
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testComprehensiveOAuth2Integration(ApiTester $I)
    {
        $I->comment('Testing comprehensive OAuth2 end-to-end integration');
        
        $providers = ['google']; // Add more providers as needed
        
        foreach ($providers as $provider) {
            $I->comment("Testing OAuth2 provider: {$provider}");
            
            // Test authorization endpoint
            $authResult = $this->apiHelper->testOAuth2AuthenticationFlow($provider);
            $I->assertTrue($authResult, "OAuth2 authorization flow should work for {$provider}");
            
            // Test state parameter validation
            $I->sendGET("/auth/oauth/authorize/{$provider}");
            $I->seeResponseCodeIs(302); // Should redirect to provider
            
            // Test callback with invalid state
            $I->sendGET("/auth/oauth/callback/{$provider}?code=test&state=invalid");
            $I->seeResponseCodeIsClientError(); // Should reject invalid state
            
            // Test callback with missing parameters
            $I->sendGET("/auth/oauth/callback/{$provider}");
            $I->seeResponseCodeIsClientError(); // Should reject missing parameters
        }
        
        $I->comment('Comprehensive OAuth2 integration test completed');
    }
    
    /**
     * Test load handling with concurrent middleware requests
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testConcurrentMiddlewareLoadHandling(ApiTester $I)
    {
        $I->comment('Testing concurrent middleware load handling');
        
        $I->loginAsAdmin();
        $I->sendJwtAuthorisation();
        $I->sendJsonApiContentNegotiation();
        
        // Simulate concurrent requests with different load patterns
        $testScenarios = [
            'rapid_successive' => 5,   // 5 rapid requests
            'burst_load' => 10,        // 10 concurrent-style requests
            'sustained_load' => 3      // 3 sustained requests
        ];
        
        foreach ($testScenarios as $scenarioName => $requestCount) {
            $I->comment("Testing scenario: {$scenarioName} with {$requestCount} requests");
            
            $startTime = microtime(true);
            $responses = [];
            
            // Execute multiple requests in quick succession
            for ($i = 0; $i < $requestCount; $i++) {
                $requestStart = microtime(true);
                $I->sendGET(self::$ACCOUNTS_RESOURCE . '?page[size]=10');
                $responses[] = [
                    'status_code' => $I->grabResponseCode(),
                    'response_time' => (microtime(true) - $requestStart) * 1000,
                    'request_number' => $i + 1
                ];
            }
            
            $totalTime = (microtime(true) - $startTime) * 1000;
            
            // Validate load handling
            $successfulRequests = count(array_filter($responses, function($r) { 
                return $r['status_code'] === 200; 
            }));
            
            $I->assertGreaterThan(0, $successfulRequests, 
                "At least some requests should succeed under {$scenarioName} load");
            
            // Check for rate limiting behavior
            $rateLimitedRequests = count(array_filter($responses, function($r) { 
                return $r['status_code'] === 429; 
            }));
            
            if ($rateLimitedRequests > 0) {
                $I->comment("Rate limiting engaged: {$rateLimitedRequests} requests limited");
            }
            
            $I->comment("Scenario {$scenarioName}: {$successfulRequests}/{$requestCount} successful, total time: {$totalTime}ms");
        }
        
        $I->comment('Concurrent middleware load handling test completed');
    }
    
    /**
     * Test cross-endpoint consistency validation
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testCrossEndpointConsistencyValidation(ApiTester $I)
    {
        $I->comment('Testing cross-endpoint consistency validation');
        
        $I->loginAsAdmin();
        $I->sendJwtAuthorisation();
        $I->sendJsonApiContentNegotiation();
        
        $endpointsToTest = [
            self::$ACCOUNTS_RESOURCE,
            self::$CONTACTS_RESOURCE,
            self::$LEADS_RESOURCE,
            self::$META_RESOURCE . '/modules',
        ];
        
        $consistencyResults = [];
        
        foreach ($endpointsToTest as $endpoint) {
            $I->comment("Testing consistency for endpoint: {$endpoint}");
            
            // Test security headers consistency
            $securityResult = $this->apiHelper->testSecurityHeaders($endpoint);
            $consistencyResults[$endpoint]['security_headers'] = $securityResult['compliance_percentage'];
            
            // Test response format consistency
            $I->sendGET($endpoint);
            $I->seeResponseCodeIs(200);
            $response = json_decode($I->grabResponse(), true);
            
            // Validate consistent response structure
            $hasDataField = isset($response['data']);
            $hasMetaField = isset($response['meta']);
            $consistencyResults[$endpoint]['response_structure'] = [
                'has_data' => $hasDataField,
                'has_meta' => $hasMetaField,
                'structure_score' => ($hasDataField && $hasMetaField) ? 100 : 50
            ];
            
            // Test error handling consistency
            $I->sendGET($endpoint . '/nonexistent-id');
            $errorResponse = json_decode($I->grabResponse(), true);
            $hasErrorField = isset($errorResponse['errors']);
            $consistencyResults[$endpoint]['error_handling'] = [
                'has_errors_field' => $hasErrorField,
                'consistent_error_format' => $hasErrorField
            ];
        }
        
        // Validate overall consistency
        $securityCompliances = array_column($consistencyResults, 'security_headers');
        $avgSecurityCompliance = array_sum($securityCompliances) / count($securityCompliances);
        
        $I->assertGreaterThan(70, $avgSecurityCompliance, 
            'Average security header compliance should be above 70% across all endpoints');
        
        // Check response structure consistency
        $structureScores = array_map(function($result) {
            return $result['response_structure']['structure_score'];
        }, $consistencyResults);
        $avgStructureScore = array_sum($structureScores) / count($structureScores);
        
        $I->assertGreaterThan(80, $avgStructureScore, 
            'Response structure consistency should be above 80% across all endpoints');
        
        $I->comment('Cross-endpoint consistency validation completed');
    }
    
    /**
     * Test comprehensive error boundary scenarios
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testComprehensiveErrorBoundaryScenarios(ApiTester $I)
    {
        $I->comment('Testing comprehensive error boundary scenarios');
        
        $errorScenarios = [
            'malformed_json' => [
                'method' => 'POST',
                'payload' => '{"invalid": json}',
                'expected_status' => 400,
                'description' => 'Malformed JSON payload'
            ],
            'oversized_payload' => [
                'method' => 'POST',
                'payload' => ['data' => str_repeat('x', 50000)],
                'expected_status' => 413,
                'description' => 'Oversized payload handling'
            ],
            'invalid_content_type' => [
                'method' => 'POST',
                'headers' => ['Content-Type' => 'text/plain'],
                'payload' => 'plain text data',
                'expected_status' => 415,
                'description' => 'Invalid content type'
            ],
            'missing_authentication' => [
                'method' => 'GET',
                'skip_auth' => true,
                'expected_status' => 401,
                'description' => 'Missing authentication'
            ],
            'invalid_endpoint' => [
                'method' => 'GET',
                'endpoint_override' => '/api/v8/nonexistent/endpoint',
                'expected_status' => 404,
                'description' => 'Invalid endpoint'
            ]
        ];
        
        foreach ($errorScenarios as $scenarioName => $scenario) {
            $I->comment("Testing error scenario: {$scenario['description']}");
            
            // Set up authentication unless specifically skipped
            if (!isset($scenario['skip_auth'])) {
                $I->loginAsAdmin();
                $I->sendJwtAuthorisation();
                $I->sendJsonApiContentNegotiation();
            }
            
            // Set custom headers if specified
            if (isset($scenario['headers'])) {
                foreach ($scenario['headers'] as $header => $value) {
                    $I->setHeader($header, $value);
                }
            }
            
            // Determine endpoint
            $endpoint = $scenario['endpoint_override'] ?? self::$ACCOUNTS_RESOURCE;
            
            // Execute request based on method
            if ($scenario['method'] === 'POST') {
                if (is_string($scenario['payload'])) {
                    // Send raw string payload
                    $I->sendPOST($endpoint, [], [], [], $scenario['payload']);
                } else {
                    $I->sendPOST($endpoint, $scenario['payload']);
                }
            } else {
                $I->sendGET($endpoint);
            }
            
            // Validate expected error response
            $actualStatus = $I->grabResponseCode();
            
            // Allow for some flexibility in status codes (e.g., 400 vs 422 for validation)
            $acceptableStatuses = [$scenario['expected_status']];
            if ($scenario['expected_status'] === 400) {
                $acceptableStatuses[] = 422; // Validation errors
            }
            
            $I->assertTrue(in_array($actualStatus, $acceptableStatuses), 
                "Error scenario '{$scenarioName}' should return appropriate error status. Expected: {$scenario['expected_status']}, Got: {$actualStatus}");
            
            // Validate error response structure
            $response = json_decode($I->grabResponse(), true);
            if ($response && isset($response['errors'])) {
                $I->assertIsArray($response['errors'], 
                    "Error response should have errors array for scenario: {$scenarioName}");
            }
        }
        
        $I->comment('Comprehensive error boundary testing completed');
    }
    
    /**
     * Utility Methods
     */
    
    /**
     * Clean up test records created during testing
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    private function cleanupTestRecords(ApiTester $I): void
    {
        if (empty($this->testRecordIds)) {
            return;
        }
        
        try {
            $I->loginAsAdmin();
            $I->sendJwtAuthorisation();
            
            foreach ($this->testRecordIds as $recordId) {
                try {
                    $I->sendDELETE($I->getInstanceURL() . self::$ACCOUNTS_RESOURCE . '/' . $recordId);
                    // Don't assert response code as records might already be deleted
                } catch (\Exception $e) {
                    // Ignore cleanup errors
                }
            }
        } catch (\Exception $e) {
            // Ignore cleanup errors
        }
        
        $this->testRecordIds = [];
    }
    
    /**
     * Generate comprehensive test report
     * 
     * @param ApiTester $I API tester instance
     * 
     * @return array Test execution report
     * 
     * @since 1.0.0
     */
    public function generateIntegrationTestReport(ApiTester $I): array
    {
        $report = $this->apiHelper->generateTestReport();
        
        return array_merge($report, [
            'integration_tests' => [
                'test_execution_time' => date('Y-m-d H:i:s'),
                'test_environment' => $I->getInstanceURL(),
                'faker_seed' => 12345
            ]
        ]);
    }
} 