<?php
use App\Core\App;

// App directory path
define('APP_ROOT', dirname(__DIR__));
// Bootstrap file path
$boostrap = APP_ROOT . str_replace(['/', '\\'], DIRECTORY_SEPARATOR,'/config/bootstrap.php');
require_once $boostrap;

$app = new App;
