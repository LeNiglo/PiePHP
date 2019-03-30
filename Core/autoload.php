<?php

spl_autoload_register(function ($class) {
    $file = null;
    if (preg_match('/Core/', $class)) {
        $file = implode(DIRECTORY_SEPARATOR,
        [
            '..', str_replace('\\', DIRECTORY_SEPARATOR, $class)
        ]
        ) . '.php';
    } elseif (preg_match('/(Controller|Model)$/', $class)) {
        $file = implode(DIRECTORY_SEPARATOR,
        [
            '..', 'src', str_replace('\\', DIRECTORY_SEPARATOR, $class)
        ]
        ) . '.php';
    } else {
        $file = implode(DIRECTORY_SEPARATOR,
        [
            '..', str_replace('\\', DIRECTORY_SEPARATOR, $class)
        ]
        ) . '.php';
    }
    if (file_exists($file)) {
        include $file;
    }
});
