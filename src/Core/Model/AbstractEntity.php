<?php

namespace App\Core\Model;

abstract class AbstractEntity
{
    public array $errors = [];

    /**
     * @param array $data
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
     * @return string
     */
    public function getEntityName(): string
    {
        $className = get_class($this);
        return substr($className, strrpos($className, '\\') + 1);
    }

    /**
     * @return bool
     */
    abstract public function save() : bool;

    /**
     * @return void;
     */
    abstract protected function addValidations(): void;

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string $field
     * @param string $rule
     * @param string $message
     * @return void
     */
    public function setErrors(string $field, string $rule, string $message): void
    {
        $this->errors[$field][$rule] = $message;
    }
}