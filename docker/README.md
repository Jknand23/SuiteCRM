# SuiteCRM Docker Setup

## Overview
This Docker configuration provides a complete development and production-ready environment for SuiteCRM with the following services:
- PHP 7.4 with Apache
- MySQL 5.7
- Elasticsearch 7.17
- Redis 6
- phpMyAdmin

## Prerequisites
- Docker Engine 20.10+
- Docker Compose 1.29+
- At least 4GB of RAM allocated to Docker
- 10GB of free disk space

## Quick Start

### 1. Clone the repository
```bash
git clone https://github.com/salesagility/SuiteCRM.git
cd SuiteCRM
```

### 2. Configure environment (recommended)
Copy and modify the environment file:
```bash
cp env.docker.example .env.docker
# Edit .env.docker to customize your settings
```

**Important environment variables:**
- `SUITECRM_ADMIN_USERNAME`: Admin username (default: admin)
- `SUITECRM_ADMIN_PASSWORD`: Admin password (default: admin123)
- `SUITECRM_SITE_URL`: Your site URL (default: http://localhost:8080)
- `MYSQL_PASSWORD`: Database password (default: suitecrm_password)

### 3. Start the containers
```bash
# Using the startup script (recommended)
chmod +x docker-startup.sh
./docker-startup.sh

# Or manually with docker-compose
docker-compose up -d
```

### 4. Access SuiteCRM
- Application: http://localhost:8080
- phpMyAdmin: http://localhost:8081

### 5. Login to SuiteCRM
The application is automatically configured on first startup! Use these credentials:
- Username: `admin` (or your custom `SUITECRM_ADMIN_USERNAME`)
- Password: `admin123` (or your custom `SUITECRM_ADMIN_PASSWORD`)

**No manual installation required!** The startup script automatically:
- Creates `config.php` with proper database settings
- Sets up the database schema
- Creates the admin user
- Locks the installer for security

## Data Persistence

Your SuiteCRM data is automatically persisted between container restarts:

### Persistent Volumes
- **Database**: MySQL data stored in named volume `mysql_data`
- **Uploads**: User uploads in `./upload/` directory
- **Customizations**: Custom code in `./custom/` directory  
- **Cache**: Application cache in `./cache/` directory
- **Configuration**: SuiteCRM config in `./config.php` file

### Starting Fresh
To completely reset your SuiteCRM installation:
```bash
# Stop containers and remove all data
docker-compose down -v
rm -f config.php
rm -rf upload/* custom/* cache/*

# Start fresh
./docker-startup.sh
```

## Container Management

### View logs
```bash
# All containers
docker-compose logs -f

# Specific service
docker-compose logs -f suitecrm
```

### Stop containers
```bash
docker-compose down
```

### Stop and remove volumes (fresh start)
```bash
docker-compose down -v
```

### Access container shell
```bash
# SuiteCRM container
docker-compose exec suitecrm bash

# MySQL container
docker-compose exec mysql bash
```

## Configuration

### PHP Settings
Edit `docker/php/php.ini` to modify PHP configuration.

### Apache Settings
Edit `docker/apache/000-default.conf` to modify Apache configuration.

### Ports
Default ports (modify in docker-compose.yml):
- 8080: SuiteCRM application
- 8081: phpMyAdmin
- 3306: MySQL
- 9200: Elasticsearch
- 6379: Redis

## Troubleshooting

### Permission Issues
If you encounter permission errors:
```bash
docker-compose exec suitecrm chown -R www-data:www-data /var/www/html
docker-compose exec suitecrm chmod -R 755 /var/www/html
```

### MySQL Connection Issues
Ensure MySQL is fully started:
```bash
docker-compose exec mysql mysqladmin ping -h localhost
```

### Elasticsearch Memory Issues
If Elasticsearch fails to start, increase Docker memory allocation or reduce ES heap size in docker-compose.yml.

### Configuration Issues
If you need to regenerate the SuiteCRM configuration:
```bash
# Remove existing config and restart
rm config.php
docker-compose restart suitecrm
```

### Admin Login Issues
If you can't login with your admin credentials:
```bash
# Reset admin password
docker-compose exec suitecrm php -r "
define('sugarEntry', true);
require_once('config.php');
require_once('include/entryPoint.php');
\$admin = new User();
\$admin->retrieve_by_string_fields(array('user_name' => 'admin'));
\$admin->user_hash = User::getPasswordHash('admin123');
\$admin->save();
echo 'Admin password reset to admin123\n';
"
```

## Production Considerations

1. **Security**: Change all default passwords in production
2. **SSL/TLS**: Use a reverse proxy (nginx/traefik) for HTTPS
3. **Backups**: Implement regular backup strategy for volumes
4. **Monitoring**: Add monitoring tools (Prometheus, Grafana)
5. **Resources**: Adjust container resources based on load

## Backup and Restore

### Backup
```bash
# Backup database
docker-compose exec mysql mysqldump -u root -proot_password suitecrm > backup.sql

# Backup files
docker cp suitecrm_app:/var/www/html/upload ./upload_backup
docker cp suitecrm_app:/var/www/html/custom ./custom_backup
```

### Restore
```bash
# Restore database
docker-compose exec -T mysql mysql -u root -proot_password suitecrm < backup.sql

# Restore files
docker cp ./upload_backup suitecrm_app:/var/www/html/upload
docker cp ./custom_backup suitecrm_app:/var/www/html/custom
``` 