<?php

namespace Sun\Alien;

use Sun\Alien as SunAlien;

class HashAlien extends SunAlien
{
    /**
     * To register Alien
     *
     * @return string namespace
     */
    public static function registerAlien()
    {
        return 'Sun\Security\Hash';
    }
}
