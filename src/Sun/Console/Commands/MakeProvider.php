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
        $providerName = $this->input->getArgument('name');

        $providerNamespace  = explode('/', $providerName);

        $providerClassName = array_pop($providerNamespace);

        $providerNamespace = implode('/', $providerNamespace);

        $providerStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeProvider.txt');

        if(empty($providerNamespace = str_replace('/', '\\', $providerNamespace))) {
            $providerStubs = str_replace([ 'dummyProviderName', 'dummyNamespace', '\\\\' ], [ $providerClassName, str_replace('\\', '', $this->app->getNamespace()), '\\' ], $providerStubs);
        } else {
            $providerStubs = str_replace([ 'dummyProviderName', 'dummyNamespace', '\\\\' ], [ $providerClassName, $this->app->getNamespace() . str_replace('/', '\\', $providerNamespace), '\\' ], $providerStubs);
        }

        if(!file_exists($filename = app_path() ."/{$providerName}.php")) {

            list($folder, $filename) = $this->getLocation($providerName);

            $this->createFile($folder, $filename, $providerStubs);

            $this->info("{$providerName} has been created successfully.");
        } else {
            $this->info("{$providerName} already exists.");
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