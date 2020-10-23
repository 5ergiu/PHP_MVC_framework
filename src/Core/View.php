<?php

namespace App\Core;

class View
{

    /**
     * @param string $folder
     * @param string $view
     * @param array $viewVariables
     * @param string|null $layout
     * @return false|string|string[]
     */
    public function render(string $folder, string $view, array $viewVariables = [], ?string $layout = null)
    {
        return str_replace(
            '{{ content }}',
            $this->__setView($folder, $view, $viewVariables),
            $this->__setLayout($layout)
        );
    }

    /**
     * @param string|null $layout
     * @return false|string
     */
    private function __setLayout(?string $layout = null)
    {
        ob_start();
        if ($layout !== null) {
            require_once LAYOUTS . "$layout.php";
        } else {
            require_once LAYOUTS . 'base.php';
        }
        return ob_get_clean();
    }

    /**
     * @param string $folder
     * @param string $view
     * @param array $viewVariables
     * @return false|string
     */
    private function  __setView(string $folder, string $view, array $viewVariables)
    {
        foreach ($viewVariables as $key => $value) {
            $$key = $value;
        }
        ob_start();
        require_once TEMPLATES . "$folder/$view.php";
        return ob_get_clean();
    }
}