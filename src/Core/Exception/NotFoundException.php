<?php

namespace App\Core\Exception;

use Exception;

class NotFoundException extends Exception
{
    protected $code = 404;
    protected $message = 'Ups! Not found!';
}