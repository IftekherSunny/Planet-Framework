<?php

namespace Sun\View;

use duncan3dc\Laravel\BladeInstance;
use Sun\Contracts\View\View as ViewContract;

class View implements ViewContract
{
    /**
     * To render view
     *
     * @param       $view
     * @param array $data
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function render($view, array $data = [])
    {
        $blade = new BladeInstance(
                    app_path(). '/Views',
                    storage_path() . '/framework/views'
                );

        return $blade->render($view, $data);
    }
}
