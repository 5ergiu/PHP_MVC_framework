<?php
namespace App\Repository;

class ArticleBookmarksRepo extends AbstractRepository
{
    /**
     * Returns a sub query to find the bookmarked articles by the logged user.
     * @return string
     */
    public function getBookmarkedByLoggedUserSubQuery(): string
    {
        return $this->createQueryBuilder('ab')
            ->select(['COUNT(*) > 0'])
            ->where([
                'ab.bookmarked_by = :bookmarked_by',
                'ab.article_id = a.id',
            ])
            ->getQuery()
            ->subQuery()
            ;
    }
}
