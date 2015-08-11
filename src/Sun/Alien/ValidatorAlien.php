<?php

namespace Sun\Alien;

use Sun\Support\Alien;

class ValidatorAlien extends Alien
{
    /**
     * To register Alien
     *
     * @return string namespace
     */
    public static function registerAlien()
    {
        return 'Sun\Validation\Validator';
    }
}
