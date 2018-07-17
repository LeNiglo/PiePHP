<?php

namespace Core;

/**
*
*/
class Logger
{
    const LOG_FILENAME = "../logs/piephp.log";
    protected static $_instance = NULL;
    private $handle = NULL;

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Logger();
        }
        return self::$_instance;
    }

    public function error($str = '')
    {
        $this->write($str, 'ERROR');
    }

    public function warn($str = '')
    {
        $this->write($str, 'WARNING');
    }

    public function info($str = '')
    {
        $this->write($str, 'INFO');
    }

    public function debug($str = '')
    {
        $this->write($str, 'DEBUG');
    }

    private function write($str, $level)
    {
        $date = date("Y-m-d H:i:s");
        fwrite($this->handle, "[{$date}][{$level}] {$str}" . PHP_EOL);
    }

    public function __construct() {
        $this->handle = fopen(self::LOG_FILENAME, 'a+');
    }

    public function __destruct() {
        fclose($this->handle);
    }
}
