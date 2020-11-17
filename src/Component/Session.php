<?php
namespace App\Component;

class Session
{
    /**
     *  Writes value to given session variable name.
     * @param string $key The session's key.
     * @param mixed $data Data to be stored in the given session key.
     * @return void
     */
    public function write(string $key, $data): void
    {
        $_SESSION[$key] = $data;
    }

    /**
     * Return the values from the session or from a given session key.
     * @param string|null $key The session's key.
     * @return mixed
     */
    public function get(?string $key = null)
    {
        return $key ? $_SESSION[$key] : $_SESSION;
    }

    /**
     * Deletes data from the session.
     * @param string $key The session's key.
     * @return void
     */
    public function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }
}
