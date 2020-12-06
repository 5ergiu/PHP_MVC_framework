<?php
namespace App\Helper;

use App\Entity\AbstractEntity as Entity;
use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Pure;

/**
 * Generates HTML forms from given data.
 * @property array $context The context(request data).
 * @property Entity|null $entity
 */
class FormHelper
{
    private const INPUT = 'input';
    private const TEXTAREA = 'textarea';
    private const CHECKBOX = 'checkbox';

    private ?Entity $entity = null;

    /**
     * FormHelper constructor.
     * @param array $context The context(request data).
     */
    public function __construct(
        private array $context,
    ) {}

    /**
     * Creates the form tag.
     * @param Entity|null $entity The Entity that will deal with the input.
     * @param array $options      The form's attributes.
     * @return string
     */
    public function create(?Entity $entity, array $options = []): string
    {
        $form = '<form ';
        if (!empty($entity)) {
            $this->entity = $entity;
            if (!empty($options['id'])) {
                $form .= "id='{$options['id']}' ";
                unset($options['id']);
            } else {
                $form .= "id='{$this->entity->getEntityName()}' ";
            }
        }
        if (!empty($options['method'])) {
            $form .= "method='{$options['method']}' ";
            unset($options['method']);
        } else {
            $form .= "method='POST' ";
        }
        if (!empty($options)) {
            $form .= $this->__setAttributes($options);
        }
        $form .= '>';
        return $form;
    }

    /**
     * Creates input tags.
     * @param string $fieldName The input name attribute.
     * @param array $options    The input's attributes.
     * @return string
     */
    public function input(string $fieldName, array $options = []): string
    {
        return $this->__buildInput(self::INPUT, $fieldName, $options);
    }

    /**
     * Creates textarea tags.
     * @param string $fieldName The textarea 'name' attribute.
     * @param array $options    The textarea attributes.
     * @return string
     */
    public function textarea(string $fieldName, array $options = []): string
    {
        return $this->__buildInput(self::TEXTAREA, $fieldName, $options);
    }

    /**
     * Creates checkboxes.
     * @param string|null $fieldName The textarea 'name' attribute.
     * @param array $options         The textarea attributes.
     * @return string
     */
    public function checkbox(?string $fieldName, array $options = []): string
    {
        return $this->__buildInput(self::CHECKBOX, $fieldName, $options);
    }

