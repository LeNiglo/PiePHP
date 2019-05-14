<?php

namespace Core;

class Core
{
    public function __construct()
    {
        session_start();
        include_once __DIR__ . '/helpers.php';
        self::dotenv();
        self::createFacades();
        include_once '../src/routes.php';
    }

    public function run()
    {
        try {
            $route = Router::get($_SERVER['REQUEST_URI']);
            $route->callable->__invoke($route->params);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public static function dotenv()
    {
        if (file_exists('../.env')) {
            $handle = fopen('../.env', 'r');
            if ($handle) {
                while (false !== ($line = fgets($handle))) {
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
            'Log' => \Core\Facade\Log::class,
        ];

        foreach ($facades as $facade => $class) {
            class_alias($class, $facade);
        }
    }
}
