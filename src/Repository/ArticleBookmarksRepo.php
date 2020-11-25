<?php
namespace App\Repository;

use Exception;

class ArticleBookmarksRepo extends AbstractRepository
{
    /**
     * TODO: go through EVERY 'throws Exception' and decide whether or not it's needed: like, if you return an error after catching it, it's needed JUST in the method where you catch it.
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
            ->toQueryString()
        ;
    }
}
