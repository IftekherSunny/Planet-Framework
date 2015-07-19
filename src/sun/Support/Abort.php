<?php

namespace sun\Support;

use Sun\Support\Helper;

class Abort
{
    public static function message($message)
    {
        Helper::output($message);
    }
}