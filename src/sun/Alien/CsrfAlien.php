<?php

namespace Sun\Alien;

use Sun\Security\Csrf;
use Sun\Alien as SunAlien;

class CsrfAlien extends SunAlien
{
    /**
     * To register Alien
     *
     * @return object
     */
    public static function registerAlien()
    {
        return new Csrf();
    }
}
