<?php

namespace Sun\View;

use Sun\Alien;

class ViewAlien extends Alien
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
