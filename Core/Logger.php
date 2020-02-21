<?php

namespace Core;

class Logger
{
    const LOG_FILENAME = 'logs/piephp.log';
    protected static $_instance = null;
    private $handle = null;

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Logger();
        }

        return self::$_instance;
    }

    public static function error($str = '')
    {
        self::getInstance()->write($str, 'ERROR');
    }

    public static function warn($str = '')
    {
        self::getInstance()->write($str, 'WARNING');
    }

    public static function info($str = '')
    {
        self::getInstance()->write($str, 'INFO');
    }

    public static function debug($str = '')
    {
        self::getInstance()->write($str, 'DEBUG');
    }

    private function write($str, $level)
    {
        if (!is_string($str)) {
            $str = json_encode($str);
        }
        $date = date('Y-m-d H:i:s');
        fwrite($this->handle, "[{$date}][{$level}] {$str}".PHP_EOL);
    }

    public function __construct()
    {
        $this->handle = fopen(self::LOG_FILENAME, 'a+');
    }

    public function __destruct()
    {
        fclose($this->handle);
    }
}
