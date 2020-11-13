<?php
namespace App\Core\Error;

use App\Core\Network\Response;
use Throwable;
/**
 * Custom error handler
 * @link https://netgen.io/blog/modern-error-handling-in-php
 */
class ErrorHandler
{
    public function handleError(Throwable $error, Response $response)
    {
        var_dump($error); die;
    }
}