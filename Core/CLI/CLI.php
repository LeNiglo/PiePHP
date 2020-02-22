<?php

namespace Core\CLI;

class CLI
{
    public function __construct()
    {
        include_once __DIR__.'/../helpers.php';
    }

    public function run($argv)
    {
        $script = array_shift($argv);
        $command = array_shift($argv);

        if (method_exists($this, $command)) {
            return call_user_func_array([$this, $command], $argv);
        }
    }

    private function serve($port = '8000')
    {
        $port = intval($port);

        return exec(sprintf('%s -S %s:%s %s', PHP_BINARY, 'localhost', $port, 'server.php'));
    }
}
