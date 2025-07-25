<?php

use Api\V8\Factory;
use Api\V8\Middleware\CorsMiddleware;
use Api\V8\Middleware\RateLimitMiddleware;
use Api\V8\Middleware\ApiKeyAuthMiddleware;
use Api\V8\Middleware\SecurityHeadersMiddleware;
use Api\V8\Middleware\EnhancedValidationMiddleware;
use Api\V8\Middleware\RequestLoggingMiddleware;
use Psr\Container\ContainerInterface as Container;
use Api\Core\Loader\CustomLoader;

return CustomLoader::mergeCustomArray([
    Factory\ParamsMiddlewareFactory::class => function (Container $container) {
        return new Factory\ParamsMiddlewareFactory($container);
    },
    Factory\ValidatorFactory::class => function (Container $container) {
        return new Factory\ValidatorFactory($container->get('Validation'));
    },
    CorsMiddleware::class => function (Container $container) {
        return new CorsMiddleware();
    },
    RateLimitMiddleware::class => function (Container $container) {
        return new RateLimitMiddleware();
    },
    ApiKeyAuthMiddleware::class => function (Container $container) {
        return new ApiKeyAuthMiddleware();
    },
    SecurityHeadersMiddleware::class => function (Container $container) {
        return new SecurityHeadersMiddleware();
    },
    EnhancedValidationMiddleware::class => function (Container $container) {
        return new EnhancedValidationMiddleware();
    },
    RequestLoggingMiddleware::class => function (Container $container) {
        return new RequestLoggingMiddleware();
    },
], basename(__FILE__));
