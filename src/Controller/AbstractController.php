<?php
namespace App\Controller;

use App\Component\Auth;
use App\Component\Session;
use App\Core\Exception\MethodNotAllowedException;
use App\Core\Network\Router;
use App\Core\View;
use App\Component\Log;
use App\Core\Network\Request;
use App\Core\Network\Response;
use App\Helper\MarkdownHelper;
use JetBrains\PhpStorm\NoReturn;

/**
 * @property Session $session
 * @property Request $request
 * @property array $referer
 * @property Response $response
 * @property Auth $auth
 * @property MarkdownHelper $markdown
 * @property Log $log
 * The framework's main controller which will be extended by all the app's controllers.
 */
abstract class AbstractController
{
    public Request $request;
    protected Session $session;
    protected array $referer;
    protected Auth $auth;
    protected MarkdownHelper $markdown;
    protected Log $log;


    public function __construct()
    {
        $this->session = new Session;
        $this->auth = new Auth($this->session);
        $this->markdown = new MarkdownHelper;
        $this->log = new Log;
        $this->referer = $this->__buildReferer();
        $this->response = new Response;
    }

    /**
     * Creates a view that will be rendered.
     * @param string $viewPath    The path of the view.
     * @param array $variables    The variables that will be used in the view.
     * @param string|null $layout The name of the layout.
     * @return void
     */
    #[NoReturn]
    protected function render(string $viewPath, array $variables = [], ?string $layout = null): void
    {
        $requestData = $this->request->data;
        $authenticatedUser = $this->auth->user();
        $notification = $this->__getNotification();
        $View = new View($requestData, $authenticatedUser, $notification, $viewPath, $variables, $layout);
        $this->response->body($View->geView());
        $this->response->send();
    }

    /**
     * Echo a json response.
     * @param mixed $response The controller's response.
     * @param array $errors
     * @return void
     */
    #[NoReturn]
    protected function newJsonResponse(mixed $response, array $errors = []): void
    {
        $result['response'] = $response;
        if (!empty($errors)) {
            $result['errors'] = $errors;
        }
        $result = json_encode($result, JSON_PRETTY_PRINT);
        $this->response->contentType('json');
        $this->response->body($result);
        $this->response->send();
    }

    /**
     * Checks the allowed methods for a controller action.
     * @param array $methods Array of allowed methods.
     * @return bool
     * @throws MethodNotAllowedException
     */
    protected function methodsAllowed(array $methods): bool
    {
        foreach ($methods as $key => $value) {
            $methods[$key] = strtoupper($value);
        }
        if (!in_array($this->request->method, $methods)) {
            throw new MethodNotAllowedException;
        } else {
            return true;
        }
    }

    /**
     * Redirect to a location.
     * @param array $url Url options.
     * @return void
     */
    #[NoReturn]
    protected function redirect(array $url): void
    {
        $this->response->location(Router::url($url, true));
        $this->response->send();
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
        $this->{$repo}->userId = $this->auth->user('id');
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

    /**
     * TODO: add more security to this
     * TODO: ERROR - when on register, if you log in, the referer isn't properly built, resulting in: 'auth/auth/register'
     * Builds the referer.
     * @return array
     */
    private function __buildReferer(): array
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
            if ($path !== Request::ROOT) {
                $path = ltrim($path, '/');
                $path = explode('/', $path);
                $url['path'] = "$path[0]/$path[1]";
                unset($path[0], $path[1]);
                if (!empty($path)) {
                    $url['params'] = array_values($path);
                }
            } else {
                $url['path'] = Request::ROOT;
            }
            $queryParams = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
            if (!empty($queryParams)) {
                $url['?'] = $queryParams;
            }
        } else {
            $url['path'] = Request::ROOT;
        }
        return $url;
    }

    /**
     * Sets the notification message in the session to be used in the view.
     * @param string $icon
     * @param string $message
     * @return void
     */
    protected function notify(string $icon, string $message): void
    {
        $this->session->write('icon', $icon);
        $this->session->write('message', $message);
    }

    /**
     * Returns an array with the notification options.
     * @return array
     */
    private function __getNotification(): array
    {
        $notification = [];
        if (!empty($this->session->get('icon'))) {
            $notification['icon'] = $this->session->get('icon');
            $this->session->delete('icon');
        }
        if (!empty($this->session->get('message'))) {
            $notification['message'] = $this->session->get('message');
            $this->session->delete('message');
        }
        return $notification;
    }
}
