<?php

namespace Sun\Console\Commands;

use Sun\Console\Command;
use Sun\Contracts\Application;
use Sun\Contracts\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeAlien extends Command
{
    /**
     * @var string Command name
     */
    protected $name = 'make:alien';

    /**
     * @var string Command description
     */
    protected $description = "Create a new alien class";

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
        $alienName  = $this->input->getArgument('name');

        $alienStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeAlien.txt');

        $alienStubs = str_replace([ 'dummyAlienName', 'dummyNamespace', '\\\\' ], [ $alienName, $this->app->getNamespace(), '\\' ], $alienStubs);
        $success = $this->filesystem->create(app_path() ."/Alien/{$alienName}Alien.php", $alienStubs);

        if($success) {
            $this->info("{$alienName}Alien has been created successfully.");
        }
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Give a name for your Alien.']
        ];
    }

    protected function getOptions()
    {
        return [

        ];
    }
}