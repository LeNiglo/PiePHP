<?php

namespace Core;

/**
*
*/
class Router
{
    private static $_routes = [];

    public static function connect($url, $route)
    {
        self::$_routes[$url] = $route;
    }

    public static function get($url)
    {
        $url = self::cleanUrl($url);
        if (array_key_exists($url, self::$_routes)) {
            return self::$_routes[$url];
        } else {
            foreach (self::$_routes as $route => $params) {
                $route = preg_replace_callback('/{([a-zA-Z]+)}/', function (array $matches) use ($params) {
                    array_shift($matches);
                    return isset($params['p'][$matches[0]]) ? "({$params['p'][$matches[0]]})" : "(.+)";
                }, $route);
                $matches = [];
                if (preg_match("#^$route$#", $url, $matches)) {
                    array_shift($matches);
                    $params['p'] = $matches;
                    return $params;
                }
            }
            return ['c' => 'error', 'a' => 'notfound'];
        }
    }

    private static function cleanUrl($url)
    {
        $url = explode('?', trim($url))[0];
        if ($url !== '/') {
            $url = rtrim($url, '/');
        }
        return $url;
    }
}
