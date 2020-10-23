<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/constants.php';
require APP_ROOT . '/config/bootstrap.php';

use App\Core\Application;

$app = new Application;
