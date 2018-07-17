<?php

namespace Core\Database;

/**
 *
 */
class Database
{
    protected static $_instance = NULL;

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new \PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASS);
        }
        return self::$_instance;
    }

    function __construct() { }
    function __destruct() { }
}
