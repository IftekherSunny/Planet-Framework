<?php

namespace Sun\Console\Commands;

use Sun\Filesystem;
use Sun\Console\Command;
use Sun\Contracts\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeConsole extends Command
{
    /**
     * @var string Command name
     */
    protected $name = 'make:console';

    /**
     * @var string Command description
     */
    protected $description = "Create a new console command";

    /**
     * @var \Sun\Filesystem
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
        $consoleName  = $this->input->getArgument('name');

        $consoleStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeConsole.txt');

        $consoleStubs = str_replace([ 'dummyConsoleCommandName', 'dummyNamespace', '\\\\' ], [ $consoleName, $this->app->getNamespace(), '\\' ], $consoleStubs);
        $success = $this->filesystem->create(app_path() ."/Console/{$consoleName}.php", $consoleStubs);

        if($success) {
            $this->info("{$consoleName} console command has been created successfully.");
        }
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Give a name for your console command.']
        ];
    }

    protected function getOptions()
    {
        return [

        ];
    }
}