# Rector Configuration Documentation

## Overview

This document provides comprehensive documentation for the Rector PHP modernization configuration (`rector.php`) created for the SuiteCRM modernization project. The configuration focuses on modernizing new SuiteCRM components to PHP 7.4+ standards while preserving legacy code compatibility.

## Configuration Summary

- **PHP Version**: 7.4 compatibility (SuiteCRM requirement)
- **Scope**: New modernized components only (~15 files)
- **Rules**: PHP 7.4 migration, code quality, type declarations, dead code removal
- **Legacy Exclusions**: Comprehensive exclusions for legacy SuiteCRM code
- **Performance**: Parallel processing enabled

## Features

### Core Modernization Capabilities
- **Type Declarations**: Automatic addition of return types, parameter types, property types
- **PHP 7.4 Features**: Arrow functions, typed properties, null coalescing assignment
- **Code Quality**: Dead code removal, unused import cleanup, coding style improvements
- **Backward Compatibility**: Maintains compatibility with existing SuiteCRM patterns

### Targeted Components
- **OAuth2 Authentication** (9 files)
- **Enhanced API Middleware** (6 files)  
- **Enhanced API Controllers** (2 files)
- **Enhanced API Services** (2 files)
- **Enhanced API Responses** (1 file)
- **Enhanced Robo Commands** (2 files)

## Usage Examples

### Basic Modernization
```bash
# Apply all modernization rules
vendor/bin/rector process --config=rector.php

# Preview changes without applying
vendor/bin/rector process --dry-run --config=rector.php
```

### Component-Specific Modernization
```bash
# Modernize only OAuth2 components
vendor/bin/rector process lib/Authentication/ --config=rector.php

# Modernize only API middleware
vendor/bin/rector process Api/V8/Middleware/ --config=rector.php
```

### Docker Usage
```bash
# Run in Docker container
docker-compose exec suitecrm vendor/bin/rector process --dry-run

# With specific config
docker-compose exec suitecrm vendor/bin/rector process --config=rector.php
```

## Configuration Sections Explained

### PHP Version Targeting
```php
$rectorConfig->phpVersion(PhpVersion::PHP_74);
```
- Ensures compatibility with SuiteCRM's PHP 7.4 requirement
- Prevents use of PHP 8.0+ features that would break compatibility

### Path Configuration
The configuration targets only new modernized components:
```php
$rectorConfig->paths([
    __DIR__ . '/lib/Authentication/OAuth2Service.php',
    __DIR__ . '/Api/V8/Middleware/EnhancedValidationMiddleware.php',
    // ... other new components
]);
```

**Why this approach?**
- Focuses modernization on new, high-quality code
- Avoids breaking legacy SuiteCRM functionality
- Ensures gradual modernization approach

### Rule Sets Applied

#### PHP 7.4 Migration Rules
```php
LevelSetList::UP_TO_PHP_74
SetList::PHP_74
```
- Migrates code to use PHP 7.4 features
- Typed properties, arrow functions, null coalescing assignment

#### Code Quality Rules
```php
SetList::CODE_QUALITY
SetList::CODING_STYLE
SetList::TYPE_DECLARATION
SetList::DEAD_CODE
```
- Removes dead code and unused imports
- Adds type declarations automatically
- Improves coding style consistency

### Excluded Rules

For SuiteCRM compatibility, certain rules are skipped:
```php
$rectorConfig->skip([
    EncapsedStringsToSprintfRector::class,
    FinalizeClassesWithoutChildrenRector::class,
    // ... other excluded rules
]);
```

**Why exclude these?**
- String concatenation patterns are SuiteCRM-specific
- Magic methods and dynamic properties are used extensively
- Performance-sensitive code shouldn't be automatically changed

## Integration with Development Workflow

### Robo Commands

Enhanced CodingStandardCommands provide easy access:
```bash
# Run Rector modernization
vendor/bin/robo quality:rector

# Preview changes
vendor/bin/robo quality:rector-dry-run
```

### Pre-commit Integration

The pre-commit hook automatically runs Rector analysis:
```bash
# Rector runs as part of quality checks
git commit -m "Update OAuth2 service"

# Skip if needed
SKIP_QUALITY_CHECKS=true git commit
```

### CI/CD Integration

