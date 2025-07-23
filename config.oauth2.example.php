<?php
/**
 * OAuth2 External Provider Configuration Example
 * 
 * Add this configuration to your config.php or config_override.php file
 * to enable OAuth2 authentication with external providers.
 * 
 * IMPORTANT SECURITY NOTES:
 * - Never commit OAuth2 client secrets to version control
 * - Use environment variables for sensitive credentials in production
 * - Ensure redirect URIs match exactly what's configured in OAuth2 provider
 * - Enable only providers that you actively use and maintain
 * 
 * @author SuiteCRM Modernization Team
 * @version 1.0.0
 * @since 2024-01-15
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// OAuth2 External Provider Configuration
$sugar_config['oauth2_external_providers'] = [
    
    // Google OAuth2 Provider (Google Workspace, Gmail)
    'google' => [
        'enabled' => false, // Set to true to enable Google OAuth2
        'client_id' => '', // Your Google OAuth2 Client ID
        'client_secret' => '', // Your Google OAuth2 Client Secret
        'scopes' => ['openid', 'email', 'profile'], // Required OAuth2 scopes
        'hosted_domain' => null, // Optional: restrict to specific Google Workspace domain
        
        // Example with environment variables (recommended for production):
        // 'client_id' => $_ENV['GOOGLE_OAUTH2_CLIENT_ID'] ?? '',
        // 'client_secret' => $_ENV['GOOGLE_OAUTH2_CLIENT_SECRET'] ?? '',
        // 'hosted_domain' => $_ENV['GOOGLE_HOSTED_DOMAIN'] ?? null,
    ],
    
    // Generic OAuth2 Provider (for custom implementations)
    'generic' => [
        'enabled' => false, // Set to true to enable generic OAuth2
        'client_id' => '', // Your OAuth2 Client ID
        'client_secret' => '', // Your OAuth2 Client Secret
        'authorize_url' => '', // OAuth2 authorization endpoint URL
        'token_url' => '', // OAuth2 token endpoint URL
        'resource_owner_url' => '', // OAuth2 user info endpoint URL
        'scopes' => ['openid', 'email', 'profile'], // Required OAuth2 scopes
        
        // Example for custom provider:
        // 'authorize_url' => 'https://your-provider.com/oauth2/authorize',
        // 'token_url' => 'https://your-provider.com/oauth2/token',
        // 'resource_owner_url' => 'https://your-provider.com/oauth2/userinfo',
    ],
];

/**
 * SETUP INSTRUCTIONS
 * 
 * 1. GOOGLE OAUTH2 SETUP:
 *    - Go to Google Cloud Console (https://console.cloud.google.com/)
 *    - Create a new project or select existing project
 *    - Enable Google+ API and/or Gmail API
 *    - Go to "Credentials" → "Create Credentials" → "OAuth 2.0 Client IDs"
 *    - Set application type to "Web application"
 *    - Add authorized redirect URIs:
 *      * https://your-suitecrm-domain.com/index.php?module=Users&action=OAuth2Callback&provider=google
 *      * https://your-suitecrm-domain.com/auth/oauth/callback/google
 *    - Copy Client ID and Client Secret to configuration above
 * 
 * 2. DATABASE SETUP:
 *    - Run the SQL script: install/suite_install/oauth2_user_providers_table.sql
 *    - Ensure the oauth2_user_providers table is created
 * 
 * 3. URL CONFIGURATION:
 *    - Ensure your site_url is correctly configured in config.php
 *    - Verify HTTPS is enabled (required for OAuth2 in production)
 *    - Test redirect URIs are accessible and return proper responses
 */

/**
 * ENVIRONMENT VARIABLES SETUP (.env file example)
 * 
 * Create a .env file in your SuiteCRM root directory with:
 * 
 * # Google OAuth2
 * GOOGLE_OAUTH2_CLIENT_ID=your_google_client_id
 * GOOGLE_OAUTH2_CLIENT_SECRET=your_google_client_secret
 * GOOGLE_HOSTED_DOMAIN=your-company.com
 * 
 * Then load these in your config_override.php using:
 * $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
 * $dotenv->load();
 */

/**
 * SECURITY BEST PRACTICES
 * 
 * 1. Use HTTPS in production (OAuth2 requires secure connections)
 * 2. Store client secrets in environment variables, not in config files
 * 3. Regularly rotate OAuth2 client secrets
 * 4. Monitor OAuth2 authentication logs for suspicious activity
 * 5. Implement proper user access controls and permissions
 * 6. Keep OAuth2 libraries updated to latest versions
 * 7. Validate and sanitize all OAuth2 callback parameters
 * 8. Use state parameter for CSRF protection (automatically handled)
 * 9. Implement rate limiting on OAuth2 endpoints
 * 10. Review and audit linked OAuth2 accounts regularly
 */ 