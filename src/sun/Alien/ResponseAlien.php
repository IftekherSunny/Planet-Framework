<?php

namespace Sun\Alien;

use Sun\Http\Response;
use Sun\Alien as SunAlien;
use Sun\Session;

class ResponseAlien extends SunAlien
{
    /**
     * To register Alien
     *
     * @return object
     */
    public static function registerAlien()
    {
        return new Response(new Session());
    }
}
