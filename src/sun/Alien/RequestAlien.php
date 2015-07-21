<?php

namespace Sun\Alien;

use Sun\Http\Request;
use Sun\Alien as SunAlien;
use Sun\Session;

class RequestAlien extends SunAlien
{
    /**
     * To register Alien
     *
     * @return object
     */
    public static function registerAlien()
    {
        return new Request(new Session());
    }
}
