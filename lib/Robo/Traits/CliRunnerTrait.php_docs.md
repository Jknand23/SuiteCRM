# CliRunnerTrait.php Documentation

## @fileoverview
Bootstrap trait that establishes a fully functional SuiteCRM environment for command-line interface operations. Provides essential initialization methods for CLI tools requiring database connectivity and complete SuiteCRM framework access.

## @package
SuiteCRM\Robo\Traits

## @copyright
Copyright (C) 2011 - 2019 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Trait Overview

### CliRunnerTrait
Foundation trait that enables CLI tools to access the complete SuiteCRM environment including database connections, configuration settings, and framework functionality. Essential for any CLI operation requiring SuiteCRM system integration.

## Methods

### bootstrap(): void
**Purpose**: Initializes complete SuiteCRM environment for CLI operations including database connectivity and framework access.

**Global Variables Initialized**:
- `$current_language`: Set to 'en_us' for consistent language context
- `$app_list_strings`: Loaded application string arrays for the current language
- `$sugar_config`: Global configuration array with merged settings

**Constants Defined**:
- **sugarEntry**: Security constant enabling SuiteCRM core file access
- **SUITE_CLI_RUNNER**: Identifies CLI execution context for framework logic

**Configuration Loading Sequence**:
1. **config.php**: Primary SuiteCRM configuration
2. **config_override.php**: Administrative and custom overrides  
3. **entryPoint.php**: Core SuiteCRM initialization
4. **tests/config.test.php**: Test environment configuration (if available)

**Environment Setup**:
- Establishes working database connection
- Configures language and localization settings
- Merges test configuration for testing environments
- Sets unlimited resource limits for CLI operations

## Initialization Process

### Security and Entry Point Setup
- **Entry Point Validation**: Defines sugarEntry constant for secure file inclusion
- **CLI Context**: Establishes SUITE_CLI_RUNNER constant for CLI-specific logic
- **Framework Access**: Enables full SuiteCRM framework functionality

### Configuration Management
- **Base Configuration**: Loads primary SuiteCRM settings
- **Override Integration**: Applies administrative configuration changes
- **Test Environment**: Merges test-specific configuration when available
- **Resource Limits**: Removes resource constraints for CLI operations

### Language and Localization
- **Default Language**: Sets English US as default CLI language
- **String Loading**: Initializes application string arrays
- **Consistent Context**: Ensures predictable language behavior

### Database Connectivity
- **Connection Establishment**: Initializes database connections through entryPoint.php
- **Framework Access**: Enables ORM and database layer functionality
- **Data Operations**: Supports full CRUD operations on SuiteCRM data

## CLI Environment Features

### Framework Integration
- **Complete Access**: Full SuiteCRM framework functionality available
- **Database Operations**: Native SugarBean and database access
- **Module System**: Access to all installed SuiteCRM modules
- **Security Context**: Proper security and permission handling

### Configuration Access
- **Global Settings**: Access to all SuiteCRM configuration options
- **Test Overrides**: Automatic test configuration integration
- **Runtime Settings**: Support for dynamic configuration changes
- **Resource Management**: Optimized settings for CLI operations

### Error Handling
- **Graceful Degradation**: Handles missing test configuration files
- **Framework Errors**: Inherits SuiteCRM's error handling mechanisms
- **CLI Context**: Appropriate error reporting for command-line usage

## Use Cases

### Development Tools
- **Database Migration**: CLI tools for schema updates and data migration
- **Code Generation**: Automated generation of SuiteCRM components
- **Development Utilities**: Tools for development workflow optimization

### System Administration
- **Maintenance Scripts**: Automated system maintenance and cleanup
- **Repair Commands**: System repair and consistency checking
- **Upgrade Tools**: Automated upgrade and update processes

### Testing Framework
- **Unit Testing**: Foundation for SuiteCRM unit test execution
- **Integration Testing**: Support for full-system integration tests
- **Test Data Management**: CLI tools for test environment setup

### Data Operations
- **Import/Export**: Bulk data operations and transformations
- **Report Generation**: CLI-based report and analytics generation
- **Data Validation**: Automated data consistency and validation tools

## Integration Patterns

### Robo Commands
```php
class MyCommand extends \Robo\Tasks
{
    use CliRunnerTrait;
    
    public function myTask()
    {
        $this->bootstrap();
        // Full SuiteCRM environment now available
        $bean = BeanFactory::getBean('Accounts');
    }
}
```

### Testing Integration
- **Test Environment**: Automatic test configuration loading
- **Isolated Context**: Separate configuration for testing scenarios
- **Framework Testing**: Support for testing SuiteCRM components

## Performance Considerations

### Initialization Overhead
- **One-time Setup**: Bootstrap required once per CLI execution
- **Framework Loading**: Complete SuiteCRM initialization overhead
- **Database Connection**: Establishes persistent database connectivity

### Resource Optimization
- **Unlimited Resources**: Removes PHP resource limits for CLI operations
- **Memory Management**: Optimized for long-running CLI processes
- **Connection Pooling**: Efficient database connection management

## Security Considerations

### Access Control
- **Entry Point Security**: Maintains SuiteCRM's security model
- **Framework Permissions**: Inherits SuiteCRM's permission system
- **CLI Context**: Appropriate security context for command-line operations

### Configuration Security
- **Secure Loading**: Safe inclusion of configuration files
- **Test Isolation**: Secure handling of test configuration data
- **Runtime Security**: Maintains security throughout CLI execution

## Best Practices

### Usage Guidelines
- **Call Bootstrap First**: Always invoke bootstrap() before SuiteCRM operations
- **Single Initialization**: Bootstrap once per CLI command execution
- **Error Handling**: Implement appropriate error handling for bootstrap failures

### Performance Optimization
- **Selective Usage**: Only use when full SuiteCRM access is required
- **Resource Monitoring**: Monitor memory usage in long-running processes
- **Connection Management**: Properly manage database connections

### Testing Considerations
- **Test Configuration**: Ensure test configuration files are properly configured
- **Environment Isolation**: Use separate configurations for test environments
- **Cleanup**: Implement proper cleanup after CLI operations 