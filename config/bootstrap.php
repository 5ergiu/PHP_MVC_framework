<?php

require dirname(__DIR__).'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

session_start();
ini_set('log_errors', 1);
ini_set('error_log', LOGS . 'errors.log');
if ($_ENV['APP_ENV'] === 'dev') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
}
