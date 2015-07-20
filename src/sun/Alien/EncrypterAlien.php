<?php

namespace Sun\Alien;

use Sun\Security\Encrypter;
use Sun\Alien as SunAlien;

class EncrypterAlien extends SunAlien
{
    /**
     * To register Alien
     *
     * @return object
     */
    public static function registerAlien()
    {
        return new Encrypter();
    }
}
