<?php

namespace Sun\Console\Commands;

use Sun\Console\Command;
use Sun\Contracts\Application;
use Sun\Contracts\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeListener extends Command
{
    /**
     * @var string Command name
     */
    protected $name = 'make:listener';

    /**
     * @var string Command description
     */
    protected $description = "Create a new listener class";

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
        $listenerName  = $this->input->getArgument('name');
        $eventName = $this->input->getOption('event');

        if(!is_null($eventName)) {
            $eventNamespace = $this->app->getNamespace(). "Events\\{$eventName}";
            $listenerStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeListenerEvent.txt');
            $listenerStubs = str_replace([ 'dummyListenerName', 'dummyEventNameVariable', 'dummyEventName', 'dummyNamespace', 'dummyUse', '\\\\' ],
                                     [ $listenerName, lcfirst($eventName), $eventName, $this->app->getNamespace(), $eventNamespace, '\\' ], $listenerStubs);
        } else {
            $listenerStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeListener.txt');
            $listenerStubs = str_replace([ 'dummyListenerName', 'dummyNamespace', '\\\\' ],
                [ $listenerName, $this->app->getNamespace(), '\\' ], $listenerStubs);
        }


        if(!file_exists($filename = app_path() ."/Listeners/{$listenerName}.php")) {
            $this->filesystem->create($filename, $listenerStubs);
            $this->info("{$listenerName} has been created successfully.");
        } else {
            $this->info("{$listenerName} already exists.");
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
            ['name', InputArgument::REQUIRED, 'Give a name for your Listener.']
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
            ['event', null, InputOption::VALUE_REQUIRED, 'Give a event name for your Listener.']
        ];
    }
}