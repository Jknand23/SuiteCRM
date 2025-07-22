# collations.php_docs.md

/**
 * @fileoverview Database collation configuration for SuiteCRM installation
 * @package SuiteCRM
 * @copyright SugarCRM Inc. / SalesAgility Ltd.
 * @license AGPL-3.0
 */

## Overview

This file defines database collation settings for SuiteCRM installation, specifically for MySQL database configurations.

## Database Operations

### Collation Configuration Array

**$collations Array**
- **Purpose**: Defines supported database collations and character sets
- **Structure**: Multi-dimensional array organized by database type
- **MySQL Collations**:
  - `utf8mb4_general_ci` with `utf8mb4` charset (recommended for full Unicode support)
  - `utf8_general_ci` with `utf8` charset (legacy support)

### Database Compatibility

**Character Set Support**
- **utf8mb4**: Supports full Unicode including emojis and special characters
- **utf8**: Standard UTF-8 support (3-byte maximum per character)

**Collation Settings**
- **general_ci**: Case-insensitive general collation
- Optimized for installation compatibility across different MySQL versions

## Internal API Integration

### Installation Process Integration
- Used by SuiteCRM installation scripts to configure database collation
- Referenced during database setup and schema creation
- Ensures consistent character encoding across all database tables

### Configuration Management
- Provides standardized collation options for different database versions
- Supports both legacy and modern MySQL installations
- Maintains backward compatibility while enabling enhanced Unicode support

## Usage Context

**Installation Scenarios**
- New SuiteCRM installations
- Database migration operations
- Character set upgrades from utf8 to utf8mb4
- Multi-language deployment configurations

**Best Practices**
- utf8mb4_general_ci recommended for new installations
- utf8_general_ci maintained for legacy system compatibility
- Consistent application across all database tables and connections 