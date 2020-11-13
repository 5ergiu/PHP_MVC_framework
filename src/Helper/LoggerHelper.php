<?php

namespace App\Helper;

/**
 * Logs messages or errors.
 */
class LoggerHelper
{
    /**
     * Used to log custom error messages in specific files.
     * @param string $message The error message.
     * @param string $file    The name of the log file.
     * @return void
     */
    public function error(string $message, string $file): void
    {
        $timestamp = date("Y-m-d H:i:s");
        error_log("$timestamp: $message \n", 3, LOGS . "{$file}.log");
    }

    /**
     * Logs all requests made to the server.
     * @return void
     */
    public static function httpRequests(): void
    {
        $data = sprintf(
            "%s %s %s\n\nHTTP headers:\n",
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $_SERVER['SERVER_PROTOCOL']
        );
        $headerList = [];
        foreach ($_SERVER as $name => $value) {
            if (preg_match('/^HTTP_/',$name)) {
                // convert HTTP_HEADER_NAME to Header-Name
                $name = strtr(substr($name,5),'_',' ');
                $name = ucwords(strtolower($name));
                $name = strtr($name,' ','-');
                // add to list
                $headerList[$name] = $value;
            }
        }
        foreach ($headerList as $name => $value) {
            $data .= $name . ': ' . $value . "\n";
        }
        $data .= "\nRequest body:\n";
        file_put_contents(
            LOGS . 'http_requests.log',
            $data . file_get_contents('php://input') . "\n*************************************************************\n",
            FILE_APPEND
        );
    }
}
