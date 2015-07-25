<?php

namespace Sun\Alien;

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
        return 'Sun\Security\Csrf';
    }
}
