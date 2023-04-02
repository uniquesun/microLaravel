<?php

namespace Core\Kernel;

class Config
{
    protected $config = [];

    public function init()
    {
        foreach (glob(PHP_PATH . '/../config/*.php') as $file) {
            $key = str_replace('.php', '', basename($file));
            $this->config[$key] = require $file;
        }
    }

    public function get($key, $default = null)
    {
        $keys = explode('.', $key);

        $config = $this->config;

        foreach ($keys as $key)
            if (isset($config[$key])) {
                $config = $config[$key];
            } else {
                return $default;
            }


        return $config;

    }

    public function set($key, $value)
    {
        $keys = explode('.', $key);

        $new_config = &$this->config;
        foreach ($keys as $key)
            $new_config = &$newconfig[$key];

        $new_config = $value;

    }

}