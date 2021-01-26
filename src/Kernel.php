<?php

namespace App;

use App\Core\Model\DoctrineEntityManager;
use App\Core\Routing\Router;
use Doctrine\DBAL\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Sergiu Bugeac <sergiu.a.bugeac@gmail.com>
 * The heart of the application.
 * @property string $env             Environment setup.
 * @property bool $debug             Debug level(true or false).
 */
class Kernel
{

    private const DEV_ENV = 'dev';

    /**
     * Kernel constructor.
     * @param string $env      Environment setup.
     * @param bool $debug      Debug level(true or false).
     */
    public function __construct(
        private string $env,
        private bool $debug,
    ) {
        $this->__init();
//        Log::httpRequests();
    }

    /**
     * Initializes session, debug and all other necessary features for the app.
     * @return void
     */
    private function __init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $this->__initializeSession(0, '/', 'blog.local', false, true);
        }
        $this->__initializeErrorReporting();
    }

    /**
     * Handles a Request to convert it to a Response.
     * @param Request $request The Request instance.
     * @return Response A Response instance.
     * @throws Exception
     */
    public function handle(Request $request): Response
    {
        $em = (new DoctrineEntityManager)->getEntityManager();
        $router = new Router($request, $em);
        return $router->resolve();
    }

    /**
     * Starting the session after enabling some security measures.
     * @param int $lifetime  The session's lifetime.
     * @param string $path   Path for the session.
     * @param string $domain Domain for the session.
     * @param bool $secure   Whether or not ssl is used.
     * @param bool $httpOnly Whether or not the session should only be access by http(NO javascript).
     * @return void
     */
    private function __initializeSession(int $lifetime, string $path, string $domain, bool $secure, bool $httpOnly): void
    {
        ini_set( 'session.cookie_httponly', 1 );
        session_set_cookie_params($lifetime, $path, $domain, $secure, $httpOnly);
        session_start();
    }

    /**
     * Simple error reporting and debugging settings, can be modified as per specific needs.
     * @return void
     */
    private function __initializeErrorReporting(): void
    {
        error_reporting(E_ALL);
        ini_set('log_errors', 1);
        ini_set('error_log', LOGS . 'errors.log');
        if ($this->env === self::DEV_ENV) {
            ini_set('display_errors', 1);
        } else {
            ini_set('display_errors', 0);
        }
    }
}
