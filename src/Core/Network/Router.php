<?php
namespace App\Core\Network;

use App\Core\Controller\AbstractController as Controller;
use App\Core\Error\ErrorHandler;
use App\Core\Exception\NotFoundException;
use Throwable;
/**
 * Handles the HTTP requests.
 * @property Controller   $controller The controller that will handle the request.
 * @property string|null  $method     The method that will be called.
 * @property array        $params     The array of params that will be sent to the called method.
 * @property Request      $request
 * @property Response     $response
 * @property ErrorHandler $errorHandler
 */
class Router
{
    private Controller $controller;
    private string $method;
    private array $params = [];
    public Request $request;
    public Response $response;
    public ErrorHandler $errorHandler;

    /**
     * @param Request $request   The Request instance.
     * @param Response $response The Response instance.
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->errorHandler = new ErrorHandler;
        try {
            $this->__run();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            $this->errorHandler->handleError($e);
        }
    }

    /**
     * Creates a string from the url options to be used in redirects or as a link.
     * $url positions format:
     * 'path' => controller and method(must correspond to the path in routes)
     * 'params' => parameters sent to the controller's method
     * '?' => query parameters
     * @param array $url Url options.
     * @param bool $full (optional) True if the link should be full(including hostname), false otherwise.
     * @return string
     */
    public static function url(array $url, bool $full = false): string
    {
        $link = [];
        $link[] = $full !== false ? HOST : null;
        $link[] = $url['path'];
        if (!empty($url['params'])) {
            foreach ($url['params'] as $param) {
                $link[] = $param;
            }
        }
        $link = implode('/', $link);
        if (!empty($url['?'])) {
            $link .= '?';
            foreach ($url['?'] as $key => $value) {
                $link .= "$key=$value&";
            }
            $link = substr($link, 0, -1);
        }
        return $link;
    }

    /**
     * Actually 'runs' the app after all the logic is applied.
     * @return void
     * @throws NotFoundException
     */
    private function __run(): void
    {
        $this->__resolve();
        if (!isset($this->controller) || !isset($this->method)) {
            throw new NotFoundException;
        } else {
            call_user_func_array([$this->controller, $this->method], $this->params);
        }
    }

    /**
     * Clears the Request URL by assigning any params sent and removing them,
     * leaving only the controller and method.
     * Resolves the http request, sets and instantiates the controller and the respective action.
     * @return void
     */
    private function __resolve(): void
    {
        if ($this->request->url !== Request::ROOT) {
            $url = explode(Request::ROOT, $this->request->url);
            $controller = '\App\Controller\\' . ucwords($url[0]) . 'Controller';
            if ($this->__setController($controller)) {
                unset($url[0]);
                if (isset($url[1])) {
                    if ($this->__setMethod($url[1])) {
                        unset($url[1]);
                        $this->__setParams($url);
                    }
                }
            }
        } else {
            $this->__setController(DEFAULT_CONTROLLER);
            $this->__setMethod(DEFAULT_ACTION);
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
