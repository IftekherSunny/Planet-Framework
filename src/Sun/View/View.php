<?php

namespace Sun\View;

use Sun\Contracts\Session\Session;
use duncan3dc\Laravel\BladeInstance;
use Sun\Contracts\View\View as ViewContract;

class View implements ViewContract
{
    /**
     * @var \duncan3dc\Laravel\BladeInstance
     */
    protected $blade;

    /**
     * @var \Sun\Contracts\Session\Session
     */
    protected $session;

    /**
     * Create a new view instance
     *
     * @param \Sun\Contracts\Session\Session $session
     */
    public function __construct(Session $session)
    {
        $this->blade = new BladeInstance(
            app_path(). '/Views',
            storage_path() . '/framework/views'
        );

        $this->session = $session;
    }

    /**
     * To render view
     *
     * @param string $view
     * @param array $data
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function render($view, array $data = [])
    {
        return $this->blade->share('errors', $this->session->pull('errors'))->render($view, $data);
    }
}
