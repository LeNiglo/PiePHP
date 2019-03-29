<?php

namespace Core;

/**
*
*/
class Request
{
    private $_get = [];
    private $_post = [];

    public function __construct()
    {
        foreach ($_GET as $key => $value) {
            $this->_get[$key] = stripcslashes(trim($value));
        }
        foreach ($_POST as $key => $value) {
            $this->_post[$key] = stripcslashes(trim($value));
        }
    }

    public function __get($value)
    {
        if (array_key_exists($value, $this->_get)) {
            return $this->_get[$value];
        } elseif (array_key_exists($value, $this->_post)) {
            return $this->_post[$value];
        } else {
            return null;
        }
    }

    public function has($value)
    {
        if (array_key_exists($value, $this->_get)) {
            return true;
        } elseif (array_key_exists($value, $this->_post)) {
            return true;
        } else {
            return false;
        }
    }

    public function all()
    {
        return array_merge($this->_get, $this->_post);
    }

    public function input($value)
    {
        return $this->_post[$value] ?? null;
    }

    public function query($value)
    {
        return $this->_get[$value] ?? null;
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function back()
    {
        return $_SERVER['HTTP_REFERER'];
    }
}
