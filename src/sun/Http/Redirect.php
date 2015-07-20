<?php

namespace Sun\Http;

use Session;

class Redirect
{
    /**
     * To redirect
     *
     * @param       $url
     * @param array $values
     */
    public function to($url, array $values = [])
    {
        if (count($values)) {
            foreach ($values as $key => $value) {
                $this->with($key, $value);
            }
        }

        header('location: ' . $url);
    }

    /**
     * To store data in a session
     *
     * @param $key
     * @param $value
     */
    public function with($key, $value)
    {
        Session::create($key, $value);
    }

    public function __destruct()
    {
        die();
    }
}
