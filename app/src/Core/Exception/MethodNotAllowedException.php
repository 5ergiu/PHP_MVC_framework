<?php
namespace App\Core\Exception;

use Exception;
class MethodNotAllowedException extends Exception
{
    protected $code = 500;
    protected $message = 'Method not allowed!';
}
