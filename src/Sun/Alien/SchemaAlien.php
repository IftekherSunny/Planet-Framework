<?php

namespace Sun\Alien;

use Sun\Support\Alien;

class SchemaAlien extends Alien
{
    /**
     * To register Alien
     *
     * @return string namespace
     */
    public static function registerAlien()
    {
        return 'schema';
    }
}
