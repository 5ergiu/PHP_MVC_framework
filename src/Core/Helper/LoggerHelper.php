<?php

namespace App\Core\Helper;

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
}