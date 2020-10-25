<?php
namespace App\Core;

use App\Core\Controller\AbstractController as Controller;
use App\Core\Error\ErrorHandler;
use App\Core\Network\Request;
use App\Core\Network\Response;

class Router
{
    private Controller $controller;
    private ?string $method = null;
    private array $params = [];
    public Request $request;
    public Response $response;
    public ErrorHandler $errorHandler;

    /**
     * Router constructor.
     *
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->errorHandler = new ErrorHandler;
        $this->__resolve();
    }

    /**
     * @return void
     */
    private function __resolve(): void
    {
        if ($this->__checkRequest()) {
            call_user_func_array([$this->controller, $this->method], $this->params);
        } else {
            $this->errorHandler->notFound();
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
     * @return bool
     */
    private function __checkRequest(): bool
    {
        if ($this->request->url !== Request::ROOT) {
            $url = explode(Request::ROOT, filter_var(rtrim($this->request->url, Request::ROOT), FILTER_SANITIZE_URL));
            $url[0] = '\App\Controller\\' . ucwords($url[0]) . 'Controller';
            if ($this->__setController($url[0])) {
                unset($url[0]);
                if (isset($url[1])) {
                    if ($this->__setMethod($url[1])) {
                        unset($url[1]);
                        $this->__setParams($url);
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return $this->__setMethod('index');
                }
            }
            return false;
        } else {
            $this->__setController('\App\Controller\\HomeController');
            $this->__setMethod('index');
            return true;
        }
    }

    /**
     * Set the controller.
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
     * Set the controller method.
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
     * Set the controller's params.
     * @param array $params
     * @return void
     */
    private function __setParams(array $params): void
    {
        $this->params = !empty($params) ? array_values($params) : [];
    }
}
