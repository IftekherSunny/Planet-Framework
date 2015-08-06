<?php

namespace Sun\Contracts\Routing;

interface Filter
{
    /**
     * To handle request
     */
    public function handle();
}