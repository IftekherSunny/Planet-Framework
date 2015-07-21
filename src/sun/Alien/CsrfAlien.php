<?php

namespace Sun\Alien;

use Sun\Security\Csrf;
use Sun\Alien as SunAlien;
use Sun\Session;

class CsrfAlien extends SunAlien
{
    /**
     * To register Alien
     *
     * @return object
     */
    public static function registerAlien()
    {
        return new Csrf(new Session());
    }
}
