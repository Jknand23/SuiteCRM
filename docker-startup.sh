#!/bin/bash

# SuiteCRM Docker Startup Script

echo "Starting SuiteCRM Docker Environment..."

# Check if .env file exists
if [ ! -f .env.docker ]; then
    echo "Error: .env.docker file not found!"
    echo "Please copy .env.docker.example to .env.docker and configure it."
    exit 1
fi

# Load environment variables
export $(grep -v '^#' .env.docker | xargs)

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

# Check if SuiteCRM is already installed
if [ ! -f config.php ]; then
    echo "SuiteCRM is not installed. Please access http://localhost:8080 to complete installation."
    echo "Database credentials:"
    echo "  Host: mysql"
    echo "  Database: suitecrm"
    echo "  Username: suitecrm"
    echo "  Password: suitecrm_password"
else
    echo "SuiteCRM is already installed."
fi

echo ""
echo "SuiteCRM is now running!"
echo "  Application: http://localhost:8080"
echo "  phpMyAdmin: http://localhost:8081"
echo ""
echo "To stop the containers, run: docker-compose down"
echo "To view logs, run: docker-compose logs -f" 