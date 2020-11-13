<?php
namespace App\Helper;

use App\Entity\AbstractEntity as Entity;
use App\Core\Network\Request;
/**
 * Generates HTML forms from given data.
 */
class FormHelper
{

    /**
     * Creates the <form> tag.
     * @param string|null $id     The form's id.
     * @param string|null $action The form's action.
     * @param string $method      The form's method(post by default).
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
     * Creates <input> tags.
     * @param Entity|null $entity    The Entity that will deal with the input.
     * @param string|null $fieldName The input's 'name' attribute.
     * @param array $options         Various options: type attribute, class attribute, label info.
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
     * Creates the end tag(</form>) for the form.
     * @return string
     */
    public function end(): string
    {
        return '</form>';
    }
}
