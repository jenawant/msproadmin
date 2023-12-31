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
use MsPro\Annotation\Api\MApiRequestParamCollector;
use MsPro\Annotation\Api\MApiResponseParamCollector;
use MsPro\Annotation\DependProxyCollector;

return [
    'scan' => [
        'paths' => [
            BASE_PATH . '/app',
            BASE_PATH . '/api',
            BASE_PATH . '/plugin',
        ],
        // 初始化注解收集器
        'collectors' => [
            MApiRequestParamCollector::class,
            MApiResponseParamCollector::class,
            DependProxyCollector::class,
        ],
        'ignore_annotations' => [
            'mixin',
            'required'
        ],
    ],
];
