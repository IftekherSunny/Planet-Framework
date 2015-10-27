<?php

namespace Sun\Support;

class Str
{
    /**
     * To generate random string
     *
     * @param int $size
     *
     * @return string
     */
    public static function random($size = 32)
    {
        $bytes = openssl_random_pseudo_bytes($size, $strong);

        if ($bytes !== false && $strong !== false) {
            $string = '';
            while (($len = strlen($string)) < $size) {
                $length = $size - $len;

                $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $length);
            }

            return $string;
        }
    }
}