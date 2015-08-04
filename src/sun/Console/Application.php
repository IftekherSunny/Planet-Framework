<?php

namespace Sun\Console;

use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication
{
    protected $app;

    protected $commands;

    public function __construct($app)
    {
        parent::__construct('Planet Framework', $app::VERSION);

        $this->app = $app;

        $command = require_once __DIR__.'/Register.php';
        $this->commands = array_merge($command, config('console'));
    }

    public function bootCommand()
    {
        foreach ($this->commands as $command) {
            $this->add($this->app->make($command));
        }
    }
}