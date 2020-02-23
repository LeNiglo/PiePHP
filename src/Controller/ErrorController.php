<?php

namespace App\Controller;

use PiePHP\Core\Controller;

class ErrorController extends Controller
{
    public function notfound()
    {
        $this->render('error.404');
    }
}
