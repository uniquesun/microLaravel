<?php

namespace Core\Kernel;


class Router
{

    protected static $routes = [];  //所有路由

    protected static $current_group_attr = [];

    public function group($group_attr, \Closure $callback)
    {
        self::$current_group_attr[] = $group_attr;
        // 就是执行 web里的方法
        call_user_func($callback, $this);
        array_pop(self::$current_group_attr);
    }

    protected function mappingRoute($current_route_index)
    {
        return self::$routes[$current_route_index] ?? false;
    }


    private static function getRoutes(): array
    {
        return self::$routes;
    }


    public static function addRoute($method, $uri, $callback)
    {
        $prefix = ''; // 前缀
        $middleware = [];  // 中间件
        $namespace = ''; // 命名空间
        if ($uri[0] !== '/') $uri = '/' . $uri;
        foreach (self::$current_group_attr as $group) {
            $prefix = $group['prefix'] ?? false;
            if ($prefix && $prefix[0] !== '/') $prefix = '/' . $prefix;
            $middleware = $group['middleware'] ?? [];
            $namespace .= $group['namespace'] ?? '';
        }

        $method = strtoupper($method);
        $uri = $prefix . $uri;

        self::$routes[$method . '_' . $uri] = [
            'method' => $method,
            'uri' => $uri,
            'action' => [
                'middleware' => $middleware,
                'namespace' => $namespace,
                'callback' => $callback
            ]
        ];
    }


    public static function get($uri, $callback)
    {
        self::addRoute('get', $uri, $callback);
    }

    public static function post($uri, $callback)
    {
        self::addRoute('post', $uri, $callback);
    }

    public static function put($uri, $callback)
    {
        self::addRoute('put', $uri, $callback);
    }

    public function delete($uri, $callback)
    {
        self::addRoute('delete', $uri, $callback);
    }


    public function dispatch(Request $request)
    {
        // 路由： 方法_路径 => 处理函数

        $method = $request->getMethod();
        $uri = $request->getUri();

        $route = $this->mappingRoute($method . '_' . $uri);
        if (!$route)
            throw new \Exception('route not found');

        $middleware = $route['action']['middleware'];
        $namespace = $route['action']['namespace'];
        $callback = $route['action']['callback'];

        if (!$callback instanceof \Closure) {
            $callback_arr = explode('@', $callback);
            $controller = $namespace . '\\' . $callback_arr[0]; // 控制器
            $method = $callback_arr[1]; // 方法

            $controllerInstance = new $controller;
            // 合并中间件
            $middleware = array_merge($middleware, $controllerInstance->getMiddleware());

            // 处理控制器
            $callback = function ($request) use ($controllerInstance, $method) {
                return $controllerInstance->callMethod($method, [$request]);
            };

        }

        // 先通过中间件，再执行后面的$callback
        return (app('pipe'))->create()->setPipes($middleware)->run($callback)($request);

    }


}