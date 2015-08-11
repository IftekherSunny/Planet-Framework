<?php

namespace Sun\Alien;

use Sun\Support\Alien;

class ViewAlien extends Alien
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
