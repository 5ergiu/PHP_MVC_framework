<?php

namespace App\Model\Entity;

use App\Core\Model\Entity;
use App\Core\Model\Validator;

class User extends Entity
{
    private Validator $validator;
    private string $name;
    private int $age;
    private string $email;
    private string $password;

    public function __construct()
    {
        $this->validator = new Validator($this);
        $this->__addValidations();
    }

    private function __addValidations()
    {
        $this->validator
            ->add('email', [
                'required',
                'isEmail',
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

    public function validate()
    {
        return $this->validator->validate();
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
            $this->validator->setErrors($field, $rule, $message);
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