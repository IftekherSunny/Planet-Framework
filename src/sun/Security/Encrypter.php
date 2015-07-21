<?php

namespace Sun\Security;

class Encrypter
{
    /**
     * To encrypt password
     *
     * @param $value
     *
     * @return bool|string
     */
    public function encrypt($value)
    {
       return password_hash(trim($value), PASSWORD_BCRYPT);
    }

    /**
     * To decrypt password
     *
     * @param $password
     * @param $hash
     *
     * @return bool
     */
    public function decrypt($password, $hash)
    {
        return password_verify(trim($password), $hash);
    }

    /**
     * To make password hash
     *
     * @param $value
     *
     * @return bool|string
     */
    public function make($value)
    {
        return $this->encrypt($value);
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
        return $this->decrypt($password, $hash);
    }
}