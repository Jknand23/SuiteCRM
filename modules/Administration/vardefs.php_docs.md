# vardefs.php

## Overview
**@fileoverview** Database schema definitions for the Administration module, including the system configuration table and upgrade history tracking table.

**@package** Administration  
**@copyright** SugarCRM Inc. / SalesAgility Ltd.  
**@license** AGPL v3  

This file defines the database schema structures for two critical tables in SuiteCRM's administration system: the configuration storage table and the upgrade history tracking table. These definitions are used by the SugarBean ORM for database interactions and query generation.

## Database Operations

### Administration Configuration Table
Defines the primary configuration storage table used by the Administration module:

#### Table Structure: `config`
- **Table Name**: `config`
- **Purpose**: System table containing system-wide configuration definitions
- **Access Pattern**: Frequently read, occasionally written
- **Relationships**: Used by Administration.php class for settings management

#### Field Definitions

##### category
- **Type**: VARCHAR(32)
- **Purpose**: Groups settings into logical categories for organization
- **Usage**: Used in WHERE clauses for filtered configuration retrieval
- **Examples**: 'mail', 'ldap', 'proxy', 'system', 'notify'
- **Index**: Indexed for performance on category-based queries

##### name
- **Type**: VARCHAR(32) 
- **Purpose**: Identifies the specific setting within a category
- **Uniqueness**: Combined with category forms logical unique identifier
- **Label**: Uses 'LBL_LIST_NAME' for UI display
- **Usage**: Part of composite key for configuration lookup

##### value
- **Type**: TEXT
- **Purpose**: Stores the actual configuration value
- **Flexibility**: TEXT type allows for large configuration values
- **Storage**: Can contain serialized data, JSON, or simple strings
- **Label**: Uses 'LBL_LIST_RATE' for UI display

#### Indexing Strategy
- **idx_config_cat**: Index on `category` field for efficient category-based queries
- **Performance**: Optimizes Administration.retrieveSettings() method calls
- **Query Pattern**: Supports `WHERE category = ?` queries used by Administration class

### Upgrade History Table
Tracks SuiteCRM upgrades and module installations over time:

#### Table Structure: `upgrade_history`
- **Table Name**: `upgrade_history`
- **Purpose**: Tracks Sugar upgrades and module installations for system maintenance
- **Usage**: Used by Upgrade Wizard and Module Loader components
- **Audit Trail**: Provides complete history of system modifications

#### Field Definitions

##### id
- **Type**: ID (Primary Key)
- **Purpose**: Unique identifier for each upgrade record
- **Required**: True
- **Reportable**: False (system data, not user-facing)
- **Auto-Generation**: Automatically generated UUID

##### filename
- **Type**: VARCHAR(255)
- **Purpose**: Stores cached filename containing upgrade scripts and content
- **Usage**: References upgrade package files in cache directory
- **Path**: Typically points to files in cache/upload/ directory

##### md5sum
- **Type**: VARCHAR(32)
- **Purpose**: MD5 checksum of the upgrade file for integrity verification
- **Unique**: Indexed as unique to prevent duplicate installations
- **Security**: Validates file integrity before and after installation

##### type
- **Type**: VARCHAR(30)
- **Purpose**: Categorizes the upgrade type
- **Values**: 'module', 'patch', 'theme', 'language', 'full'
- **Filtering**: Used for upgrade type-specific reporting and management

##### status
- **Type**: VARCHAR(50)
- **Purpose**: Tracks the current status of the upgrade
- **Values**: 'installed', 'uninstalled', 'disabled', 'pending'
- **Workflow**: Used by Upgrade Wizard for installation state management

##### version
- **Type**: VARCHAR(64)
- **Purpose**: Version information from the manifest file
- **Source**: Extracted from upgrade package manifest
- **Comparison**: Used for version conflict detection and dependency checking

##### name
- **Type**: VARCHAR(255)
- **Purpose**: Human-readable name of the upgrade or module
- **Display**: Used in administrative interfaces for user-friendly identification
- **Source**: Typically from manifest file's name field

##### description
- **Type**: TEXT
- **Purpose**: Detailed description of the upgrade or module
- **Content**: Extended information about functionality and changes
- **UI Display**: Shown in upgrade history and module management interfaces

##### id_name
- **Type**: VARCHAR(255)
- **Purpose**: Unique identifier for the module or upgrade package
- **Uniqueness**: Used to prevent duplicate installations of same package
- **Reference**: Links to manifest file's id_name field

##### manifest
- **Type**: LONGTEXT
- **Purpose**: Serialized copy of the complete manifest file
- **Storage**: PHP serialized array containing all manifest data
- **Recovery**: Allows for upgrade reversal and detailed package information

##### date_entered
- **Type**: DATETIME
- **Purpose**: Timestamp of upgrade or module installation
- **Required**: True
- **Audit**: Provides chronological history of system changes
- **Timezone**: Stored in system timezone

##### enabled
- **Type**: BOOLEAN
- **Purpose**: Indicates whether the upgrade/module is currently active
- **Default**: True (1)
- **Control**: Allows for disabling upgrades without uninstalling

## Internal API Calls

### Dictionary Definition Structure
The file uses SuiteCRM's standard dictionary pattern:
- **Global Dictionary**: Adds definitions to global `$dictionary` array
- **Table Mapping**: Maps bean names to database tables
- **Field Metadata**: Provides comprehensive field definitions for ORM
- **Index Specifications**: Defines database indexes for performance

### Field Type Integration
Integrates with SuiteCRM's field type system:
- **Standard Types**: varchar, text, datetime, bool, id
- **Length Specifications**: Proper field length definitions
- **Required Flags**: Indicates mandatory fields for validation
- **Default Values**: Specifies default values for new records

## External API Calls

### SugarBean Integration
These definitions are used by SugarBean and related classes:
- **Administration.php**: Uses config table definition for settings management
- **UpgradeWizard**: Uses upgrade_history table for installation tracking
- **Module Loader**: References upgrade_history for module management

### Database Layer Integration
Integrates with SuiteCRM's database abstraction layer:
- **DDL Generation**: Used for CREATE TABLE statements during installation
- **Query Builder**: Provides metadata for query generation
- **Validation**: Field definitions used for data validation

## UI Functionality

### Administration Interface Support
The schema supports various administrative interfaces:
- **Configuration Forms**: Config table fields mapped to form elements
- **Upgrade History**: Upgrade history table displayed in management interfaces
- **Module Loader**: Status and metadata displayed in module management

### Field Labels
Uses SuiteCRM's label system for internationalization:
- **LBL_LIST_SYMBOL**: Label for category field
- **LBL_LIST_NAME**: Label for name field  
- **LBL_LIST_RATE**: Label for value field

## Performance Considerations

### Indexing Strategy
Optimized for common query patterns:
- **Category Index**: `idx_config_cat` optimizes category-based configuration queries
- **Primary Key**: Standard UUID primary key for upgrade_history
- **Unique Constraint**: MD5 unique index prevents duplicate upgrade installations

### Data Types
Chosen for optimal storage and performance:
- **VARCHAR Limits**: Appropriate lengths for typical use cases
- **TEXT Fields**: Used for variable-length content storage
- **LONGTEXT**: For large manifest storage requirements

The vardefs.php file provides the foundational database schema for SuiteCRM's administration and upgrade systems, ensuring proper data storage, indexing, and integration with the application's ORM layer. 