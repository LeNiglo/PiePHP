<?php

namespace Core\Database;

use PDO;

class Database
{
    protected static $_instance = null;

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            $host = env('DB_HOST', 'localhost');
            $port = env('DB_PORT', '3306');
            $name = env('DB_NAME', 'piephp');
            $user = env('DB_USER', 'root');
            $pass = env('DB_PASS', '');

            self::$_instance = new PDO("mysql:host=$host;port=$port;dbname=$name;charset=utf8", $user, $pass);
            self::$_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$_instance->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
            self::$_instance->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
            self::$_instance->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
        }

        return self::$_instance;
    }

    public function __construct()
    {
    }

    public function __destruct()
    {
    }
}
