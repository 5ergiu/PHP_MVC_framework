<?php
namespace App\Component;

/**
 * @property SessionComponent $session
 */
class AuthComponent
{
    public static string $sessionKey = 'Auth';
    private SessionComponent $session;

    public function __construct()
    {
        $this->session = new SessionComponent;
    }

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
     * Returns the authenticated user if there is one, if not, false.
     * @return array|null
     */
    public function user(): ?array
    {
        return $this->session->get(self::$sessionKey);
    }
}
