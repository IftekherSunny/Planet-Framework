<?php

namespace Sun\Alien;

use Sun\Alien as SunAlien;

class ViewAlien extends SunAlien
{
    /**
     * To register Alien
     *
     * @return string namespace
     */
    public static function registerAlien()
    {
        return 'Sun\View\View';
    }
}
