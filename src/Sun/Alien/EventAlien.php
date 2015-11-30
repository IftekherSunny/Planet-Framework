<?php

namespace Sun\Alien;

use Sun\Support\Alien;

class EventAlien extends Alien
{
    /**
     * To register Alien
     *
     * @return string namespace
     */
    public static function registerAlien()
    {
        return 'Sun\Contracts\Event\Event';
    }
}
