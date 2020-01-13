<?php
namespace ShangYou\Providers;

use App\Repositories\Platform;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ShangYou\Helpers\PlatformMapper;
use ShangYou\Validation;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // 加载配置文件
        $this->mergeConfigFrom(realpath(__DIR__ . '/../../config/ShangYou.php'), 'ShangYou');
        $config = $this->app['config']['ShangYou'];
        
        // 注册Requester
        $this->app->singleton('ShangYou.api.requester',
            function ($app) use ($config) {
                return new $config['requester']($app);
            });

        // 注册sdk路由中间件
        Route::middleware('sdk', $config['middleware']);

        // 平台映射配置
        $this->app->singleton('ShangYou.platform_mapper', function($app) use ($config) {
            $mappers = Platform::instance()->getPlatformMappers();
            $config['platform_aware']['mappers'] = array_merge($config['platform_aware']['mappers'], $mappers);

            return $config['platform_aware'];
        });

        // 注册域名平台映射
        $this->app->singleton('ShangYou.platform_aware', function($app) {
            return new PlatformMapper(app('ShangYou.platform_mapper'));
        });
    }

    public function boot()
    {
        // 发布配置文件
        $this->publishes([
            __DIR__ . '/../../config/ShangYou.php' => config_path('ShangYou.php'),
        ]);

        // 注册校验规则
        $validation = new Validation(app('validator'));
        $validation->registerAll();
    }
}