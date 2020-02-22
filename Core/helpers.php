<?php

function dd()
{
    $args = func_get_args();
    dump($args);
    die;
}

function dump()
{
    if (PHP_SAPI !== 'cli') {
        echo '<pre>';
    }
    $args = func_get_args();
    foreach ($args as $arg) {
        call_user_func_array('var_dump', [$arg]);
    }
    if (PHP_SAPI !== 'cli') {
        echo '</pre>';
    }
}

function route($name = '', $params = [])
{
    $route = \Core\Router::findNamedRoute($name, $params);

    return BASE_URI.'/'.trim($route ?? $name, '/');
}

function asset($path = '')
{
    return BASE_URI.'/public/'.trim($path, '/');
}

function env($variable, $default = null)
{
    return get_defined_constants(true)['user'][$variable] ?? $default;
}

if (file_exists('src/helpers.php')) {
    require_once 'src/helpers.php';
}
