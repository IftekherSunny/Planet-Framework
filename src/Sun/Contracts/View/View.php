<?php

namespace Sun\Contracts\View;

use Sun\View\InvalidArgumentException;

interface View
{
    /**
     * To render view
     *
     * @param string $view
     * @param array $data
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function render($view, array $data = []);
}