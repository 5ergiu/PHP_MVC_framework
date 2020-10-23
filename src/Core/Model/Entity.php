<?php

namespace App\Core\Model;

class Entity
{

    /**
     * @param array $data
     * @return void
     */
    public function bindValues(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $funcName = 'set' . ucwords($key);
                if (method_exists($this, $funcName)) {
                    $this->{$funcName}($value);
                }
            }
        }
    }
}