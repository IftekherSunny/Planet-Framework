<?php

namespace Sun\Alien;

use Sun\Support\Alien;

class RequestAlien extends Alien
{
    /**
     * To register Alien
     *
     * @return string namespace
     */
    public static function registerAlien()
    {
        return 'Sun\Http\Request';
    }
}
