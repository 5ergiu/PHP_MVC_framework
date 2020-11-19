<?php
namespace App\Core\Network;

/**
 * Manipulates the app's response.
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
     * Returns a json encoded response.
     * @param array $json The json array from the controllers.
     * @return void
     */
    public function json(array $json): void
    {
        header('Content-type:application/json');
        echo json_encode($json, JSON_PRETTY_PRINT);
    }

    /**
     * Redirect to a location.
     * @param array $url Url options.
     * @param bool $full True if the link should be full(including hostname), false otherwise.
     * @return void
     */
    public function redirect(array $url, $full = false): void
    {
        header('Location: ' . Router::url($url, $full));
    }
}
