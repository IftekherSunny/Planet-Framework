<?php

namespace Sun\Console\Commands;

use Sun\Console\Command;
use Sun\Contracts\Application;
use Sun\Contracts\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeEvent extends Command
{
    /**
     * @var string Command name
     */
    protected $name = 'make:event';

    /**
     * @var string Command description
     */
    protected $description = "Create a new event class";

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
        $eventName  = $this->input->getArgument('name');

        $eventStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeEvent.txt');

        $eventStubs = str_replace([ 'dummyEventName', 'dummyNamespace', '\\\\' ], [ $eventName, $this->app->getNamespace(), '\\' ], $eventStubs);

        if(!file_exists($filename = app_path() ."/Events/{$eventName}.php")) {
            $this->filesystem->create($filename, $eventStubs);
            $this->info("{$eventName} has been created successfully.");
        } else {
            $this->info("{$eventName} already exists.");
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
            ['name', InputArgument::REQUIRED, 'Give a name for your Event.']
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