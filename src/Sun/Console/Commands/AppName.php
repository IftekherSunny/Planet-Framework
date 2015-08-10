<?php

namespace Sun\Console\Commands;

use Sun\Console\Command;
use Sun\Contracts\Application;
use Sun\Contracts\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class AppName extends Command
{
    /**
     * @var string Command name
     */
    protected $name = 'app:name';

    /**
     * @var string Command description
     */
    protected $description = "Set the application namespace";

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
        $oldNamespace = rtrim($this->app->getNamespace(),'\\');
        $newNamespace = ucfirst($this->input->getArgument('name'));

        $this->setAppNamespace($oldNamespace, $newNamespace);

        $this->setBootstrapNamespace($oldNamespace, $newNamespace);

        $this->setConfigNamespace($oldNamespace, $newNamespace);

        $this->setComposerNamespace($oldNamespace, $newNamespace);

        $this->composerDumpAutoload();
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Give a name for your namespace.']
        ];
    }

    protected function getOptions()
    {
        return [

        ];
    }

    /**
     * To set app directory files namespace
     *
     * @param $oldNamespace
     * @param $newNamespace
     *
     * @throws \Sun\FileNotFoundException
     */
    private function setAppNamespace($oldNamespace, $newNamespace)
    {
        $files = $this->filesystem->files(app_path());

        foreach ($files as $file) {
            $content = $this->filesystem->get($file);
            $this->filesystem->create($file, preg_replace("/\\b" . $oldNamespace . "\\b/", $newNamespace, $content));
        }
    }

    /**
     * To set bootstrap file namespace
     *
     * @param $oldNamespace
     * @param $newNamespace
     *
     * @return array
     * @throws \Sun\FileNotFoundException
     */
    private function setBootstrapNamespace($oldNamespace, $newNamespace)
    {
        $files = $this->filesystem->files(base_path() . '/bootstrap');

        foreach ($files as $file) {
            $content = $this->filesystem->get($file);
            $this->filesystem->create($file, str_replace($oldNamespace . '\Controllers', $newNamespace . '\Controllers', $content));
        }
    }

    /**
     * To set config directory files namespace
     *
     * @param $oldNamespace
     * @param $newNamespace
     *
     * @return array
     * @throws \Sun\FileNotFoundException
     */
    private function setConfigNamespace($oldNamespace, $newNamespace)
    {
        $files = $this->filesystem->files(base_path() . '/config');

        foreach ($files as $file) {
            $content = $this->filesystem->get($file);
            $this->filesystem->create($file, preg_replace("/\\b" . $oldNamespace . "\\b/", $newNamespace, $content));
        }
    }

    /**
     * To set composer namespace
     *
     * @param $oldNamespace
     * @param $newNamespace
     *
     * @throws \Sun\FileNotFoundException
     */
    private function setComposerNamespace($oldNamespace, $newNamespace)
    {
        $files = $this->filesystem->files(base_path() . '/vendor/composer');

        foreach ($files as $file) {
            $content = $this->filesystem->get($file);
            $this->filesystem->create($file, preg_replace("/\\b" . $oldNamespace . "\\b/", $newNamespace, $content));
        }

        $file = base_path() . '/composer.json';
        $content = $this->filesystem->get($file);
        $this->filesystem->create($file, preg_replace("/\\b" . $oldNamespace . "\\b/", $newNamespace, $content));
    }

    /**
     * To dump composer autoload
     */
    private function composerDumpAutoload()
    {
        exec('composer dumpautoload');
    }
}