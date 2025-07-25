# PHPStan Configuration Documentation

## Overview

This document provides comprehensive documentation for the PHPStan static analysis configuration (`phpstan.neon`) created for the SuiteCRM modernization project. The configuration focuses on analyzing new modernized components while preserving compatibility with the existing legacy codebase.

## Configuration Summary

- **Analysis Level**: 6 (recommended for new code)
- **PHP Version**: 7.4.0 compatibility
- **Scope**: New modernized components only (~15 files)
- **Legacy Exclusions**: Comprehensive exclusions for legacy SuiteCRM code
- **Memory Limit**: 512M for complex analysis
- **Parallel Processing**: 4 processes for faster analysis

## Installation Instructions

### Method 1: Standard Installation (Preferred)

```bash
# In Docker container
docker-compose exec suitecrm composer install --dev

# Or if dev dependencies are separate
docker-compose exec suitecrm composer require --dev phpstan/phpstan
```

### Method 2: PHP 7.4 Compatible Installation

If encountering compatibility issues with the latest PHPStan version:

```bash
# Install specific PHPStan version compatible with PHP 7.4
docker-compose exec suitecrm composer require --dev "phpstan/phpstan:^1.8"

# Alternative: Use PHP 7.4 compatible version
docker-compose exec suitecrm composer require --dev "phpstan/phpstan:^1.0"
```

### Method 3: Manual Installation for Testing

```bash
# Download PHPStan PHAR for PHP 7.4
docker-compose exec suitecrm bash -c "curl -L https://github.com/phpstan/phpstan/releases/download/1.8.11/phpstan.phar -o phpstan.phar"
docker-compose exec suitecrm chmod +x phpstan.phar
```

## Usage Examples

### Basic Analysis

```bash
# Analyze all configured paths
docker-compose exec suitecrm vendor/bin/phpstan analyse

# With PHAR file
docker-compose exec suitecrm php phpstan.phar analyse
```

### Specific Component Analysis

```bash
# Analyze only OAuth2 components
docker-compose exec suitecrm vendor/bin/phpstan analyse lib/Authentication/

# Analyze only API middleware
docker-compose exec suitecrm vendor/bin/phpstan analyse Api/V8/Middleware/
```

### Generate Baseline (Recommended for Initial Setup)

```bash
# Generate baseline to ignore existing issues
docker-compose exec suitecrm vendor/bin/phpstan analyse --generate-baseline

# This creates phpstan-baseline.neon that can be included
```

### Output Formats

```bash
# Default table format
docker-compose exec suitecrm vendor/bin/phpstan analyse

# JSON format for CI/CD
docker-compose exec suitecrm vendor/bin/phpstan analyse --error-format=json

# Checkstyle format for IDE integration
docker-compose exec suitecrm vendor/bin/phpstan analyse --error-format=checkstyle
```

## Configuration Sections Explained

### Analysis Paths

The configuration targets only new modernized components:

```yaml
paths:
    # OAuth2 Authentication Infrastructure
    - lib/Authentication/OAuth2Service.php
    - lib/Authentication/ProviderFactory.php
    # ... (15+ specific files)
```

**Why this approach?**
- Focuses analysis on new, high-quality code
- Avoids noise from legacy SuiteCRM components
- Ensures new code meets modern PHP standards

### Legacy Exclusions

Comprehensive exclusions prevent analysis of problematic legacy code:

```yaml
excludes_analyse:
    # Legacy modules with potential issues
    - modules/*/metadata/
    - include/SugarObjects/
    # ... (extensive exclusion list)
```

### Error Suppressions

Strategic error suppression for SuiteCRM compatibility:

```yaml
ignoreErrors:
    # Allow dynamic property access for Bean compatibility
    - '#Access to an undefined property [a-zA-Z0-9\\_]+::\$[a-zA-Z0-9_]+#'
```

## Common Issues and Solutions

### Issue 1: "Could not open input file: vendor/bin/phpstan"

**Solution**: PHPStan is not installed
```bash
# Install dev dependencies
docker-compose exec suitecrm composer install --dev

# Or install PHPStan specifically
docker-compose exec suitecrm composer require --dev phpstan/phpstan
```

### Issue 2: Memory Limit Exceeded

**Solution**: Increase memory limit in configuration
```yaml
parameters:
    memoryLimitFile: 1024M  # Increase from 512M
```

### Issue 3: Too Many Errors from Legacy Code

**Solution**: Add more exclusions or use baseline
```bash
# Generate baseline to ignore existing issues
docker-compose exec suitecrm vendor/bin/phpstan analyse --generate-baseline
```

### Issue 4: PHP 7.4 Compatibility Issues

**Solution**: Use compatible PHPStan version
```bash
# Install specific version
docker-compose exec suitecrm composer require --dev "phpstan/phpstan:^1.8"
```

## Integration with Development Workflow

### Pre-commit Hook

Create `.git/hooks/pre-commit`:
```bash
#!/bin/sh
echo "Running PHPStan analysis..."
docker-compose exec suitecrm vendor/bin/phpstan analyse --no-progress
if [ $? -ne 0 ]; then
    echo "PHPStan analysis failed. Commit aborted."
    exit 1
fi
```

### CI/CD Integration

For automated testing:
```yaml
# .github/workflows/phpstan.yml
steps:
  - name: Run PHPStan
    run: |
      docker-compose exec suitecrm vendor/bin/phpstan analyse --error-format=json > phpstan-results.json
```

