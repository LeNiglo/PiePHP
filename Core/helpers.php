<?php

function dd()
{
    $args = func_get_args();
    dump($args);
    die;
}

function dump()
{
    echo '<pre>';
    $args = func_get_args();
    foreach ($args as $arg) {
        call_user_func_array('var_dump', [$arg]);
    }
    echo '</pre>';
}

function route($name = '', $params = [])
{
    $route = \Core\Router::findNamedRoute($name, $params);
    return BASE_URI . '/' . trim($route ?? $name, '/');
    ;
}

function asset($path = '')
{
    return BASE_URI . '/public/' . trim($path, '/');
}

if (file_exists('../src/helpers.php')) {
    require_once '../src/helpers.php';
}
