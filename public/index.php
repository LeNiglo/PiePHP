<?php

define('BASE_URI', str_replace([$_SERVER['DOCUMENT_ROOT'], '/public/index.php'], '', $_SERVER['SCRIPT_FILENAME']));
require_once __DIR__.'/../vendor/autoload.php';

$app = new \PiePHP\Core\Core();
$app->run();
