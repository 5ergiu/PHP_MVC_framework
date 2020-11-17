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
    private string $image;
    private string $summary;

    public const ROLE_ADMIN = 'Admin';
    public const ROLE_USER = 'User';
    public const ROLE_AUTHOR = 'Author';

    /**
     * @inheritDoc
     * @return void
     */
    protected function validations(): void
    {
        $this->validator
            ->add('email', [
                'required',
                'isEmail',
            ])
            ->add('password', [
//                'maxLength' => [
//                    'maxLength' => 8,
//                    'message' => 'mai mult',
//                ],
                'minLength' => [
                    'minLength' => 5,
                    'message' => 'mai putin',
                ],
            ]);
//            ->add('age', [
//                'checkMoreThanWhateverFunction' => [
//                    'method' => 'checkMoreThanWhateverFunction',
//                    'message' => 'check more no mers'
//                ],
//            ]);
    }

    /**
     * @param $input
     * @param string $field
     * @param string $rule
     * @param string $message
     * @return void
     */
    public function checkMoreThanWhateverFunction($input, string $field, string $rule, string $message): void
    {
        if ($input > 10) {
            $this->setErrors($field, $rule, $message);
        }
    }

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
    protected function setUsername(string $username): void
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
    protected function setEmail(string $email): void
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
    protected function setRole(string $role): void
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
    protected function setPassword(string $password): void
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
