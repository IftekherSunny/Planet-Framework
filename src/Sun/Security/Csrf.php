<?php

namespace Sun\Security;

use Sun\Contracts\Http\Request;
use Sun\Contracts\Session\Session;
use Sun\Contracts\Security\Csrf as CsrfContract;

class Csrf implements CsrfContract
{
    /**
     * To get http request data
     *
     * @var \Sun\Contracts\Http\Request
     */
    protected $request;

    /**
     * To store session data
     *
     * @var \Sun\Contracts\Session\Session
     */
    protected $session;

    /**
     * Create a new csrf instance
     *
     * @param \Sun\Contracts\Http\Request $request
     * @param \Sun\Contracts\Session\Session $session
     */
    public function __construct(Request $request, Session $session)
    {
        $this->request = $request;
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
     * @return bool
     */
    public function check()
    {
        if($this->session->get('token') === $this->request->input('token') || $this->session->get('token') === $this->request->header('X-CSRF-TOKEN')) {
            return true;
        }

        return false;
    }
}