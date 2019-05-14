<?php

namespace Core\CLI;

class CLI
{
    public function __construct()
    {
        include_once __DIR__ . '/../helpers.php';
    }

    public function run($argv)
    {
        $script = array_shift($argv);
        $command = array_shift($argv);
        dd($script, $command, $argv);
    }
}
