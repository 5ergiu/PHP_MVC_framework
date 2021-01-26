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
 * @property Controller $controller            The Controller instance.
 * @property EntityManager $em                 The Entity Manager instance.
 * @property AnnotationDirectoryLoader $loader The Loader instance used to load the routes from the controllers.
 */
class Router extends SymfonyRouter {

    private Controller $controller;

    /**
     * Router constructor.
     * @param Request $request  The Request instance.
     * @param EntityManager $em The Entity Manager instance.
     */
    public function __construct(
        public Request $request,
        private EntityManager $em
    ) {
        $this->loader = $this->__loadRoutes();
        parent::__construct($this->loader, './');
    }

    /**
     * Load routes from the yaml file.
     * @return AnnotationDirectoryLoader An AnnotationDirectoryLoader instance.
     */
    private function __loadRoutes(): AnnotationDirectoryLoader
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
            $controller = strtok($parameters['_controller'], '::');
            $action = substr(strstr($parameters['_controller'], '::'), 2);
            unset($parameters['_route'], $parameters['_controller']);
            $this->controller = new $controller;
            $this->controller->request = $this->request;
            $this->controller->em = $this->em;
            return call_user_func_array([$this->controller, $action], $parameters);
        } catch (ResourceNotFoundException $e) {
            echo $e->getMessage(); die;
        }
    }
}
