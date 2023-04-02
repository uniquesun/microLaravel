<?php

namespace Core\Kernel;

class Controller
{
    protected $middleware = [];

    public function getMiddleware()
    {
        return $this->middleware;
    }


    public function callMethod($method, $para)
    {
        // Call the $foo->bar() method with 2 arguments
//        $foo = new foo;
//        call_user_func_array(array($foo, "bar"), array("three", "four"));
        return call_user_func_array([$this, $method], $para);
    }

}