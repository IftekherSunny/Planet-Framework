<?php

namespace Sun\Console\Commands;

use Sun\Filesystem;
use Sun\Console\Command;
use Sun\Contracts\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeFilter extends Command
{
    /**
     * @var string Command name
     */
    protected $name = 'make:filter';

    /**
     * @var string Command description
     */
    protected $description = "Create a new filter class";

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
        $filterName  = $this->input->getArgument('name');

        $filterStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeFilter.txt');

        $filterStubs = str_replace([ 'dummyFilterName', 'dummyNamespace', '\\\\' ], [ $filterName, $this->app->getNamespace(), '\\' ], $filterStubs);
        $success = $this->filesystem->create(app_path() ."/Filters/{$filterName}.php", $filterStubs);

        if($success) {
            $this->info("{$filterName} filter has been created successfully.");
        }
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Give a name for your filer.']
        ];
    }

    protected function getOptions()
    {
        return [

        ];
    }
}