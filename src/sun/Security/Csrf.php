<?php

namespace Sun\Security;

use Sun\Contracts\Session\Session;
use Sun\Contracts\Security\Csrf as CsrfContract;

class Csrf implements CsrfContract
{
    /**
     * To store session data
     *
     * @var \Sun\Session
     */
    protected $session;

    /**
     * Create a new csrf instance
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
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