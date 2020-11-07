<?php
namespace App\Model\Repository;

use App\Core\Model\Repository;
use Exception;

class UserRepository extends Repository
{
    private string $table = 'users';

    /**
     * @inheritDoc
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}