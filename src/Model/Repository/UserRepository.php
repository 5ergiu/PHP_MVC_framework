<?php
namespace App\Model\Repository;

use App\Core\Model\AbstractRepository;
use Exception;

class UserRepository extends AbstractRepository
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