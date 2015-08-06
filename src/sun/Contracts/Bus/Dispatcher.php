<?php

namespace Sun\Contracts\Bus;

use Sun\Bus\CommandNotFoundException;
use Sun\Bus\HandlerNotFoundException;

interface Dispatcher
{
    /**
     * To dispatch command
     *
     * @param $object
     *
     * @return mixed
     * @throws CommandNotFoundException
     * @throws HandlerNotFoundException
     */
    public function dispatch($object);
}