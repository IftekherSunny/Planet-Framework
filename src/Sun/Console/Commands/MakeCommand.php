<?php

namespace Sun\Console\Commands;

use Sun\Support\Str;
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
        // generate command class
        $commandName  = $this->input->getArgument('name');

        $commandNamespace = $this->getNamespace('Commands', $commandName);

        $commandStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeCommand.txt');
        $commandStubs = str_replace([ 'dummyCommandName', 'dummyNamespace', '\\\\' ], [ basename($commandName), $commandNamespace, '\\' ], $commandStubs);

        if(!file_exists($filename = app_path() ."/Commands/{$commandName}Command.php")) {
            $this->filesystem->create($filename, $commandStubs);
            $this->info("{$commandName} command has been created successfully.");
        } else {
            $this->info("{$commandName} command already exists.");
        }

        // generate command handler class
        $commandHandlerNamespace = $this->getNamespace('Handlers', $commandName);
        $commandClassName = basename($commandName);
        $commandHandlerUse = "{$commandNamespace}\\{$commandClassName}";

        $commandHandlerStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeCommand-handler.txt');
        $commandHandlerStubs = str_replace(
            [ 'dummyCommandName', 'dummyNamespace', 'dummyUse', '\\\\' ],
            [ basename($commandName), $commandHandlerNamespace, $commandHandlerUse, '\\' ], $commandHandlerStubs
        );

        if(!file_exists($filename = app_path() ."/Handlers/{$commandName}CommandHandler.php")) {
            $this->filesystem->create($filename, $commandHandlerStubs);
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