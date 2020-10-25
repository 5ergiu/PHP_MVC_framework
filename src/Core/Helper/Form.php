<?php

namespace App\Core\Helper;

use App\Core\Model\AbstractEntity as Entity;
use App\Core\Network\Request;

class Form
{

    /**
     * @param string|null $id
     * @param string|null $action
     * @param string $method
     * @return string
     */
    public function create(?string $id = null, ?string $action = null, string $method = Request::POST): string
    {
        return sprintf(
            '<form id="%s" action="%s" method="%s">',
            $id, $action, $method
        );
    }

    /**
     * @param Entity|null $entity
     * @param string|null $fieldName
     * @param array $options
     * @return string
     */
    public function input(?Entity $entity = null, ?string $fieldName = null, array $options = []): string
    {
        $name = $fieldName;
        $type = $options['type'] ?? 'text';
        $class = $options['class'] ?? '';
        $labelClass = $options['label']['class'] ?? '';
        $labelText = $options['label']['text'] ?? '';
        $errors = false;
        $value = '';
        if ($entity !== null && $entity instanceof Entity) {
            $entityName = $entity->getEntityName();
            $name = "data[$entityName][$fieldName]";
            $funcName = 'get' . ucwords($fieldName);
            $value = $entity->{$funcName}();
            if (!empty($entity->errors[$fieldName])) {
                $errors[$fieldName] = $entity->errors[$fieldName];
            }
        }
        $displayErrors = null;
        if (!empty($errors)) {
            $displayErrors = '<div>';
            foreach ($errors as $field => $error) {
                foreach ($error as $rule => $message) {
                    $displayErrors .= "<p class='error'>$message</p>";
                }
            }
            $displayErrors .= '</div>';
        }
        return sprintf(
            '<div>
                <label class="%s">%s</label>
                <input class="%s" tpye="%s" name="%s" value="%s" />
                %s
            </div>',
            $labelClass, $labelText, $class, $type, $name, $value, $displayErrors
        );
    }

    public function hidden()
    {

    }

    public function button()
    {

    }

    public function text()
    {

    }

    /**
     * @return string
     */
    public function submit(): string
    {
        return '<button>Submit</button>';
    }

    /**
     * @return string
     */
    public function end(): string
    {
        return '</form>';
    }
}
