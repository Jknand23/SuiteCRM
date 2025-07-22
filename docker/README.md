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

### 2. Configure environment (optional)
Copy and modify the environment file if needed:
```bash
cp .env.docker.example .env.docker
```

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

### 5. Complete installation
On first run, access http://localhost:8080 and use these database credentials:
- Database Host: `mysql`
- Database Name: `suitecrm`
- Database User: `suitecrm`
- Database Password: `suitecrm_password`

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