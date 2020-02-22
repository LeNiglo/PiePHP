<?php

define('BASE_URI', str_replace([$_SERVER['DOCUMENT_ROOT'], '/public/index.php'], '', $_SERVER['SCRIPT_FILENAME']));
require_once implode(DIRECTORY_SEPARATOR, ['Core', 'autoload.php']);

$app = new \Core\Core();
$app->run();
