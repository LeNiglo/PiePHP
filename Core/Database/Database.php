<?php

namespace Core\Database;

use PDO;

class Database
{
    protected static $_instance = null;

    public function __construct()
    {
    }

    public function __destruct()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
            self::$_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$_instance->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
            self::$_instance->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
            self::$_instance->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
        }

        return self::$_instance;
    }
}
