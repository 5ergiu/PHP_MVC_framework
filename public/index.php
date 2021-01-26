<?php

use App\Kernel;
use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/config/constants.php';
require_once dirname(__DIR__).'/vendor/autoload.php';

(Dotenv::createImmutable(dirname(__DIR__)))->load();

$request = Request::createFromGlobals();
$kernel = new Kernel($_ENV['APP_ENV'], (bool) $_ENV['APP_DEBUG']);
$response = $kernel->handle($request);
$response->send();
//$kernel->terminate($request, $response);
