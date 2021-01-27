<?php
namespace App\Controller;

use App\Component\Auth;
use App\Component\Session;
use App\Core\Exception\MethodNotAllowedException;
use App\Core\Model\DoctrineEntityManager;
use App\Core\View;
use App\Component\Log;
use App\Component\MarkdownComponent;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @property array $data Request data.
 * @property Session $session
 * @property Request $request
 * @property Auth $auth
 * @property MarkdownComponent $markdown
 * @property Log $log
 * @property string $referer
 * @property EntityManager $em The Entity Manager instance.
 * The framework's main controller which will be extended by all the app's controllers.
 */
abstract class AbstractController
{

    public Request $request;
    public array $data;
    protected Session $session;
    protected Auth $auth;
    protected MarkdownComponent $markdown;
    protected Log $log;
    protected EntityManager $em;

    public function __construct()
    {
        $this->session = new Session;
        $this->auth = new Auth($this->session);
        $this->em = $this->__getEm();
        $this->markdown = new MarkdownComponent;
        $this->log = new Log;
    }

    /**
     * Creates a view that will be rendered.
     * @param string $viewPath    The path of the view.
     * @param array $variables    The variables that will be used in the view.
     * @param string|null $layout The name of the layout.
     * @return Response
     */
    #[NoReturn]
    protected function render(string $viewPath, array $variables = [], ?string $layout = null): Response
    {
        $View = new View(
            $this->request->query->all(),
            $this->auth->user(),
            $this->__getNotification(),
            $viewPath,
            $variables,
            $layout
        );
        return new Response($View->getView());
    }

    /**
     * Returns the referer or null.
     * @return string|null
     */
    protected function referer(): ?string
    {
        return $this->request->headers->get('referer') ?? '/';
    }

    /**
     * Checks the allowed methods for a controller action.
     * @param array $methods Array of allowed methods.
     * @return bool
     * @throws MethodNotAllowedException
     */
    protected function methodsAllowed(array $methods): bool
    {
        foreach ($methods as $key => $value) {
            $methods[$key] = strtoupper($value);
        }
        if (!in_array($this->request->getMethod(), $methods)) {
            throw new MethodNotAllowedException;
        } else {
            return true;
        }
    }

    /**
     * Redirect to a location.
     * @param string $url
     * @return Response
     */
    #[NoReturn]
    protected function redirect(string $url): Response
    {
        return new RedirectResponse($url);
    }

    /**
     * Creates a new property of a repository instance on the current controller instance.
     * @param string entity
     * @return void
     */
    protected function loadRepo(string $entity): void
    {
        $entity = ucwords($entity);
        $entityClass = "App\Entity\\$entity";
        $repo = $entity . 'Repo';
        $this->{$repo} = $this->em->getRepository($entityClass);
    }

    /**
     * Creates a new property of a component instance on the current controller instance.
     * @param string $component
     * @return void
     */
    protected function loadComponent(string $component): void
    {
        $component = ucwords($component);
        $componentClass = 'App\Component\\' . $component;
        $this->{$component} = new $componentClass;
    }

    /**
     * Sets the success notification message in the session to be used in the view.
     * @param string $message
     * @return void
     */
    protected function notifySuccess(string $message): void
    {
        $this->session->write('icon', '✔️');
        $this->session->write('message', $message);
    }

    /**
     * Sets the error notification message in the session to be used in the view.
     * @param string $message
     * @return void
     */
    protected function notifyError(string $message): void
    {
        $this->session->write('icon', '❌');
        $this->session->write('message', $message);
    }

    /**
     * Returns an array with the notification options.
     * @return array
     */
    private function __getNotification(): array
    {
        $notification = [];
        if (!empty($this->session->get('icon'))) {
            $notification['icon'] = $this->session->get('icon');
            $this->session->delete('icon');
        }
        if (!empty($this->session->get('message'))) {
            $notification['message'] = $this->session->get('message');
            $this->session->delete('message');
        }
        return $notification;
    }

    /**
     * Returns an instance of the EntityManager.
     * @return EntityManager
     */
    private function __getEm(): EntityManager
    {
        try {
            return (new DoctrineEntityManager)->getEntityManager();
        } catch (Exception $e) {
            echo $e->getMessage(); die;
        }
    }
}
