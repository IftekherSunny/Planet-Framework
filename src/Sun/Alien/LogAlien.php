<?php

namespace Sun\Alien;

use Sun\Support\Alien;

class LogAlien extends Alien
{
    /**
     * To register Alien
     *
     * @return string namespace
     */
    public static function registerAlien()
    {
        return 'Sun\Contracts\Log\Log';
    }
}
