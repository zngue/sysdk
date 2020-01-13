<?php
namespace ShangYou\Providers;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\ProcessIdProcessor;
use ShangYou\Validation;

class LumenServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // 加载配置文件
        $this->app->configure('ShangYou');
        $this->mergeConfigFrom(realpath(__DIR__ . '/../../config/ShangYou.php'),
            'ShangYou');
        $config = $this->app['config']['ShangYou'];

        // 注册Requester
        $this->app->singleton('ShangYou.api.requester',
            function ($app) use ($config) {
                return new $config['requester']($app);
            });

        // 注册sdk路由中间件
        $this->app->routeMiddleware([
            'sdk' => $config['middleware']
        ]);
    }

    public function boot()
    {

        // 配置日志处理器
        $this->app->configureMonologUsing(function (Logger $logger) {
            $apiName = env('API_NAME');
            $apiVersion = env('API_VERSION', 'v1');

            $logFile = sprintf($this->app['config']['ShangYou']['storage_log_path'],  date('Y-m/d/') . "{$apiName}-{$apiVersion}-%s");

            $logger->pushHandler(new StreamHandler(sprintf($logFile, 'warning'),
                Logger::WARNING));
            $logger->pushHandler(new StreamHandler(sprintf($logFile, 'debug'),
                Logger::DEBUG));
            $logger->pushHandler(new StreamHandler(sprintf($logFile, 'info'),
                Logger::INFO));

            $logger->pushProcessor(new ProcessIdProcessor());

            return $logger;
        });

        // 验证异常
        $handler = app('Dingo\Api\Exception\Handler');
        $handler->register(function (
            ValidationException $exception
        ) {
            $response = [
                'message' => $exception->getMessage(),
                'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'errors' => $exception->validator->errors(),
            ];

            Log::debug("调试请求参数", $response);

            return new \Dingo\Api\Http\Response($response, 200);
        });

        // 资源部存在异常
        $handler->register(function(ModelNotFoundException $exception) {
            return [
                'message' => 'No query results for resource',
                'status_code' => Response::HTTP_NOT_FOUND,
            ];
        });

        $this->registerValidator();
    }

    /**
     * 注册自定义校验规则
     */
    private function registerValidator()
    {
        $validation = new Validation(app('validator'));
        $validation->registerAll();
    }

}