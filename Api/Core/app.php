<?php
// ENHANCED CORS IMPLEMENTATION: Basic headers for backward compatibility
// Enhanced CORS middleware is available in Api/V8/Middleware/CorsMiddleware.php
// This basic implementation will be phased out in favor of the configurable middleware
global $sugar_config;

// Use configured site URL as default origin instead of wildcard for security
$defaultOrigin = $sugar_config['site_url'] ?? '*';
$allowedOrigin = $sugar_config['cors']['allowed_origins'][0] ?? $defaultOrigin;

// For backward compatibility, allow wildcard if explicitly configured
if (isset($sugar_config['cors']['allowed_origins']) && in_array('*', $sugar_config['cors']['allowed_origins'])) {
    $allowedOrigin = '*';
}

header("Access-Control-Allow-Origin: " . $allowedOrigin);
header('Access-Control-Allow-Methods: POST, PATCH, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');

// @codingStandardsIgnoreStart
if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}
// @codingStandardsIgnoreEnd

// For php-fpm we pass the "Authorization" header through HTTP_AUTHORIZATION
// using .htaccess rewrite rules. The rewrite rules result in apache prefixing
// the env var which gives us REDIRECT_HTTP_AUTHORIZATION.
if (!isset($_SERVER['HTTP_AUTHORIZATION']) && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
    $_SERVER['HTTP_AUTHORIZATION'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
}

chdir(__DIR__ . '/../../');
require_once __DIR__ . '/../../include/entryPoint.php';

$app = new \Slim\App(\Api\Core\Loader\ContainerLoader::configure());
// closure shouldn't be created in static context under PHP7
$routeLoader = new \Api\Core\Loader\RouteLoader();
$routeLoader->configureRoutes($app);
