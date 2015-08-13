<?php

namespace Sun\Console\Commands;

use Sun\Console\Command;
use Sun\Contracts\Application;
use Sun\Contracts\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeCommand extends Command
{
    /**
     * @var string Command name
     */
    protected $name = 'make:command';

    /**
     * @var string Command description
     */
    protected $description = "Create a new command class";

    /**
     * @var \Sun\Contracts\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * @param Application $app
     * @param Filesystem  $filesystem
     */
    public function __construct(Application $app, Filesystem $filesystem)
    {
        parent::__construct();
        $this->app = $app;
        $this->filesystem = $filesystem;
    }

    /**
     * To handle console command
     */
    public function handle()
    {
        $commandName  = $this->input->getArgument('name');

        $commandStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeCommand.txt');
        $commandStubs = str_replace([ 'dummyCommandName', 'dummyNamespace', '\\\\' ], [ $commandName, $this->app->getNamespace(), '\\' ], $commandStubs);

        if(file_exists($filename = app_path() ."/Commands/{$commandName}Command.php")) {
            $this->filesystem->create($filename, $commandStubs);
            $this->info("{$commandName} command has been created successfully.");
        } else {
            $this->info("{$commandName} command already exists.");
        }

        $commandHanderStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeCommand-handler.txt');
        $commandHanderStubs = str_replace([ 'dummyCommandName', 'dummyNamespace', '\\\\' ], [ $commandName, $this->app->getNamespace(), '\\' ], $commandHanderStubs);

        if(file_exists($filename = app_path() ."/Handlers/{$commandName}CommandHandler.php")) {
            $this->filesystem->create($filename, $commandHanderStubs);
            $this->info("{$commandName} handler has been created successfully.");
        } else {
            $this->info("{$commandName} handler already exists.");
        }
    }

    /**
     * Set your command arguments
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Give a name for your command.']
        ];
    }

    /**
     * Set your command options
     *
     * @return array
     */
    protected function getOptions()
    {
        return [

        ];
    }
}