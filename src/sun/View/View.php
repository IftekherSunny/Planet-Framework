<?php

namespace Sun\View;

use duncan3dc\Laravel\BladeInstance;

class View
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
            __DIR__.'/../../../../../../app/Views',
            __DIR__.'/../../../../../../storage/framework/views');

        return $blade->render($view, $data);
    }
}
