<?php
namespace App\Component;

use Exception;
/**
 * @property SessionComponent $session
 * @property array|null $user Authenticated user or null.
 */
class AuthComponent
{
    public static string $sessionKey = 'Auth';
    private ?array $user;

    public function __construct(SessionComponent $session)
    {
        $this->session = $session;
        $this->__setAuthenticatedUser();
    }

    /**
     * Sets the authenticated user if there is one in the session.
     * @return void
     */
    private function __setAuthenticatedUser(): void
    {
        $this->user = $this->session->get(self::$sessionKey) ?? null;
    }

    /**
     * Returns user related info.
     * @param string|null $key
     * @return mixed
     */
    public function user(string $key = null)
    {
        if (!empty($this->user)) {
            if (!empty($key)) {
                return $this->user[$key] ?: null;
            } else {
                return $this->user;
            }
        } else {
            return null;
        }
    }

    /**
     * Logs the user and writes the user's info to the session.
     * @param array $user
     * @param string|null $password
     * @param bool $skipVerification Used in case suer registers to log in automatically.
     * @throws Exception
     * @return array|null
     */
    public function login(array $user, ?string $password, bool $skipVerification = false): ?array
    {
        if ($password === null && $skipVerification) {
            $checkPassword = true;
        } else {
            $checkPassword = password_verify($password, $user['password']);
        }
        if ($checkPassword) {
            unset($user['password']);
            $this->session->write(self::$sessionKey, $user);
            return $user;
        } else {
            return null;
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
}
