<?php

namespace Core\Database;

class MysqlConnection extends Connect
{
    protected static $connection;

    public function getConnection()
    {
        return self::$connection;
    }

    // 执行sql
    public function select($sql, $bindings = [], $useReadPdo = true)
    {
        $statement = $this->pdo;
        $sth = $statement->prepare($sql);
        try {
            $sth->execute($bindings);
            return $sth->fetchAll();
        } catch (\Exception $exception) {
            echo($exception->getMessage());
        }

    }
}