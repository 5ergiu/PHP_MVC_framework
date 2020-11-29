<?php
namespace App\Core;

use App\Component\Log;
use App\Core\Network\Request;
use App\Core\Network\Router;
/**
 * @author Sergiu Bugeac <sergiu.a.bugeac@gmail.com>
 * Main app class, the start point of the application.
 * @property Request $request
 * @property Router  $router
 */
class App
{
    public Request $request;
    private Router $router;

    public function __construct()
    {
        $this->request = new Request;
        $this->router = new Router($this->request);
        Log::httpRequests();
    }
}
