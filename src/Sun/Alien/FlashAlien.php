<?php

namespace Sun\Alien;

use Sun\Alien as SunAlien;

class FlashAlien extends SunAlien
{
    /**
     * To register Alien
     *
     * @return string namespace
     */
    public static function registerAlien()
    {
        return 'Sun\Flash\Flash';
    }
}
