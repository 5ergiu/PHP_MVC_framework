<?php
namespace App\Core\Network;

/**
 * Manipulates the http response.
 */
class Response
{
    /**
     * Sets the http response code.
     * @param int $code Status code.
     * @return int
     */
    public function setStatusCode(int $code): int
    {
        return http_response_code($code);
    }

    /**
     * Transforms a response to json.
     * @param array $response
     * @return void
     */
    public function json(array $response): void
    {
        echo json_encode($response, JSON_PRETTY_PRINT);
    }
}
