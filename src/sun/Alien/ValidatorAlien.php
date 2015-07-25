<?php

namespace Sun\Alien;

use Sun\Alien as SunAlien;

class ValidatorAlien extends SunAlien
{
    /**
     * To register Alien
     *
     * @return object
     */
    public static function registerAlien()
    {
        return 'Sun\Validation\Validator';
    }
}
