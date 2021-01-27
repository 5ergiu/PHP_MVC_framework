<?php

namespace App\Core\Routing;

use JetBrains\PhpStorm\NoReturn;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Routing\Loader\AnnotationClassLoader;
use Symfony\Component\Routing\Route;

/**
 * AnnotatedRouteControllerLoader is an implementation of AnnotationClassLoader
 * that sets the '_controller' default based on the class and method names.
 */
class AnnotatedRouteControllerLoader extends AnnotationClassLoader
{
    /**
     * Configures the _controller default parameter of a given Route instance.
     *
     * @param Route $route
     * @param ReflectionClass $class
     * @param ReflectionMethod $method
     * @param mixed $annot The annotation class instance
     */
    protected function configureRoute(Route $route, ReflectionClass $class, ReflectionMethod $method, mixed $annot)
    {
        $route->setDefault('controller', $class->getName());
        $route->setDefault('action', $method->getName());
    }
}
