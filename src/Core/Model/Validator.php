<?php

namespace App\Core\Model;

use App\Core\Model\AbstractEntity as Entity;
class Validator
{
    private Entity $entity;
    private array $rules;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param string $field
     * @param array $validations
     * @return self
     */
    public function add(string $field, array $validations) {

        foreach ($validations as $rule => $details) {
            if (is_string($details)) {
                $this->rules[$field][$details] = $details;
            } else {
                $this->rules[$field][$rule] = $details;
            }
        }
        return $this;
    }

    /**
     * @return void
     */
    public function validate()
    {
        foreach ($this->rules as $field => $validations) {
            $funcName = 'get' . ucwords($field);
            foreach ($validations as $rule => $details) {
                if (is_string($details)) {
                    $this->{$rule}($this->entity->{$funcName}(), $field, $rule);
                } else {
                    if (array_key_first($details) !== 'method') {
                        if (method_exists($this, $rule)) {
                            $this->{$rule}($this->entity->{$funcName}(), $field, $rule, $details[$rule], $details['message']);
                        }
                    } else {
                        if (method_exists($this->entity, $rule)) {
                            $this->entity->{$rule}($this->entity->{$funcName}(), $field, $rule, $details['message']);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param mixed $input
     * @param string $field
     * @param string $rule
     * @return void
     */
    private function required($input, string $field, string $rule): void
    {
        if (empty($input)) {
            $message = "$field cannot be empty.";
            $this->entity->setErrors($field, $rule, $message);
        }
    }

    /**
     * @param string $input
     * @param string $field
     * @param string $rule
     * @param int $validation
     * @param string $message
     * @return void
     */
    private function maxLength(string $input, string $field, string $rule, int $validation, string $message): void
    {
        $input = (int)strlen($input);
        if ($input > (int)$validation) {
            $this->entity->setErrors($field, $rule, $message);
        }
    }

    /**
     * @param string $input
     * @param string $field
     * @param string $rule
     * @param int $validation
     * @param string $message
     * @return void
     */
    private function minLength(string $input, string $field, string $rule, int $validation, string $message): void
    {
        $input = (int)strlen($input);
        if ($input < (int)$validation) {
            $this->entity->setErrors($field, $rule, $message);
        }
    }

    /**
     * @param string $input
     * @param string $field
     * @param string $rule
     * @return void
     */
    private function isEmail(string $input, string $field, string $rule): void
    {
        if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $message = "$field has to be a valid email address.";
            $this->entity->setErrors($field, $rule, $message);
        }
    }
}