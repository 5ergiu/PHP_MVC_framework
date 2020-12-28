<?php
namespace App\Core\Exception;

use Exception;
class HaveToWorkOnTheseExceptions extends Exception
{
    protected $code = 500;
    protected $message = 'HaveToWorkOnTheseExceptions!';
}
