<?php
namespace App\Core;

class Configuration
{
    public const DEV = 'dev';
    public const PROD = 'prod';

    /**
     * Starting the session after enabling some security measures.
     * @param int $lifetime  The session's lifetime.
     * @param string $path   Path for the session.
     * @param string $domain Domain for the session.
     * @param bool $secure   Whether or not ssl is used.
     * @param bool $httpOnly Whether or not the session should only be access by http(NO javascript).
     * @return void
     */
    public static function initializeSession(int $lifetime, string $path, string $domain, bool $secure, bool $httpOnly): void
    {
        ini_set( 'session.cookie_httponly', 1 );
        session_set_cookie_params($lifetime, $path, $domain, $secure, $httpOnly);
        session_start();
    }

    /**
     * Simple debugging settings, can be modified as per specific needs.
     * @return void
     */
    public static function initializeDebugging(): void
    {
        error_reporting(E_ALL);
        ini_set('log_errors', 1);
        ini_set('error_log', LOGS . 'errors.log');
        if ($_ENV['APP_ENV'] === Configuration::DEV) {
            ini_set('display_errors', 1);
        } else {
            ini_set('display_errors', 0);
        }
    }
}
