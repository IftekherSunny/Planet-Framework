<?php

namespace Sun\Console\Commands;

use Sun\Console\Command;
use Sun\Contracts\Application;
use Sun\Contracts\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class KeyGenerator extends Command
{
    /**
     * @var string Command name
     */
    protected $name = 'app:key';

    /**
     * @var string Command description
     */
    protected $description = "To generate encryption key";

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
        $bytes = openssl_random_pseudo_bytes($this->size, $strong);

        if ($bytes !== false && $strong !== false) {
            $string = '';
            while (($len = strlen($string)) < $this->size) {
                $length = $this->size - $len;

                $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $length);
            }

            $content = $this->filesystem->get(config_path().'/app.php');

            $key = config('app.key');

            $content = str_replace($key, $string, $content);

            $this->filesystem->create(config_path().'/app.php', $content);

            $this->info("Application key set successfully.");
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