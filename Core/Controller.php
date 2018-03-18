<?php

namespace Core;

use Core\TemplateEngine;

/**
 *
 */
class Controller
{

    private static $_render;

    function __destruct()
    {
        echo self::$_render;
    }

    protected function render($view, $scope = [], $layout = 'layout')
    {
        extract($scope);
        $tpl = new TemplateEngine();

        $f = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'src', 'View', str_replace('.', DIRECTORY_SEPARATOR, $view)]) . '.php';
        $f_tpl = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'src', 'View', str_replace('.', DIRECTORY_SEPARATOR, $view)]) . '.blade.php';

        $l = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'src', 'View', $layout]) . '.php';
        $l_tpl = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'src', 'View', $layout]) . '.blade.php';

        if (file_exists($f_tpl)) {
            ob_start();
            eval(' ?>'.$tpl->parse($f_tpl));
            $_view = ob_get_clean();
        } else if (file_exists($f)) {
            ob_start();
            include $f;
            $_view = ob_get_clean();
        } else {
            echo "View '$f' not found.";
            die;
        }

        if (file_exists($l_tpl)) {
            ob_start();
            eval(' ?>'.$tpl->parse($l_tpl));
            self::$_render = ob_get_clean();
        } else if (file_exists($l)) {
            ob_start();
            include $l;
            self::$_render = ob_get_clean();
        } else {
            echo "Layout '$l' not found.";
            die;
        }
    }
}
