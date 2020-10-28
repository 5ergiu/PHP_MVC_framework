<?php
namespace App\Core\Network;

use App\Core\Controller\AbstractController as Controller;
use App\Core\Error\ErrorHandler;
use App\Core\Exception\NotFoundException;
use Throwable;
/**
 * Handles the HTTP requests.
 * @property array $routes          The array of predefined routes.
 * @property Controller $controller The controller that will handle the request.
 * @property string|null $method    The method that will be called.
 * @property array $params          The array of params that will be sent to the called method.
 * @property Request $request
 * @property Response $response
 * @property ErrorHandler $errorHandler
 */
class Router
{
    private array $routes;
    private Controller $controller;
    private string $method;
    private array $params = [];
    public Request $request;
    public Response $response;
    public ErrorHandler $errorHandler;

    /**
     * @param array $routes The array of predefined routes.
     * @param Request $request The Request instance.
     * @param Response $response The Response instance.
     */
    public function __construct(array $routes, Request $request, Response $response)
    {
        $this->routes = $routes;
        $this->request = $request;
        $this->response = $response;
        $this->errorHandler = new ErrorHandler;
        $this->__run();
    }

    /**
     * Actually 'runs' the app after all the logic is applied.
     * @return void
     */
    private function __run(): void
    {
        try {
            $this->__resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            $this->errorHandler->handleError($e);
        }
    }

    /**
     * @param array $url
     * @param bool $full
     * @return array
     */
    public static function url(array $url, bool $full = false): array
    {

        return $url;
    }

    /**
     * Resolves the http request, sets and instantiates the controller and calling respective method.
     * @return void
     * @throws NotFoundException
     */
    private function __resolve(): void
    {
        $this->__parseUrl();
        foreach ($this->routes as $name => $route) {
            if ($this->request->url === $route['path']) {
                if ($this->__setController($route['controller'])) {
                    if ($this->__setMethod($route['method'])) {
                        call_user_func_array([$this->controller, $this->method], $this->params);
                        break;
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
            } else {
                continue;
            }
        }
        if (!isset($this->controller) || !isset($this->method)) {
            throw new NotFoundException;
        }
    }

    /**
     * Clears the Request URL by assigning any params sent and removing them,
     * leaving only the controller and method.
     * @return void
     */
    private function __parseUrl(): void
    {
        if ($this->request->url !== Request::ROOT) {
            $url = explode(Request::ROOT, $this->request->url);
            $request['controller'] = $url[0];
            unset($url[0]);
            if (isset($url[1])) {
                $request['method'] = $url[1];
                unset($url[1]);
            }
            $this->request->url = implode('/', $request);
            $this->__setParams($url);
        }
    }

    /**
     * Sets the controller.
     * @param string $controller
     * @return bool
     */
    private function __setController(string $controller): bool
    {
        if (class_exists($controller)) {
            $this->controller = new $controller;
            return true;
        }
        return false;
    }

    /**
     * Sets the controller method.
     * @param string $method
     * @return bool
     */
    private function __setMethod(string $method): bool
    {
        if (method_exists($this->controller, $method)) {
            $this->method = $method;
            return true;
        }
        return false;
    }

    /**
     * Sets the controller's params.
     * @param array $params
     * @return void
     */
    private function __setParams(array $params): void
    {
        $this->params = !empty($params) ? array_values($params) : [];
    }
}
