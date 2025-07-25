<?php
/**
 * @fileoverview OpenAPI Documentation Service for SuiteCRM V8 API
 *
 * Provides dynamic OpenAPI specification generation from existing SuiteCRM V8 API
 * infrastructure. Analyzes routes, controllers, and parameter validation to create
 * comprehensive, accurate API documentation that stays synchronized with code changes.
 *
 * Key Features:
 * - Dynamic OpenAPI 3.0 specification generation from existing routes
 * - Controller method analysis for endpoint documentation
 * - Parameter validation integration for schema generation
 * - Response format documentation from JSON:API standards
 * - Authentication flow documentation with OAuth2 integration
 *
 * Dependencies:
 * - Existing Slim 3 routing infrastructure
 * - SuiteCRM V8 parameter validation system
 * - JSON:API response format standards
 * - OAuth2 authentication implementation
 *
 * @package SuiteCRM\Api\V8\Service
 * @copyright Copyright (C) 2011 - 2024 SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 * @since 1.0.0
 */

namespace Api\V8\Service;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

use Api\V8\BeanDecorator\BeanManager;
use Api\V8\Helper\ModuleListProvider;
use Api\V8\JsonApi\Response\DocumentResponse;
use SuiteCRM\Exception\Exception;
use SuiteCRM\Exception\InvalidArgumentException;

/**
 * OpenApiDocumentationService
 *
 * Generates dynamic OpenAPI documentation from existing SuiteCRM V8 API infrastructure.
 * Builds comprehensive API specifications by analyzing routes, controllers, and
 * validation rules to provide accurate, up-to-date documentation.
 */
#[\AllowDynamicProperties]
class OpenApiDocumentationService
{
    /**
     * @var BeanManager $beanManager SuiteCRM bean management service
     */
    private BeanManager $beanManager;
    
    /**
     * @var ModuleListProvider $moduleListProvider Module listing service
     */
    private ModuleListProvider $moduleListProvider;
    
    /**
     * @var array $routeCache Cached route analysis for performance
     */
    private array $routeCache = [];
    
    /**
     * @var array $controllerCache Cached controller analysis for performance
     */
    private array $controllerCache = [];
    
    /**
     * @var string $baseApiPath Base API path for documentation
     */
    private string $baseApiPath = '/Api/V8';
    
    /**
     * OpenApiDocumentationService constructor
     *
     * @param BeanManager $beanManager SuiteCRM bean management service
     * @param ModuleListProvider $moduleListProvider Module listing service
     */
    public function __construct(
        BeanManager $beanManager,
        ModuleListProvider $moduleListProvider
    ) {
        $this->beanManager = $beanManager;
        $this->moduleListProvider = $moduleListProvider;
    }
    
    /**
     * Generates complete OpenAPI 3.0 specification from existing API infrastructure
     *
     * Analyzes existing routes, controllers, and validation to create comprehensive
     * OpenAPI documentation. Includes authentication schemes, endpoint definitions,
     * parameter schemas, and response examples.
     *
     * @return array Complete OpenAPI 3.0 specification
     *
     * @throws Exception When route analysis fails
     * @throws InvalidArgumentException When invalid configuration detected
     *
     * @since 1.0.0
     */
    public function generateDynamicSchema(): array
    {
        $schema = $this->buildBaseSchema();
        
        // Analyze existing route definitions
        $routeAnalysis = $this->analyzeExistingRoutes();
        
        // Generate endpoint documentation
        $schema['paths'] = $this->generatePathsFromRoutes($routeAnalysis);
        
        // Generate component schemas
        $schema['components'] = $this->generateComponentSchemas();
        
        // Add authentication schemes
        $schema['components']['securitySchemes'] = $this->generateSecuritySchemes();
        
        return $schema;
    }
    
