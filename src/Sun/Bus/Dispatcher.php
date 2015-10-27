<?php

namespace Sun\Bus;

use Sun\Contracts\Application;
use Sun\Contracts\Bus\Dispatcher as DispatcherContract;
use Sun\Contracts\Bus\CommandTranslator as CommandTranslatorContract;

class Dispatcher implements DispatcherContract
{
    /**
     * @var \Sun\Contracts\Bus\CommandTranslator
     */
    protected $commandTranslator;

    /**
     * @var \Sun\Contracts\Application
     */
    protected $app;

    /**
     * Create a new dispatcher instance
     *
     * @param \Sun\Contracts\Bus\CommandTranslator $commandTranslator
     * @param \Sun\Contracts\Application       $app
     */
    public function __construct(CommandTranslatorContract $commandTranslator, Application $app)
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