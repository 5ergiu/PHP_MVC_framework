<?php
namespace App\Helper;

use App\Entity\AbstractEntity as Entity;
use App\Core\Network\Request;
/**
 * Generates HTML forms from given data.
 * @property Entity $entity
 * @property string $entityName
 */
class FormHelper
{
    private ?Entity $entity = null;
    private string $entityName;

    /**
     * Creates the <form> tag.
     * @param Entity|null $entity The Entity that will deal with the input.
     * @param array $options      The form's options.
     * @return string
     */
    public function create(?Entity $entity, array $options = []): string
    {
        $form = '<form ';
        if (!empty($entity)) {
            $this->entityName = $entity->getEntityName();
        }
        if (!empty($options['id'])) {
            $form .= "id={$options['id']} ";
            unset($options['id']);
        } else {
            $form .= "id=$this->entityName ";
        }
        if (!empty($options['method'])) {
            $form .= "method={$options['method']} ";
            unset($options['method']);
        } else {
            $form .= "method='POST' ";
        }
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $form .= "$key=$value ";
            }
        }
        $form .= '>';
        return $form;
    }

    /**
     * Creates <input> tags.

     * @param string|null $fieldName The input's 'name' attribute.
     * @param array $options         Various options: type attribute, class attribute, label info.
     * @return string
     */
    public function input(?string $fieldName, array $options = []): string
    {
        $name = $fieldName;
        $type = null;
        if (!empty($options['type'])) {
            $type = $options['type'];
            unset($options['type']);
        } else {
            $type = 'text';
        }
        $class = null;
        if (!empty($options['class'])) {
            $class = $options['class'];
            unset($options['class']);
        } else {
            $class = 'form__input';
        }
        $id = null;
        if (!empty($options['id'])) {
            $id = $options['id'];
            unset($options['id']);
        } else {
            if (!empty($this->entity)) {
                $id = $this->entityName . ucwords($fieldName);
            }
        }
        $label = null;
        if (!empty($options['label'])) {
            $label['class'] = $options['label']['class'] ?? null;
            $label['text'] = $options['label']['text'] ?? null;
            unset($options['label']);
        }
        $errors = false;
        $value = null;
        if ($this->entity !== null && $this->entity instanceof Entity) {
            $name = "data[$this->entityName][$fieldName]";
            $funcName = 'get' . ucwords($fieldName);
            $value = $this->entity->{$funcName}();
            if (!empty($this->entity->errors[$fieldName])) {
                $errors[$fieldName] = $this->entity->errors[$fieldName];
            }
        }
        $displayErrors = null;
        if (!empty($errors)) {
            $displayErrors = '<div>';
            foreach ($errors as $field => $error) {
                foreach ($error as $rule => $message) {
                    $displayErrors .= '<p class="error">$message</p>';
                }
            }
            $displayErrors .= '</div>';
            $class .= ' form__input--error';
        }
        $input = '<div class="form__group">';
        if (!empty($label)) {
            $input .= sprintf(
                '<label class="%s" for="%s">%s</label>',
                $label['class'], $id, $label['text']
            );
        }
        $input .= '<input ';
        if (!empty($id)) {
            $input .= "id=$id ";
        }
        if (!empty($class)) {
            $input .= "class=$class ";
        }
        $input .= sprintf(
            'type="%s" name="%s" ',
            $type, $name
        );
        if (!empty($value)) {
            $input .= "value=$value ";
        }
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                if ($value === true) {
                    $input .= "$value ";
                } else {
                    $input .= "$key=$value ";
                }
            }
        }
        $input .= ' />';
        $input .= $displayErrors ?: '</div>';
        return $input;
    }

    /**
     * Creates <input> type hidden.
     * @return string
     */
    public function hidden(): string
    {
        return 'test';
    }

    /**
     * Creates <button> tags.
     * @return string
     */
    public function button(): string
    {
        return 'test';
    }

    /**
     * Creates <textarea> tags.
     * @return string
     */
    public function text(): string
    {
        return 'test';
    }

    /**
     * Creates <button> type 'submit'.
     * @return string
     */
    public function submit(): string
    {
        return '<button>Submit</button>';
    }

    /**
     * Resets the entity.
     * Creates the end tag(</form>) for the form.
     * @return string
     */
    public function end(): string
    {
        $this->entity = null;
        return '</form>';
    }
}
