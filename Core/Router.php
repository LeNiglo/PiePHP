<?php

namespace Core;

/**
*
*/
class Router
{
    private static $_routes = [];

    public static function &connect($url, $route)
    {
        $url = self::cleanUrl($url);
        self::$_routes[$url] = new class {
            public $callable = null;
            public $params = [];
            public $name = null;

            public function name($value)
            {
                $this->name = $value;
            }

            public function params($value)
            {
                if (is_array($value)) {
                    $this->params = $value;
                }
            }
        };

        if (is_string($route)) {
            $expl = explode('@', $route);
            self::$_routes[$url]->callable = self::createCallable("\\Controller\\{$expl[0]}", $expl[1]);
        } elseif (is_callable($route)) {
            self::$_routes[$url]->callable = self::createCallable($route);
        }

        return self::$_routes[$url];
    }

    public static function get($url)
    {
        $url = self::cleanUrl($url);
        if (array_key_exists($url, self::$_routes)) {
            return self::$_routes[$url];
        } else {
            foreach (self::$_routes as $route_url => $route) {
                $route_url = preg_replace_callback('/{([a-zA-Z]+?)}/', function (array $matches) use ($route) {
                    array_shift($matches);
                    return !empty($route->params[$matches[0]]) ? "({$route->params[$matches[0]]})" : "(.+?)";
                }, $route_url);
                $matches = [];
                if (preg_match("#^$route_url$#", $url, $matches)) {
                    array_shift($matches);
                    $route->params = array_map(function ($m) {
                        return utf8_decode(urldecode($m));
                    }, $matches);
                    return $route;
                }
            }

            $error = new class {
                public $callable = null;
                public $params = [];
            };
            $error->callable = self::createCallable('\\Controller\\ErrorController', 'notfound');
            return $error;
        }
    }

    private static function createCallable($controllerName, $actionName = null)
    {
        if (is_callable($controllerName)) {
            return function ($params = []) use ($controllerName) {
                return call_user_func_array($controllerName, $params);
            };
        } else {
            return function ($params = []) use ($controllerName, $actionName) {
                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $actionName)) {
                        return call_user_func_array([$controller, $actionName], $params);
                    }
                }
            };
        }
    }

    private static function cleanUrl($url)
    {
        $url = explode('?', str_replace(BASE_URI, '', trim($url)))[0];
        if ($url !== '/') {
            $url = rtrim($url, '/');
        }
        return $url;
    }
}
