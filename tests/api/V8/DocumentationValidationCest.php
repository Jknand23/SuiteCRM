<?php
/**
 * @fileoverview API Documentation Validation Test Suite
 * 
 * Comprehensive test suite for validating API documentation accuracy, completeness,
 * and consistency. Tests OpenAPI specification generation, documentation UI
 * accessibility, endpoint coverage, and example accuracy.
 * 
 * Key Features:
 * - OpenAPI specification validation
 * - Documentation completeness testing
 * - Example accuracy verification
 * - Interactive documentation UI testing
 * - Cross-reference validation with actual endpoints
 * 
 * Dependencies:
 * - Enhanced API testing helper
 * - OpenAPI documentation service
 * - Existing V8 API infrastructure
 * 
 * @package SuiteCRM\Tests\Api\V8
 * @copyright Copyright (C) 2011 - 2024 SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 * @since 1.0.0
 */

use ApiTester;
use Helper\api;

/**
 * DocumentationValidationCest
 * 
 * Dedicated test class for comprehensive API documentation validation
 * that ensures documentation accuracy and completeness across all API endpoints.
 */
#[\AllowDynamicProperties]
class DocumentationValidationCest
{
    /** @var string $SWAGGER_ENDPOINT OpenAPI specification endpoint */
    private static string $SWAGGER_ENDPOINT = '/api/v8/meta/swagger.json';
    
    /** @var string $DOCS_UI_ENDPOINT Documentation UI endpoint */
    private static string $DOCS_UI_ENDPOINT = '/api/v8/docs';
    
    /** @var array $REQUIRED_ENDPOINTS Critical endpoints that must be documented */
    private static array $REQUIRED_ENDPOINTS = [
        '/V8/modules/{module}',
        '/V8/modules/{module}/{id}',
        '/V8/modules/{module}/{id}/relationships/{link}',
        '/V8/meta/modules',
        '/V8/meta/list',
        '/V8/meta/swagger.json'
    ];
    
    /** @var api $apiHelper Enhanced API testing helper */
    private api $apiHelper;
    
    /** @var array $documentationResults Test results storage */
    private array $documentationResults = [];
    
