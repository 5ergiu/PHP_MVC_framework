<?php
namespace App\Repository;

use App\Repository\AbstractRepository as Repository;
use Exception;
class UsersRepo extends Repository
{
    /**
     * Returns a username based on a user's id.
     * @param int $userId
     * @return string
     * @throws Exception
     */
    public function getUsernameById(int $userId): string
    {
        return $this->createQueryBuilder('u')
            ->where([
                'u.id = :id'
            ])
            ->setParameters([
                'id' => $userId,
            ])
            ->getQuery()
            ->firstOrNull()['username']
        ;
    }
}
