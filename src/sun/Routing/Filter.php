<?php

namespace Sun\Routing;

use Sun\Contracts\Routing\Filter as FilterContract;

abstract class Filter implements FilterContract
{
    /**
     * To handle request
     */
    abstract public function handle();

}
