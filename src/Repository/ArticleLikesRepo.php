<?php
namespace App\Repository;

class ArticleLikesRepo extends AbstractRepository
{
    /**
     * Returns a subQuery to find the articles liked by the logged user.
     * @param int|null $userId Logged user.
     * @return array|null
     */
    public function likedByLoggedUserSubQuery(?int $userId): ?array
    {
        if ($userId !== null) {
            $subQuery['parameters']['liked_by'] = $userId;
            $subQuery['query'] = $this->createQueryBuilder('al')
                ->select(['COUNT(*)'])
                ->where([
                    'al.liked_by = :liked_by',
                    'al.article_id = a.id',
                ])
                ->getQuery()
                ->toQueryString()
                ;
            return $subQuery;
        } else {
            return null;
        }
    }
}
