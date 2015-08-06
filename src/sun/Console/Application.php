<?php

namespace Sun\Console;

use Sun\Contracts\Console\Application as ApplicationContract;
use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication implements ApplicationContract
{
    /**
     * @var \Sun\Application
     */
    protected $app;

    /**
     * store all commands
     *
     * @var array
     */
    protected $commands;

    /**
     * Create a new console command instance
     *
     * @param string $app
     */
    public function __construct($app)
    {
        parent::__construct('Planet Framework', $app::VERSION);

        $this->app = $app;

        $command = require_once __DIR__.'/Register.php';
        $this->commands = array_merge($command, config('console'));
    }

    /**
     * Resolve all dependencies
     */
    public function bootCommand()
    {
        foreach ($this->commands as $command) {
            $this->add($this->app->make($command));
        }
    }
}