<?php

namespace App\Core\Model\DBAL;

class EnumArticleStatusType extends EnumType
{
    protected string $name = 'enumArticleStatusType';
    protected array $values = ['review', 'draft', 'approved', 'rejected'];
}
