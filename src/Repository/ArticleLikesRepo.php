<?php
namespace App\Repository;

class ArticleLikesRepo extends AbstractRepository
{
    /**
     * Returns a subQuery to find the articles liked by the logged user.

     * @return string|null
     */
    public function likedByLoggedUserSubQuery(): ?string
    {
        return $this->createQueryBuilder('al')
            ->select(['COUNT(*)'])
            ->where([
                'al.liked_by = :liked_by',
                'al.article_id = :article_id',
            ])
            ->getQuery()
            ->toQueryString()
        ;
    }
}
