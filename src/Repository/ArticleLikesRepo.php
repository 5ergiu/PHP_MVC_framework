<?php
namespace App\Repository;

class ArticleLikesRepo extends AbstractRepository
{
    /**
     * @inheritDoc
     * @return void
     */
    protected function validations(): void
    {
        // TODO: Implement validations() method.
    }

    /**
     * Returns a sub query to find the liked articles by the logged user.
     * @return string
     */
    public function getLikedByLoggedUserSubQuery(): string
    {
        return $this->createQueryBuilder('al')
            ->select(['COUNT(*) > 0'])
            ->where([
                'AND' => [
                    'al.liked_by = :liked_by',
                    'al.article_id = a.id',
                ],
            ])
            ->getQuery()
            ->toQueryString()
        ;
    }
}
