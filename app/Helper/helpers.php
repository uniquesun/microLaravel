<?php

use Core\Container;


if (!function_exists('app')) {
    // 获取容器实例或者容器中的对象实例
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }
}

if (!function_exists('ENV')) {
    function ENV($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('view')) {
    function view($path, $params = [])
    {
        return app('view')->render($path, $params);
    }
}

if (!function_exists('app_run_time')) {
    function app_run_time()
    {
        $time = microtime(true) - PHP_START;
        echo '<br/><br/><br/><br/><br/><hr/>';
        echo "运行时间: " . round($time * 1000, 2) . 'ms<br/>';
    }
}