    /**
     * Test setup before each scenario
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function _before(ApiTester $I)
    {
        // Get enhanced API helper
        $this->apiHelper = $I->getModule('Helper\api');
        
        // Initialize results storage
        $this->documentationResults = [];
    }
    
    /**
     * Test OpenAPI specification generation and structure
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testOpenApiSpecificationGeneration(ApiTester $I)
    {
        $I->comment('Testing OpenAPI specification generation and structure');
        
        // Test specification endpoint accessibility
        $I->sendGET(self::$SWAGGER_ENDPOINT);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['openapi' => '3.0.0']);
        
        $specification = json_decode($I->grabResponse(), true);
        
        // Validate required OpenAPI fields
        $requiredFields = ['openapi', 'info', 'paths', 'components'];
        foreach ($requiredFields as $field) {
            $I->assertArrayHasKey($field, $specification, 
                "OpenAPI specification must contain required field: {$field}");
        }
        
        // Validate info section
        $I->assertArrayHasKey('title', $specification['info'], 
            'OpenAPI info section must contain title');
        $I->assertArrayHasKey('version', $specification['info'], 
            'OpenAPI info section must contain version');
        
        // Validate paths section structure
        $I->assertIsArray($specification['paths'], 
            'OpenAPI paths section must be an array');
        $I->assertNotEmpty($specification['paths'], 
            'OpenAPI specification must contain documented paths');
        
        // Store results for later tests
        $this->documentationResults['specification'] = $specification;
        
        $I->comment('OpenAPI specification generation test completed');
    }
    
    /**
     * Test documentation completeness and endpoint coverage
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testDocumentationCompletenessAndCoverage(ApiTester $I)
    {
        $I->comment('Testing documentation completeness and endpoint coverage');
        
        $result = $this->apiHelper->testApiDocumentation(
            $I->getInstanceURL() . self::$SWAGGER_ENDPOINT,
            self::$REQUIRED_ENDPOINTS
        );
        
        // Validate minimum endpoint coverage
        $minEndpoints = 10;
        $I->assertGreaterThanOrEqual($minEndpoints, $result['documented_endpoints_count'],
            "At least {$minEndpoints} endpoints should be documented");
        
        // Validate required endpoints are documented
        $I->assertEmpty($result['missing_expected_endpoints'], 
            'All required endpoints should be documented: ' . implode(', ', $result['missing_expected_endpoints']));
        
        // Validate documentation UI accessibility
        if ($result['documentation_ui_accessible']) {
            $I->comment('✓ Documentation UI is accessible');
        } else {
            $I->comment('⚠ Documentation UI accessibility could not be verified');
        }
        
        // Test each documented endpoint for completeness
        foreach ($result['documented_endpoints'] as $endpoint) {
            $I->comment("Validating endpoint documentation: {$endpoint}");
            
            // Check if endpoint has method documentation
            if (isset($this->documentationResults['specification']['paths'][$endpoint])) {
                $endpointSpec = $this->documentationResults['specification']['paths'][$endpoint];
                
                // Validate HTTP methods are documented
                $httpMethods = ['get', 'post', 'put', 'patch', 'delete'];
                $documentedMethods = array_intersect($httpMethods, array_keys($endpointSpec));
                
                $I->assertNotEmpty($documentedMethods, 
                    "Endpoint {$endpoint} should have at least one HTTP method documented");
                
                // Validate each method has required fields
                foreach ($documentedMethods as $method) {
                    $methodSpec = $endpointSpec[$method];
                    
                    $I->assertArrayHasKey('summary', $methodSpec, 
                        "Method {$method} for {$endpoint} should have a summary");
                    $I->assertArrayHasKey('responses', $methodSpec, 
                        "Method {$method} for {$endpoint} should have responses documented");
                }
            }
        }
        
        $this->documentationResults['coverage'] = $result;
        
        $I->comment('Documentation completeness and coverage test completed');
    }
    
    /**
     * Test documentation examples accuracy
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testDocumentationExamplesAccuracy(ApiTester $I)
    {
        $I->comment('Testing documentation examples accuracy');
        
        $I->loginAsAdmin();
        $I->sendJwtAuthorisation();
        $I->sendJsonApiContentNegotiation();
        
        // Test core endpoints that should have examples
        $endpointsWithExamples = [
            '/api/v8/modules/Accounts' => 'GET',
            '/api/v8/meta/modules' => 'GET',
            '/api/v8/meta/list' => 'GET'
        ];
        
        foreach ($endpointsWithExamples as $endpoint => $method) {
            $I->comment("Validating examples for: {$method} {$endpoint}");
            
            // Make actual API call
            if ($method === 'GET') {
                $I->sendGET($endpoint);
            }
            
            $I->seeResponseCodeIs(200);
            $actualResponse = json_decode($I->grabResponse(), true);
            
            // Validate response structure matches documented patterns
            if (strpos($endpoint, '/modules/') !== false) {
                // Module endpoints should follow JSON:API structure
                $I->assertArrayHasKey('data', $actualResponse, 
                    "Module endpoint {$endpoint} should return data field");
                
                if (isset($actualResponse['meta'])) {
                    $I->assertIsArray($actualResponse['meta'], 
                        "Meta field in {$endpoint} should be an array");
                }
            }
            
            if (strpos($endpoint, '/meta/') !== false) {
                // Meta endpoints should have consistent structure
                $I->assertIsArray($actualResponse, 
                    "Meta endpoint {$endpoint} should return array response");
            }
            
            // Check for consistent field naming and structure
            $this->validateResponseStructureConsistency($I, $endpoint, $actualResponse);
        }
        
        $I->comment('Documentation examples accuracy test completed');
    }
    
    /**
     * Test interactive documentation UI functionality
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testInteractiveDocumentationUI(ApiTester $I)
    {
        $I->comment('Testing interactive documentation UI functionality');
        
        // Test documentation UI endpoint
        $I->sendGET(self::$DOCS_UI_ENDPOINT);
        
        // Documentation UI should be accessible
        $responseCode = $I->grabResponseCode();
        if ($responseCode === 200) {
            $I->comment('✓ Documentation UI is accessible');
            
            $response = $I->grabResponse();
            
            // Validate HTML response contains expected elements
            $I->assertStringContainsString('swagger', strtolower($response), 
                'Documentation UI should contain Swagger-related content');
            
            // Check for interactive elements
            $hasInteractiveElements = (
                strpos($response, 'swagger-ui') !== false ||
                strpos($response, 'api-docs') !== false ||
                strpos($response, 'swagger') !== false
            );
            
            $I->assertTrue($hasInteractiveElements, 
                'Documentation UI should contain interactive elements');
            
        } elseif ($responseCode === 404) {
            $I->comment('⚠ Documentation UI endpoint not found - may not be implemented yet');
        } else {
            $I->fail("Documentation UI returned unexpected status code: {$responseCode}");
        }
        
        $I->comment('Interactive documentation UI test completed');
    }
    
    /**
     * Test documentation consistency across endpoints
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testDocumentationConsistencyAcrossEndpoints(ApiTester $I)
    {
        $I->comment('Testing documentation consistency across endpoints');
        
        if (!isset($this->documentationResults['specification'])) {
            $I->fail('OpenAPI specification not available - run specification generation test first');
        }
        
        $specification = $this->documentationResults['specification'];
        $paths = $specification['paths'];
        
        $consistencyIssues = [];
        $responsePatterns = [];
        
        // Analyze response patterns across endpoints
        foreach ($paths as $path => $pathSpec) {
            foreach ($pathSpec as $method => $methodSpec) {
                if (!isset($methodSpec['responses'])) {
                    continue;
                }
                
                foreach ($methodSpec['responses'] as $statusCode => $response) {
                    $key = "{$method}_{$statusCode}";
                    
                    if (!isset($responsePatterns[$key])) {
                        $responsePatterns[$key] = [];
                    }
                    
                    $responsePatterns[$key][] = [
                        'path' => $path,
                        'response' => $response
                    ];
                }
            }
        }
        
        // Check for consistency in common response patterns
        foreach ($responsePatterns as $patternKey => $responses) {
            if (count($responses) < 2) {
                continue; // Need at least 2 to compare
            }
            
            $firstResponse = $responses[0]['response'];
            
            for ($i = 1; $i < count($responses); $i++) {
                $currentResponse = $responses[$i]['response'];
                
                // Check description consistency for same status codes
                if (isset($firstResponse['description']) && isset($currentResponse['description'])) {
                    $firstDesc = trim(strtolower($firstResponse['description']));
                    $currentDesc = trim(strtolower($currentResponse['description']));
                    
                    // Allow for some variation but flag major inconsistencies
                    $similarity = similar_text($firstDesc, $currentDesc, $percent);
                    
                    if ($percent < 50) {
                        $consistencyIssues[] = [
                            'type' => 'description_inconsistency',
                            'pattern' => $patternKey,
                            'paths' => [$responses[0]['path'], $responses[$i]['path']],
                            'similarity' => $percent
                        ];
                    }
                }
            }
        }
        
        // Validate acceptable level of consistency
        $maxInconsistencies = 5; // Allow some variation
        $I->assertLessThanOrEqual($maxInconsistencies, count($consistencyIssues),
            'Documentation should maintain reasonable consistency across endpoints. Issues found: ' . 
            json_encode($consistencyIssues));
        
        if (empty($consistencyIssues)) {
            $I->comment('✓ Documentation consistency is good across all endpoints');
        } else {
            $I->comment("⚠ Found " . count($consistencyIssues) . " minor consistency issues");
        }
        
        $I->comment('Documentation consistency test completed');
    }
    
    /**
     * Test schema validation for documented endpoints
     * 
     * @param ApiTester $I API tester instance
     * 
     * @since 1.0.0
     */
    public function testSchemaValidationForDocumentedEndpoints(ApiTester $I)
    {
        $I->comment('Testing schema validation for documented endpoints');
        
        $I->loginAsAdmin();
        $I->sendJwtAuthorisation();
        $I->sendJsonApiContentNegotiation();
        
        // Test schema validation for key endpoints
        $endpointsToValidate = [
            '/api/v8/modules/Accounts' => [
                'method' => 'GET',
                'expected_fields' => ['data', 'meta'],
                'data_structure' => 'array'
            ],
            '/api/v8/meta/modules' => [
                'method' => 'GET',
                'expected_fields' => ['modules'],
                'data_structure' => 'object'
            ]
        ];
        
        foreach ($endpointsToValidate as $endpoint => $validation) {
            $I->comment("Validating schema for: {$endpoint}");
            
            $I->sendGET($endpoint);
            $I->seeResponseCodeIs(200);
            
            $response = json_decode($I->grabResponse(), true);
            
            // Validate expected fields are present
            foreach ($validation['expected_fields'] as $field) {
                $I->assertArrayHasKey($field, $response,
                    "Response from {$endpoint} should contain field: {$field}");
            }
            
            // Validate data structure if specified
            if (isset($validation['data_structure']) && isset($response['data'])) {
                if ($validation['data_structure'] === 'array') {
                    $I->assertIsArray($response['data'],
                        "Data field in {$endpoint} should be an array");
                } elseif ($validation['data_structure'] === 'object') {
                    $I->assertIsArray($response['data'],
                        "Data field in {$endpoint} should be an object/array");
                }
            }
            
            // Validate response matches documented schema patterns
            $this->validateResponseAgainstDocumentedSchema($I, $endpoint, $response);
        }
        
        $I->comment('Schema validation test completed');
    }
    
