<?php

namespace Sun\Security;

use Sun\Session;

class Csrf
{
    /**
     * To store session data
     *
     * @var \Sun\Session
     */
    protected $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * To generate token
     *
     * @return string
     */
    public function token()
    {
        $token = base64_encode(openssl_random_pseudo_bytes(32));
        $this->session->create('token', $token);

        return $token;
    }

    /**
     * To check token
     *
     * @param $token
     *
     * @return bool
     */
    public function check($token)
    {
        if($this->session->has('token') && ($this->session->get('token') === $token)) {

            return true;
        }

        return false;
    }
}