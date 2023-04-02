<?php

namespace Core;

use ReflectionClass;

class Container
{
    public static $instance;

    protected $binds = [];

    protected $instances = [];

    public function bind($abstract, $concrete, $shared = false)
    {
        if (!$concrete instanceof \Closure)
            $concrete = function ($container) use ($concrete) {
                return $container->build($concrete);
            };
        $this->binds[$abstract] = compact('concrete', 'shared');
    }


    public function make($abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $instance = $this->binds[$abstract]['concrete']($this);
        if ($this->binds[$abstract]['shared']) {
            $this->instances[$abstract] = $instance;
        }
        return $instance;
    }

    /**
     * @throws \ReflectionException
     */
    public function build($concrete)
    {
        $reflector = new ReflectionClass($concrete); // 反射
        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return $reflector->newInstance();
        }
        $dependencies = $constructor->getParameters();
        $instances = $this->getDependencies($dependencies);
        return $reflector->newInstanceArgs($instances);
    }

    protected function getDependencies($paramters)
    {
        $dependencies = []; // 当前类的所有依赖
        foreach ($paramters as $paramter) {
            if ($paramter->getClass()) {
                $dependencies[] = $this->get($paramter->getClass()->name);
            }
        }

        return $dependencies;
    }

    public static function getInstance(): Container
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
    }

    public function __clone()
    {
    }

}