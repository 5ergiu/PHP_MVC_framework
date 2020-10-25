<?php

namespace App\Core\Error;

use Throwable;

class ErrorHandler
{
    public function handleError(Throwable $error)
    {
        var_dump($error);
    }
}