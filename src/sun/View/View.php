<?php

namespace Sun\View;

use Twig_Environment;
use Twig_Error_Loader;
use Twig_Loader_Filesystem;

class View
{
    /**
     * To render view
     *
     * @param       $view
     * @param array $data
     *
     * @return string
     * @throws Exception
     */
    public function render($view, array $data = [])
    {

        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../../../../../../app/Views');
        $twig = new Twig_Environment($loader, array(
            'cache' => __DIR__ . '/../../../../../../storage/framework/views',
            'auto_reload' => true
        ));
        try {
            $template = $twig->loadTemplate($view);
        } catch (Twig_Error_Loader $e) {
            throw new Exception($e->getMessage());
        }

        return $template->render($data);
    }
}
