<?php

namespace Core\Facade;

use Core\Logger;

/**
*
*/
class Log
{
    public static function error($str = '')
    {
        Logger::getInstance()->error($str);
    }

    public static function warn($str = '')
    {
        Logger::getInstance()->warn($str);
    }

    public static function info($str = '')
    {
        Logger::getInstance()->info($str);
    }

    public static function debug($str = '')
    {
        Logger::getInstance()->debug($str);
    }
}
