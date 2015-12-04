<?php 

namespace Sun\Console\Commands;

use Sun\Console\Command;
use Sun\Contracts\Application;
use Sun\Contracts\Filesystem\Filesystem;
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
        $controllerName = $this->input->getArgument('name');

        $controllerNamespace = $this->getNamespace('Controllers', $controllerName);

        $isPlain = $this->input->getOption('plain');
        $isResource = $this->input->getOption('resource');

        if($isPlain) {
            $controllerStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeControllerPlain.txt');
        } elseif($isResource) {
            $controllerStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeControllerResource.txt');
        } else {
            $controllerStubs = $this->filesystem->get(__DIR__.'/../stubs/MakeController.txt');
        }

        $controllerStubs = str_replace([ 'dummyControllerName', 'dummyNamespace', '\\\\' ], [ basename($controllerName), $controllerNamespace, '\\' ], $controllerStubs);

        if(!file_exists($filename = app_path() ."/Controllers/{$controllerName}Controller.php")) {
            $this->filesystem->create($filename, $controllerStubs);
            $this->info("{$controllerName}Controller has been created successfully.");
        } else {
            $this->info("{$controllerName}Controller already exists.");
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
            ['name', InputArgument::REQUIRED, 'Give a name for your controller.']
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
            ['plain', null, InputOption::VALUE_NONE, 'To create plain controller.'],
            ['resource', null, InputOption::VALUE_NONE, 'To create resource controller.']
        ];
    }
}