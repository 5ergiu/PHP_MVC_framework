<?php
namespace App\Core\Network;

/**
 * Manipulates the app's response.
 * @property string $body
 * @property array $headers
 */
class Response
{

    private string $body;
    private array $headers;

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
        $this->__setHeaders(['Content-type' => 'application/json']);
        $this->__setBody(json_encode($json, JSON_PRETTY_PRINT));
        echo $this->body;
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

    /**
     * @param string $body
     */
    private function __setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @param array $headers
     */
    private function __setHeaders(array $headers): void
    {
        foreach ($headers as $header => $value) {
            if (!is_array($value)) {
                $this->headers[$header] = $value;
                header("$header:$value");
            } else {
                foreach ($header as $key => $val) {
                    $this->headers[$key] = $val;
                    header("$key:$val");
                }
            }
        }
    }
}
