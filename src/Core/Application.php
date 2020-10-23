<?php
namespace App\Core;

use App\Core\Network\Request;
use App\Core\Network\Response;

/**
 * @author Sergiu Bugeac <sergiu.a.bugeac@gmail.com>
 */
class Application
{

    public Router $router;
    public Request $request;
    public Response $response;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $this->request = new Request;
        $this->response = new Response;
        $this->router = new Router($this->request, $this->response);
    }
}