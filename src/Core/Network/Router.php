<?php
namespace App\Core\Network;

use App\Controller\AbstractController as Controller;
use App\Core\Error\ErrorHandler;
use App\Core\Exception\NotFoundException;
use Throwable;
/**
 * Handles the HTTP requests.
 * @property Controller   $controller The controller that will handle the request.
 * @property string|null  $method     The method that will be called.
 * @property array        $params     The array of params that will be sent to the called method.
 * @property Request      $request
 * @property ErrorHandler $errorHandler
 */
class Router
{
    private Controller $controller;
    private string $method;
    private array $params = [];
    public ErrorHandler $errorHandler;

    /**
     * @param Request $request
     * @throws NotFoundException
     */
    public function __construct(
        public Request $request
    ) {
        $this->errorHandler = new ErrorHandler;
        $this->__resolve();
    }

    /**
     * Creates a string from the url options to be used in redirects or as a link.
     * $url positions format:
     * - 'path' => controller and method(must correspond to the path in routes)
     * - 'params' => parameters sent to the controller's method
     * - '?' => query parameters
     * @param array $url Url options.
     * @param bool $full True if the link should be full(including hostname), false otherwise.
     * @return string
     */
    public static function url(array $url, bool $full = false): string
    {
        if ($url['path'] === Request::ROOT) {
            return Request::ROOT;
        }
        $link = [];
        $link[] = $full ? HOST : null;
        $link[] = $url['path'];
        if (!empty($url['params'])) {
            foreach ($url['params'] as $param) {
                $link[] = $param;
            }
        }
        $link = implode('/', $link);
        if (!empty($url['?'])) {
            $link .= '?';
            if (is_array($url['?'])) {
                foreach ($url['?'] as $key => $value) {
                    if ($key !== array_key_last($url['?'])) {
                        $link .= "$key=$value&";
                    } else {
                        $link .= "$key=$value";
                    }
                }
            } elseif (is_string($url['?'])) {
                $link .= $url['?'];
            }
        }
        if (!empty($url['ext'])) {
            $link .= "{$url['ext']}.json";
        }
        return $link;
    }

    /**
     * Clears the Request URL by assigning any params sent and removing them,
     * leaving only the controller and method.
     * Resolves the http request, sets and instantiates the controller and the respective action.
     * @return void
     * @throws NotFoundException
     */
    private function __resolve(): void
    {
        if (is_array($this->request->url)) {
            $url = $this->request->url;
            $url[0] = str_replace('_', '', ucwords($url[0], '_'));
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
        if (!isset($this->controller) || !isset($this->method)) {
            throw new NotFoundException;
        } else {
            try {
                call_user_func_array([$this->controller, $this->method], $this->params);
            } catch (Throwable $e) {
                $this->errorHandler->handleError($e);
            }
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
            $this->controller->request = $this->request;
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
        $method = str_replace('_', '', lcfirst(ucwords($method, '_')));
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