### IDE Integration

For PhpStorm/VSCode integration:
1. Install PHPStan plugin
2. Configure path to `phpstan.neon`
3. Set PHP interpreter to Docker container

## Performance Optimization

### Parallel Processing

The configuration enables parallel processing:
```yaml
parallel:
    maximumNumberOfProcesses: 4
```

Adjust based on your system:
- **2 processes**: Single-core systems
- **4 processes**: Quad-core systems  
- **8 processes**: High-performance systems

### Scan Directories

Optimize scanning for faster analysis:
```yaml
scanDirectories:
    - lib/
    - Api/V8/
```

Only includes directories with new components.

## Analysis Levels Explained

The configuration uses **Level 6** for new components:

- **Level 0**: Basic checks (syntax, unknown functions)
- **Level 1**: Unknown classes, unknown methods called on `$this`
- **Level 2**: Unknown methods checked on all expressions
- **Level 3**: Return types, types assigned to properties
- **Level 4**: Basic dead code detection
- **Level 5**: Checking types of arguments passed to methods
- **Level 6**: Report missing typehints (**Current setting**)
- **Level 7**: Report partially wrong union types
- **Level 8**: Report calling methods and accessing properties on nullable types
- **Level 9**: Be strict about the `mixed` type

**Why Level 6?**
- Balance between strictness and practicality
- Catches most important type-related issues
- Compatible with SuiteCRM's coding patterns
- Doesn't overwhelm with minor issues

## Extending the Configuration

### Adding New Components

When creating new modernized components:

1. Add the file path to the `paths` section:
```yaml
paths:
    # Add new components here
    - Api/V8/Middleware/NewMiddleware.php
    - lib/NewService/NewService.php
```

2. Run analysis to ensure quality:
```bash
docker-compose exec suitecrm vendor/bin/phpstan analyse Api/V8/Middleware/NewMiddleware.php
```

### Custom Rules

Add custom PHPStan extensions:
```yaml
includes:
    - phpstan-strict-rules.neon
    - phpstan-deprecation-rules.neon
```

### Project-Specific Ignores

Add project-specific error ignores:
```yaml
ignoreErrors:
    # Allow specific SuiteCRM patterns
    - 
        message: '#specific error pattern#'
        path: specific/file/path.php
```

## Alternative Testing Approaches

If PHPStan cannot be installed due to PHP 7.4 limitations:

### Manual Code Review Checklist

For each new component, verify:
- [ ] All public methods have complete PHPDoc blocks
- [ ] Return types are specified for all methods
- [ ] Parameter types are specified
- [ ] Class properties have type declarations
- [ ] No undefined variables or properties
- [ ] Error handling follows patterns

### Basic PHP Syntax Validation

```bash
# Check syntax of new files
docker-compose exec suitecrm bash -c "find lib/Authentication -name '*.php' -exec php -l {} \;"
```

### Simple Static Analysis

```bash
# Use PHP's built-in reflection for basic type checking
docker-compose exec suitecrm php -r "
\$reflection = new ReflectionClass('SuiteCRM\\Authentication\\OAuth2Service');
foreach (\$reflection->getMethods() as \$method) {
    if (!\$method->hasReturnType()) {
        echo 'Missing return type: ' . \$method->getName() . PHP_EOL;
    }
}
"
```

## Maintenance and Updates

### Regular Updates

Keep PHPStan updated for latest checks:
```bash
docker-compose exec suitecrm composer update phpstan/phpstan
```

### Configuration Review

Review and update configuration quarterly:
1. Check for new components to include
2. Review exclusions for relevance
3. Consider increasing analysis level
4. Update ignore patterns if needed

### Performance Monitoring

Monitor analysis performance:
```bash
# Time analysis runs
docker-compose exec suitecrm time vendor/bin/phpstan analyse
```

## Troubleshooting

### Debug Mode

Enable debug output for troubleshooting:
```bash
docker-compose exec suitecrm vendor/bin/phpstan analyse --debug
```

### Verbose Output

Get detailed information:
```bash
docker-compose exec suitecrm vendor/bin/phpstan analyse -v
```

### Memory Issues

If experiencing memory issues:
1. Increase `memoryLimitFile` in configuration
2. Reduce `maximumNumberOfProcesses`
3. Analyze smaller file sets
4. Use baseline to ignore non-critical issues

## Best Practices

### Code Quality Standards

For new components analyzed by PHPStan:
- Always specify return types
- Use type hints for all parameters
- Document complex types with PHPDoc
- Avoid `mixed` type where possible
- Use nullable types (`?string`) when appropriate

### Integration Workflow

1. **Development**: Run PHPStan on modified files
2. **Pre-commit**: Analyze all changed files
3. **CI/CD**: Full analysis on pull requests
4. **Release**: Generate analysis report

### Error Handling

When PHPStan reports errors:
1. **Fix the code** (preferred approach)
2. **Add specific ignore** (if unfixable)
3. **Exclude the file** (last resort)

## Summary

This PHPStan configuration provides:
- ✅ **Modern code quality** for new components
- ✅ **Legacy compatibility** through strategic exclusions
- ✅ **PHP 7.4 compatibility** with appropriate settings
- ✅ **Performance optimization** through parallel processing
- ✅ **Flexible integration** with development workflows

The configuration can be used immediately when PHPStan is installed, and provides a solid foundation for maintaining code quality in the SuiteCRM modernization project. 