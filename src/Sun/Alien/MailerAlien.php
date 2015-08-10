<?php

namespace Sun\Alien;

use Sun\Alien as SunAlien;

class MailerAlien extends SunAlien
{
    /**
     * To register Alien
     *
     * @return string namespace
     */
    public static function registerAlien()
    {
        return 'Sun\Mail\Mailer';
    }
}