    /**
     * Creates button tag.
     * @param string $text   The inner text of the button.
     * @param array $options The button attributes.
     * @return string
     */
    #[Pure]
    public function button(string $text, array $options = []): string
    {
        $button = '<button ';
        if (!empty($options)) {
            $button .= $this->__setAttributes($options);
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
        // resetting the entity in case multiple forms will be created,
        // because there's a single View instance that's using the same FormHelper instance.
        $this->entity = null;
        return '</form>';
    }

    /**
     * Creates the tag's attributes.
     * @param array $options
     * @return string
     */
    private function __setAttributes(array $options): string
    {
        $attributes = null;
        foreach ($options as $key => $value) {
            if ($value === true) {
                $attributes .= "'$value' ";
            } else {
                $attributes .= "$key='$value' ";
            }
        }
        return $attributes;
    }

    /**
     * Returns the errors from the entity for a particular input.
     * @param string $fieldName
     * @return array
     */
    private function __getEntityErrors(string $fieldName): array
    {
        $errors = [];
        if (!empty($this->entity->errors[$fieldName])) {
            $errors[$fieldName] = $this->entity->errors[$fieldName];
        }
        return $errors;
    }

    /**
     * Returns the value from the entity for a particular input.
     * @param string $fieldName
     * @return mixed
     */
    private function __getEntityValue(string $fieldName): mixed
    {
        $funcName = 'get' . ucwords($fieldName);
        if (!empty($this->context['data'][$this->entity->getEntityName()])) {
            return $this->entity->{$funcName}();
        }
        return null;
    }

    /**
     * Builds the errors that will be displayed for each input.
     * @param array|null $errors
     * @return string|null
     */
    private function __displayErrors(?array $errors): ?string
    {
        if (!empty($errors)) {
            $displayErrors = '<div class="form__input_errors">';
            foreach ($errors as $field => $error) {
                foreach ($error as $rule => $message) {
                    $displayErrors .= "<p class='error'>$message</p>";
                }
            }
            $displayErrors .= '</div>';
            return $displayErrors;
        }
        return null;
    }

    /**
     * Builds the start of inputs(inputs/textareas).
     * @param string $classification It can be either input/textarea.
     * @param string|null $fieldName
     * @param array $options
     * @return string
     */
    private function __buildInput(
        #[ExpectedValues([self::INPUT, self::TEXTAREA, self::CHECKBOX])]
        string $classification,
        ?string $fieldName,
        array $options
    ): string
    {
        $input = '<div class="form__group">';
        $id = null;
        $errors = null;
        $value = null;
        if (!empty($this->entity) && $this->entity instanceof Entity && $fieldName !== null) {
            $errors = $this->__getEntityErrors($fieldName);
            if (empty($errors)) {
                $value = $this->__getEntityValue($fieldName);
            }
            if (!empty($options['id'])) {
                $id = $options['id'];
                unset($options['id']);
            } else {
                $id = $this->entity->getEntityName() . ucwords($fieldName);
            }
            $fieldName = "data[{$this->entity->getEntityName()}][$fieldName]";
        } else {
            if (!empty($options['id'])) {
                $id = $options['id'];
                unset($options['id']);
            }
            if (!empty($options['name'])) {
                $fieldName = $options['name'];
                unset($options['name']);
            }
        }
        if (!empty($options['class'])) {
            $class = $options['class'];
            unset($options['class']);
        } else {
            $class = 'form__input';
        }
        if (!empty($errors)) {
            $class .= ' form__input--error';
        }
        $displayErrors = $this->__displayErrors($errors);
        if ($classification === self::INPUT) {
            $label = null;
            if (!empty($options['label'])) {
                $input .= sprintf(
                    '<label class="%s" for="%s">%s</label>',
                    $options['label']['class'], $id, $options['label']['text']
                );
                unset($options['label']);
            }
            if (!empty($options['type'])) {
                $type = $options['type'];
                unset($options['type']);
            } else {
                $type = 'text';
            }
            $input .= "<input class='$class' ";
            if (!empty($id)) {
                $input .= "id='$id' ";
            }
            $input .= "type='$type' ";
            if (!empty($value)) {
                $input .= "value='$value' ";
            }
            $input .= "name='$fieldName' ";
            if (!empty($options)) {
                $input .= $this->__setAttributes($options);
            }
            $input .= '/>';
        } elseif ($classification === self::TEXTAREA) {
            $input .= "<textarea id='$id' class='$class' ";
            $input .= "name='$fieldName' ";
            if (!empty($options)) {
                $input .= $this->__setAttributes($options);
            }
            $input .= '>';
            if (!empty($value)) {
                $input .= $value;
            }
            $input .= '</textarea>';
        } elseif ($classification === self::CHECKBOX) {
            $input .= "<input type='checkbox' class='$class' ";
            if (!empty($options['value'])) {
                $id .= $options['value'];
            }
            if (!empty($id)) {
                $input .= "id='$id' ";
            }
            $label = sprintf(
                '<label class="%s" for="%s">%s</label>',
                $options['label']['class'], $id, $options['label']['text']
            );
            unset($options['label']);
            if (!empty($options['value'])) {
                $input .= "name='{$fieldName}[{$options['value']}]' ";
            } else {
                $input .= "name='$fieldName' ";
            }
            if (!empty($options)) {
                $input .= $this->__setAttributes($options);
            }
            if (!empty($options['value'])) {
                $input .= "value='{$options['value']}' ";
                unset($options['value']);
            }
            $input .= '/>';
            $input .= $label;
        }
        $input .= $displayErrors ?? null;
        $input .= '</div>';
        return $input;
    }
}
