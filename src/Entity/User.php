<?php
namespace App\Entity;

/**
 * @property string $name The name of the user.
 * @property int|null $age The age of the user.
 * @property string $email The email address of the user.
 * @property string $password The user's password.
 */
class User extends AbstractEntity
{
    private string $name = '';
    private ?int $age = null;
    private string $email = '';
    private string $password = '';

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
            ->add('name', [
                'required',
            ])
            ->add('password', [
                'maxLength' => [
                    'maxLength' => 8,
                    'message' => 'mai mult',
                ],
                'minLength' => [
                    'minLength' => 5,
                    'message' => 'mai putin',
                ],
            ])
            ->add('age', [
                'checkMoreThanWhateverFunction' => [
                    'method' => 'checkMoreThanWhateverFunction',
                    'message' => 'check more no mers'
                ],
            ]);
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return (int)$this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
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
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}