<?php

namespace Sun\Bus;

use Sun\Contracts\Application;

class Dispatcher
{
    /**
     * @var CommandTranslator
     */
    private $commandTranslator;
    /**
     * @var Application
     */
    private $app;

    /**
     * @param CommandTranslator $commandTranslator
     * @param Application       $app
     */
    public function __construct(CommandTranslator $commandTranslator, Application $app)
    {
        $this->commandTranslator = $commandTranslator;

        $this->app = $app;
    }

    /**
     * To dispatch command
     *
     * @param $object
     *
     * @return mixed
     * @throws CommandNotFoundException
     * @throws HandlerNotFoundException
     */
    public function dispatch($object)
    {
        $handler = $this->commandTranslator->translate($object);

        return $this->app->make($handler)->handle($object);
    }
}