<?php
namespace App\Controller;

use App\Component\AuthComponent;
use App\Helper\LoggerHelper;
use App\Core\Network\Request;
use App\Core\Network\Response;
use App\Core\Renderer;
/**
 * @property Request $request
 * @property Response $response
 * @property Renderer $renderer
 * @property LoggerHelper $log
 * @property AuthComponent $auth
 * The framework's main controller which will be extended by all the app's controllers.
 */
abstract class AbstractController
{

    protected Request $request;
    protected Response $response;
    private Renderer $renderer;
    protected LoggerHelper $log;
    protected AuthComponent $auth;

    public function __construct()
    {
        $this->request = new Request;
        $this->response = new Response;
        $this->renderer = new Renderer($this->request);
        $this->log = new LoggerHelper;
        $this->auth = new AuthComponent;
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
        $this->renderer->render($view, $viewVariables, $layout);
    }

    /**
     * Echo a json response.
     * @param mixed $response      The result to be returned to js.
     * @param array $errors
     * @return void
     */
    protected function newJsonResponse($response, array $errors = []): void
    {
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        echo json_encode($response, JSON_PRETTY_PRINT);
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

    /**
     * Creates a new property of a component instance on the current controller instance.
     * @param string $component
     * @return void
     */
    protected function loadComponent(string $component): void
    {
        $component = ucwords($component);
        $componentClass = 'App\Component\\' . $component;
        $this->{$component} = new $componentClass;
    }
}
