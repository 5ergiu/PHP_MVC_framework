<?php
namespace App\Entity;

use App\Core\Network\Request;
use App\Core\Validator;
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
    protected array $context;
    public array $errors = [];

    public function __construct()
    {
        $this->__setContext();
        $this->validator = new Validator($this);
        $this->validations();
    }

    /**
     * Binds the values to an entity.
     * @param array $data The array of data. This should contain only the actual entity fields values,
     * so, all the logic should be built in the controller's method prior to 'patching' the entity.
     * @return void
     */
    public function bindValues(array $data): void
    {
        $entityName = $this->getEntityName();
        $entityData = $data['data'][$entityName];
        foreach ($entityData as $field => $input) {
            if (property_exists($this, $field)) {
                $funcName = 'set' . ucwords($field);
                if (method_exists($this, $funcName)) {
                    $this->{$funcName}($input);
                }
            }
        }
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
        $className = get_class($this);
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
    protected function getContext(): array
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
     * Returns the errors.
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
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