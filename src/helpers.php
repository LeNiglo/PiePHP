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
    call_user_func_array('var_dump', func_get_args());
    echo '</pre>';
}
