#!/bin/bash
set -e

# SuiteCRM Docker Entrypoint Script with OAuth2 Support
echo "Starting SuiteCRM with OAuth2/SSO support..."

# If config.php exists on the host and is mounted to /tmp, copy it to the correct location
if [ -f /tmp/config.php ]; then
    echo "Using existing config.php from host mount"
    cp /tmp/config.php /var/www/html/config.php
    chown www-data:www-data /var/www/html/config.php
    chmod 644 /var/www/html/config.php
    echo "Configuration restored successfully"
else
    echo "No config.php found, SuiteCRM will need to be installed"
fi

# Do the same for config_override.php
if [ -f /tmp/config_override.php ]; then
    echo "Using existing config_override.php from host mount"
    cp /tmp/config_override.php /var/www/html/config_override.php
    chown www-data:www-data /var/www/html/config_override.php
    chmod 644 /var/www/html/config_override.php
    echo "OAuth2 override configuration restored successfully"
fi

# Ensure proper permissions on directories and OAuth2 files
echo "Setting file permissions..."
chown -R www-data:www-data /var/www/html/upload /var/www/html/custom /var/www/html/cache 2>/dev/null || true
chown -R www-data:www-data /var/www/html/lib/Authentication 2>/dev/null || true

# Create cache directories for OAuth2 if they don't exist
mkdir -p /var/www/html/cache/oauth2 2>/dev/null || true
chown -R www-data:www-data /var/www/html/cache/oauth2 2>/dev/null || true

# OAuth2 Configuration Validation
echo "Validating OAuth2 configuration..."
oauth2_providers_enabled=0

if [ ! -z "$GOOGLE_OAUTH2_CLIENT_ID" ] && [ ! -z "$GOOGLE_OAUTH2_CLIENT_SECRET" ]; then
    echo "✓ Google OAuth2 credentials configured"
    oauth2_providers_enabled=$((oauth2_providers_enabled + 1))
fi

if [ ! -z "$MICROSOFT_OAUTH2_CLIENT_ID" ] && [ ! -z "$MICROSOFT_OAUTH2_CLIENT_SECRET" ]; then
    echo "✓ Microsoft OAuth2 credentials configured"
    oauth2_providers_enabled=$((oauth2_providers_enabled + 1))
fi

if [ ! -z "$GITHUB_OAUTH2_CLIENT_ID" ] && [ ! -z "$GITHUB_OAUTH2_CLIENT_SECRET" ]; then
    echo "✓ GitHub OAuth2 credentials configured"
    oauth2_providers_enabled=$((oauth2_providers_enabled + 1))
fi

if [ $oauth2_providers_enabled -eq 0 ]; then
    echo "⚠ No OAuth2 providers configured. OAuth2 login buttons will not be functional."
    echo "   Set OAuth2 credentials in .env.docker to enable SSO login."
else
    echo "✓ OAuth2/SSO ready with $oauth2_providers_enabled provider(s) enabled"
fi

# Validate encryption key
if [ -z "$OAUTH2_ENCRYPTION_KEY" ] || [ ${#OAUTH2_ENCRYPTION_KEY} -lt 32 ]; then
    echo "⚠ OAuth2 encryption key is missing or too short. Using default (INSECURE for production)."
    echo "   Set OAUTH2_ENCRYPTION_KEY to a 32+ character string in .env.docker"
fi

# Start Apache
echo "Starting Apache with OAuth2/SSO support..."
exec apache2-foreground 