<?php

namespace Core;

/**
*
*/
class Core
{
    function __construct()
    {
        self::dotenv();
        require_once '../routes.php';
        if (file_exists('../src/helpers.php')) {
            require_once '../src/helpers.php';
        }
    }

    public function run()
    {
        $route = Router::get($_SERVER['REQUEST_URI']);
        $controllerName = 'Controller\\' . ucfirst($route['c']) . 'Controller';
        $actionName = $route['a'];

        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            if (method_exists($controller, $actionName)) {
                call_user_func_array([$controller, $actionName], $route['p'] ?? []);
            }
        }
    }

    public static function dotenv()
    {
        if (file_exists('../.env')) {
            $handle = fopen('../.env', 'r');
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $expl = explode('=', trim($line));
                    define(strtoupper($expl[0]), $expl[1]);
                }
                fclose($handle);
            }
        }
    }
}
