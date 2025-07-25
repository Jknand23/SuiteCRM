<?php
/**
 * @fileoverview OAuth2 Callback Entry Point
 *
 * Entry point for handling OAuth2 provider callbacks. Processes the
 * authorization response and redirects to the controller for token exchange.
 *
 * Usage: index.php?entryPoint=oauth2Callback&provider={provider}&code={code}&state={state}
 *
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Validate provider parameter
if (empty($_REQUEST['provider'])) {
    sugar_die('Missing OAuth2 provider parameter');
}

// Build query parameters for controller
$params = array(
    'module' => 'OAuth2',
    'action' => 'callback',
    'provider' => $_REQUEST['provider']
);

// Pass through OAuth2 response parameters
if (isset($_REQUEST['code'])) {
    $params['code'] = $_REQUEST['code'];
}

if (isset($_REQUEST['state'])) {
    $params['state'] = $_REQUEST['state'];
}

if (isset($_REQUEST['error'])) {
    $params['error'] = $_REQUEST['error'];
}

if (isset($_REQUEST['error_description'])) {
    $params['error_description'] = $_REQUEST['error_description'];
}

// Redirect to OAuth2 controller
$url = 'index.php?' . http_build_query($params);
header("Location: {$url}");
exit;
