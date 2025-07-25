<?php
/**
 * @fileoverview Interactive API Documentation Controller for SuiteCRM V8 API
 *
 * Provides interactive Swagger UI interface for exploring and testing API endpoints.
 * Builds upon existing MetaService and OpenAPI documentation infrastructure to
 * deliver comprehensive API documentation with live testing capabilities.
 *
 * Key Features:
 * - Interactive Swagger UI interface for API exploration
 * - Live API testing with authentication integration
 * - Responsive documentation interface
 * - Integration with existing OAuth2 authentication
 *
 * Dependencies:
 * - Existing MetaService for OpenAPI specification
 * - Enhanced OpenApiDocumentationService
 * - Slim 3 routing infrastructure
 * - Existing authentication middleware
 *
 * @package SuiteCRM\Api\V8\Controller
 * @copyright Copyright (C) 2011 - 2024 SalesAgility Ltd.
 * @license GNU Affero General Public License version 3
 * @since 1.0.0
 */

namespace Api\V8\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * DocumentationController
 *
 * Serves interactive API documentation interface using Swagger UI.
 * Builds upon existing API infrastructure to provide comprehensive,
 * user-friendly documentation with live testing capabilities.
 */
#[\AllowDynamicProperties]
class DocumentationController extends BaseController
{
    /**
     * Serves the main interactive API documentation interface
     *
     * Generates a complete Swagger UI interface that integrates with the existing
     * /V8/meta/swagger.json endpoint. Provides live API testing capabilities
     * with authentication integration.
     *
     * @param Request $request HTTP request object
     * @param Response $response HTTP response object
     * @param array $args Route arguments
     *
     * @return Response HTML response with Swagger UI interface
     *
     * @since 1.0.0
     */
    public function getApiDocumentation(Request $request, Response $response, array $args): Response
    {
        try {
            $html = $this->generateSwaggerUIHtml($request);
            
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'text/html; charset=utf-8')
                ->write($html);
        } catch (\Exception $e) {
            return $this->generateErrorResponse($response, $e, 500);
        }
    }
    
    /**
     * Generates the HTML for Swagger UI interface
     *
     * Creates a responsive HTML interface that loads Swagger UI and configures
     * it to use the existing OpenAPI specification endpoint. Includes authentication
     * integration and SuiteCRM branding.
     *
     * @param Request $request HTTP request for base URL determination
     *
     * @return string Complete HTML document for Swagger UI
     *
     * @since 1.0.0
     */
    private function generateSwaggerUIHtml(Request $request): string
    {
        global $sugar_config;
        
        $baseUrl = $this->getBaseUrl($request);
        $swaggerJsonUrl = $baseUrl . '/V8/meta/swagger.json';
        $siteUrl = $sugar_config['site_url'] ?? $baseUrl;
        
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuiteCRM V8 API Documentation</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5.10.5/swagger-ui.css" />
    <link rel="icon" type="image/png" href="{$siteUrl}/themes/default/images/favicon.ico" sizes="32x32" />
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        
        *, *:before, *:after {
            box-sizing: inherit;
        }
        
        body {
            margin: 0;
            background: #fafafa;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        
        .swagger-ui .topbar {
            background-color: #1f4e79;
            border-bottom: 1px solid #1f4e79;
        }
        
        .swagger-ui .topbar .download-url-wrapper .select-label {
            color: #fff;
        }
        
        .swagger-ui .topbar .download-url-wrapper .download-url-button {
            background-color: #5cb85c;
            border-color: #4cae4c;
        }
        
        .custom-header {
            background-color: #1f4e79;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .custom-header h1 {
            margin: 0 0 10px 0;
            font-size: 2em;
        }
        
        .custom-header p {
            margin: 0;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="custom-header">
        <h1>SuiteCRM V8 API Documentation</h1>
        <p>Interactive API explorer with live testing capabilities</p>
    </div>
    
    <div id="swagger-ui"></div>
    
    <script src="https://unpkg.com/swagger-ui-dist@5.10.5/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.10.5/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            // Begin Swagger UI call region
            const ui = SwaggerUIBundle({
                url: '{$swaggerJsonUrl}',
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                validatorUrl: null,
                tryItOutEnabled: true,
                supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
                onComplete: function() {
                    // Custom initialization if needed
                    console.log('SuiteCRM API Documentation loaded successfully');
                },
                requestInterceptor: function(request) {
                    // Add custom headers or modify requests if needed
                    return request;
                },
                responseInterceptor: function(response) {
                    // Process responses if needed
                    return response;
                }
            });
            
            // End Swagger UI call region
            window.ui = ui;
        };
    </script>
</body>
</html>
HTML;
    }
    
    /**
     * Determines the base URL for API endpoints
     *
     * Extracts the base URL from the current request to properly configure
     * Swagger UI with the correct API endpoint URLs.
     *
     * @param Request $request HTTP request object
     *
     * @return string Base URL for API endpoints
     *
     * @since 1.0.0
     */
    private function getBaseUrl(Request $request): string
    {
        $uri = $request->getUri();
        $scheme = $uri->getScheme();
        $authority = $uri->getAuthority();
        
        $baseUrl = ($scheme ? $scheme . ':' : '') . ($authority ? '//' . $authority : '');
        
        // Remove /Api/V8/docs or similar from path to get base
        $path = $uri->getPath();
        $apiPath = '/Api';
        
        if (strpos($path, $apiPath) !== false) {
            $baseUrl .= substr($path, 0, strpos($path, $apiPath)) . $apiPath;
        } else {
            $baseUrl .= '/Api';
        }
        
        return $baseUrl;
    }
}
