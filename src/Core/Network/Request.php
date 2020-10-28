<?php
namespace App\Core\Network;

/**
 * Formats the Request URL to be passed to the Router.
 * Sets the request data and checks the request type.
 * @property string $url      Formatted Request URL.
 * @property array|null $data Request data.
 */
class Request
{
    public const POST = 'POST';
    public const GET = 'GET';
    public const PUT = 'PUT';
    public const PATCH = 'PATCH';
    public const DELETE = 'DELETE';
    public const ROOT = '/';

    public string $url;
    public ?array $data;

    public function __construct()
    {
        $this->__setUrl();
        $this->__setData();
    }

    /**
     * Check the request type.
     * @param string $type The given request type.
     * @return bool
     */
    public function is(string $type): bool
    {
        $type = strtoupper($type);
        switch ($type) {
            case self::POST :
                return $_SERVER['REQUEST_METHOD'] === self::POST;
            case self::GET :
                return $_SERVER['REQUEST_METHOD'] === self::GET;
            case self::PUT :
                return $_SERVER['REQUEST_METHOD'] === self::PUT;
            case self::PATCH :
                return $_SERVER['REQUEST_METHOD'] === self::PATCH;
            case self::DELETE :
                return $_SERVER['REQUEST_METHOD'] === self::DELETE;
            default :
                return false;
        }
    }

    /**
     * Method used to set the Request URL.
     * Removes any query parameters in order to leave just the controller, action and params.
     * @return void
     */
    private function __setUrl(): void
    {
        $url = $_SERVER['REQUEST_URI'] ?? self::ROOT;
        if ($url !== self::ROOT) {
            $url = ltrim($url, $url[0]);
            $position = strpos($url, '?');
            if ($position !== false) {
                $url = substr($url, 0, $position);
            }
            $url = filter_var(rtrim($url, Request::ROOT), FILTER_SANITIZE_URL);
        }
        $this->url = $url;
    }

    /**
     * Sets the request data.
     * Parsing the data if it is sent in a json format, otherwise,
     * setting the data to the respective request type.
     * @return void
     */
    public function __setData(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data === null) {
            if ($this->is('GET')) {
                $data = $_GET;
            }
            if ($this->is('POST')) {
                $data = $_POST;
            }
        }
        $this->data = $data;
    }
}
