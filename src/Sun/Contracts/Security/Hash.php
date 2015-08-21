<?php

namespace Sun\Contracts\Security;

interface Hash
{
    /**
     * To make password hash
     *
     * @param string $value
     *
     * @return bool|string
     */
    public function make($value);

    /**
     * To verify password
     *
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public function verify($password, $hash);
}