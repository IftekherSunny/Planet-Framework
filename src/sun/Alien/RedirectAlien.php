<?php

namespace Sun\Alien;

use Sun\Http\Redirect;
use Sun\Alien as SunAlien;

class RedirectAlien extends SunAlien
{
    /**
     * To register Alien
     *
     * @return object
     */
    public static function registerAlien()
    {
        return new Redirect();
    }
}
