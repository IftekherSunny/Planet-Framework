<?php

namespace Sun\Contracts\Console;

interface Application
{
    /**
     * Resolve all dependencies
     */
    public function bootCommand();
}