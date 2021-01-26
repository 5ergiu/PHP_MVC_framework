<?php

namespace App\Core\Model\DBAL;

class EnumRoleType extends EnumType
{
    protected string $name = 'enumroletype';
    protected array $values = ['User', 'Admin', 'Author'];
}
