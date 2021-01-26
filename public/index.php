<?php

require_once dirname(__DIR__).'/config/bootstrap.php';

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$kernel = new Kernel($_ENV['APP_ENV'], (bool) $_ENV['APP_DEBUG']);
$response = $kernel->handle($request);
$response->send();
//$kernel->terminate($request, $response);
