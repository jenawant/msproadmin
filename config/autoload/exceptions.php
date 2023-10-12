<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

return [
    'handler' => [
        'http' => [
            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,
            MsPro\Exception\Handler\ValidationExceptionHandler::class,
            MsPro\Exception\Handler\TokenExceptionHandler::class,
            MsPro\Exception\Handler\NoPermissionExceptionHandler::class,
            MsPro\Exception\Handler\NormalStatusExceptionHandler::class,
            MsPro\Exception\Handler\AppExceptionHandler::class,
        ],
    ],
];
