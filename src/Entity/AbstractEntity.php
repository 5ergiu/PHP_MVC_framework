<?php
namespace App\Entity;

use App\Core\Network\Request;
use App\Core\Model\Validator;
use JetBrains\PhpStorm\Pure;

/**
 * The framework's main entity which will be extended by all the app's entities.
 * Used for binding values to entities, saving or editing entities.
 * @property array $context The request data at the point of binding the values, available in each entity.
 * @property array $errors  The errors array, available in each entity.
 * @property Validator $validator
 */
abstract class AbstractEntity
{
    protected Validator $validator;
    public array $context = [];
    public array $errors = [];

    public function __construct() {
        $this->validator = new Validator($this);
        $this->__setContext();
        $this->validations();
    }

    /**
     * Binds the values to an entity.
     * @param array $data The array of data. This should only contain the actual entity fields values,
     * so, all the logic should be built in the controller's method prior to 'patching' the entity.
     * @return bool
     */
    public function bindValues(array $data): bool
    {
        $entityName = $this->getEntityName();
        $entityData = $data['data'][$entityName];
        foreach ($entityData as $field => $input) {
            $field = str_replace('_', '', lcfirst(ucwords($field, '_')));
            if (property_exists($this, $field)) {
                $funcName = 'set' . ucwords($field);
                if (method_exists($this, $funcName)) {
                    $this->{$funcName}($input);
                }
            }
        }
        $this->validator->validate();
        if (!empty($this->errors)) {
            return false;
        }
        return true;
    }

    /**
     * Adds validations in the Validator object.
     * @return void;
     */
    abstract protected function validations(): void;

    /**
     * Returns the entity's name.
     * @return string
     */
    public function getEntityName(): string
    {
        $className = $this::class;
        return substr($className, strrpos($className, '\\') + 1);
    }

    /**
     * 'Slugifies' a given string.
     * @param string $string The string to be 'slugify'.
     * @return string
     */
    protected function slugify(string $string): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-')) . '-' . uniqid();
    }

    /**
     * Returns the context.
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Sets the context.
     * @return void
     */
    private function __setContext(): void
    {
        $request = new Request;
        $this->context = $request->data;
        unset($request);
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
        $this->errors[$field][$rule] = $message;
    }
}
