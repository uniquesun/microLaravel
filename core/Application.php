<?php

namespace Core;

use App\Providers\EventServiceProvider;
use App\Providers\LogServiceProvider;
use App\Providers\RoutingServiceProvider;
use Core\Kernel\Blade;
use Core\Kernel\Config;
use Core\Kernel\Database;
use Core\Kernel\Env;
use Core\Kernel\HandleExceptions;
use Core\Kernel\Log;
use Core\Kernel\Pipe;
use Core\Kernel\Response;
use Core\Kernel\Router;

class Application extends Container
{

    public function __construct($basePath = null)
    {
        $this->registerBaseBindings();
        $this->registerShared();  // 注册服务
        $this->boot(); // 服务注册了 才能启动
    }

    private function registerShared()
    {
        $registers = [
            'response' => Response::class,
            'router' => Router::class,
            'pipe' => Pipe::class,
            'config' => Config::class,
            'db' => Database::class,
            'view' => Blade::class,
            'log' => Log::class,
            'exception' => HandleExceptions::class,
            'env' => Env::class,
        ];
        foreach ($registers as $abstract => $concrete) {
            $this->bind($abstract, $concrete, true);
        }
    }

    private function registerBaseBindings()
    {
        static::$instance = $this;
    }


    private function boot()
    {
        app('env')->init();
        // 托管异常
        app('exception')->init();
        // 配置文件
        app('config')->init();

        // 路由
        app('router')->group([
            'namespace' => 'App\\controller'
        ], function ($router) {
            require_once '../routes/web.php';
        });

        app('router')->group([
            'namespace' => 'App\\controller',
            'prefix' => 'api'
        ], function ($router) {
            require_once '../routes/api.php';
        });

    }
}