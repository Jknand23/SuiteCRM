<?php
/**
 * @fileoverview OAuth2 Authorization Entry Point
 *
 * Entry point for initiating OAuth2 authorization flow. Redirects to the
 * OAuth2 controller's authorize action with proper parameter handling.
 *
 * Usage: index.php?entryPoint=oauth2Authorize&provider={provider}
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

// Store return URL if provided
if (!empty($_REQUEST['return_url'])) {
    $_SESSION['oauth2_return_url'] = $_REQUEST['return_url'];
}

// Redirect to OAuth2 controller
$provider = urlencode($_REQUEST['provider']);
$url = "index.php?module=OAuth2&action=authorize&provider={$provider}";

header("Location: {$url}");
exit;
