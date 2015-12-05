<?php

namespace Sun\Console;

use Exception;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

abstract class Command extends SymfonyCommand
{
    /**
     * @var \Sun\Contracts\Application
     */
    protected $app;

    /**
     * Command name
     *
     * @var string
     */
    protected $name;

    /**
     * command description
     *
     * @var string
     */
    protected $description;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * SetUp
     */
    protected function configure()
    {
        $this->setName($this->name);
        $this->setDescription($this->description);
        $this->setCommandArguments();
        $this->setCommandOptions();
    }

    /**
     * To set command arguments
     *
     * @throws Exception
     */
    protected function setCommandArguments()
    {
        foreach ($this->getArguments() as $argument) {
            call_user_func_array([$this, 'addArgument'], $argument);
        }
    }

    /**
     * To set command options
     *
     * @throws Exception
     */
    protected function setCommandOptions()
    {
        foreach ($this->getOptions() as $option) {
            call_user_func_array([$this, 'addOption'], $option);
        }
    }

    /**
     * To get arguments
     *
     * @throws Exception
     */
    protected function getArguments()
    {
        throw new Exception('please set arguments');
    }

    /**
     * To set options
     *
     * @throws Exception
     */
    protected function getOptions()
    {
        throw new Exception('Please set options');
    }

    /**
     * To exectue command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;

        $this->output = new SymfonyStyle($this->input, $output);

        $this->handle();
    }

    /**
     * To show green color text message
     *
     * @param $message
     */
    protected function info($message)
    {
        $this->output->writeln("<info>$message</info>");
    }

    /**
     * To show red color text message
     *
     * @param $message
     */
    protected function error($message)
    {
        $this->output->error($message);
    }

    /**
     * To show yellow color text message
     *
     * @param $message
     */
    protected function warning($message)
    {
        $this->output->warning($message);
    }

    /**
     * To show white color text message with green background
     *
     * @param $message
     */
    protected function success($message)
    {
        $this->output->block($message, null, 'fg=white;bg=blue', ' ', true);
    }

    /**
     * To show note message
     *
     * @param $message
     */
    protected function note($message)
    {
        $this->output->note($message);
    }

    /**
     * To show caution message
     *
     * @param $message
     */
    protected function caution($message)
    {
        $this->output->caution($message);
    }

    /**
     * To ask question
     *
     * @param      $question
     * @param null $default
     * @param null $validator
     */
    protected function ask($question, $default = null, $validator = null)
    {
        return $this->output->ask($question, $default, $validator);
    }

    /**
     * To ask hidden question
     *
     * @param      $question
     * @param null $validator
     */
    protected function askHidden($question, $validator = null)
    {
        return $this->output->askHidden($question, $validator);
    }

    /**
     * To confirm answer of a question
     * @param      $question
     * @param bool $default
     */
    protected function confirm($question, $default = true)
    {
        return $this->output->confirm($question, $default);
    }

    /**
     * To choice answer from a question
     *
     * @param       $question
     * @param array $choices
     * @param null  $default
     */
    protected function choice($question, array $choices, $default = null)
    {
        return $this->output->choice($question, $choices, $default);
    }

    /**
     * To call another console command
     *
     * @param string $command
     * @param array  $params
     *
     * @return int
     */
    protected function call($command, $params = [])
    {
        $parameters['command'] = $command;

        foreach($params as $key => $value) {
            $parameters[$key] = $value;
        }

        $command = $this->getApplication()->find($command);

        return $command->run(new ArrayInput($parameters), $this->output);
    }

    /**
     * @param $providerName
     *
     * @return array
     */
    protected function getLocation($providerName)
    {
        $folder = explode('/', $providerName);
        $filename = array_pop($folder);

        return array($folder, $filename);
    }

    /**
     * Create file from stubs
     *
     * @param string $folder
     * @param string $filename
     * @param string $stubs
     */
    protected function createFile($folder, $filename, $stubs, $defaultFolder = '')
    {
        if(!empty($folder = implode('/', $folder))) {
            $dir = app_path() . "{$defaultFolder}/{$folder}";
            $this->filesystem->createDirectory($dir);
            $this->filesystem->create("{$dir}/{$filename}.php", $stubs);
        } else {
            $this->filesystem->create(app_path() ."{$defaultFolder}/{$filename}.php", $stubs);
        }
    }

    /**
     * @param string $directory
     * @param string $name
     *
     * @return string
     */
    protected function getNamespace($directory = null, $name)
    {
        if (is_null($directory)) {
            $namespace = $this->app->getNamespace() . "\\" . str_replace('/', '\\', dirname($name));

            return $namespace;
        } elseif (($name = str_replace('/', '\\', dirname($name))) !== ".") {
            $namespace = $this->app->getNamespace() . "\\{$directory}\\" . $name;

            return $namespace;
        } else {
            $namespace = $this->app->getNamespace() . "\\{$directory}";

            return $namespace;
        }
    }
}