<?php

namespace Sun\Contracts\Http;

interface Redirect
{
    /**
     * To redirect
     *
     * @param string $url
     * @param array $values
     */
    public function to($url, array $values = []);

    /**
     * To store data in a session
     *
     * @param string $key
     * @param mixed $value
     *
     * @return \Sun\Http\Redirect
     */
    public function with($key, $value);

    /**
     * To redirect back
     */
    public function back();

    /**
     * To redirect back with value
     *
     * @param string $key
     * @param mixed $value
     */
    public function backWith($key, $value);
}