<?php
namespace App\Component;

class Auth extends AbstractComponent
{
    public static string $sessionKey = 'Auth';

    /**
     * @param array $data
     */
    public function login(array $data): void
    {
        $this->session->write(self::$sessionKey, $data);
    }

    public function logout()
    {
        $this->session->delete(self::$sessionKey);
    }
}
