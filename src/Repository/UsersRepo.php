<?php
namespace App\Repository;

use App\Repository\AbstractRepository as Repository;
use Exception;
class UsersRepo extends Repository
{

    /**
     * @param string $name
     * @return array
     * @throws Exception
     */
    public function findByExample(string $name): array
    {
        return $this->createQueryBuilder('u')
            ->select([
                'u.email',
            ])
            ->setParams([
                'u.name = :name'
            ])
            ->addConditions([
                'u.name' => $name,
            ])
            ->orderBy([
                'ASC' => 'u.id',
                'u.name',
            ])
            ->setMaxResults(2)
            ->getQuery()
            ->getResults();
    }
}