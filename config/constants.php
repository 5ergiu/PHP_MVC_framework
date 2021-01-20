<?php

// The server/domain name
define('HOST', 'http://blog.local');
// Assets paths used in frontend
define('ASSETS_CSS', HOST . '/css/');
define('ASSETS_JS', HOST . '/js/');
define('ASSETS_IMG', HOST . '/images/');
define('ASSETS_FONT', HOST . '/css/');
define('ASSETS_UPLOADS', HOST . '/uploads/');
// Uploads file system path
define('UPLOADS', APP_ROOT . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, '/public/uploads/'));
// Vendor directory path(public)
define('VENDOR', HOST . '/vendor/');
// Vendor directory path
define('VENDOR_ROOT', APP_ROOT . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, '/vendor/'));
// Templates directory path
define('TEMPLATES', APP_ROOT . str_replace(['/', '\\'], DIRECTORY_SEPARATOR,'/src/templates/'));
// Layouts directory path
define('LAYOUTS', TEMPLATES . str_replace(['/', '\\'], DIRECTORY_SEPARATOR,'layouts/'));
// Elements directory path
define('ELEMENTS', TEMPLATES . str_replace(['/', '\\'], DIRECTORY_SEPARATOR,'elements/'));
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