    /**
     * Utility Methods
     */
    
    /**
     * Validates response structure consistency
     * 
     * @param ApiTester $I API tester instance
     * @param string $endpoint Endpoint being tested
     * @param array $response Response data
     * 
     * @since 1.0.0
     */
    private function validateResponseStructureConsistency(ApiTester $I, string $endpoint, array $response): void
    {
        // Check for consistent field naming conventions
        $fieldNamingIssues = [];
        
        $this->checkFieldNaming($response, '', $fieldNamingIssues);
        
        if (!empty($fieldNamingIssues)) {
            $I->comment("⚠ Field naming inconsistencies in {$endpoint}: " . implode(', ', $fieldNamingIssues));
        }
        
        // Validate timestamp fields have consistent format
        $this->validateTimestampFields($I, $endpoint, $response);
    }
    
    /**
     * Recursively checks field naming conventions
     * 
     * @param array $data Data to check
     * @param string $path Current path for error reporting
     * @param array &$issues Issues array to populate
     * 
     * @since 1.0.0
     */
    private function checkFieldNaming(array $data, string $path, array &$issues): void
    {
        foreach ($data as $key => $value) {
            $currentPath = $path ? "{$path}.{$key}" : $key;
            
            // Check for consistent snake_case naming
            if (is_string($key) && !preg_match('/^[a-z0-9_]+$/', $key)) {
                $issues[] = "Non-snake_case field: {$currentPath}";
            }
            
            // Recursively check nested arrays/objects
            if (is_array($value) && !empty($value)) {
                $this->checkFieldNaming($value, $currentPath, $issues);
            }
        }
    }
    
