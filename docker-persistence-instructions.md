# Docker Configuration Persistence Setup

## Overview
This guide explains how to persist your SuiteCRM configuration between Docker container restarts.

## The Problem
Previously, your configuration files were mounted as read-only to `/tmp/` and then copied to the container. Any changes made through the SuiteCRM UI were lost when the container was restarted.

## The Solution
We've updated the Docker setup to directly mount configuration files as read-write volumes, allowing changes to persist on your host system.

## Changes Made

### 1. Updated docker-compose.yml
- Changed config file mounts from read-only `/tmp/` to direct mounts:
  ```yaml
  # Old (read-only, temporary):
  - ./config.php:/tmp/config.php:ro
  - ./config_override.php:/tmp/config_override.php:ro
  
  # New (read-write, direct):
  - ./config.php:/var/www/html/config.php
  - ./config_override.php:/var/www/html/config_override.php
  ```

### 2. Created docker-entrypoint-updated.sh
- Removed the copy logic from `/tmp/`
- Sets proper permissions on mounted files
- Creates empty config files if they don't exist

## How to Apply These Changes

1. **Stop your current containers:**
   ```powershell
   docker-compose down
   ```

2. **Backup your current configuration (if any):**
   ```powershell
   Copy-Item config.php config.php.backup-$(Get-Date -Format "yyyyMMdd-HHmmss")
   Copy-Item config_override.php config_override.php.backup-$(Get-Date -Format "yyyyMMdd-HHmmss")
   ```

3. **Rebuild and start containers:**
   ```powershell
   docker-compose build --no-cache
   docker-compose up -d
   ```

## What This Means

- ✅ Any configuration changes made through the SuiteCRM UI will be saved to your host files
- ✅ When you restart containers, your configuration will persist
- ✅ You can edit config files on your host and see changes in the container
- ✅ No need to reconfigure settings after container restarts

## Additional Configuration Files

If you have other configuration files that need persistence, you can add them to docker-compose.yml:

```yaml
volumes:
  # Add any additional config files here
  - ./path/to/host/file:/var/www/html/path/to/container/file
```

## Troubleshooting

### Permission Issues
If you encounter permission errors, ensure the files have proper permissions:
```powershell
# In PowerShell, you may need to adjust file permissions through Windows Explorer
# Right-click → Properties → Security → Edit permissions
```

### Configuration Not Persisting
1. Check that the files exist on your host system
2. Verify the volume mounts in docker-compose.yml
3. Check container logs: `docker logs suitecrm_app`

### Fresh Installation
If starting fresh:
1. Delete or rename existing config files
2. Start containers - empty files will be created
3. Go through SuiteCRM installation
4. Configuration will be saved to host files

## Best Practices

1. **Regular Backups:** Keep backups of your config files
2. **Version Control:** Consider adding config files to .gitignore if they contain sensitive data
3. **Environment Variables:** Use .env.docker for sensitive credentials instead of hardcoding in config files

## Summary
Your Docker setup is now configured to persist all configuration changes. You should no longer lose settings when restarting containers! 