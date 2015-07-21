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

    public function back()
    {
        $this->to($_SERVER['REQUEST_URI']);
    }

    public function backWith($key, $value)
    {
       $this->with($key, $value);

       $this->to($_SERVER['REQUEST_URI']);
    }

    public function __destruct()
    {
        die();
    }
}
