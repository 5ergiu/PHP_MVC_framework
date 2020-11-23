<?php
namespace App\Repository;

use Exception;
class ArticlesRepo extends AbstractRepository
{
    /**
     * Returns all articles.
     * @param string|null $likedByLoggedUserSubQuery
     * @param int|null $userId Logged user.
     * @return array|string
     * @throws Exception
     */
    public function getArticles(?string $likedByLoggedUserSubQuery, ?int $userId)
    {
        $selections = ['a.id', 'a.title', 'a.content', 'a.cover', 'a.slug', 'count(DISTINCT l.liked_by) as likes'];
        if ($userId !== null) {
            $selections[] = "$likedByLoggedUserSubQuery AS liked_by_current_user";
        }
        return $this->createQueryBuilder('a')
            ->select($selections)
            ->where([
                'a.status = :status'
            ])
            ->setParameters([
                'status' => 'approved',
                'liked_by' => $userId,
                'article_id' => 'a.id',
            ])
            ->joins([
                [
                    'table' => 'article_likes',
                    'alias' => 'l',
                    'type' => 'LEFT',
                    'conditions' => [
                        'a.id = l.article_id',
                    ],
                ],
            ])
            ->groupBy(['a.id'])
            ->getQuery()
            ->getResults()
        ;
    }
}
