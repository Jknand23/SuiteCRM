#!/bin/bash

# SuiteCRM Docker Startup Script

echo "Starting SuiteCRM Docker Environment..."

# Check if .env file exists, create from template if not
if [ ! -f .env.docker ]; then
    if [ -f env.docker.example ]; then
        echo "Creating .env.docker from template..."
        cp env.docker.example .env.docker
        echo "Please review and customize .env.docker file if needed."
    else
        echo "Error: .env.docker file not found and no template available!"
        echo "Please create .env.docker file with your configuration."
        exit 1
    fi
fi

# Load environment variables
set -a  # automatically export all variables
source .env.docker
set +a

# Build and start containers
echo "Building Docker images..."
docker-compose build

echo "Starting containers..."
docker-compose up -d

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
until docker-compose exec mysql mysqladmin ping -h localhost --silent; do
    echo "Waiting for MySQL..."
    sleep 5
done

echo "MySQL is ready!"

# Generate config.php if it doesn't exist
if [ ! -f config.php ]; then
    echo "Generating SuiteCRM configuration..."
    
    # Generate unique keys and values
    UNIQUE_KEY=$(openssl rand -hex 16)
    SITE_SECURITY_KEY=$(openssl rand -hex 32)
    OAUTH2_ENCRYPTION_KEY=$(openssl rand -hex 32)
    SITE_HOST=$(echo $SUITECRM_SITE_URL | sed 's|.*://||' | sed 's|/.*||' | sed 's|:.*||')
    
    # Set defaults for optional environment variables
    SUITECRM_LOG_LEVEL=${SUITECRM_LOG_LEVEL:-"info"}
    SUITECRM_TIMEZONE=${SUITECRM_TIMEZONE:-"UTC"}
    SUITECRM_SYSTEM_NAME=${SUITECRM_SYSTEM_NAME:-"SuiteCRM"}
    
    # Create config.php from template
    cp config.template.php config.php
    
    # Replace template variables with actual values
    sed -i "s/\${DATABASE_HOST}/mysql/g" config.php
    sed -i "s/\${DATABASE_PORT}/3306/g" config.php
    sed -i "s/\${DATABASE_NAME}/$MYSQL_DATABASE/g" config.php
    sed -i "s/\${DATABASE_USER}/$MYSQL_USER/g" config.php
    sed -i "s/\${DATABASE_PASSWORD}/$MYSQL_PASSWORD/g" config.php
    sed -i "s/\${SITE_URL}/$SUITECRM_SITE_URL/g" config.php
    sed -i "s/\${SITE_HOST}/$SITE_HOST/g" config.php
    sed -i "s/\${SYSTEM_NAME}/$SUITECRM_SYSTEM_NAME/g" config.php
    sed -i "s/\${UNIQUE_KEY}/$UNIQUE_KEY/g" config.php
    sed -i "s/\${SITE_SECURITY_KEY}/$SITE_SECURITY_KEY/g" config.php
    sed -i "s/\${OAUTH2_ENCRYPTION_KEY}/$OAUTH2_ENCRYPTION_KEY/g" config.php
    sed -i "s/\${LOG_LEVEL}/$SUITECRM_LOG_LEVEL/g" config.php
    sed -i "s/\${TIMEZONE}/$SUITECRM_TIMEZONE/g" config.php
    sed -i "s/\${DISABLE_PERSISTENT_CONNECTIONS}/false/g" config.php
    sed -i "s/\${SAVE_QUERY}/false/g" config.php
    sed -i "s/\${DEMO_DATA}/no/g" config.php
    
    echo "Configuration generated successfully!"
    
    # Temporarily unlock installer for setup
    sed -i "s/'installer_locked' => true/'installer_locked' => false/g" config.php
    
    # Run the complete SuiteCRM installation process
    echo "Running SuiteCRM installation process..."
    docker-compose exec -T suitecrm php -r "
        // Set up session variables for silent installation
        session_start();
        \$_SESSION['install_type'] = 'typical';
        \$_SESSION['setup_db_host_name'] = 'mysql';
        \$_SESSION['setup_db_port_num'] = '3306';
        \$_SESSION['setup_db_database_name'] = '$MYSQL_DATABASE';
        \$_SESSION['setup_db_sugarsales_user'] = '$MYSQL_USER';
        \$_SESSION['setup_db_sugarsales_password'] = '$MYSQL_PASSWORD';
        \$_SESSION['setup_db_admin_user_name'] = 'root';
        \$_SESSION['setup_db_admin_password'] = '$MYSQL_ROOT_PASSWORD';
        \$_SESSION['setup_db_create_database'] = false;
        \$_SESSION['setup_db_drop_tables'] = false;
        \$_SESSION['setup_db_create_sugarsales_user'] = false;
        \$_SESSION['demoData'] = 'no';
        \$_SESSION['setup_site_url'] = '$SUITECRM_SITE_URL';
        \$_SESSION['setup_site_admin_user_name'] = '$SUITECRM_ADMIN_USERNAME';
        \$_SESSION['setup_site_admin_password'] = '$SUITECRM_ADMIN_PASSWORD';
        \$_SESSION['default_language'] = 'en_us';
        \$_SESSION['default_currency_name'] = 'US Dollars';
        \$_SESSION['default_currency_symbol'] = '$';
        \$_SESSION['default_currency_iso4217'] = 'USD';
        
        // Define entry point and run installation
        define('sugarEntry', true);
        \$install_script = true;
        
        // Load installation modules
        require_once('install/install_utils.php');
        require_once('install/performSetup.php');
        
        echo 'SuiteCRM installation completed successfully!\n';
    "
    
    # Lock installer after installation
    sed -i "s/'installer_locked' => false/'installer_locked' => true/g" config.php
    
    echo "SuiteCRM has been automatically installed and configured!"
else
    echo "SuiteCRM is already configured."
fi

echo ""
echo "SuiteCRM is now running!"
echo "  Application: http://localhost:8080"
echo "  phpMyAdmin: http://localhost:8081"
echo ""
echo "To stop the containers, run: docker-compose down"
echo "To view logs, run: docker-compose logs -f" 