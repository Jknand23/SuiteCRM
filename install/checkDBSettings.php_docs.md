# checkDBSettings.php Documentation

## @fileoverview Database configuration validation and connection testing for SuiteCRM installation process
## @package SuiteCRM\Install
## @copyright 2004-2013 SugarCRM Inc., 2011-2018 SalesAgility Ltd.
## @license GNU Affero General Public License version 3

## Overview
The checkDBSettings.php file provides comprehensive database configuration validation during the SuiteCRM installation process. It validates database parameters, tests connections, and ensures database compatibility before proceeding with the installation.

## Core Functionality

### Database Validation Process
The main function `checkDBSettings()` performs systematic validation:
1. **Basic Parameter Validation**: Checks required fields and formats
2. **Database Name Validation**: Ensures valid database naming conventions
3. **Connection Testing**: Attempts database connections with provided credentials
4. **Database-Specific Validation**: Applies database type-specific rules

### Error Handling and Reporting
- Comprehensive error collection and reporting
- Database-specific error messages
- Detailed logging of validation steps
- User-friendly error presentation

## Database Operations

### Connection Testing
- Tests database connectivity using provided credentials
- Validates administrative and application user accounts
- Supports various database types (MySQL, MSSQL, Oracle)
- Graceful handling of connection failures

### Database Instance Management
- Creates database instance objects for testing
- Configures connection parameters dynamically
- Handles database-specific connection requirements
- Manages connection cleanup and resource disposal

## Internal API Calls

### Validation Functions
- `copyInputsIntoSession()`: Transfers form inputs to session storage
- `getInstallDbInstance()`: Creates database instance for testing
- `printErrors()`: Formats and displays validation errors
- `installLog()`: Records validation process steps

### Database Interaction
- Database connection testing with various parameter combinations
- Database name format validation per database type
- User credential validation and authentication testing
- Database existence and accessibility verification

### Session Management
- Retrieves configuration from session variables
- Validates session data integrity
- Stores validation results in session
- Manages installation state persistence

## External API Calls

### Database Connectivity
- Database connection attempts using configured parameters
- Database-specific driver utilization
- Connection pooling and resource management
- Network connectivity validation

## UI Functionality

### Error Display
- Formatted error message presentation
- Multi-language error message support
- Context-specific error explanations
- Recovery suggestion display

### Progress Reporting
- Installation step logging and progress tracking
- Real-time feedback during validation process
- Status updates for long-running operations
- Silent mode support for automated installations

## Validation Rules

### Database Name Validation
Different validation rules based on database type:

#### MySQL Validation
- Prohibits forward slashes and periods in database names
- Applies MySQL-specific naming conventions
- Validates character encoding compatibility

#### MSSQL Validation
- Prohibits special characters: `"'*/\?:<>-`
- Enforces MSSQL identifier naming rules
- Validates reserved word conflicts

#### Oracle Validation
- Specific validation rules for Oracle database naming
- Service name and SID validation
- TNS entry validation

### Connection Parameter Validation
- **Host Name**: Required for most database types (optional for Oracle)
- **Port Number**: Optional but validated if provided
- **Database Name**: Required and format-validated
- **Credentials**: Username/password validation and testing

### Security Validation
- Password confirmation matching
- User account privilege verification
- Database access permission testing
- Security policy compliance checking

## Error Categories

### Configuration Errors
- Missing required parameters
- Invalid database names
- Malformed connection strings
- Incompatible database versions

### Connection Errors
- Network connectivity issues
- Authentication failures
- Database server unavailability
- Permission denied errors

### Database-Specific Errors
- Database type compatibility issues
- Version mismatch problems
- Character set conflicts
- Collation incompatibilities

## Session Variables

### Required Session Data
- `$_SESSION['setup_db_database_name']`: Target database name
- `$_SESSION['setup_db_host_name']`: Database server hostname
- `$_SESSION['setup_db_type']`: Database type identifier
- `$_SESSION['setup_db_sugarsales_user']`: Application database user
- `$_SESSION['setup_db_sugarsales_password']`: Application user password

### Optional Configuration
- `$_SESSION['setup_db_port_num']`: Database server port
- `$_SESSION['setup_db_host_instance']`: Database instance name
- `$_SESSION['setup_db_create_database']`: Database creation flag
- `$_SESSION['setup_db_create_sugarsales_user']`: User creation flag

## Associated Tests
No specific test files identified for this functionality. Testing would cover:
- Database connection validation accuracy
- Error message correctness and localization
- Database type-specific validation rules
- Session data handling integrity
- Network failure handling
- Security validation effectiveness

## Dependencies
- Database abstraction layer and drivers
- Session management system
- Logging infrastructure (`installLog`)
- Error formatting utilities (`printErrors`)
- Language localization system (`$mod_strings`)

## Security Considerations

### Credential Handling
- Secure credential validation without storage
- Password confirmation verification
- Session-based credential management
- Protection against credential exposure

### Connection Security
- Secure database connection establishment
- SSL/TLS support for database connections
- Network security validation
- Connection timeout management

## Integration Points

### Installation Workflow
- Pre-installation validation step
- Configuration persistence to session
- Progress tracking integration
- Error handling coordination

### Database System Integration
- Multi-database type support
- Database driver abstraction
- Connection pooling compatibility
- Performance optimization

## Performance Considerations

### Validation Efficiency
- Efficient parameter validation ordering
- Minimal database connection attempts
- Resource cleanup and disposal
- Connection reuse where possible

### Error Handling Optimization
- Early validation failure detection
- Efficient error collection and reporting
- Minimal resource usage during failures
- Quick recovery mechanisms

## Configuration Support

### Database Types
- MySQL/MariaDB support
- Microsoft SQL Server support
- Oracle database support
- PostgreSQL compatibility (if configured)

### Installation Modes
- Interactive installation validation
- Silent installation support
- Automated deployment compatibility
- Custom configuration validation 