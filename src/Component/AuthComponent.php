<?php
namespace App\Component;

use App\Repository\UsersRepo;
use Exception;
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
     * @param string $username
     * @param string $password
     * @throws Exception
     * @return array|null
     */
    public function login(string $username, string $password): ?array
    {
        $user = $this->__getUser($username);
        if (!empty($user)) {
            $checkPassword = password_verify($password, $user['password']);
            if ($checkPassword) {
                unset($user['password']);
                $this->session->write(self::$sessionKey, $user);
                return $user;
            } else {
                return null;
            }
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

    /**
     * Returns the authenticated user if there is one, if not, false.
     * @return array|null
     */
    public function user(): ?array
    {
        return $this->session->get(self::$sessionKey);
    }

    /**
     * Returns a user's info or null if there's no user found.
     * @param string $username
     * @return array|null
     * @throws Exception
     */
    private function __getUser(string $username): ?array
    {
        $UsersRepo = new UsersRepo;
        $user = $UsersRepo->findBy('username', $username);
        unset($UsersRepo);
        return $user;
    }
}
