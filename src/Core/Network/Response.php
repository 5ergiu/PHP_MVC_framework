<?php
namespace App\Core\Network;

use JetBrains\PhpStorm\NoReturn;

/**
 * Responsible for managing the response text, status and headers of a HTTP response.
 * Controllers will use this class to render their response.
 * @property Request $request
 * @property int $statusCode
 * @property string $charSet
 * @property string $contentType
 * @property array $mimeTypes
 * @property array $headers
 * @property array $cookies
 * @property string $body
 */
class Response
{
    private int $statusCode = 200;
    private string $charSet = 'UTF-8';
    private string $contentType = 'text/html';
    private array $headers = [];
    private array $cookies = [];
    private string $body;

    /**
     * Holds 'allowed' mime type mappings
     */
    private array $mimeTypes = [
        'html' => 'text/html',
        'json' => 'application/json',
    ];

    /**
     * @param int $code Status code.
     * @return void
     */
    public function statusCode(int $code): void
    {
        $this->statusCode = $code;
    }

    /**
     * @param string $type
     * @return void
     */
    public function contentType(string $type): void
    {
        if (array_key_exists($type, $this->mimeTypes)) {
            $this->contentType = $this->mimeTypes[$type];
        }
    }

    /**
     * @param string $body
     * @return void
     */
    public function body(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @param string $charSet
     * @return void
     */
    public function charSet(string $charSet): void
    {
        $this->charSet = $charSet;
    }

    /**
     * Set the Location header value.
     * @param string $url Location.
     * @return void
     */
    public function location(string $url): void
    {
        $this->header('Location', $url);
    }

    /**
     * @param string $name The name of the header.
     * @param mixed $value The value of the header.
     */
    public function header(string $name, mixed $value): void
    {
        $this->headers[$name] = $value;
    }

    /**
     * @param array $options
     * @return void
     */
    public function cookie(array $options): void
    {
        $defaults = [
            'name' => 'Cookie',
            'value' => '',
            'expire' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httpOnly' => true,
        ];
        $options += $defaults;
        $this->cookies[$options['name']] = $options;
    }

    /**
     * Sets the http response code.
     * @return void
     */
    private function __setHttpCode(): void
    {
        http_response_code($this->statusCode);
    }

    /**
     * Formats the Content-Type header based on the configured contentType.
     * @return void
     */
    private function __setContentType(): void
    {
        $this->header('Content-Type', "{$this->contentType}; charset={$this->charSet}");
    }

    /**
     * Sends the headers.
     * @return void
     */
    private function __sendHeaders(): void
    {
        if (headers_sent($filename, $linenum)) {
            return;
        }
        if (!empty($this->headers)) {
            foreach ($this->headers as $name => $value) {
                header("{$name}: {$value}");
            }
        }
    }

    /**
     * Sets the cookies before any other output is sent to the client.
     * have been set.
     * @return void
     */
    private function __setCookies(): void
    {
        foreach ($this->cookies as $name => $c) {
            setcookie(
                $name, $c['value'], $c['expire'], $c['path'],
                $c['domain'], $c['secure'], $c['httpOnly']
            );
        }
    }

    /**
     * @return void
     */
    private function __sendContent(): void
    {
        echo $this->body;
    }

    /**
     * Sends the complete response to the client including headers and message body.
     * Will echo out the content in the response body.
     * @return void
     */
    #[NoReturn]
    public function send(): void
    {
        if (isset($this->headers['Location']) && $this->statusCode === 200) {
            $this->statusCode(302);
        }
        $this->__setHttpCode();
        $this->__setContentType();
        $this->__setCookies();
        $this->__sendHeaders();
        $this->__sendContent();
        exit($this->statusCode);
    }
}
