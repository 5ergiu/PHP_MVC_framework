<?php
namespace App\Core\Model;

/**
 * The framework's main entity which will be extended by all the app's entities.
 * Used for binding values to entities, saving or editing entities.
 * @property array $context The request data at the point of binding the values, available in each entity.
 * @property array $errors  The errors array, available in each entity.
 */
abstract class AbstractEntity
{
    public array $context = [];
    public array $errors = [];

    /**
     * Binds the values to an entity.
     * @param array $data The array of data. This should be set as the entire request data so
     * even thought not all values will be bound in case they don't correspond to the entity's
     * fields, they could still be accessed from the $context.
     * @return void
     */
    public function bindValues(array $data): void
    {
        $this->setContext($data);
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
     * Saves the entity in the database.
     * @return bool
     */
    abstract public function save() : bool;

    /**
     * Adds validations in the Validator object.
     * @return void;
     */
    abstract protected function addValidations(): void;

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
    public function slugify(string $string): string
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
     * @param array $context
     */
    public function setContext(array $context): void
    {
        $this->context = $context;
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