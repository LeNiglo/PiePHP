<?php

/**
 *
 */
class Request
{

    function __construct()
    {
        foreach ($_POST as $key => $value) {
            $this->{$key} = htmlentities(stripcslashes(trim($value)));
        }
        foreach ($_GET as $key => $value) {
            $this->{$key} = htmlentities(stripcslashes(trim($value)));
        }
    }
}
