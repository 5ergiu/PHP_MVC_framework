<?php
namespace App\Entity;

/**
 * The framework's main entity which will be extended by all the app's entities.
 * Used for binding values to entities, saving or editing entities.
 * @property array $context The request data at the point of binding the values, available in each entity.
 * @property array $errors  The errors array, available in each entity.
 */
abstract class AbstractEntity
{

    /**
     * 'Slugifies' a given string.
     * @param string $string The string to be 'slugify'.
     * @return string
     */
    protected function slugify(string $string): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-')) . '-' . uniqid();
    }
}
