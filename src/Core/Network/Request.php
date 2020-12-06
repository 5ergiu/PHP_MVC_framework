<?php
namespace App\Core\Network;

use JetBrains\PhpStorm\Pure;

/**
 * Formats the Request URL to be passed to the Router.
 * Sets the request data and checks the request type.
 * @property string|array $url Formatted Request URL.
 * @property array $data       Request data.
 * @property string $method
 */
class Request
{
    public const POST = 'POST';
    public const GET = 'GET';
    public const PUT = 'PUT';
    public const PATCH = 'PATCH';
    public const DELETE = 'DELETE';
    public const ROOT = '/';

    public string|array $url;
    public array $data;
    public string $method;

    public function __construct()
    {
        $this->__setUrl();
        $this->__setData();
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Check the request type.
     * @param string $type The given request type.
     * @return bool
     */
    #[Pure]
    public function is(string $type): bool
    {
        $type = strtoupper($type);
        switch ($type) {
            case self::POST :
                return $this->method === self::POST;
            case self::GET :
                return $this->method === self::GET;
            case self::PUT :
                return $this->method === self::PUT;
            case self::PATCH :
                return $this->method === self::PATCH;
            case self::DELETE :
                return $this->method === self::DELETE;
            default :
                return false;
        }
    }

    /**
     * Method used to set the Request URL.
     * Removes any query parameters in order to leave just the controller, action and params.
     * Extensions such as '.json' will be placed after the method parameter, but BEFORE
     * the query parameters, so if there is an extension, check for it after removing the query parameters.
     * The extension will be paced to the router as an element of the $url array.
     * @return void
     */
    private function __setUrl(): void
    {
        $url = $_SERVER['REQUEST_URI'];
        if ($url !== self::ROOT) {
            $ext = null;
            $url = ltrim($url, $url[0]);
            $queryPosition = strpos($url, '?');
            if ($queryPosition !== false) {
                $url = substr($url, 0, $queryPosition);
            }
            $extPosition = strpos($url, '.');
            if ($extPosition) {
                $ext = substr($url, $extPosition + 1);
                $url = substr($url, 0, $extPosition);
            }
            $url = filter_var(rtrim($url, self::ROOT), FILTER_SANITIZE_URL);
            $url = explode(self::ROOT, $url);
            if (!empty($ext)) {
                $url['ext'] = $ext;
            }
        }
        $this->url = $url;
    }

    /**
     * Sets the request data.
     * Also parses the data if it is sent in a json format.
     * @return void
     */
    public function __setData(): void
    {
        $this->data = $_REQUEST;
        $jsonData = json_decode(file_get_contents('php://input'), true);
        if (!empty($jsonData)) {
            foreach ($jsonData as $key => $data) {
                $this->data[$key] = $data;
            }
        }
    }

    /**
     * @param string|int $key
     * @return mixed
     */
    public function data(string|int $key): mixed
    {
        return !empty($this->data['data'][$key]) ? $this->data['data'][$key] : false;
    }
}
