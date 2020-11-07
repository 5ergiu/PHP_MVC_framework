<?php
require_once 'constants.php';
require_once VENDOR . 'autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

Config::initializeSession(0, '/', 'localhost', false, true);

Config::initializeDebugging();
