<?php
namespace App\Core;

use App\Controller\AbstractController as Controller;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class Router
 * @property Request $request        The Request instance.
 * @property RouteCollection $routes The routes collection.
 * @property RequestContext $context The RequestContext instance.
 * @property Controller $controller  The Controller instance.
 */
class Router {

    private RequestContext $context;
    private Controller $controller;

    /**
     * Router constructor.
     * @param Request $request The Request instance.
     */
    public function __construct(
        private Request $request
    ) {
        $this->routes = $this->__loadRoutes();
        $this->context = (new RequestContext)->fromRequest($this->request);
    }

    /**
     * Load routes from the yaml file.
     * @return RouteCollection A RouteCollection instance.
     */
    private function __loadRoutes(): RouteCollection
    {
        $fileLocator = new FileLocator([CONFIG]);
        $loader = new YamlFileLoader($fileLocator);
        return $loader->load('routes.yaml');
    }

    /**
     * Resolves the current route and calls the controller + action.
     * @return Response
     */
    public function resolve(): Response
    {
        try {
            $matcher = new UrlMatcher($this->routes, $this->context);
            $parameters = $matcher->match($this->context->getPathInfo());
            $controller = strtok($parameters['controller'], '::');
            $action = substr(strstr($parameters['controller'], '::'), 2);
            unset($parameters['_route'], $parameters['controller']);
            $this->controller = new $controller;
            $this->controller->request = $this->request;
            return call_user_func_array([$this->controller, $action], $parameters);
        } catch (ResourceNotFoundException $e) {
            echo $e->getMessage(); die;
        }
    }
}
