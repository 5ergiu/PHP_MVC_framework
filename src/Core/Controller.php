<?php

namespace App\Core;

use App\Core\Network\Request;
use App\Core\Network\Response;

class Controller
{
    private View $view;
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->view = new View;
        $this->request = new Request;
        $this->response = new Response();
    }

    /**
     * @param string $folder
     * @param string $view
     * @param array $viewVariables
     * @param string|null $layout
     * @return void
     */
    protected function render(string $folder, string $view, array $viewVariables = [], ?string $layout = null): void
    {
        echo $this->view->render($folder ,$view, $viewVariables, $layout);
    }

    /**
     * Redirect to given url.
     * @param array $url
     * @param bool $full
     * @return void
     */
    protected function redirect(array $url, $full = false): void
    {

        header("Location: $url");
//            /**
//             * Redirects to given $url, after turning off $this->autoRender. Script
//             * execution is halted after the redirect.
//             *
//             * @param string|mixed[] $url A string or array-based URL pointing to another location
//             *            within the app, or an absolute URL
//             * @param int $status   Optional HTTP status code (eg: 404)
//             * @param bool $exit     If true, exit() will be called after the redirect
//             * @return \CakeResponse|null
//             * @link https://book.cakephp.org/2.0/en/controllers.html#Controller::redirect
//             */
//            public function redirect($url, $status = null, $exit = true)
//        {
//            $url = $this->_addControllerInUrl($url);
//            $isRedirectToLogin = false;
//            $isRedirectHomeToHome = false;
//            if (is_array($url) && !empty($url['action'])) {
//                $isRedirectToLogin = ($url['action'] == 'login');
//                $isRedirectHomeToHome = ($url['action'] == 'home' && $this->request->action == 'home');
//            }
//            if (!$this->_isJsonOrXmlResponse() || $isRedirectToLogin || $isRedirectHomeToHome) {
//                $url = $this->Localizator->localizeUrl($url);
//                return parent::redirect($url, $status, $exit);
//            }
//            return null;
//        }
    }
}