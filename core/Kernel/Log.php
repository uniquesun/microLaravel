<?php

namespace Core\Kernel;

use Core\Log\StackLogger;

class Log
{
    protected $channels = [];
    protected $config;

    public function __construct()
    {
        $this->config = app('config')->get('log');
    }

    public function channel($name = null)
    {
        if (!$name) $name = $this->config['default'];
        if (isset($this->channels[$name])) return $this->channels[$name];

        $config = app('config')->get('log.channels.' . $name);

        $log_factory = null;
        switch ($config['driver']) {
            case 'stack':
                $log_factory = StackLogger::class;
                break;
        }

        return $this->channels[$name] = new $log_factory($config);
    }

    public function __call($method, $params)
    {
        return $this->channel()->$method(...$params);
    }

}