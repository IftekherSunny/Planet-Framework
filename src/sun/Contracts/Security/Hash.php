<?php

namespace Sun\Contracts\Security;

interface Hash
{
    /**
     * To make password hash
     *
     * @param $value
     *
     * @return bool|string
     */
    public function make($value);

    /**
     * To verify password
     *
     * @param $password
     * @param $hash
     *
     * @return bool
     */
    public function verify($password, $hash);
}