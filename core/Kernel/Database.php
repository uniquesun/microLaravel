<?php

namespace Core\Kernel;

use Core\Database\MysqlConnection;

class Database
{
    protected $connections = [];

    protected function getDefaultConnection()
    {
        return app('config')->get('database.default');
    }

    public function setDefaultConnection($name)
    {
        return app('config')->set('database.default', $name);
    }

    public function connect($name = null)
    {
        if (!$name) $name = $this->getDefaultConnection();
        if (isset($this->connections[$name])) return $this->connections[$name];

        $config = app('config')->get('database.connections.' . $name);

        // 简单工厂模式
        $connect_factory = null;
        switch ($config['driver']) {
            case 'mysql':
                $connect_factory = MysqlConnection::class;
                break;
        }
        $dsn = sprintf('%s:host=%s;dbname=%s', $config['driver'], $config['host'], $config['database']);
        $pdo = new \PDO($dsn, $config['username'], $config['password'], $config['options']);

        return $this->connections[$name] = new $connect_factory($pdo, $config);
    }


    public function __call($method, $parameters)
    {
        return $this->connect()->$method(...$parameters);
    }


}