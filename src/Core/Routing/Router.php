<?php
namespace App\Core\Routing;

use App\Controller\AbstractController as Controller;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Router as SymfonyRouter;

/**
 * Class Router
 * @property Request $request                  The Request instance.
 * @property AnnotationDirectoryLoader $loader The Loader instance used to load the routes from the controllers.
 */
class Router extends SymfonyRouter {

    /**
     * Router constructor.
     * @param Request $request  The Request instance.
     */
    public function __construct(
        public Request $request,
    ) {
        $this->loader = $this->__buildLoader();
        parent::__construct($this->loader, './');
    }

    /**
     * Builds the AnnotationDirectoryLoader instance to be used to load routes from controllers.
     * @return AnnotationDirectoryLoader An AnnotationDirectoryLoader instance.
     */
    private function __buildLoader(): AnnotationDirectoryLoader
    {
        $locator = new FileLocator(SRC . 'Controller');
        $annotationReader = new AnnotationReader();
        $classLoader = new AnnotatedRouteControllerLoader($annotationReader);
        return new AnnotationDirectoryLoader($locator, $classLoader);
    }

    /**
     * Resolves the current route and calls the controller + action.
     * @return Response
     */
    public function resolve(): Response
    {
        try {
            $parameters = $this->matchRequest($this->request);
            $controller = $parameters['controller'];
            $action = $parameters['action'];
            unset($parameters['_route'], $parameters['controller'], $parameters['action']);
            /** @var Controller $controller */
            $controller = new $controller;
            $controller->request = $this->request;
            return call_user_func_array([$controller, $action], $parameters);
        } catch (ResourceNotFoundException $e) {
            echo $e->getMessage(); die;
        }
    }
}
