<?php
return [
    /**
     * API key
     */
    'apikey' => env('YS_API_KEY', ''),

    /**
     * 路由中间件配置
     */
    'middleware' => env('YS_MIDDLEWARE_PROVIDER', ShangYou\Http\Middleware\ServiceMiddleware::class),

    /**
     * Http Requester实现类,用于向其他API发送请求
     */
    'requester' => env('YS_REQUESTER', ShangYou\Http\Requester::class),

    /**
     * 默认是本地配置,可选值为
     *
     *  local - 本地
     *  [url] - 注册服务器访问地址
     */
    'service_reg_center' => env('YS_SERVICE_REG_CENTER', 'local'),

    /**
     * 服务中心域名
     */
    'service_center'  => env('YS_SERVICE_CENTER', 'http://dev.oss.ShangYou.cn:28000/'),

    /**
     * 服务域名
     */
    'service_domains' => [
        'scm:test'     => 'test',
    ],

    /**
     * 日志存储路径,配置值中必须包含%s,框架会自动替换为相应的日志级别
     */
    'storage_log_path' => env('YS_STORAGE_LOG_PATH', storage_path("logs/%s.log")),

    /**
     * 启用https的域名
     */
    'https_host' => env('YS_PL_DOMAIN', 'www.ShangYou.com'),

    /**
     * 域名到平台的映射关系
     */
    'platform_aware' => [
        'mappers' => [

        ]
    ]
];