For automated modernization checks:
```yaml
# .github/workflows/rector.yml
steps:
  - name: Run Rector
    run: |
      docker-compose exec suitecrm vendor/bin/rector process --dry-run
```

## Common Transformations

### Type Declarations
**Before:**
```php
public function processToken($token)
{
    return $this->validateToken($token);
}
```

**After:**
```php
public function processToken(string $token): bool
{
    return $this->validateToken($token);
}
```

### PHP 7.4 Features
**Before:**
```php
$config = $config ?? [];
$config['timeout'] = $config['timeout'] ?? 30;
```

**After:**
```php
$config ??= [];
$config['timeout'] ??= 30;
```

### Dead Code Removal
**Before:**
```php
/**
 * @param string $token
 * @return bool
 */
public function validateToken(string $token): bool
{
    // Removes redundant PHPDoc
}
```

**After:**
```php
public function validateToken(string $token): bool
{
    // PHPDoc removed as it's redundant with type declarations
}
```

## Performance Optimization

### Parallel Processing
```php
$rectorConfig->parallel(120, 8, 10);
```
- **120 seconds**: Maximum job runtime
- **8 processes**: Parallel worker count
- **10 seconds**: Job timeout

### Cache Directory
```php
$rectorConfig->cacheDirectory(__DIR__ . '/cache/rector');
```
- Improves subsequent run performance
- Cache directory is automatically excluded from version control

## Best Practices

### Development Workflow
1. **Preview First**: Always use `--dry-run` to see changes
2. **Incremental**: Process components individually
3. **Review**: Carefully review all changes before committing
4. **Test**: Run tests after modernization

### File-by-File Approach
```bash
# Process one component at a time
vendor/bin/rector process lib/Authentication/OAuth2Service.php --dry-run
vendor/bin/rector process lib/Authentication/OAuth2Service.php
```

### Integration with Other Tools
- **After Rector**: Run PHP-CS-Fixer for style cleanup
- **Before Rector**: Ensure PHPStan passes for better type inference
- **Testing**: Always run tests after modernization

## Troubleshooting

### Common Issues

#### "Class not found" Errors
**Solution**: Ensure Composer autoloader is updated
```bash
composer dump-autoload
```

#### Memory Limit Exceeded
**Solution**: Increase PHP memory limit
```bash
php -d memory_limit=512M vendor/bin/rector process
```

#### Unexpected Changes
**Solution**: Use `--dry-run` first and review exclusions
```bash
vendor/bin/rector process --dry-run --config=rector.php
```

### Debug Mode
```bash
# Enable debug output
vendor/bin/rector process --debug

# Show processing details
vendor/bin/rector process --debug --dry-run
```

## Maintenance and Updates

### Regular Updates
Keep Rector updated for latest modernization rules:
```bash
composer update rector/rector
```

### Configuration Review
Review and update configuration quarterly:
1. Check for new PHP features to adopt
2. Review exclusions for relevance
3. Add new components to path list
4. Update parallel processing settings

### Performance Monitoring
Monitor modernization performance:
```bash
# Time modernization runs
time vendor/bin/rector process --dry-run
```

## Security Considerations

### Code Changes
- All changes are purely structural/syntactic
- No functional logic is modified
- Original behavior is preserved

### Review Process
- Always review changes before committing
- Use version control to track modifications
- Test thoroughly after modernization

### Scope Limitation
- Only targets new modernized components
- Legacy code remains untouched
- Gradual modernization approach

## Integration Points

### SuiteCRM Compatibility
- Respects SuiteCRM's magic method patterns
- Preserves dynamic property usage where needed
- Maintains backward compatibility with PHP 7.4

### Development Tools
- Works alongside PHP-CS-Fixer for style enforcement
- Integrates with PHPStan for static analysis
- Supports pre-commit hooks for automated checks

## Summary

This Rector configuration provides:
- ✅ **Safe modernization** of new components only
- ✅ **PHP 7.4 compatibility** maintained
- ✅ **SuiteCRM pattern preservation** through strategic exclusions
- ✅ **Performance optimization** through parallel processing
- ✅ **Integration** with existing development workflow

The configuration enables gradual modernization of the SuiteCRM codebase while maintaining stability and compatibility with existing functionality. 