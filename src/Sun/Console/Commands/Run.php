<?php

namespace Sun\Console\Commands;

use Sun\Console\Command;
use Sun\Contracts\Application;
use Sun\Contracts\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Run extends Command
{
    /**
     * @var string Command name
     */
    protected $name = 'run';

    /**
     * @var string Command description
     */
    protected $description = "Run the application on the PHP built-in server";

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
        $port  = $this->input->getOption('port');

        if($port) {
            $this->info("Planet Framework built-in server started on http://localhost:{$port}");
            exec("php -S localhost:{$port} -t public");
        }

        $this->info("Planet Framework built-in server started on http://localhost:8000");
        exec("php -S localhost:8000 -t public");

    }

    protected function getArguments()
    {
        return [

        ];
    }

    protected function getOptions()
    {
        return [
            ['port', null, InputOption::VALUE_REQUIRED, 'Give a port number to run server.']
        ];
    }
}