<?php

namespace Sun\Security;

use Sun\Contracts\Session\Session;
use Sun\Contracts\Security\Csrf as CsrfContract;

class Csrf implements CsrfContract
{
    /**
     * To store session data
     *
     * @var \Sun\Contracts\Session\Session
     */
    protected $session;

    /**
     * Create a new csrf instance
     *
     * @param \Sun\Contracts\Session\Session $session
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
        if(!$this->session->has('token')) {
            $token = base64_encode(openssl_random_pseudo_bytes(32));
            $this->session->create('token', $token);
        }

        return $this->session->get('token');
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