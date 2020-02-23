<?php

namespace PiePHP\Core;

class Router
{
    private static $_routes = [];

    public static function findNamedRoute($name, $params = [])
    {
        foreach (self::$_routes as $url => $route) {
            if ($route->name === $name) {
                if (!empty($params)) {
                    foreach ($params as $key => $value) {
                        $url = preg_replace("/\{$key\}/", $value, $url);
                    }
                }

                return $url;
            }
        }

        return null;
    }

    public static function &connect($url, $route)
    {
        $url = self::cleanUrl($url);
        self::$_routes[$url] = new class() {
            public $controller = null;
            public $action = null;
            public $callable = null;
            public $params = [];
            public $name = null;

            public function name($value)
            {
                $this->name = $value;

                return $this;
            }

            public function params($value)
            {
                if (is_array($value)) {
                    $this->params = $value;
                }

                return $this;
            }
        };

        if (is_string($route)) {
            $expl = explode('@', $route);
            $expl[0] = "\\App\\Controller\\{$expl[0]}";
            self::$_routes[$url]->controller = $expl[0];
            self::$_routes[$url]->action = $expl[1];
            self::$_routes[$url]->callable = self::createCallable($expl[0], $expl[1]);
        } elseif (is_callable($route)) {
            self::$_routes[$url]->callable = self::createCallable($route);
        }

        return self::$_routes[$url];
    }

    public static function get($url)
    {
        $url = self::cleanUrl($url);
        if (array_key_exists($url, self::$_routes)) {
            \Log::debug(self::$_routes[$url]);

            return self::$_routes[$url];
        } else {
            foreach (self::$_routes as $route_url => $route) {
                $route_url = preg_replace_callback(
                    '/{([a-zA-Z]+?)}/', function (array $matches) use ($route) {
                        array_shift($matches);

                        return !empty($route->params[$matches[0]]) ? "({$route->params[$matches[0]]})" : '(.+?)';
                    }, $route_url
                );
                $matches = [];
                if (preg_match("#^$route_url$#", $url, $matches)) {
                    array_shift($matches);
                    $route->params = array_map(
                        function ($m) {
                            return utf8_decode(urldecode($m));
                        }, $matches
                    );
                    \Log::debug($route);

                    return $route;
                }
            }

            $error = new class() {
                public $callable = null;
                public $params = [];
            };
            $error->callable = self::createCallable('\\App\\Controller\\ErrorController', 'notfound');

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
                    } else {
                        throw new \RuntimeException("Method $actionName in $controllerName not found.");
                    }
                } else {
                    throw new \RuntimeException("Class $controllerName not found.");
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
