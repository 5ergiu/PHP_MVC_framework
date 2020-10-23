<?php

namespace App\Core\Helper;

class Logger
{
    /**
     * Used to log custom error messages in specific files.
     * @param string $message The error message.
     * @param string $file    The name of the log file.
     * @return void
     */
    public static function logError(string $message, string $file): void
    {
        $timestamp = date("Y-m-d H:i:s");
        error_log("$timestamp: $message \n", 3, LOGS . DIRECTORY_SEPARATOR . "{$file}.log");
    }
}