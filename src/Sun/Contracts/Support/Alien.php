<?php

namespace Sun\contracts\Support;

use Sun\Exception\BindingException;
use Sun\Exception\MethodNotFoundException;

interface Alien
{
    /**
     * To execute method
     *
     * @param $namespace
     *
     * @return mixed
     * @throws BindingException
     * @throws MethodNotFoundException
     * @throws \DI\NotFoundException
     */
    public static function execute($namespace);
}