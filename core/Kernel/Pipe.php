<?php

namespace Core\Kernel;

class Pipe
{
    protected $pipes;

    public function create()
    {
        return clone $this;
    }

    public function setPipes($pipes)
    {
        $this->pipes = $pipes;
        return $this;
    }

    public function run($callback)
    {
        // 本质就是循环中间件，把当前request和最后执行的callback当参数传过去
        return array_reduce(array_reverse($this->pipes), function ($callback, $currPipe) {

            return function ($request) use ($callback, $currPipe) {

                return (new $currPipe)->handle($request, $callback);
            };

        }, $callback);
    }

}