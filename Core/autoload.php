<?php

spl_autoload_register(
    function ($class) {
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        if (file_exists(implode(DIRECTORY_SEPARATOR, ['..', $file]))) {
            include implode(DIRECTORY_SEPARATOR, ['..', $file]);
        } elseif (file_exists(implode(DIRECTORY_SEPARATOR, ['..', 'src', $file]))) {
            include implode(DIRECTORY_SEPARATOR, ['..', 'src', $file]);
        } elseif (file_exists(implode(DIRECTORY_SEPARATOR, [$file]))) {
            include implode(DIRECTORY_SEPARATOR, [$file]);
        } elseif (file_exists(implode(DIRECTORY_SEPARATOR, ['src', $file]))) {
            include implode(DIRECTORY_SEPARATOR, ['src', $file]);
        } else {
            throw new \ErrorException("Class ${class} not found.", 1);
        }
    }
);
