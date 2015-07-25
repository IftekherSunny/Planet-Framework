<?php

namespace Sun\Alien;

use Sun\Alien as SunAlien;

class RequestAlien extends SunAlien
{
    /**
     * To register Alien
     *
     * @return object
     */
    public static function registerAlien()
    {
        return 'Sun\Http\Request';
    }
}
