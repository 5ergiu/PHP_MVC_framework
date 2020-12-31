<?php
namespace App\Core\Model;

use App\Entity\AbstractEntity as Entity;
use App\Repository\AbstractRepository as Repo;

/**
 * Builds custom validations based on the rules sent by an entity,
 * checks them and sets the errors on the entity if needed.
 * @property array $rules An array of rules.
 * @property Entity $entity
 * @property Repo $repo
 */
class Validator
{
    private array $rules = [];
    private Entity $entity;

    public function __construct(
       private Repo $repo
    ) {}

    /**
     * Adds validations to the rules.
     * @param string $field      The field's name.
     * @param array $validations Array of validations.
     * @return $this
     */
    public function add(string $field, array $validations): static
    {
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
     * Checks the validations provided, both general validations and custom made(created in the entity).
     * @param Entity $entity
     * @return void
     */
    public function validate(Entity $entity)
    {
        $this->entity = $entity;
        if (!empty($this->rules)) {
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
                                $this->repo->{$rule}($this->entity->{$funcName}(), $field, $rule, $details['message']);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Validates that a field isn't empty.
     * @param mixed $input  User input.
     * @param string $field The field's name.
     * @param string $rule  The rule's name.
     * @return void
     */
    private function required(mixed $input, string $field, string $rule): void
    {
        if (empty($input)) {
            $message = "$field cannot be empty.";
            $this->setErrors($field, $rule, $message);
        }
    }

    /**
     * Validates the max length of a field.
     * @param mixed $input    User input.
     * @param string $field   The field's name.
     * @param string $rule    The rule's name.
     * @param int $validation The max length set for the rule.
     * @param string $message The error message.
     * @return void
     */
    private function maxLength(string $input, string $field, string $rule, int $validation, string $message): void
    {
        $input = (int)strlen($input);
        if ($input > (int)$validation) {
            $this->setErrors($field, $rule, $message);
        }
    }

    /**
     * Validates the min length of a field.
     * @param mixed $input    User input.
     * @param string $field   The field's name.
     * @param string $rule    The rule's name.
     * @param int $validation The min length set for the rule.
     * @param string $message The error message.
     * @return void
     */
    private function minLength(string $input, string $field, string $rule, int $validation, string $message): void
    {
        $input = (int)strlen($input);
        if ($input < (int)$validation) {
            $this->setErrors($field, $rule, $message);
        }
    }

    /**
     * Validates that the pattern of a field corresponds to an email address.
     * @param mixed $input  User input.
     * @param string $field The field's name.
     * @param string $rule  The rule's name.
     * @return void
     */
    private function isEmail(string $input, string $field, string $rule): void
    {
        if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $message = "$field has to be a valid email address.";
            $this->setErrors($field, $rule, $message);
        }
    }

    /**
     * Sets the errors.
     * @param string $field   The field's name.
     * @param string $rule    The rule's name.
     * @param string $message The error message.
     * @return void
     */
    public function setErrors(string $field, string $rule, string $message): void
    {
        $this->entity->errors[$field][$rule] = $message;
    }
}
