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
     * @return void
     */
    public function render(?string $folder, string $view, array $viewVariables = [], ?string $layout = null): void
    {
        echo str_replace(
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
        $fullPath = LAYOUTS;
        $fullPath .= $layout !== null ? "$layout.php" : 'base.php';
        ob_start();
        require_once $fullPath;
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
        $fullPath = TEMPLATES;
        if (!empty($viewVariables)) {
            foreach ($viewVariables as $key => $value) {
                $$key = $value;
            }
        }
        $fullPath .= $folder !== null ? "$folder" : null;
        $fullPath .= "/$view.php";
        $fullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fullPath);
        ob_start();
        require_once $fullPath;
        return ob_get_clean();
    }
}