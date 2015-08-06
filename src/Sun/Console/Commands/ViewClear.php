<?php

namespace Sun\Console\Commands;

use Sun\Filesystem;
use Sun\Console\Command;
use Sun\Contracts\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ViewClear extends Command
{
    /**
     * @var string Command name
     */
    protected $name = 'view:clear';

    /**
     * @var string Command description
     */
    protected $description = "To clean all compiled view files";

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
        $isSuccess = $this->filesystem->cleanDirectory(storage_path().'/framework/views');

        if($isSuccess) {
            $this->info('Compiled views cleared!');
        }
    }

    protected function getArguments()
    {
        return [

        ];
    }

    protected function getOptions()
    {
        return [

        ];
    }
}