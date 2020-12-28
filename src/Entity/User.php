<?php
namespace App\Entity;

/**
 * @property string $username
 * @property string $email
 * @property string $role    User's role('user' by default).
 * @property string $password
 * @property string $image   User's profile picture.
 * @property string $summary User's profile small description.
 */
class User extends AbstractEntity
{
    private string $username;
    private string $email;
    private string $role = self::ROLE_USER;
    private string $password;
    private string $image = 'guest.svg';
    private string $summary;

    public const ROLE_ADMIN = 'Admin';
    public const ROLE_USER = 'User';
    public const ROLE_AUTHOR = 'Author';

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }
}
