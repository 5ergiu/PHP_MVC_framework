<?php
require_once 'constants.php';
require_once VENDOR . 'autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Starting the session after taking some security measure
function sessionStart($lifetime, $path, $domain, $secure, $httpOnly) {
    ini_set( 'session.cookie_httponly', 1 );
    session_set_cookie_params($lifetime, $path, $domain, $secure, $httpOnly);
    session_start();
}
sessionStart(0, '/', 'localhost', false, true);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', LOGS . 'errors.log');
if ($_ENV['APP_ENV'] === 'dev') {
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
}
