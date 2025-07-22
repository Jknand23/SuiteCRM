# RoboTrait.php Documentation

## @fileoverview
Utility trait providing common helper methods for Robo CLI commands in SuiteCRM. Offers user interaction utilities and configuration access patterns to streamline CLI command development and improve user experience.

## @package
SuiteCRM\Robo\Traits

## @copyright
Copyright (C) 2011 - 2018 SalesAgility Ltd.

## @license
GNU Affero General Public License version 3

## Trait Overview

### RoboTrait
Reusable utility trait designed for Robo command classes that extend `\Robo\Tasks`. Provides common functionality for user interaction, configuration access, and CLI workflow patterns.

## Dependencies

### Configuration Access
- **SuiteCRM\Robo\config**: Includes Robo configuration file for environment setup
- **SugarConfig**: Utilizes SuiteCRM's configuration management system

## Methods

### askDefaultOptionWhenEmpty(string $question, string $default, string &$option): void
**Purpose**: Interactive prompt that asks user for input when a required option is not provided.

**Parameters**:
- `$question` (string): Question text to display to user
- `$default` (string): Default value if user provides no input
- `$option` (string, by reference): Variable to store user input or default value

**Behavior**:
- Checks if option parameter is empty
- Prompts user with question and default value when needed
- Updates option variable with user input or default
- Bypasses prompt when option already has value

**Use Cases**:
- Interactive configuration of CLI commands
- User-friendly option collection for complex operations
- Default value provision for optional parameters

**User Experience**: Provides smooth CLI interaction without requiring all parameters upfront.

### chooseConfigOrDefault(string $configKey, string $default): mixed
**Purpose**: Retrieves configuration values with fallback to default when configuration is unavailable.

**Parameters**:
- `$configKey` (string): SuiteCRM configuration key (supports dot notation like 'db_config.db_name')
- `$default` (string): Fallback value when configuration key is not found

**Returns**: Configuration value from SuiteCRM settings or provided default.

**Behavior**:
- Returns default immediately if configKey is empty
- Accesses SugarConfig singleton for configuration retrieval
- Uses SugarConfig::get() with fallback support
- Handles missing configuration gracefully

**Configuration Access**: Supports dot notation for nested configuration values.

## CLI Interaction Patterns

### Interactive Option Collection
```php
$this->askDefaultOptionWhenEmpty(
    'Enter database name:', 
    'suitecrm', 
    $dbName
);
```

### Configuration with Fallback
```php
$dbHost = $this->chooseConfigOrDefault(
    'dbconfig.db_host_name', 
    'localhost'
);
```

## Integration with Robo Framework

### Command Class Integration
- **Robo\Tasks Extension**: Designed for classes extending Robo\Tasks
- **Method Availability**: Methods available through trait inclusion
- **CLI Context**: Optimized for command-line interface operations

### User Experience Enhancement
- **Progressive Disclosure**: Collect information as needed
- **Sensible Defaults**: Provide reasonable default values
- **Minimal Friction**: Reduce barrier to CLI tool usage

## Configuration Management

### SugarConfig Integration
- **Singleton Access**: Efficient configuration instance retrieval
- **Dot Notation**: Support for nested configuration key access
- **Fallback Handling**: Graceful degradation when config unavailable

### Environment Flexibility
- **Development**: Easy configuration override for development
- **Production**: Reliable fallback for production deployments
- **Testing**: Consistent behavior across different environments

## Use Cases

### Development CLI Tools
- **Setup Commands**: Interactive setup with configuration prompts
- **Migration Tools**: Database configuration collection
- **Development Utilities**: Environment-specific configuration access

### Administrative Commands
- **System Configuration**: Admin tool configuration collection
- **Maintenance Scripts**: Configuration-driven maintenance operations
- **Deployment Tools**: Environment-aware deployment configuration

### User-Friendly CLI Design
- **Guided Setup**: Step-by-step configuration collection
- **Smart Defaults**: Intelligent default value provision
- **Error Reduction**: Minimize user input errors through prompts

## Best Practices

### User Interaction Design
- **Clear Questions**: Use descriptive prompt text
- **Reasonable Defaults**: Provide sensible default values
- **Progressive Complexity**: Start simple, add complexity as needed

### Configuration Access
- **Fallback Values**: Always provide reasonable fallbacks
- **Environment Awareness**: Consider different deployment scenarios
- **Error Handling**: Handle missing configuration gracefully

### CLI Command Development
- **Trait Composition**: Combine with other traits for full functionality
- **User Experience**: Prioritize ease of use and clear feedback
- **Documentation**: Provide clear usage instructions

## Performance Considerations

### Configuration Access
- **Singleton Pattern**: Efficient SugarConfig instance reuse
- **Lazy Loading**: Configuration loaded only when needed
- **Minimal Overhead**: Lightweight configuration access methods

### User Interaction
- **Conditional Prompts**: Only prompt when necessary
- **Quick Defaults**: Fast default value provision
- **Responsive Interface**: Minimal delay in user interaction

## Integration Points

### Other Robo Traits
- **CliRunnerTrait**: Combine for full SuiteCRM environment access
- **Custom Traits**: Extensible for additional CLI functionality
- **Framework Integration**: Seamless integration with Robo command framework

### SuiteCRM Configuration
- **Global Config**: Access to all SuiteCRM configuration settings
- **Module Config**: Support for module-specific configuration
- **Runtime Config**: Dynamic configuration access during execution

## Error Handling

### Configuration Errors
- **Missing Config**: Graceful fallback to default values
- **Invalid Keys**: Safe handling of malformed configuration keys
- **Environment Issues**: Robust handling of configuration problems

### User Input Validation
- **Input Sanitization**: Safe handling of user input
- **Default Provision**: Reliable fallback when user provides no input
- **Type Safety**: Consistent data type handling 