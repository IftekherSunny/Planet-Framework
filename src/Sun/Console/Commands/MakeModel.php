<?php

namespace Sun\Console\Commands;

use Sun\Console\Command;
use Sun\Contracts\Application;
use Sun\Contracts\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeModel extends Command
{
    /**
     * @var string Command name
     */
    protected $name = 'make:model';

    /**
     * @var string Command description
     */
    protected $description = "Create a new model class";

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
        $modelName  = $this->input->getArgument('name');

        $modelStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeModel.txt');

        $modelStubs = str_replace([ 'dummyModelName', 'dummyNamespace', '\\\\' ], [ $modelName, $this->app->getNamespace(), '\\' ], $modelStubs);
        $success = $this->filesystem->create(app_path() ."/Models/{$modelName}.php", $modelStubs);

        if($success) {
            $this->info("{$modelName} model has been created successfully.");
        }
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Give a name for your model.']
        ];
    }

    protected function getOptions()
    {
        return [

        ];
    }
}