<?php

namespace App\Core\View;

use App\Core\Helper\Form;

/**
 * @property Form $Form;
 */
class Render
{

    private Form $Form;

    public function __construct()
    {
        $this->Form = new Form;
    }

    /**
     * @param string|null $folder
     * @param string $view
     * @param array $viewVariables
     * @param string|null $layout
     * @return false|string|string[]
     */
    public function render(?string $folder, string $view, array $viewVariables = [], ?string $layout = null)
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
     * @param string|null $folder
     * @param string $view
     * @param array $viewVariables
     * @return false|string
     */
    private function  __setView(?string $folder, string $view, array $viewVariables = [])
    {
        if (!empty($viewVariables)) {
            foreach ($viewVariables as $key => $value) {
                $$key = $value;
            }
        }
        ob_start();
        if ($folder !== null) {
            require_once TEMPLATES . "$folder" . DIRECTORY_SEPARATOR . "$view.php";
        } else {
            require_once TEMPLATES . "$view.php";
        }
        return ob_get_clean();
    }
}