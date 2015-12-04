<?php

namespace Sun\Console\Commands;

use Sun\Console\Command;
use Sun\Contracts\Application;
use Sun\Contracts\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeProvider extends Command
{
    /**
     * @var string Command name
     */
    protected $name = 'make:provider';

    /**
     * @var string Command description
     */
    protected $description = "Create a new service provider class";

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
        $providerName  = $this->input->getArgument('name');

        $providerNamespace = $this->getNamespace(null, $providerName);

        $providerStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeProvider.txt');

        $providerStubs = str_replace([ 'dummyFilterName', 'dummyNamespace', '\\\\' ], [ basename($providerName), $providerNamespace, '\\' ], $providerStubs);

        if(!file_exists($filename = app_path() ."/{$providerName}.php")) {
            $this->filesystem->create($filename, $providerStubs);
            $this->info("{$providerName} provider has been created successfully.");
        } else {
            $this->info("{$providerName} provider already exists.");
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