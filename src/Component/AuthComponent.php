<?php
namespace App\Component;

class AuthComponent extends AbstractComponent
{
    public static string $sessionKey = 'Auth';

    /**
     * Logs the user and writes the user's info to the session.
     * @param string $password
     * @param array $user
     * @return bool
     */
    public function login(string $password,array $user): bool
    {
        $checkPassword = password_verify($password, $user['password']);
        if ($checkPassword) {
            unset($user['password']);
            $this->session->write(self::$sessionKey, $user);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Removes the user's info from the session.
     * @return void
     */
    public function logout(): void
    {
        $this->session->delete(self::$sessionKey);
    }

    /**
     * Checks if a user is already authenticated.
     * @return bool
     */
    public function loggedIn(): bool
    {
        $isLoggedIn = $this->session->get(self::$sessionKey);
        return $isLoggedIn !== false;
    }
}
