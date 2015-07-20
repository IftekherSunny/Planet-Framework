<?php

namespace Sun\Security;

use Sun\Session;

class Csrf
{
    protected $session;

    public function __construct()
    {
        $this->session = new Session();
    }
    public function token()
    {
        $token = base64_encode(password_hash(uniqid(), PASSWORD_BCRYPT));
        $this->session->create('token', $token);

        return $token;
    }

    public function check($token)
    {
        if($this->session->has('token') && ($this->session->get('token') === $token)) {

            return true;
        }

        return false;
    }
}