<?php
use App\Core\Configuration;
require_once 'constants.php';
require_once VENDOR_ROOT . 'autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

Configuration::initializeSession(0, '/', 'localhost', false, true);

Configuration::initializeDebugging();
