<?php

namespace Sun\Alien;

use Sun\View\View;
use Sun\Alien as SunAlien;

class ViewAlien extends SunAlien
{
    /**
     * To register Alien
     *
     * @return object
     */
    public static function registerAlien()
    {
        return new View();
    }
}