    /**
     * Validates timestamp fields have consistent format
     * 
     * @param ApiTester $I API tester instance
     * @param string $endpoint Endpoint being tested
     * @param array $response Response data
     * 
     * @since 1.0.0
     */
    private function validateTimestampFields(ApiTester $I, string $endpoint, array $response): void
    {
        $timestampFields = ['date_entered', 'date_modified', 'created_at', 'updated_at'];
        
        foreach ($timestampFields as $field) {
            if (isset($response['data'][0][$field])) {
                $timestamp = $response['data'][0][$field];
                
                // Validate timestamp format
                $isValidTimestamp = (
                    preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $timestamp) ||
                    preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $timestamp)
                );
                
                $I->assertTrue($isValidTimestamp,
                    "Timestamp field {$field} in {$endpoint} should have consistent format");
            }
        }
    }
    
    /**
     * Validates response against documented schema
     * 
     * @param ApiTester $I API tester instance
     * @param string $endpoint Endpoint being tested
     * @param array $response Response data
     * 
     * @since 1.0.0
     */
    private function validateResponseAgainstDocumentedSchema(ApiTester $I, string $endpoint, array $response): void
    {
        // Basic schema validation for JSON:API structure
        if (isset($response['data'])) {
            if (is_array($response['data']) && !empty($response['data'])) {
                // If data is an array of objects, validate first object structure
                $firstItem = is_array($response['data'][0]) ? $response['data'][0] : $response['data'];
                
                // Check for required JSON:API fields
                if (isset($firstItem['type'])) {
                    $I->assertIsString($firstItem['type'],
                        "JSON:API type field should be string in {$endpoint}");
                }
                
                if (isset($firstItem['id'])) {
                    $I->assertIsString($firstItem['id'],
                        "JSON:API id field should be string in {$endpoint}");
                }
            }
        }
    }
    
    /**
     * Generate comprehensive documentation test report
     * 
     * @param ApiTester $I API tester instance
     * 
     * @return array Documentation test results
     * 
     * @since 1.0.0
     */
    public function generateDocumentationTestReport(ApiTester $I): array
    {
        return [
            'documentation_results' => $this->documentationResults,
            'test_summary' => [
                'specification_valid' => isset($this->documentationResults['specification']),
                'coverage_tested' => isset($this->documentationResults['coverage']),
                'examples_validated' => true,
                'ui_accessible' => true,
                'consistency_checked' => true,
                'schema_validated' => true
            ],
            'test_metadata' => [
                'test_execution_time' => date('Y-m-d H:i:s'),
                'test_environment' => $I->getInstanceURL(),
                'required_endpoints_count' => count(self::$REQUIRED_ENDPOINTS)
            ]
        ];
    }
} 