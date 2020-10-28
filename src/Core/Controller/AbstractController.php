<?php
namespace App\Core\Controller;

use App\Core\Helper\LoggerHelper;
use App\Core\Network\Request;
use App\Core\Network\Response;
use App\Core\View\Render;
/**
 * @property Render $Render
 * @property Request $request
 * @property Response $response
 * @property LoggerHelper $log
 * The framework's main controller which will be extended by all the app's controllers.
 */
abstract class AbstractController
{
    private Render $Render;
    protected Request $request;
    protected Response $response;
    protected LoggerHelper $log;

    public function __construct()
    {
        $this->Render = new Render;
        $this->request = new Request;
        $this->response = new Response;
        $this->log = new LoggerHelper;
    }

    /**
     * Renders a view.
     * @param string|null $folder  The folder where the view is located.
     * @param string $view         The name of the view.
     * @param array $viewVariables The variables that can be used in the view.
     * @param string|null $layout  The name of the layout.
     * @return void
     */
    protected function render(?string $folder, string $view, array $viewVariables = [], ?string $layout = null): void
    {
        $this->Render->render($folder ,$view, $viewVariables, $layout);
    }

    /**
     * Redirect to a location.
     * @param array $url Url options.
     * @param bool $full (optional) True if the link should be full(including hostname), false otherwise.
     * @return void
     */
    protected function redirect(array $url, $full = false): void
    {
        $this->response->redirect($url, $full);
    }
}
