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
    private ?string $method = null;
    private array $params = [];
    public Request $request;
    public Response $response;
    public ErrorHandler $errorHandler;

    /**
     * @param array $routes      The array of predefined routes.
     * @param Request $request   The Request instance.
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
     * @return void
     * @throws NotFoundException
     */
    private function __resolve(): void
    {
        if ($this->request->url !== Request::ROOT) {
            $url = explode(Request::ROOT, $this->request->url);
            $url[0] = '\App\Controller\\' . ucwords($url[0]) . 'Controller';
            if ($this->__setController($url[0])) {
                unset($url[0]);
                if (isset($url[1])) {
                    if ($this->__setMethod($url[1])) {
                        unset($url[1]);
                        $this->__setParams($url);
                    }
                } else {
                    $this->__setMethod('index');
                }
            }
        } else {
            $this->__setController('\App\Controller\\HomeController');
            $this->__setMethod('index');
        }
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Set the controller.
     * @param string $controller
     * @return bool
     * @throws NotFoundException
     */
    private function __setController(string $controller): bool
    {
        if (class_exists($controller)) {
            $this->controller = new $controller;
            return true;
        }
        throw new NotFoundException;
    }

    /**
     * Set the controller method.
     * @param string $method
     * @return bool
     * @throws NotFoundException
     */
    private function __setMethod(string $method): bool
    {
        if (method_exists($this->controller, $method)) {
            $this->method = $method;
            return true;
        }
        throw new NotFoundException;
    }

    /**
     * Set the controller's params.
     * @param array $params
     * @return void
     */
    private function __setParams(array $params): void
    {
        $this->params = !empty($params) ? array_values($params) : [];
    }
}
