# SuiteCRM Installation Fix for Windows
Write-Host "Fixing SuiteCRM installation..." -ForegroundColor Green

# Check if .env.docker exists, create if not
if (!(Test-Path .env.docker)) {
    Copy-Item env.docker.example .env.docker
    Write-Host "Created .env.docker from template" -ForegroundColor Yellow
}

# Load environment variables from .env.docker
Get-Content .env.docker | Where-Object {$_ -notmatch '^#' -and $_ -match '='} | ForEach-Object {
    $key, $value = $_ -split '=', 2
    Set-Variable -Name $key -Value $value
}

# Create config.php from template
Copy-Item config.template.php config.php
Write-Host "Created config.php from template" -ForegroundColor Yellow

# Replace template variables with actual values
$content = Get-Content config.php -Raw

# Generate unique keys
$uniqueKey = [System.Guid]::NewGuid().ToString("N")
$siteSecurityKey = [System.Web.Security.Membership]::GeneratePassword(32, 0)
$oauth2Key = [System.Web.Security.Membership]::GeneratePassword(32, 0)

# Extract hostname from URL
$siteHost = ([System.Uri]$SUITECRM_SITE_URL).Host

# Set defaults
if (!$SUITECRM_LOG_LEVEL) { $SUITECRM_LOG_LEVEL = "info" }
if (!$SUITECRM_TIMEZONE) { $SUITECRM_TIMEZONE = "UTC" }
if (!$SUITECRM_SYSTEM_NAME) { $SUITECRM_SYSTEM_NAME = "SuiteCRM" }

# Replace placeholders
$content = $content -replace '\$\{DATABASE_HOST\}', 'mysql'
$content = $content -replace '\$\{DATABASE_PORT\}', '3306'
$content = $content -replace '\$\{DATABASE_NAME\}', $MYSQL_DATABASE
$content = $content -replace '\$\{DATABASE_USER\}', $MYSQL_USER
$content = $content -replace '\$\{DATABASE_PASSWORD\}', $MYSQL_PASSWORD
$content = $content -replace '\$\{SITE_URL\}', $SUITECRM_SITE_URL
$content = $content -replace '\$\{SITE_HOST\}', $siteHost
$content = $content -replace '\$\{SYSTEM_NAME\}', $SUITECRM_SYSTEM_NAME
$content = $content -replace '\$\{UNIQUE_KEY\}', $uniqueKey
$content = $content -replace '\$\{SITE_SECURITY_KEY\}', $siteSecurityKey
$content = $content -replace '\$\{OAUTH2_ENCRYPTION_KEY\}', $oauth2Key
$content = $content -replace '\$\{LOG_LEVEL\}', $SUITECRM_LOG_LEVEL
$content = $content -replace '\$\{TIMEZONE\}', $SUITECRM_TIMEZONE
$content = $content -replace '\$\{DISABLE_PERSISTENT_CONNECTIONS\}', 'false'
$content = $content -replace '\$\{SAVE_QUERY\}', 'false'
$content = $content -replace '\$\{DEMO_DATA\}', 'no'

# Temporarily unlock installer
$content = $content -replace "'installer_locked' => true", "'installer_locked' => false"

# Save config.php
Set-Content config.php $content -Encoding UTF8
Write-Host "Configured config.php with environment variables" -ForegroundColor Yellow

# Run SuiteCRM installation
Write-Host "Running SuiteCRM installation..." -ForegroundColor Green

$installScript = @"
// Set up session variables for silent installation
session_start();
`$_SESSION['install_type'] = 'typical';
`$_SESSION['setup_db_host_name'] = 'mysql';
`$_SESSION['setup_db_port_num'] = '3306';
`$_SESSION['setup_db_database_name'] = '$MYSQL_DATABASE';
`$_SESSION['setup_db_sugarsales_user'] = '$MYSQL_USER';
`$_SESSION['setup_db_sugarsales_password'] = '$MYSQL_PASSWORD';
`$_SESSION['setup_db_admin_user_name'] = 'root';
`$_SESSION['setup_db_admin_password'] = '$MYSQL_ROOT_PASSWORD';
`$_SESSION['setup_db_create_database'] = false;
`$_SESSION['setup_db_drop_tables'] = false;
`$_SESSION['setup_db_create_sugarsales_user'] = false;
`$_SESSION['demoData'] = 'no';
`$_SESSION['setup_site_url'] = '$SUITECRM_SITE_URL';
`$_SESSION['setup_site_admin_user_name'] = '$SUITECRM_ADMIN_USERNAME';
`$_SESSION['setup_site_admin_password'] = '$SUITECRM_ADMIN_PASSWORD';
`$_SESSION['default_language'] = 'en_us';
`$_SESSION['default_currency_name'] = 'US Dollars';
`$_SESSION['default_currency_symbol'] = '$';
`$_SESSION['default_currency_iso4217'] = 'USD';

// Define entry point and run installation
define('sugarEntry', true);
`$install_script = true;

// Load installation modules
require_once('install/install_utils.php');
require_once('install/performSetup.php');

echo 'SuiteCRM installation completed successfully!';
"@

# Execute PHP installation script
docker-compose exec -T suitecrm php -r $installScript

# Lock installer after installation
$content = Get-Content config.php -Raw
$content = $content -replace "'installer_locked' => false", "'installer_locked' => true"
Set-Content config.php $content -Encoding UTF8

Write-Host ""
Write-Host "SuiteCRM installation completed!" -ForegroundColor Green
Write-Host "Access your SuiteCRM at: $SUITECRM_SITE_URL" -ForegroundColor Cyan
Write-Host "Login with: $SUITECRM_ADMIN_USERNAME / $SUITECRM_ADMIN_PASSWORD" -ForegroundColor Cyan 