<?php

namespace App\Core\Model\DBAL;

class EnumUserRoleType extends EnumType
{
    protected string $name = 'enumUserRoleType';
    protected array $values = ['User', 'Admin', 'Author'];
}
