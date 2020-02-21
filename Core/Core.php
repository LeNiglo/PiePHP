<?php

namespace Core;

class Core
{
    public function __construct()
    {
        session_start();
        require_once __DIR__.'/helpers.php';
        self::dotenv();
        self::createFacades();
        require_once 'src/routes.php';
    }

    public function run()
    {
        try {
            $route = Router::get($_SERVER['REQUEST_URI']);
            $controllerName = 'Controller\\'.ucfirst($route['c']).'Controller';
            $actionName = $route['a'];

            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $actionName)) {
                    call_user_func_array([$controller, $actionName], $route['p'] ?? []);
                }
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public static function dotenv()
    {
        if (file_exists('.env')) {
            $handle = fopen('.env', 'r');
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    if (!empty($line = trim($line))) {
                        $expl = explode('=', $line);
                        define(strtoupper($expl[0]), $expl[1]);
                    }
                }
                fclose($handle);
            }
        }
    }

    public static function createFacades()
    {
        $facades = [
            'Auth' => \Core\Facade\Auth::class,
        ];

        foreach ($facades as $facade => $class) {
            class_alias($class, $facade);
        }
    }
}
