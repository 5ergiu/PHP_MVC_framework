<?php

namespace App\Core\Network;

class Response
{
    /**
     * @param int $code Status code.
     * @return int
     */
    public function setStatusCode(int $code): int
    {
        return http_response_code($code);
    }
}