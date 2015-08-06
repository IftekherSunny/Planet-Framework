<?php

namespace Sun\Contracts\Bus;

use Sun\Bus\CommandNotFoundException;
use Sun\Bus\HandlerNotFoundException;

interface CommandTranslator
{
    /**
     * To translate command to command handler
     *
     * @param $object
     *
     * @return string
     * @throws CommandNotFoundException
     * @throws HandlerNotFoundException
     */
    public function translate($object);
}