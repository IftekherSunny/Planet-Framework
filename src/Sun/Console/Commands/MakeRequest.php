<?php

namespace Sun\Console\Commands;

use Sun\Console\Command;
use Sun\Contracts\Application;
use Sun\Contracts\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeRequest extends Command
{
    /**
     * @var string Command name
     */
    protected $name = 'make:request';

    /**
     * @var string Command description
     */
    protected $description = "Create a new request class";

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
        $filterName  = $this->input->getArgument('name');

        $filterNamespace = $this->getNamespace('Requests', $filterName);

        $filterStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeRequest.txt');

        $filterStubs = str_replace([ 'dummyFilterName', 'dummyNamespace', '\\\\' ], [ basename($filterName), $filterNamespace, '\\' ], $filterStubs);

        if(!file_exists($filename = app_path() ."/Requests/{$filterName}.php")) {
            $this->filesystem->create($filename, $filterStubs);
            $this->info("{$filterName} request has been created successfully.");
        } else {
            $this->info("{$filterName} request already exists.");
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
            ['name', InputArgument::REQUIRED, 'Give a name for your request.']
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