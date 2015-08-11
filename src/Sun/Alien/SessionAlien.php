<?php

namespace Sun\Alien;

use Sun\Alien as BaseAlien;

class SessionAlien extends BaseAlien
{
    /**
     * To register Alien
     *
     * @return string namespace
     */
    public static function registerAlien()
    {
        return 'Sun\Session\Session';
    }
}
