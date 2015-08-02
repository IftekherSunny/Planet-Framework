<?php

namespace Sun\Security;

class Hash
{
    /**
     * To make password hash
     *
     * @param $value
     *
     * @return bool|string
     */
    public function make($value)
    {
       return password_hash(trim($value), PASSWORD_BCRYPT);
    }

    /**
     * To verify password
     *
     * @param $password
     * @param $hash
     *
     * @return bool
     */
    public function verify($password, $hash)
    {
        return password_verify(trim($password), $hash);
    }
}