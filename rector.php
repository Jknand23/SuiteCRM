<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Core\ValueObject\PhpVersion;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;
use Rector\TypeDeclaration\Rector\Param\ParamTypeDeclarationRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\CodingStyle\Rector\ClassMethod\OrderAttributesRector;
use Rector\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector;

/**
 * @fileoverview Rector configuration for SuiteCRM modernization
 * 
 * This configuration focuses on modernizing new SuiteCRM components to PHP 7.4+
 * standards while preserving legacy code compatibility. Only targets new modernized
 * components to avoid breaking existing functionality.
 * 
 * @version 1.0.0
 * @since 2024-01-15
 */

return static function (RectorConfig $rectorConfig): void {
    // PHP version target (PHP 7.4 for SuiteCRM compatibility)
    $rectorConfig->phpVersion(PhpVersion::PHP_74);

    // Paths to modernize - ONLY new modernized components
    $rectorConfig->paths([
        // OAuth2 Authentication Infrastructure
        __DIR__ . '/lib/Authentication/OAuth2Service.php',
        __DIR__ . '/lib/Authentication/ProviderFactory.php',
        __DIR__ . '/lib/Authentication/SecurityValidator.php',
        __DIR__ . '/lib/Authentication/TokenManager.php',
        __DIR__ . '/lib/Authentication/UserLinker.php',
        __DIR__ . '/lib/Authentication/OAuth2AuthenticationProvider.php',
        __DIR__ . '/lib/Authentication/SecurityMonitoringService.php',
        __DIR__ . '/lib/Authentication/entrypoints/',
        
        // Enhanced API Middleware
        __DIR__ . '/Api/V8/Middleware/EnhancedValidationMiddleware.php',
        __DIR__ . '/Api/V8/Middleware/RequestLoggingMiddleware.php',
        __DIR__ . '/Api/V8/Middleware/RateLimitMiddleware.php',
        __DIR__ . '/Api/V8/Middleware/SecurityHeadersMiddleware.php',
        __DIR__ . '/Api/V8/Middleware/CorsMiddleware.php',
        __DIR__ . '/Api/V8/Middleware/ApiKeyAuthMiddleware.php',
        
        // Enhanced API Controllers
        __DIR__ . '/Api/V8/Controller/EnhancedBaseController.php',
        __DIR__ . '/Api/V8/Controller/DocumentationController.php',
        
        // Enhanced API Services
        __DIR__ . '/Api/V8/Service/OpenApiDocumentationService.php',
        __DIR__ . '/Api/V8/Service/MetaService.php',
        
        // Enhanced API Responses
        __DIR__ . '/Api/V8/JsonApi/Response/EnhancedErrorResponse.php',
        
        // Enhanced Robo Commands (selective modernization)
        __DIR__ . '/lib/Robo/Plugin/Commands/ApiCommands.php',
        __DIR__ . '/lib/Robo/Plugin/Commands/BuildCommands.php',
    ]);

    // Skip paths that should not be modernized
    $rectorConfig->skip([
        // Legacy SuiteCRM modules
        __DIR__ . '/modules',
        __DIR__ . '/include/SugarObjects',
        __DIR__ . '/include/SubPanel',
        __DIR__ . '/include/ListView',
        __DIR__ . '/include/EditView',
        __DIR__ . '/include/DetailView',
        __DIR__ . '/include/Dashlets',
        __DIR__ . '/include/database',
        __DIR__ . '/include/utils',
        
        // Third-party libraries
        __DIR__ . '/vendor',
        __DIR__ . '/XTemplate',
        __DIR__ . '/Zend',
        __DIR__ . '/include/nusoap',
        __DIR__ . '/include/tcpdf',
        __DIR__ . '/include/Smarty',
        __DIR__ . '/include/Pear',
        __DIR__ . '/include/ytree',
        
        // Legacy API components
        __DIR__ . '/Api/Core',
        __DIR__ . '/Api/V8/Controller/BaseController.php',
        __DIR__ . '/Api/V8/Controller/UserController.php',
        __DIR__ . '/Api/V8/Controller/ModuleController.php',
        __DIR__ . '/Api/V8/Middleware/ParamsMiddleware.php',
        
        // Cache and temporary files
        __DIR__ . '/cache',
        __DIR__ . '/upload',
        __DIR__ . '/custom',
        __DIR__ . '/install',
        __DIR__ . '/tests',
        
        // Documentation files
        '**/*_docs.md',
        __DIR__ . '/AI_Docs',
        __DIR__ . '/plans',
    ]);

    // Apply rule sets for PHP 7.4 modernization
    $rectorConfig->sets([
        // PHP 7.4 migration rules
        LevelSetList::UP_TO_PHP_74,
        
        // Code quality improvements
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::TYPE_DECLARATION,
        SetList::DEAD_CODE,
        
        // PHP 7.4 specific features
        SetList::PHP_74,
    ]);

    // Individual rules for new components
    $rectorConfig->rules([
        // Type declarations
        AddVoidReturnTypeWhereNoReturnRector::class,
        ReturnTypeFromReturnNewRector::class,
        AddReturnTypeDeclarationRector::class,
        TypedPropertyFromAssignsRector::class,
        ParamTypeDeclarationRector::class,
        
        // Dead code removal
        RemoveUselessParamTagRector::class,
        RemoveUselessReturnTagRector::class,
        
        // Coding style improvements
        OrderAttributesRector::class,
        SeparateMultiUseImportsRector::class,
        UseClassKeywordForClassNameResolutionRector::class,
    ]);

    // Skip specific rules that might break SuiteCRM compatibility
    $rectorConfig->skip([
        // Skip string concatenation changes that might affect SuiteCRM patterns
        EncapsedStringsToSprintfRector::class,
        
        // Skip rules that might conflict with SuiteCRM's magic methods
        \Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector::class,
        \Rector\Privatization\Rector\Method\PrivatizeLocalOnlyMethodRector::class,
        \Rector\Privatization\Rector\Property\PrivatizeLocalPropertyToPrivatePropertyRector::class,
        
        // Skip array to foreach conversions that might affect performance
        \Rector\CodeQuality\Rector\For_\ForToForeachRector::class,
        
        // Skip complex transformations that might break OAuth2 provider patterns
        \Rector\Transform\Rector\StaticCall\StaticCallToFuncCallRector::class,
        \Rector\Transform\Rector\FuncCall\FuncCallToStaticCallRector::class,
    ]);

    // Import short classes for better readability
    $rectorConfig->importShortClasses(false);
    
    // Cache directory for improved performance
    $rectorConfig->cacheDirectory(__DIR__ . '/cache/rector');
    
    // Parallel processing for faster execution
    $rectorConfig->parallel(120, 8, 10);
}; 