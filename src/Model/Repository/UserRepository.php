<?php
namespace App\Model\Repository;

use App\Core\Model\AbstractRepository;
class UserRepository extends AbstractRepository
{
    protected string $table = 'users';

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }
}