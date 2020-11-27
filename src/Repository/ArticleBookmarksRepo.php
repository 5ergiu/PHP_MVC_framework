<?php
namespace App\Repository;

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
                'AND' => [
                    'ab.bookmarked_by = :bookmarked_by',
                    'ab.article_id = a.id',
                ],
            ])
            ->getQuery()
            ->toQueryString()
        ;
    }

    /**
     * Overwriting the default 'exists' method so that it will include the authenticated user's id.
     * @inheritDoc
     * @return bool
     */
    public function exists(array $conditions): ?int
    {
        $conditions['bookmarked_by'] = $this->userId;
        $result = $this->findBy($conditions);
        return !empty($result) ? $result['id'] : null;
    }
}
