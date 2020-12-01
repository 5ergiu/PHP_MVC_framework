<?php
namespace App\Core;

use App\Component\Auth;
use App\Core\Network\Request;
use App\Helper\FormHelper;
use JetBrains\PhpStorm\NoReturn;

/**
 * @property FormHelper $form
 * @property array|null $user    Logged user or null.
 * @property array $notification An array with the notification options.
 * @property string $folder      Folder where the view file is located.
 * @property string $file        View file.
 * @property array $variables    The variables that will be used in the view.
 * @property string|null $layout Layout file.
 * @property string $body        Response body.
 * @property array $css          Css files to be included.
 * @property array $javascript   Javascript files to be included.
 * Renders a complete view with data from the controllers and all required helpers for the view.
 */
class View
{
    private FormHelper $form;
    private string $folder;
    private string $file;
    private string $body;
    private array $css = [];
    private array $javascript = [];

    public function __construct(
        private array $requestData,
        private ?array $user,
        private array $notification,
        private string $viewPath,
        private array $variables,
        private ?string $layout
    ) {
        $this->form = new FormHelper($requestData);
        $this->folder = substr($viewPath, 0, strpos($viewPath, '/'));
        $this->file = substr($viewPath, strpos($viewPath, '/') + 1);
        $this->__setBody();
    }

    /**
     * Sets the body that will be rendered.
     * @return void
     */
    private function __setBody(): void
    {
        $this->body = str_replace(
            '{{ content }}',
            $this->__setView(),
            $this->__setLayout()
        );
    }

    /**
     * Returns the formatted view.
     * @return string
     */
    public function geView(): string
    {
        return $this->body;
    }

    /**
     * Sets the layout.
     * @return false|string
     */
    private function __setLayout(): bool|string
    {
        $fullPath = LAYOUTS;
        $fullPath .= $this->layout !== null ? "{$this->layout}.php" : DEFAULT_LAYOUT;
        ob_start();
        require_once $fullPath;
        return ob_get_clean();
    }

    /**
     * Sets the view and view variables.
     * @return false|string
     */
    private function __setView(): bool|string
    {
        $fullPath = TEMPLATES;
        if (!empty($this->variables)) {
            foreach ($this->variables as $key => $value) {
                $$key = $value;
            }
        }
        $fullPath .= str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $this->folder . "/{$this->file}.php");
        ob_start();
        require_once $fullPath;
        return ob_get_clean();
    }

    /**
     * Sets the css files.
     * @param array $css
     * @return void
     */
    private function __addCss(array $css): void
    {
        if (!empty($css['css'])) {
            foreach ($css['css'] as $style) {
                $this->css[] = "$style.css";
            }
        }
    }

    /**
     * Renders an element.
     * Will NOT use reference variable here so they don't interfere with the ones from the actual view.
     * All data will be accessed from the '$data' array inside any element.
     * @param string $element  The name of the element.
     * @param array $data      The variables for the element.
     * @param string|null $css Custom css files to include in the head.
     * @return void
     */
    public function element(string $element, array $data = [], string $css = null): void
    {
        if (!empty($css)) {
            $this->css[] = "$css.css";
        }
        require ELEMENTS . "$element.php";
    }

    /**
     * Adds css to the page.
     * @param array $styles Css files to be included.
     * @return void
     */
    public function includeCss(array $styles): void
    {
        foreach ($styles as $css) {
            $this->css[] = "$css.css";
        }
    }

    /**
     * Adds javascript to the page.
     * @param array $scripts Javascript files to be included.
     * @return void
     */
    public function includeJs(array $scripts): void
    {
        foreach ($scripts as $script) {
            $this->javascript[] = "$script.css";
        }
    }
}
