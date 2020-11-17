<?php
namespace App\Repository;

use App\Repository\AbstractRepository as Repository;
use Exception;
class UsersRepo extends Repository
{

//    /**
//     * @param string $username
//     * @return array
//     * @throws Exception
//     */
//    public function findByUsername(string $username): array
//    {
//        return $this->createQueryBuilder('u')
//            ->setParams([
//                'u.username = :username'
//            ])
//            ->addConditions([
//                'u.username' => $username,
//            ])
//            ->getQuery()
//            ->getResults();
//    }
}
