<?php

namespace Sun\Console;

use Phpmig\Console\Command;
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

        $this->bootPHPMig();
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

    /**
     * Bootstrap PHP Mig
     */
    protected function bootPHPMig()
    {
        foreach($this->getPhpMigCommands() as $command) {
            $command->setName('migration:'.$command->getName());
            $this->add($command);
        }
    }

    /**
     * Get PHP Mig console commands
     *
     * @return array
     */
    protected function getPhpMigCommands()
    {
        return [
            new Command\CheckCommand(),
            new Command\DownCommand(),
            new Command\GenerateCommand(),
            new Command\MigrateCommand(),
            new Command\RedoCommand(),
            new Command\RollbackCommand(),
            new Command\StatusCommand(),
            new Command\UpCommand(),
        ];
    }
}