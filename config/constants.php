<?php

// The server/domain name
define('HOST', 'http://localhost');
// Vendor directory path
define('VENDOR', APP_ROOT . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, '/vendor/'));
// Templates directory path
define('TEMPLATES', APP_ROOT . str_replace(['/', '\\'], DIRECTORY_SEPARATOR,'/src/templates/'));
// Layouts directory path
define('LAYOUTS', TEMPLATES . str_replace(['/', '\\'], DIRECTORY_SEPARATOR,'layouts/'));
// Default layout
define('DEFAULT_LAYOUT', 'base.php');
// Logs directory path
define('LOGS', APP_ROOT . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, '/var/logs/'));
// Config directory path
define('CONFIG', APP_ROOT . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, '/config/'));
// Default controller
define('DEFAULT_CONTROLLER', '\App\Controller\HomeController');
// Default method
define('DEFAULT_ACTION', 'index');
