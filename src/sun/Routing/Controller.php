<?php

namespace Sun\Routing;

use Sun\Http\Redirect;
use Sun\Http\Response;

class Controller
{
    /**
     * @var \Sun\Http\Redirect
     */
    protected $redirect;

    /**
     * @var \Sun\Http\Response
     */
    protected $response;

    /**
     * @param Redirect $redirect
     * @param Response $response
     */
    public function __construct(Redirect $redirect, Response $response)
    {
        $this->redirect = $redirect;

        $this->response = $response;
    }
}