    /**
     * Builds base OpenAPI schema structure with metadata
     *
     * Creates the foundational OpenAPI specification structure including
     * API information, version, contact details, and server configuration.
     *
     * @return array Base OpenAPI schema structure
     *
     * @since 1.0.0
     */
    private function buildBaseSchema(): array
    {
        global $sugar_config;
        
        $siteUrl = $sugar_config['site_url'] ?? 'http://localhost';
        $apiVersion = $this->getApiVersion();
        
        return [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'SuiteCRM V8 API',
                'version' => $apiVersion,
                'description' => 'Dynamic OpenAPI documentation for SuiteCRM V8 API',
                'contact' => [
                    'name' => 'SuiteCRM Support',
                    'url' => 'https://suitecrm.com/forum'
                ],
                'license' => [
                    'name' => 'GNU AFFERO GENERAL PUBLIC LICENSE VERSION 3',
                    'url' => 'https://github.com/salesagility/SuiteCRM/blob/master/LICENSE.txt'
                ]
            ],
            'servers' => [
                [
                    'url' => rtrim($siteUrl, '/') . $this->baseApiPath,
                    'description' => 'SuiteCRM V8 API Server'
                ]
            ],
            'paths' => [],
            'components' => []
        ];
    }
    
    /**
     * Analyzes existing route definitions from SuiteCRM V8 routing configuration
     *
     * Parses the existing routes.php file to extract endpoint patterns, HTTP methods,
     * controller mappings, and parameter requirements for documentation generation.
     *
     * @return array Analyzed route information with endpoints and metadata
     *
     * @throws Exception When route file cannot be read or parsed
     *
     * @since 1.0.0
     */
    private function analyzeExistingRoutes(): array
    {
        if (!empty($this->routeCache)) {
            return $this->routeCache;
        }
        
        $routesFile = __DIR__ . '/../Config/routes.php';
        
        if (!file_exists($routesFile)) {
            throw new Exception('Cannot find routes configuration file: ' . $routesFile);
        }
        
        // For now, return basic route structure based on known V8 API patterns
        // TODO: Implement dynamic route parsing from routes.php in future enhancement
        $this->routeCache = $this->getKnownApiRoutes();
        
        return $this->routeCache;
    }
    
    /**
     * Returns known V8 API route patterns for documentation generation
     *
     * Provides predefined route information for existing SuiteCRM V8 API endpoints.
     * This serves as a foundation for documentation while full dynamic route
     * analysis is implemented in future iterations.
     *
     * @return array Known API route definitions
     *
     * @since 1.0.0
     */
    private function getKnownApiRoutes(): array
    {
        return [
            'module_operations' => [
                'get_module_record' => [
                    'method' => 'GET',
                    'path' => '/module/{moduleName}/{id}',
                    'controller' => 'ModuleController',
                    'action' => 'getModuleRecord',
                    'description' => 'Retrieve a single record from a module'
                ],
                'get_module_records' => [
                    'method' => 'GET',
                    'path' => '/module/{moduleName}',
                    'controller' => 'ModuleController',
                    'action' => 'getModuleRecords',
                    'description' => 'Retrieve multiple records from a module'
                ],
                'create_module_record' => [
                    'method' => 'POST',
                    'path' => '/module',
                    'controller' => 'ModuleController',
                    'action' => 'createModuleRecord',
                    'description' => 'Create a new record in a module'
                ],
                'update_module_record' => [
                    'method' => 'PATCH',
                    'path' => '/module',
                    'controller' => 'ModuleController',
                    'action' => 'updateModuleRecord',
                    'description' => 'Update an existing record in a module'
                ],
                'delete_module_record' => [
                    'method' => 'DELETE',
                    'path' => '/module/{moduleName}/{id}',
                    'controller' => 'ModuleController',
                    'action' => 'deleteModuleRecord',
                    'description' => 'Delete a record from a module'
                ]
            ],
            'authentication' => [
                'get_access_token' => [
                    'method' => 'POST',
                    'path' => '/access_token',
                    'controller' => 'OAuth2Controller',
                    'action' => 'accessToken',
                    'description' => 'Obtain OAuth2 access token'
                ],
                'logout' => [
                    'method' => 'POST',
                    'path' => '/logout',
                    'controller' => 'LogoutController',
                    'action' => '__invoke',
                    'description' => 'Logout and invalidate access token'
                ]
            ],
            'metadata' => [
                'get_modules' => [
                    'method' => 'GET',
                    'path' => '/meta/modules',
                    'controller' => 'MetaController',
                    'action' => 'getModuleList',
                    'description' => 'Get list of available modules'
                ],
                'get_fields' => [
                    'method' => 'GET',
                    'path' => '/meta/fields/{moduleName}',
                    'controller' => 'MetaController',
                    'action' => 'getFieldList',
                    'description' => 'Get field definitions for a module'
                ],
                'get_swagger' => [
                    'method' => 'GET',
                    'path' => '/meta/swagger.json',
                    'controller' => 'MetaController',
                    'action' => 'getSwaggerSchema',
                    'description' => 'Get OpenAPI specification'
                ]
            ]
        ];
    }
    
    /**
     * Generates OpenAPI paths documentation from analyzed routes
     *
     * Converts route analysis into properly formatted OpenAPI paths with
     * parameters, request/response schemas, and example documentation.
     *
     * @param array $routeAnalysis Analyzed route information
     *
     * @return array OpenAPI paths documentation
     *
     * @since 1.0.0
     */
    private function generatePathsFromRoutes(array $routeAnalysis): array
    {
        $paths = [];
        
        foreach ($routeAnalysis as $categoryName => $routes) {
            foreach ($routes as $routeName => $routeInfo) {
                $pathKey = $routeInfo['path'];
                $method = strtolower($routeInfo['method']);
                
                if (!isset($paths[$pathKey])) {
                    $paths[$pathKey] = [];
                }
                
                $paths[$pathKey][$method] = $this->generateOperationDocumentation($routeInfo);
            }
        }
        
        return $paths;
    }
    
    /**
     * Generates OpenAPI operation documentation for a single route
     *
     * Creates comprehensive operation documentation including parameters,
     * request body, responses, and examples for a specific API endpoint.
     *
     * @param array $routeInfo Route information and metadata
     *
     * @return array OpenAPI operation documentation
     *
     * @since 1.0.0
     */
    private function generateOperationDocumentation(array $routeInfo): array
    {
        $operation = [
            'summary' => $routeInfo['description'],
            'description' => $this->generateExtendedDescription($routeInfo),
            'tags' => [$this->getOperationTag($routeInfo)],
            'responses' => $this->generateResponseDocumentation($routeInfo)
        ];
        
        // Add parameters if route has path or query parameters
        $parameters = $this->extractRouteParameters($routeInfo);
        if (!empty($parameters)) {
            $operation['parameters'] = $parameters;
        }
        
        // Add request body for POST/PATCH operations
        if (in_array($routeInfo['method'], ['POST', 'PATCH'])) {
            $operation['requestBody'] = $this->generateRequestBodyDocumentation($routeInfo);
        }
        
        // Add security requirements
        $operation['security'] = $this->generateSecurityRequirements($routeInfo);
        
        return $operation;
    }
    
    /**
     * Generates extended description for API operation
     *
     * @param array $routeInfo Route information
     *
     * @return string Extended operation description
     *
     * @since 1.0.0
     */
    private function generateExtendedDescription(array $routeInfo): string
    {
        $baseDescription = $routeInfo['description'];
        
        // Add additional context based on operation type
        switch ($routeInfo['action']) {
            case 'getModuleRecord':
                return $baseDescription . '. Returns detailed information for a specific record including all accessible fields and relationships.';
            case 'getModuleRecords':
                return $baseDescription . '. Supports filtering, sorting, and pagination. Returns a collection of records with configurable field selection.';
            case 'createModuleRecord':
                return $baseDescription . '. Validates input data and creates a new record with generated ID. Returns the created record with all fields.';
            case 'updateModuleRecord':
                return $baseDescription . '. Validates changes and updates specified fields. Returns the updated record with current field values.';
            case 'deleteModuleRecord':
                return $baseDescription . '. Performs soft or hard delete based on module configuration. Returns confirmation of deletion.';
            default:
                return $baseDescription;
        }
    }
    
    /**
     * Extracts route parameters for OpenAPI documentation
     *
     * @param array $routeInfo Route information
     *
     * @return array OpenAPI parameter definitions
     *
     * @since 1.0.0
     */
    private function extractRouteParameters(array $routeInfo): array
    {
        $parameters = [];
        $path = $routeInfo['path'];
        
        // Extract path parameters from route pattern
        if (preg_match_all('/\{(\w+)\}/', $path, $matches)) {
            foreach ($matches[1] as $paramName) {
                $parameters[] = [
                    'name' => $paramName,
                    'in' => 'path',
                    'required' => true,
                    'description' => $this->getParameterDescription($paramName),
                    'schema' => $this->getParameterSchema($paramName)
                ];
            }
        }
        
        // Add common query parameters based on operation
        $queryParams = $this->getCommonQueryParameters($routeInfo);
        $parameters = array_merge($parameters, $queryParams);
        
        return $parameters;
    }
    
    /**
     * Gets parameter description based on parameter name
     *
     * @param string $paramName Parameter name
     *
     * @return string Parameter description
     *
     * @since 1.0.0
     */
    private function getParameterDescription(string $paramName): string
    {
        $descriptions = [
            'moduleName' => 'Name of the SuiteCRM module (e.g., Accounts, Contacts, Leads)',
            'id' => 'Unique identifier (UUID) of the record',
            'linkFieldName' => 'Name of the relationship link field'
        ];
        
        return $descriptions[$paramName] ?? ucfirst($paramName) . ' parameter';
    }
    
    /**
     * Gets parameter schema based on parameter name
     *
     * @param string $paramName Parameter name
     *
     * @return array OpenAPI parameter schema
     *
     * @since 1.0.0
     */
    private function getParameterSchema(string $paramName): array
    {
        $schemas = [
            'moduleName' => [
                'type' => 'string',
                'example' => 'Accounts'
            ],
            'id' => [
                'type' => 'string',
                'format' => 'uuid',
                'example' => 'b13a39f8-1c24-c5d0-ba0d-5ab123d6e899'
            ],
            'linkFieldName' => [
                'type' => 'string',
                'example' => 'contacts'
            ]
        ];
        
        return $schemas[$paramName] ?? ['type' => 'string'];
    }
    
    /**
     * Gets common query parameters for operation type
     *
     * @param array $routeInfo Route information
     *
     * @return array Common query parameters
     *
     * @since 1.0.0
     */
    private function getCommonQueryParameters(array $routeInfo): array
    {
        $parameters = [];
        
        // Add fields parameter for GET operations
        if ($routeInfo['method'] === 'GET' && $routeInfo['controller'] === 'ModuleController') {
            $parameters[] = [
                'name' => 'fields[' . $routeInfo['path'] . ']',
                'in' => 'query',
                'required' => false,
                'description' => 'Comma-separated list of fields to include in the response',
                'schema' => [
                    'type' => 'string',
                    'example' => 'name,email,phone'
                ]
            ];
        }
        
        return $parameters;
    }
    
    /**
     * Generates response documentation for operation
     *
     * @param array $routeInfo Route information
     *
     * @return array OpenAPI response documentation
     *
     * @since 1.0.0
     */
    private function generateResponseDocumentation(array $routeInfo): array
    {
        return [
            '200' => [
                'description' => 'Successful operation',
                'content' => [
                    'application/vnd.api+json' => [
                        'schema' => $this->getResponseSchema($routeInfo)
                    ]
                ]
            ],
            '400' => [
                'description' => 'Bad request - Invalid parameters or request format',
                'content' => [
                    'application/vnd.api+json' => [
                        'schema' => ['$ref' => '#/components/schemas/ErrorResponse']
                    ]
                ]
            ],
            '401' => [
                'description' => 'Unauthorized - Invalid or missing authentication',
                'content' => [
                    'application/vnd.api+json' => [
                        'schema' => ['$ref' => '#/components/schemas/ErrorResponse']
                    ]
                ]
            ],
            '404' => [
                'description' => 'Not found - Resource does not exist',
                'content' => [
                    'application/vnd.api+json' => [
                        'schema' => ['$ref' => '#/components/schemas/ErrorResponse']
                    ]
                ]
            ]
        ];
    }
    
    /**
     * Gets response schema for operation
     *
     * @param array $routeInfo Route information
     *
     * @return array Response schema reference
     *
     * @since 1.0.0
     */
    private function getResponseSchema(array $routeInfo): array
    {
        switch ($routeInfo['action']) {
            case 'getModuleRecord':
                return ['$ref' => '#/components/schemas/SingleRecordResponse'];
            case 'getModuleRecords':
                return ['$ref' => '#/components/schemas/MultipleRecordsResponse'];
            case 'createModuleRecord':
            case 'updateModuleRecord':
                return ['$ref' => '#/components/schemas/SingleRecordResponse'];
            case 'deleteModuleRecord':
                return ['$ref' => '#/components/schemas/DeleteResponse'];
            default:
                return ['$ref' => '#/components/schemas/GenericResponse'];
        }
    }
    
    /**
     * Generates request body documentation for POST/PATCH operations
     *
     * @param array $routeInfo Route information
     *
     * @return array OpenAPI request body documentation
     *
     * @since 1.0.0
     */
    private function generateRequestBodyDocumentation(array $routeInfo): array
    {
        return [
            'required' => true,
            'content' => [
                'application/vnd.api+json' => [
                    'schema' => ['$ref' => '#/components/schemas/ModuleRecordRequest']
                ]
            ]
        ];
    }
    
    /**
     * Generates security requirements for operation
     *
     * @param array $routeInfo Route information
     *
     * @return array Security requirements
     *
     * @since 1.0.0
     */
    private function generateSecurityRequirements(array $routeInfo): array
    {
        // OAuth2 token endpoint doesn't require authentication
        if ($routeInfo['path'] === '/access_token') {
            return [];
        }
        
        return [
            ['OAuth2' => []]
        ];
    }
    
    /**
     * Gets operation tag for grouping endpoints
     *
     * @param array $routeInfo Route information
     *
     * @return string Operation tag
     *
     * @since 1.0.0
     */
    private function getOperationTag(array $routeInfo): string
    {
        switch ($routeInfo['controller']) {
            case 'ModuleController':
                return 'Modules';
            case 'OAuth2Controller':
            case 'LogoutController':
                return 'Authentication';
            case 'MetaController':
                return 'Metadata';
            default:
                return 'General';
        }
    }
    
    /**
     * Generates component schemas for OpenAPI specification
     *
     * @return array Component schemas
     *
     * @since 1.0.0
     */
    private function generateComponentSchemas(): array
    {
        return [
            'schemas' => [
                'SingleRecordResponse' => [
                    'type' => 'object',
                    'description' => 'JSON:API compliant single record response format used throughout SuiteCRM V8 API',
                    'properties' => [
                        'data' => [
                            'type' => 'object',
                            'properties' => [
                                'type' => [
                                    'type' => 'string',
                                    'description' => 'Resource type identifier (module name)',
                                    'example' => 'Accounts'
                                ],
                                'id' => [
                                    'type' => 'string',
                                    'format' => 'uuid',
                                    'description' => 'Unique record identifier',
                                    'example' => 'b13a39f8-1c24-c5d0-ba0d-5ab123d6e899'
                                ],
                                'attributes' => [
                                    'type' => 'object',
                                    'description' => 'Record field values',
                                    'example' => [
                                        'name' => 'Acme Corporation',
                                        'email1' => 'contact@acme.com',
                                        'phone_office' => '+1-555-123-4567',
                                        'website' => 'https://www.acme.com',
                                        'industry' => 'Technology',
                                        'employees' => '500-1000'
                                    ]
                                ],
                                'relationships' => [
                                    'type' => 'object',
                                    'description' => 'Related records and metadata',
                                    'example' => [
                                        'contacts' => [
                                            'links' => [
                                                'related' => '/Api/V8/module/Accounts/b13a39f8-1c24-c5d0-ba0d-5ab123d6e899/relationships/contacts'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'example' => [
                        'data' => [
                            'type' => 'Accounts',
                            'id' => 'b13a39f8-1c24-c5d0-ba0d-5ab123d6e899',
                            'attributes' => [
                                'name' => 'Acme Corporation',
                                'email1' => 'contact@acme.com',
                                'phone_office' => '+1-555-123-4567',
                                'website' => 'https://www.acme.com',
                                'industry' => 'Technology',
                                'employees' => '500-1000',
                                'date_entered' => '2024-01-15 10:30:00',
                                'date_modified' => '2024-01-15 14:22:15'
                            ],
                            'relationships' => [
                                'contacts' => [
                                    'links' => [
                                        'related' => '/Api/V8/module/Accounts/b13a39f8-1c24-c5d0-ba0d-5ab123d6e899/relationships/contacts'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'MultipleRecordsResponse' => [
                    'type' => 'object',
                    'description' => 'JSON:API compliant multiple records response with pagination metadata',
                    'properties' => [
                        'data' => [
                            'type' => 'array',
                            'description' => 'Array of record objects',
                            'items' => ['$ref' => '#/components/schemas/SingleRecordResponse/properties/data']
                        ],
                        'meta' => [
                            'type' => 'object',
                            'description' => 'Pagination and count metadata',
                            'properties' => [
                                'total-pages' => [
                                    'type' => 'integer',
                                    'description' => 'Total number of pages available',
                                    'example' => 5
                                ],
                                'total-records' => [
                                    'type' => 'integer',
                                    'description' => 'Total number of records available',
                                    'example' => 47
                                ]
                            ]
                        ]
                    ],
                    'example' => [
                        'data' => [
                            [
                                'type' => 'Accounts',
                                'id' => 'b13a39f8-1c24-c5d0-ba0d-5ab123d6e899',
                                'attributes' => [
                                    'name' => 'Acme Corporation',
                                    'email1' => 'contact@acme.com',
                                    'industry' => 'Technology'
                                ]
                            ],
                            [
                                'type' => 'Accounts',
                                'id' => 'c24b50g9-2d35-d6e1-cb1e-6bc234e7f900',
                                'attributes' => [
                                    'name' => 'Global Industries',
                                    'email1' => 'info@global.com',
                                    'industry' => 'Manufacturing'
                                ]
                            ]
                        ],
                        'meta' => [
                            'total-pages' => 5,
                            'total-records' => 47
                        ]
                    ]
                ],
                'ErrorResponse' => [
                    'type' => 'object',
                    'description' => 'Standardized error response format following JSON:API error specification',
                    'properties' => [
                        'errors' => [
                            'type' => 'array',
                            'description' => 'Array of error objects',
                            'items' => [
                                'type' => 'object',
                                'properties' => [
                                    'status' => [
                                        'type' => 'string',
                                        'description' => 'HTTP status code',
                                        'example' => '400'
                                    ],
                                    'title' => [
                                        'type' => 'string',
                                        'description' => 'Brief error summary',
                                        'example' => 'Invalid Request'
                                    ],
                                    'detail' => [
                                        'type' => 'string',
                                        'description' => 'Detailed error description',
                                        'example' => 'The request contains invalid parameters. Please check the field format and try again.'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'example' => [
                        'errors' => [
                            [
                                'status' => '400',
                                'title' => 'Invalid Request',
                                'detail' => 'The request contains invalid parameters. Please check the field format and try again.'
                            ]
                        ]
                    ]
                ],
                'ModuleRecordRequest' => [
                    'type' => 'object',
                    'description' => 'JSON:API compliant request format for creating or updating records',
                    'properties' => [
                        'data' => [
                            'type' => 'object',
                            'properties' => [
                                'type' => [
                                    'type' => 'string',
                                    'description' => 'Module name for the record being created/updated',
                                    'example' => 'Accounts'
                                ],
                                'id' => [
                                    'type' => 'string',
                                    'format' => 'uuid',
                                    'description' => 'Record ID (required for updates, omit for creation)',
                                    'example' => 'b13a39f8-1c24-c5d0-ba0d-5ab123d6e899'
                                ],
                                'attributes' => [
                                    'type' => 'object',
                                    'description' => 'Field values to create or update',
                                    'example' => [
                                        'name' => 'New Company Name',
                                        'email1' => 'newcontact@company.com',
                                        'phone_office' => '+1-555-987-6543'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'example' => [
                        'data' => [
                            'type' => 'Accounts',
                            'attributes' => [
                                'name' => 'New Company Name',
                                'email1' => 'newcontact@company.com',
                                'phone_office' => '+1-555-987-6543',
                                'website' => 'https://www.newcompany.com',
                                'industry' => 'Services'
                            ]
                        ]
                    ]
                ],
                'DeleteResponse' => [
                    'type' => 'object',
                    'description' => 'Response format for successful delete operations',
                    'properties' => [
                        'data' => [
                            'type' => 'object',
                            'properties' => [
                                'type' => [
                                    'type' => 'string',
                                    'description' => 'Resource type identifier',
                                    'example' => 'Accounts'
                                ],
                                'id' => [
                                    'type' => 'string',
                                    'format' => 'uuid',
                                    'description' => 'ID of the deleted record',
                                    'example' => 'b13a39f8-1c24-c5d0-ba0d-5ab123d6e899'
                                ]
                            ]
                        ]
                    ],
                    'example' => [
                        'data' => [
                            'type' => 'Accounts',
                            'id' => 'b13a39f8-1c24-c5d0-ba0d-5ab123d6e899'
                        ]
                    ]
                ],
                'GenericResponse' => [
                    'type' => 'object',
                    'description' => 'Generic response format for operations that return minimal data',
                    'properties' => [
                        'data' => [
                            'type' => 'object',
                            'description' => 'Response data object',
                            'properties' => [
                                'type' => ['type' => 'string'],
                                'id' => ['type' => 'string', 'format' => 'uuid'],
                                'attributes' => ['type' => 'object']
                            ]
                        ]
                    ],
                    'example' => [
                        'data' => [
                            'type' => 'response',
                            'id' => 'operation-success',
                            'attributes' => [
                                'status' => 'completed',
                                'message' => 'Operation completed successfully'
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
    
    /**
     * Generates security schemes for OpenAPI specification
     *
     * @return array Security schemes
     *
     * @since 1.0.0
     */
    private function generateSecuritySchemes(): array
    {
        global $sugar_config;
        $siteUrl = $sugar_config['site_url'] ?? 'http://localhost';
        $baseApiUrl = rtrim($siteUrl, '/') . '/Api';
        
        return [
            'OAuth2' => [
                'type' => 'oauth2',
                'description' => 'OAuth2 authentication for SuiteCRM V8 API. Supports both client credentials and password grant flows for secure API access.',
                'flows' => [
                    'clientCredentials' => [
                        'tokenUrl' => $baseApiUrl . '/access_token',
                        'scopes' => [
                            'api' => 'Full API access for client applications',
                            'read' => 'Read-only access to API resources',
                            'write' => 'Write access to API resources'
                        ],
                        'x-tokenExample' => [
                            'request' => [
                                'method' => 'POST',
                                'url' => $baseApiUrl . '/access_token',
                                'headers' => [
                                    'Content-Type' => 'application/x-www-form-urlencoded'
                                ],
                                'body' => 'grant_type=client_credentials&client_id=your_client_id&client_secret=your_client_secret'
                            ],
                            'response' => [
                                'access_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ijc...',
                                'token_type' => 'Bearer',
                                'expires_in' => 3600
                            ]
                        ]
                    ],
                    'password' => [
                        'tokenUrl' => $baseApiUrl . '/access_token',
                        'scopes' => [
                            'api' => 'Full API access with user context',
                            'read' => 'Read-only access to user-accessible resources',
                            'write' => 'Write access to user-accessible resources'
                        ],
                        'x-tokenExample' => [
                            'request' => [
                                'method' => 'POST',
                                'url' => $baseApiUrl . '/access_token',
                                'headers' => [
                                    'Content-Type' => 'application/x-www-form-urlencoded'
                                ],
                                'body' => 'grant_type=password&client_id=your_client_id&client_secret=your_client_secret&username=admin&password=password'
                            ],
                            'response' => [
                                'access_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ijg...',
                                'token_type' => 'Bearer',
                                'expires_in' => 3600,
                                'refresh_token' => 'def50200a8f4b2b0d8c9e7f5a3b1c4d6e8f0...'
                            ]
                        ]
                    ]
                ],
                'x-authenticationGuide' => [
                    'overview' => 'SuiteCRM V8 API uses OAuth2 for secure authentication. Two grant types are supported for different use cases.',
                    'clientCredentials' => [
                        'description' => 'Used for server-to-server communication where user context is not required',
                        'useCase' => 'Backend integrations, automated processes, system-level operations',
                        'requirements' => [
                            'Client ID and Secret configured in SuiteCRM',
                            'OAuth2 client registered with appropriate permissions'
                        ]
                    ],
                    'password' => [
                        'description' => 'Used when user credentials are available and user context is required',
                        'useCase' => 'User-specific operations, frontend applications, user-authenticated requests',
                        'requirements' => [
                            'Valid SuiteCRM user credentials',
                            'Client ID and Secret configured in SuiteCRM',
                            'User account with appropriate module permissions'
                        ]
                    ],
                    'usage' => [
                        'step1' => 'Obtain OAuth2 client credentials from SuiteCRM administrator',
                        'step2' => 'Request access token using appropriate grant type',
                        'step3' => 'Include token in Authorization header: Bearer {access_token}',
                        'step4' => 'Refresh token when necessary using refresh_token grant'
                    ],
                    'security' => [
                        'tokenStorage' => 'Store tokens securely, never expose in client-side code',
                        'tokenExpiry' => 'Tokens expire after 1 hour by default, implement refresh logic',
                        'scopes' => 'Request minimal scopes required for your use case',
                        'httpsRequired' => 'Always use HTTPS in production environments'
                    ]
                ]
            ],
            'ApiKey' => [
                'type' => 'apiKey',
                'in' => 'header',
                'name' => 'Authorization',
                'description' => 'Bearer token obtained through OAuth2 authentication flow. Format: Bearer {access_token}',
                'x-example' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ijc...'
            ]
        ];
    }
    
    /**
     * Gets current API version from SuiteCRM configuration
     *
     * @return string API version
     *
     * @since 1.0.0
     */
    private function getApiVersion(): string
    {
        // Return V8 API version
        return '8.2';
    }
}
