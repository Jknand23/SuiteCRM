# SuiteCRM Docker Commands Reference

## Container Management

### Start/Stop Containers
```bash
# Start all containers in background
docker-compose up -d

# Start specific service
docker-compose up -d suitecrm

# Stop all containers
docker-compose down

# Stop and remove volumes (fresh start)
docker-compose down -v

# Restart specific service
docker-compose restart suitecrm
```

### Container Status & Monitoring
```bash
# Check container status
docker-compose ps

# View logs for all containers
docker-compose logs -f

# View logs for specific service
docker-compose logs -f suitecrm
docker-compose logs -f mysql
docker-compose logs -f elasticsearch

# Check container resource usage
docker stats
```

### Access Container Shells
```bash
# Access SuiteCRM container bash
docker-compose exec suitecrm bash

# Access MySQL container
docker-compose exec mysql bash

# Access as root user
docker-compose exec -u root suitecrm bash
```

## Development & Testing Commands

### Running Tests
```bash
# Install development dependencies
docker-compose exec suitecrm bash -c "cd /var/www/html && composer install --dev"

# Run all unit tests
docker-compose exec suitecrm bash -c "cd /var/www/html && vendor/bin/phpunit tests/unit/phpunit/lib/SuiteCRM/Utility/ --bootstrap tests/bootstrap.php"

# Run single test file
docker-compose exec suitecrm bash -c "cd /var/www/html && vendor/bin/phpunit tests/unit/phpunit/ConfigTest.php --bootstrap tests/bootstrap.php --verbose"

# Run Codeception tests
docker-compose exec suitecrm bash -c "cd /var/www/html && vendor/bin/codecept run unit"

# Run tests with coverage
docker-compose exec suitecrm bash -c "cd /var/www/html && vendor/bin/phpunit tests/unit/phpunit/lib/SuiteCRM/Utility/ --bootstrap tests/bootstrap.php --coverage-text"
```

### File Operations
```bash
# Copy files from host to container
docker cp tests suitecrm_app:/var/www/html/
docker cp codeception.dist.yml suitecrm_app:/var/www/html/

# Copy files from container to host
docker cp suitecrm_app:/var/www/html/upload ./upload_backup
docker cp suitecrm_app:/var/www/html/custom ./custom_backup

# Set proper permissions
docker-compose exec suitecrm chown -R www-data:www-data /var/www/html
docker-compose exec suitecrm chmod -R 755 /var/www/html
```

### Database Operations
```bash
# Access MySQL CLI
docker-compose exec mysql mysql -u root -proot_password

# Access SuiteCRM database
docker-compose exec mysql mysql -u suitecrm -psuitecrm_password suitecrm

# Create database backup
docker-compose exec mysql mysqldump -u root -proot_password suitecrm > backup.sql

# Restore database from backup
docker-compose exec -T mysql mysql -u root -proot_password suitecrm < backup.sql

# Check MySQL status
docker-compose exec mysql mysqladmin ping -h localhost
```

## Application Management

### SuiteCRM Operations
```bash
# Check SuiteCRM version and status
docker-compose exec suitecrm bash -c "cd /var/www/html && php -v"

# Run SuiteCRM repairs
docker-compose exec suitecrm bash -c "cd /var/www/html && php -f repair.php"

# Clear cache
docker-compose exec suitecrm bash -c "cd /var/www/html && rm -rf cache/*"

# Check disk usage
docker-compose exec suitecrm bash -c "df -h"

# View active processes
docker-compose exec suitecrm bash -c "ps aux"
```

### Composer Operations
```bash
# Install dependencies
docker-compose exec suitecrm bash -c "cd /var/www/html && composer install"

# Install dev dependencies
docker-compose exec suitecrm bash -c "cd /var/www/html && composer install --dev"

# Update dependencies
docker-compose exec suitecrm bash -c "cd /var/www/html && composer update"

# Show installed packages
docker-compose exec suitecrm bash -c "cd /var/www/html && composer show"
```

## Service-Specific Commands

### Elasticsearch
```bash
# Check Elasticsearch health
curl http://localhost:9200/_cluster/health

# From inside container
docker-compose exec suitecrm curl http://elasticsearch:9200/_cluster/health

# View Elasticsearch logs
docker-compose logs elasticsearch
```

### Redis
```bash
# Access Redis CLI
docker-compose exec redis redis-cli

# Check Redis status
docker-compose exec redis redis-cli ping

# Monitor Redis operations
docker-compose exec redis redis-cli monitor
```

### phpMyAdmin
```bash
# Access phpMyAdmin (web interface)
# http://localhost:8081

# Check phpMyAdmin logs
docker-compose logs phpmyadmin
```

## Debugging & Troubleshooting

### Debug Commands
```bash
# Check container resource limits
docker-compose exec suitecrm bash -c "cat /proc/meminfo"
docker-compose exec suitecrm bash -c "cat /proc/cpuinfo"

# Check PHP configuration
docker-compose exec suitecrm bash -c "php -i"
docker-compose exec suitecrm bash -c "php -m"

# Check Apache status
docker-compose exec suitecrm bash -c "service apache2 status"

# View error logs
docker-compose exec suitecrm bash -c "tail -f /var/log/apache2/error.log"
docker-compose exec suitecrm bash -c "tail -f /var/log/php_errors.log"
```

### Health Checks
```bash
# Test application connectivity
curl http://localhost:8080

# Check all service endpoints
curl http://localhost:8080          # SuiteCRM
curl http://localhost:8081          # phpMyAdmin  
curl http://localhost:9200          # Elasticsearch
curl http://localhost:6379          # Redis (will show error but confirms port)
```

### Network Troubleshooting
```bash
# Check container network
docker network ls
docker network inspect suitecrm_suitecrm_network

# Test inter-container connectivity
docker-compose exec suitecrm ping mysql
docker-compose exec suitecrm ping elasticsearch
docker-compose exec suitecrm ping redis
```

## Development Workflows

### Fresh Environment Setup
```bash
# Complete fresh start
docker-compose down -v
docker system prune -f
docker-compose up -d
docker-compose exec suitecrm composer install --dev
```

### Daily Development Commands
```bash
# Morning startup
docker-compose up -d
docker-compose logs -f suitecrm

# Run tests before committing
docker-compose exec suitecrm bash -c "cd /var/www/html && vendor/bin/phpunit tests/unit/phpunit/lib/SuiteCRM/Utility/ --bootstrap tests/bootstrap.php"

# End of day shutdown
docker-compose down
```

### Performance Monitoring
```bash
# Monitor container performance
docker stats --no-stream

# Check container sizes
docker images
docker system df
```

## Quick Reference URLs

- **SuiteCRM Application**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081  
- **Elasticsearch**: http://localhost:9200
- **Redis**: localhost:6379 (CLI access only)

## Environment Variables

Key environment variables in docker-compose.yml:
- `DATABASE_HOST=mysql`
- `DATABASE_NAME=suitecrm`
- `DATABASE_USER=suitecrm`
- `DATABASE_PASSWORD=suitecrm_password`
- `SITE_URL=http://localhost:8080`

## Notes

1. Always ensure containers are running before executing commands
2. Use `docker-compose exec` for interactive commands
3. Use `docker-compose logs` to troubleshoot issues
4. Back up data before running destructive operations
5. The `--dev` flag is needed for test dependencies 