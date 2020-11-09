<?php

return function (Mix\FastRoute\RouteCollector $collector) {
    $collector->post('/file/upload',
        [\App\Api\Controllers\FileController::class, 'upload'],
        [\App\Api\Middleware\ActionMiddleware::class]
    );

    $collector->group('/v2',
        function (Mix\FastRoute\RouteCollector $collector) {
            $collector->post('/user/create',
                [\App\Api\Controllers\UserController::class, 'create'],
                [\App\Api\Middleware\ActionMiddleware::class]
            );
        },
        [\App\Api\Middleware\GroupMiddleware::class]
    );
};
