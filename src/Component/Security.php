<?php
namespace App\Component;

class Security
{
    private ?string $password;

    /**
     * @param string $password
     */
    public function __construct(string $password)
    {
        $this->hashPassword($password);
    }

    /**
     * @param string $password
     * @return void
     */
    public function hashPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
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
     * @param string $actual
     * @return bool
     */
    public function verifyPassword(string $password, string $actual): bool
    {
        return password_verify($password, $actual);
    }
}
