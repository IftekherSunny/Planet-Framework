<?php

namespace Sun\Contracts\Support;

use Sun\Exception\BindingException;
use Sun\Exception\MethodNotFoundException;

interface Alien
{
    /**
     * To execute method
     *
     * @param string $namespace
     *
     * @return mixed
     * @throws BindingException
     * @throws MethodNotFoundException
     * @throws \DI\NotFoundException
     */
    public static function execute($namespace);
}