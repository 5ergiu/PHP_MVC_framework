<?php
namespace App\Model\Repository;

use App\Core\Model\AbstractRepository;
use Exception;

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

    /**
     * @param string $name
     * @return array
     * @throws Exception
     */
    public function findByExample(string $name): array
    {
        return $this->createQueryBuilder('u')
            ->orderBy([
                'ASC' => 'u.id',
                'u.name',
            ])
            ->setMaxResults(3)
            ->getQuery()
            ->getResults();
    }
}