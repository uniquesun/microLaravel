<?php

namespace App\Middleware;

use Closure;
use Core\Kernel\Request;

class Authenticate
{
    // $next闭包 控制器执行的方法 \Core\Kernel\Router->dispatch() 中的 $callback
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}