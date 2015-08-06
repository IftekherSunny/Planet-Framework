<?php
namespace Sun\Contracts\Security;

interface Csrf
{
    /**
     * To generate token
     *
     * @return string
     */
    public function token();

    /**
     * To check token
     *
     * @param $token
     *
     * @return bool
     */
    public function check($token);
}