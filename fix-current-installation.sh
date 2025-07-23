#!/bin/bash

# Fix Current SuiteCRM Installation State
# This script fixes the current partially configured installation

echo "Fixing SuiteCRM installation state..."

# Stop containers
echo "Stopping containers..."
docker-compose down

# Remove partial configuration
echo "Cleaning up partial configuration..."
rm -f config.php

# Restart with fresh installation
echo "Starting fresh installation..."
./docker-startup.sh

echo ""
echo "Installation fix complete!"
echo "SuiteCRM should now be accessible at http://localhost:8080"
echo "Login with your configured credentials from .env.docker" 