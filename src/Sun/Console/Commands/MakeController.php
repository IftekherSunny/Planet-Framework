<?php 

namespace Sun\Console\Commands;

use Sun\Filesystem;
use Sun\Console\Command;
use Sun\Contracts\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeController extends Command
{
    /**
     * @var string Command name
     */
    protected $name = 'make:controller';

    /**
     * @var string Command description
     */
    protected $description = "Create a new controller class";

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
        $controllerName = $this->input->getArgument('name');
        $isPlain = $this->input->getOption('plain');

        if($isPlain) {
            $controllerStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeControllerPlain.txt');
        } else {
            $controllerStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeController.txt');
        }

        $controllerStubs = str_replace([ 'dummyControllerName', 'dummyNamespace', '\\\\' ], [ $controllerName, $this->app->getNamespace(), '\\' ], $controllerStubs);
        $success = $this->filesystem->create(app_path() ."/Controllers/{$controllerName}Controller.php", $controllerStubs);

        if($success) {
            $this->info("{$controllerName}Controller has been created successfully.");
        }
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Give a name for your controller.']
        ];
    }

    protected function getOptions()
    {
        return [
            ['plain', null, InputOption::VALUE_NONE, 'To create plain controller.']
        ];
    }
}