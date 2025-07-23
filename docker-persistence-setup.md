# SuiteCRM Docker Persistence Setup

## Overview

This document describes the automated Docker setup for SuiteCRM that eliminates the need to manually configure the application through the web installer every time you restart the containers.

## Problem Solved

**Before**: Every time you restarted Docker containers, SuiteCRM would:
- Lose the `config.php` file
- Redirect to the installation wizard
- Require manual database and admin setup
- Reset all configuration settings

**After**: SuiteCRM now automatically:
- Persists configuration between restarts
- Auto-generates `config.php` from environment variables
- Creates admin user automatically
- Maintains all data and settings

## Files Created/Modified

### New Files
1. **`env.docker.example`** - Environment variables template
2. **`config.template.php`** - PHP configuration template
3. **`docker-persistence-setup.md`** - This documentation

### Modified Files
1. **`docker-compose.yml`** - Added config.php volume mount
2. **`docker-startup.sh`** - Added automatic configuration logic
3. **`docker/README.md`** - Updated documentation

## How It Works

### 1. Environment Configuration
- Copy `env.docker.example` to `.env.docker`
- Customize variables like admin username, password, site URL
- Environment loaded automatically by startup script

### 2. Automatic Configuration Generation
When `config.php` doesn't exist, the startup script:
- Generates unique security keys
- Creates config from template with environment variables
- Connects to database with Docker network settings
- Sets installer_locked flag for security

### 3. Admin User Creation
- Automatically creates admin user from environment variables
- Uses secure password hashing
- Sets proper permissions and status

### 4. Data Persistence
- Database: MySQL volume (`mysql_data`)
- Uploads: Host directory (`./upload/`)
- Custom code: Host directory (`./custom/`)
- Cache: Host directory (`./cache/`)
- Configuration: Host file (`./config.php`)

## Usage

### First Time Setup
```bash
# 1. Copy environment template
cp env.docker.example .env.docker

# 2. Edit configuration (optional)
nano .env.docker

# 3. Start containers
./docker-startup.sh
```

### Subsequent Starts
```bash
# Simple restart - all data persists
docker-compose up -d

# Or use the full startup script
./docker-startup.sh
```

### Reset Everything
```bash
# Complete clean slate
docker-compose down -v
rm -f config.php
rm -rf upload/* custom/* cache/*
./docker-startup.sh
```

## Key Benefits

1. **Zero Manual Setup** - No web installer required
2. **Environment Driven** - Configure via .env file
3. **Persistent Data** - All data survives container restarts
4. **Secure Defaults** - Auto-generated keys and locked installer
5. **Development Friendly** - Easy reset and reconfiguration
6. **Production Ready** - Proper volume management

## Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `SUITECRM_ADMIN_USERNAME` | `admin` | Admin username |
| `SUITECRM_ADMIN_PASSWORD` | `admin123` | Admin password |
| `SUITECRM_SITE_URL` | `http://localhost:8080` | Site URL |
| `SUITECRM_SYSTEM_NAME` | `SuiteCRM` | System name |
| `MYSQL_DATABASE` | `suitecrm` | Database name |
| `MYSQL_USER` | `suitecrm` | Database user |
| `MYSQL_PASSWORD` | `suitecrm_password` | Database password |

## Technical Implementation

### Config Template System
- `config.template.php` contains placeholders like `${DATABASE_HOST}`
- Startup script replaces placeholders with actual values
- Supports complex PHP configuration arrays
- Maintains SuiteCRM compatibility

### Volume Mounting Strategy
- Config file mounted as single file volume
- Directories mounted for uploads/custom code
- Database uses Docker named volume
- Enables atomic config replacement

### Security Features
- Auto-generated unique keys (32-64 chars)
- Installer locked by default
- Environment-based secrets
- No hardcoded credentials

## Troubleshooting

### Config Regeneration
```bash
rm config.php
docker-compose restart suitecrm
```

### Password Reset
```bash
docker-compose exec suitecrm php -r "
define('sugarEntry', true);
require_once('config.php');
require_once('include/entryPoint.php');
\$admin = new User();
\$admin->retrieve_by_string_fields(array('user_name' => 'admin'));
\$admin->user_hash = User::getPasswordHash('newpassword123');
\$admin->save();
"
```

### View Generated Config
```bash
# Check current configuration
cat config.php | head -20
```

## Architecture Benefits

This setup follows Docker best practices:
- **Immutable Infrastructure**: Containers can be replaced without data loss
- **Configuration as Code**: All settings in version-controlled files
- **Environment Parity**: Same setup works for dev/staging/production
- **Stateless Containers**: All state externalized to volumes/environment

The solution eliminates the classic "container restart = lost config" problem while maintaining the flexibility to customize any SuiteCRM setting through environment variables. 