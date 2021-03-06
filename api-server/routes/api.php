<?php

return function (Mix\FastRoute\RouteCollector $collector) {
    $auth_middleware = [
        \App\Api\Middleware\CorsMiddleware::class,
        \App\Api\Middleware\ActionMiddleware::class,
        \App\Api\Middleware\AuthMiddleware::class,
    ];
    $common_middleware = [
        \App\Api\Middleware\CorsMiddleware::class,
        \App\Api\Middleware\ActionMiddleware::class,
    ];

    $collector->post('/file/upload', [\App\Api\Controllers\FileController::class, 'upload'], $auth_middleware);
    $collector->post('/file/uploadPublic', [\App\Api\Controllers\FileController::class, 'uploadPublic'], $auth_middleware);

    /** 后台相关 */
    $collector->get('/auth/user/me', [\App\Api\Controllers\AuthController::class, 'me'], $auth_middleware); // 用户个人信息
    $collector->post('/card/profile/update', [\App\Api\Controllers\CardController::class, 'update'], $auth_middleware); // 更新名片信息
    $collector->post('/auth/user/logout', [\App\Api\Controllers\AuthController::class, 'logout'], $auth_middleware); // 用户注销

    $collector->post('/auth/user/login', [\App\Api\Controllers\AuthController::class, 'login'], $common_middleware); // 用户登录
    $collector->post('/user/create', [\App\Api\Controllers\UserController::class, 'create'], $common_middleware);

    /** 小程序相关 */
    $collector->get('/card/profile/details', [\App\Api\Controllers\CardController::class, 'details'], $common_middleware); // 小程序获取信息

    /* 解决CORS的通用路由 */
    $collector->options('/{all:.+}', function (\Mix\Http\Message\ServerRequest $request, \Mix\Http\Message\Response $response) {
        return '';
    }, $common_middleware);
};
