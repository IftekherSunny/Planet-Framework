<?php

namespace Sun\Alien;

use Sun\Validation\Validator;
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
        return new Validator();
    }
}
