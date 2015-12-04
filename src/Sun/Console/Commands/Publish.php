<?php

namespace Sun\Console\Commands;

use Sun\Support\Str;
use Sun\Console\Command;
use Sun\Contracts\Application;
use Sun\Contracts\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Publish extends Command
{
    /**
     * @var string Command name
     */
    protected $name = 'publish';

    /**
     * @var string Command description
     */
    protected $description = "Publish vendor assets";

    /**
     * @var \Sun\Contracts\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var int
     */
    protected $size = 32;

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
        $services = $this->app->config('provider');

        if(!is_null($services)) {
            $published = false;

            foreach($services as $service) {
                $publishableItems = $this->app->make($service)->publish();

                if($this->publishItems($publishableItems)) {
                    $published = true;
                }
            }

            if($published) {
                $this->info("Package assets has been published.");
            } else {
                $this->info("Package assets already published.");
            }
        }
    }

    /**
     * Publish service assets
     *
     * @param $publishableItems
     *
     * @return bool
     */
    protected function publishItems($publishableItems)
    {
        $published = false;

        foreach($publishableItems as $source => $destination) {

            $destination = Str::path($destination);

            if(! $this->filesystem->exists($destination)) {
                $this->filesystem->create(
                    $destination,
                    $this->filesystem->get($source)
                );

                $published = true;
            }
        }

        return $published;
    }

    /**
     * Set your command arguments
     *
     * @return array
     */
    protected function getArguments()
    {
        return [

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