<?php

use Dotenv\Dotenv;

require_once 'constants.php';
require_once dirname(__DIR__).'/vendor/autoload.php';

(Dotenv::createImmutable(dirname(__DIR__)))->load();
