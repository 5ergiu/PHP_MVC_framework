<?php
namespace App\Controller;

use App\Helper\LoggerHelper;
use App\Core\Network\Request;
use App\Core\Network\Response;
use App\Core\Render;
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
     * @param string $view         The path of the view.
     * @param array $viewVariables The variables that can be used in the view.
     * @param string|null $layout  The name of the layout.
     * @return void
     */
    protected function render(string $view, array $viewVariables = [], ?string $layout = null): void
    {
        $this->Render->render($view, $viewVariables, $layout);
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

    /**
     * Creates a new property of a repository instance on the current controller instance.
     * @param string $repo
     * @return void
     */
    protected function loadRepo(string $repo): void
    {
        $repo = ucwords($repo) . 'Repo';
        $repoClass = 'App\Repository\\' . $repo;
        $this->{$repo} = new $repoClass;
    }
}
