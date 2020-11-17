<?php
namespace App\Helper;

use App\Entity\AbstractEntity as Entity;
use App\Core\Network\Request;
/**
 * Generates HTML forms from given data.
 * @property array $data
 * @property Entity $entity
 */
class FormHelper
{
    private ?Entity $entity = null;
    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Creates the <form> tag.
     * @param Entity|null $entity The Entity that will deal with the input.
     * @param array $options      The form's attributes.
     * @return string
     */
    public function create(?Entity $entity, array $options = []): string
    {
        $form = '<form ';
        if (!empty($entity)) {
            $this->entity = $entity;
        }
        if (!empty($options['id'])) {
            $form .= "id={$options['id']} ";
            unset($options['id']);
        } else {
            if (!empty($entity)) {
                $form .= "id={$this->entity->getEntityName()} ";
            }
        }
        if (!empty($options['method'])) {
            $form .= "method={$options['method']} ";
            unset($options['method']);
        } else {
            $form .= "method='POST' ";
        }
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $form .= "$key='$value' ";
            }
        }
        $form .= '>';
        return $form;
    }

    /**
     * @param string $type
     * @param string $fieldName
     * @param array $options
     * @return string
     */
    private function __buildInput(string $type, string $fieldName, array $options = []): string
    {
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
                $id = $this->entity->getEntityName() . ucwords($fieldName);
            }
        }
        $label = null;
        if (!empty($options['label'])) {
            $label['class'] = $options['label']['class'] ?? null;
            $label['text'] = $options['label']['text'] ?? null;
            unset($options['label']);
        }
        $errors = null;
        $value = null;
        $context = $this->data;
        if ($this->entity !== null && $this->entity instanceof Entity) {
            $context = $this->entity->getContext();
            $fieldName = "data[{$this->entity->getEntityName()}][$fieldName]";
            if (!empty($context['data'][$this->entity->getEntityName()])) {
                if (!empty($this->entity->errors[$fieldName])) {
                    $errors[$fieldName] = $this->entity->errors[$fieldName];
                } else {
                    $funcName = 'get' . ucwords($fieldName);
                    $value = $this->entity->{$funcName}();
                }
            }
        } else {
            if (!empty($context[$fieldName]['errors'])) {
                $errors[$fieldName] = $context[$fieldName]['errors']['message'];
            } else {
                if (!empty($context[$fieldName])) {
                    $value = $context[$fieldName];
                }
            }
        }
        $displayErrors = null;
        if (!empty($errors)) {
            $displayErrors = '<div class="form__input_errors">';
            foreach ($errors as $field => $error) {
                foreach ($error as $rule => $message) {
                    $displayErrors .= "<p class='error'>$message</p>";
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
        switch ($type) {
            case 'textarea' :
                $input .= '<textarea autocomplete="off" ';
                break;
            default :
                $input .= "<input type=$type autocomplete='off' ";
                break;
        }
        if (!empty($id)) {
            $input .= "id='$id' ";
        }
        if (!empty($class)) {
            $input .= "class='$class' ";
        }
        $input .= "name='$fieldName' ";
        if (!empty($value)) {
            $input .= "value='$value' ";
        }
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                if ($value === true) {
                    $input .= "'$value' ";
                } else {
                    $input .= "$key='$value' ";
                }
            }
        }
        switch ($type) {
            case 'textarea' :
                $input .= '</textarea>';
                break;
            default :
                $input .= ' />';
                break;
        }
        $input .= $displayErrors ?? null;
        $input .= '</div>';
        return $input;
    }

    /**
     * Creates <input> tags.
     * @param string $fieldName The input's 'name' attribute.
     * @param array $options         The input's attributes.
     * @return string
     */
    public function input(string $fieldName, array $options = []): string
    {
       return $this->__buildInput('input', $fieldName, $options);
    }

    /**
     * Creates <textarea> tags.
     * @param string $fieldName The textarea's 'name' attribute.
     * @param array $options         The textarea's attributes.
     * @return string
     */
    public function text(string $fieldName, array $options = []): string
    {
        return $this->__buildInput('textarea', $fieldName, $options);
    }

    /**
     * Creates <button> tag.
     * @param string $text   The inner text of the button.
     * @param array $options The button's attributes.
     * @return string
     */
    public function button(string $text, array $options = []): string
    {
        $button = '<button ';
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                if ($value === true) {
                    $button .= "$value ";
                } else {
                    $button .= "$key='$value' ";
                }
            }
        }
        $button .= ">$text</button>";
        return $button;
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
