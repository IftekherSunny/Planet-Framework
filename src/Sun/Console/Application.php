<?php

namespace Sun\Console;

use Sun\Contracts\Application as ApplicationContract;
use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication
{
    /**
     * @var \Sun\Contracts\Application
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
     * @param \Sun\Contracts\Application $app
     */
    public function __construct(ApplicationContract $app)
    {
        parent::__construct('Planet Framework', $app::VERSION);

        $this->app = $app;

        $command = require_once __DIR__.'/Register.php';
        $applicationCommand = config('console')?: [];

        $this->commands = array_merge($command, $applicationCommand);

        $this->bootCommand();
    }

    /**
     * Resolve all dependencies
     */
    protected function bootCommand()
    {
        foreach ($this->commands as $command) {
            $this->add($this->app->make($command));
        }
    }
}