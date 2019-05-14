<?php

namespace Controller;

use Core\Controller;

class ErrorController extends Controller
{
    public function notfound()
    {
        $this->render('error.404');
    }
}
