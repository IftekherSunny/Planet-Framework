<?php

namespace Sun\Alien;

use Sun\Support\Alien;

class DBAlien extends Alien
{
    /**
     * To register Alien
     *
     * @return string namespace
     */
    public static function registerAlien()
    {
        return 'Illuminate\Database\Capsule\Manager';
    }
}
